<?php
// This file is part of the Moodle plugin block_evasys_sync
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace block_evasys_sync;

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot . "/local/lsf_unification/lib_his.php");
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->dirroot . '/course/lib.php');
/**
 * Class evasys_inviter
 * @package block_evasys_sync
 * @copyright 2019 Robin Tschudi and whoever owns the copyright to the evasys_synchronizer class
 */

class evasys_inviter {

    public static $instance = null;
    private $soapclient;

    private function __construct() {
        $this->soapclient = $this->init_soap_client();
    }

    public static function get_instance() {
        if (self::$instance) {
            return self::$instance;
        }
        self::$instance = new evasys_inviter();
        return self::$instance;
    }

    public static function get_evasysids($courseid) {
        global $DB;
        // Fetch persistent object id.
        $pid = $DB->get_field('block_evasys_sync_courses', 'id', array('course' => $courseid));
        // Get all associated courses.
        if ($pid !== false) {
            $allocation = new \block_evasys_sync\course_evasys_courses_allocation($pid);
            $extras = explode('#', $allocation->get('evasyscourses'));
        } else {
            $extras = [];
        }
        $extras = array_filter($extras);
        establish_secondary_DB_connection();
        // Fetch the Evasysids for the courses.
        $relevantcourses = array();
        foreach ($extras as $course) {
            $courseinfo = get_course_by_veranstid(intval($course));
            if (!is_object($courseinfo)) {
                throw new \Exception('Cannot sync: Connection to LSF could not be established. Please try again later.');
            }
            $relevantcourses[] = trim($courseinfo->veranstnr) . ' ' . trim($courseinfo->semestertxt);
        }
        // Maybe add entry via the $course->idnumber.
        $course = get_course($courseid);
        if ($course->idnumber) {
            $courseinfo = get_course_by_veranstid(intval($course->idnumber));
            $maincourse = trim($courseinfo->veranstnr) . ' ' . trim($courseinfo->semestertxt);
        }
        if (!in_array($maincourse, $relevantcourses)) {
            $relevantcourses[] = $maincourse;
        }
        close_secondary_DB_connection();
        return $relevantcourses;
    }

    /**
     * Get surveys for an Evasys course
     * @param $evasyskennung Identifier of the course in EvaSys.
     * @param bool $all if false, only surveys with OpenState != 0 are returned.
     * @return array IDs of surveys
     */
    public function get_evasys_course_surveyids($evasyskennung, $all = true) {
        $soapresult = $this->soapclient->GetCourse($evasyskennung, 'PUBLIC', true, false);
        $surveyids = $soapresult->m_oSurveyHolder->m_aSurveys;
        if (is_soap_fault($soapresult)) {
            return array();
        }
        if (is_object($surveyids->Surveys)) {
            if ($all or $surveyids->Surveys->m_nOpenState != 0) {
                return array($surveyids->Surveys);
            }
            return array();
        } else {
            $surveys = array();
            foreach ($surveyids->Surveys as $survey) {
                if ($all or $survey->m_nOpenState != 0) {
                    $surveys[] = $survey;
                }
            }
            return $surveys;
        }
    }

    public function close_evasys_surveys($courses) {
        foreach ($courses as $evasysid => $moodlecourse) {
            $errorsurveys = array();
            // Get all open evasys Surveys.
            $surveys = $this->get_evasys_course_surveyids($evasysid, false);
            foreach ($surveys as $survey) {
                if (!$this->set_close_task($survey->m_nSurveyId)) {
                    $errorsurveys[] = $survey;
                }
            }
            if (count($errorsurveys) > 0) {
                $this->send_failure_warning($moodlecourse, $evasysid, $errorsurveys);
            }
        }
    }

    public function send_failure_warning ($moodlecourse, $evasysid, $errorsurveys) {
        global $USER;
        $course = get_course($moodlecourse);
        $userto = evasys_synchronizer::get_assigned_user($course);
        $userfrom = $USER;
        $subject = "Automatisches Senden der Evaluationsergebnisse für Moodlekurs $moodlecourse fehlgeschlagen!";
        $message = "Sehr geehte/r Evaluationskoordinator/in \n\r\n\r";
        $message .= "Leider gab es ein Problem beim automatischen Versand der Ergebnisse.\r\n";
        $message .= "Bitte versenden sie die Evaluationsergebnisse für folgende Umfragen: \r\n\r\n";
        $message .= "\tEvasysveranstaltung: $evasysid \r\n";
        $message .= "\tEvasysumfrageids: \r\n";
        foreach ($errorsurveys as &$survey) {
            $message .= "\t\tFragebogen-ID: " . $survey->formIdPub . " (" . $survey->formId . ")\r\n";
            $message .= "\t\tFragebogenname: " . $survey->formName . "\r\n\r\n";
        }
        $message .= "\r\n";
        $message .= "Mit freundlichen Grüßen \r\n";
        $message .= "Ihr Learnweb-Support.";
        email_to_user($userfrom, $userto, $subject, $message);
    }

    public function open_evasys_surveys($courses) {
        $surveys = array();
        foreach ($courses as $course) {
            // Get all open evasys Surveys.
            $surveys = array_merge($surveys, $this->get_evasys_course_surveyids($course, false));
        }
        $surveys = array_unique($surveys);

        foreach ($surveys as $survey) {
            $this->soapclient->sendInvitationToParticipants($survey);
        }
    }

    public function open_moodle_courses($courseids) {
        global $DB;
        $courses = array();
        $usedcourseids = array();
        // Push users into (viable) surveys.
        foreach ($courseids as $courseid) {
            if (self::getmode(get_course($courseid)->category)) {
                $this->push_users_in_moodlecourse($courseid);
                $courses = array_merge($courses, self::get_evasysids($courseid));
                $usedcourseids[] = $courseid;
            }
        }
        if (count($usedcourseids) == 0) {
            // There are no courses for which a survey could be opened.
            return;
        }
        $this->open_evasys_surveys($courses);

        // Update state information for Moodle.
        list($where, $params) = $DB->get_in_or_equal($usedcourseids);
        $DB->execute("UPDATE {block_evasys_sync_courseeval} SET state = 1 WHERE course $where", $params);

        // Record that this has happened.
        foreach ($usedcourseids as $courseid) {
            $event = event\evaluation_opened::create(array(
                'context' => \context_course::instance($courseid),
                'courseid' => $courseid,
                )
            );
            $event->trigger();
        }
    }

    public function close_moodle_course($courseids) {
        global $DB;
        $courses = array();
        $usedcourseids = array();
        // Collect viable courses.
        foreach ($courseids as $courseid) {
            if (self::getmode(get_course($courseid)->category)) {
                foreach (self::get_evasysids($courseid) as $evasysid) {
                    $courses[$evasysid] = $courseid;
                }
                $usedcourseids[] = $courseid;
            }
        }
        $this->close_evasys_surveys($courses);
        // Update internal state for keeping track.
        $courseidssql = implode(",", $usedcourseids);
        if ($courseidssql !== '') {
            $DB->execute("UPDATE {block_evasys_sync_courseeval} SET state = 2 WHERE course in ($courseidssql)");
        }
        foreach ($usedcourseids as $courseid) {
            $event = event\evaluation_closed::create(array(
                'context' => \context_course::instance($courseid),
                'courseid' => $courseid
                )
            );
            $event->trigger();
        }
    }

    public function push_users_in_moodlecourse($moodlecourse) {
        $evasyscourses = self::get_evasysids($moodlecourse);
        $emailadresses = self::get_enrolled_student_email_adresses_from_usernames($moodlecourse);
        $students = array();
        foreach ($emailadresses as $emailadress) {
            $soapmsidentifier = new \SoapVar($emailadress, XSD_STRING, null, null, 'm_sIdentifier', null);
            $soapmsemail = new \SoapVar($emailadress, XSD_STRING, null, null, 'm_sEmail', null);
            $student = new \SoapVar(array($soapmsidentifier, $soapmsemail), SOAP_ENC_OBJECT, null, null, 'Persons', null);
            $students[] = $student;
        }
        $personlist = new \SoapVar($students, SOAP_ENC_OBJECT, null, null, 'PersonList', null);

        $soapresult = false;
        foreach ($evasyscourses as $evasyscourse) {
            $soapresult = $this->soapclient->InsertParticipants($personlist, $evasyscourse, 'PUBLIC', false);
            if (!is_soap_fault($soapresult) && $soapresult) {
                // Create enough passwords, then try again.
                $this->make_sure_enough_passwords_are_available($evasyscourse);
            } else {
                $soapresult = false;
            }
        }
        return $soapresult;
    }

    public function make_sure_enough_passwords_are_available($evasyscourseid) {
        $evasyscourse = $this->soapclient->GetCourse($evasyscourseid, 'PUBLIC' , false, false);
        if (!is_soap_fault($evasyscourse)) {
            $usercount = $evasyscourse->m_nCountStud;
            $surveys = $this->get_evasys_course_surveyids($evasyscourseid);
            foreach ($surveys as $survey) {
                $this->soapclient->GetPswdsBySurvey($survey, $usercount, 1, true, false);
            }
        }
    }

    public static function get_enrolled_student_email_adresses_from_usernames($moodlecourseid) {
        $emailadresses = array();

        $enrolledusers = get_users_by_capability(\context_course::instance($moodlecourseid), 'block/evasys_sync:mayevaluate');

        foreach ($enrolledusers as $user) {
            $emailadresses[] = $user->username . "@uni-muenster.de";
        }

        return $emailadresses;
    }

    public function init_soap_client() {
        $soapclient = new \SoapClient(get_config('block_evasys_sync', 'evasys_wsdl_url'), [
            'trace' => 1,
            'exceptions' => 0,
            'location' => get_config('block_evasys_sync', 'evasys_soap_url')
        ]);

        $headerbody = new \SoapVar([
                                       new \SoapVar(get_config('block_evasys_sync', 'evasys_username'), XSD_STRING, null, null, 'Login', null),
                                       new \SoapVar(get_config('block_evasys_sync', 'evasys_password'), XSD_STRING, null, null, 'Password', null),
                                   ], SOAP_ENC_OBJECT);
        $header = new \SOAPHEADER('soap', 'Header', $headerbody);
        $soapclient->__setSoapHeaders($header);
        return $soapclient;
    }

    public function set_close_task($surveyid) {
        $time = new \DateTime();
        $time->add(new \DateInterval("PT10S"));
        $task = new \stdClass();
        $task->SurveyID = strval($surveyid);
        $task->StartTime = $time->format("Y-m-d\TH:i:s");
        $task->Status = 3;
        $task->SendReport = false;
        $soapresult = $this->soapclient->InsertCloseTask($task);
        if (is_soap_fault($soapresult)) {
            // The Close Task has already been executed. This can happen if the Survey was reopened for some Reason.
            // Because of this, we'll still close the survey, however we can't send the result mail.
            // Also there might be another Error. In any Case we want to make sure the Survey gets closed.
            // If this was called by the close survey method the evaluationkoordinator will be notified.
            $this->soapclient->CloseSurvey($surveyid);
            return false;
        }
        return true;
    }

    /**
     * @param $category
     * @return bool wether the teacher may set dates for this survey himself.
     * @throws dml_exception
     * @throws moodle_exception
     */
    public static function getmode($category) {
        global $DB;
        $mode = $DB->get_record('block_evasys_sync_categories', array('course_category' => $category));
        if ($mode) {
            return (bool) $mode->category_mode;
        } else {
            $parents = \core_course_category::get($category)->get_parents();
            for ($i = count($parents) - 1; $i >= 0; $i--) {
                $mode = $DB->get_record('block_evasys_sync_categories', array('course_category' => $parents[$i]));
                if ($mode) {
                    return (bool) $mode->category_mode;
                }
            }
        }
        $default = get_config('block_evasys_sync', 'default_evasys_mode');
        return (bool)$default;
    }
}