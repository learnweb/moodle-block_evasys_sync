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
if (!defined('BEHAT_SITE_RUNNING')) {
    require_once($CFG->dirroot . "/local/lsf_unification/lib_his.php");
} else {
    require_once($CFG->dirroot . "/blocks/evasys_sync/classes/lsf_api_mock_testable.php");
}
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->dirroot . '/course/lib.php');
/**
 * Class evasys_inviter
 * @package block_evasys_sync
 * @copyright 2019 Robin Tschudi and whoever owns the copyright to the evasys_synchronizer class
 */

class evasys_inviter {

    public static $instance = null;
    private $evasysapi;

    private function __construct() {
        $this->evasysapi = evasys_api::get_instance();
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
    public function get_evasys_course_surveys($evasyskennung, $all = true) {
        $soapresult = $this->evasysapi->get_course($evasyskennung);
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
            $surveys = $this->get_evasys_course_surveys($evasysid, false);
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
        $subject = "Automatisches Senden der Evaluationsergebnisse fehlgeschlagen: $moodlecourse";
        $message = "Sehr geehrte*r Evaluationskoordinator*in,\r\n\r\n";
        $message .= "leider gab es ein Problem beim automatischen Versand der Ergebnisse nach geplantem Abschluss der Evaluation.\r\n";
        $message .= "Bitte versenden Sie die Evaluationsergebnisse für folgende Umfragen (und schließen Sie diese gegebenenfalls): \r\n\r\n";
        $message .= "\tEvaSys-Veranstaltung: $evasysid \r\n";
        $message .= "\tFragebögen: \r\n";
        foreach ($errorsurveys as &$survey) {
            $message .= "\t\tFragebogen-ID: " . $survey->formIdPub . " (" . $survey->formId . ")\r\n";
            $message .= "\t\tFragebogenname: " . $survey->formName . "\r\n\r\n";
        }
        $message .= "\r\n";
        $message .= "Mit freundlichen Grüßen,\r\n";
        $message .= "Ihr Learnweb-Support.";
        email_to_user($userfrom, $userto, $subject, $message);
    }

    public function open_evasys_surveys($courses) {
        $surveys = array();
        foreach ($courses as $course) {
            // Get all open evasys Surveys.
            $surveys = array_merge($surveys, $this->get_evasys_course_surveys($course, false));
        }
        $surveyids = array();
        foreach ($surveys as $survey) {
            $surveyids[] = (string)$survey->m_nSurveyId;
        }
        $surveyids = array_unique($surveyids);

        foreach ($surveyids as $surveyid) {
            $this->evasysapi->send_invitation_to_participants_of_survey($surveyid);
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
            $soapresult = $this->evasysapi->insert_participants($personlist, $evasyscourse);
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
        $evasyscourse = $this->evasysapi->get_course($evasyscourseid);
        if (!is_soap_fault($evasyscourse)) {
            $usercount = $evasyscourse->m_nCountStud;
            $surveys = $this->get_evasys_course_surveys($evasyscourseid);
            foreach ($surveys as $survey) {
                $this->evasysapi->create_passwords((string)$survey->m_nSurveyId, $usercount);
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

    public function set_close_task($surveyid) {
        $time = new \DateTime();
        $time->add(new \DateInterval("PT10S"));
        $task = new \stdClass();
        $task->SurveyID = strval($surveyid);
        $task->StartTime = $time->format("Y-m-d\TH:i:s");
        $task->Status = 3;
        $task->SendReport = false;
        $soapresult = $this->evasysapi->insert_close_task($task);
        if (is_soap_fault($soapresult)) {
            // The Close Task has already been executed. This can happen if the Survey was reopened for some Reason.
            // Because of this, we'll still close the survey, however we can't send the result mail.
            // Also there might be another Error. In any Case we want to make sure the Survey gets closed.
            // If this was called by the close survey method the evaluationkoordinator will be notified.
            $this->evasysapi->close_survey($surveyid);
            return false;
        }
        return true;
    }

    /**
     * Determines if the workflow used by the course category is automated or manual.
     * If true, the category uses the automated mode. If false, the workflow is manual.
     * @param $category
     * @return bool wether the teacher may set dates for this survey himself.
     * @throws \dml_exception
     * @throws \moodle_exception
     */
    public static function getmode($category) {
        global $DB;
        $mode = $DB->get_record('block_evasys_sync_categories', array('course_category' => $category));
        if ($mode !== false) {
            return (bool) $mode->category_mode;
        } else {
            $parents = \core_course_category::get($category)->get_parents();
            for ($i = count($parents) - 1; $i >= 0; $i--) {
                $mode = $DB->get_record('block_evasys_sync_categories', array('course_category' => $parents[$i]));
                if ($mode !== false) {
                    return (bool) $mode->category_mode;
                }
            }
        }
        $default = get_config('block_evasys_sync', 'default_evasys_mode');
        return (bool)$default;
    }

    /**
     * Alerts the Evaluation-coordinator of a given course, that a teacher has set a timeframe for the evaluation.
     * @param $courseid int
     * @param $startdate \DateTime Startdate
     * @param $enddate \DateTime
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public static function alert_coordinator($courseid, $startdate, $enddate) {
        global $USER;
        $course = get_course($courseid);
        $evacourses = course_evasys_courses_allocation::raw_get_evasyscourses($courseid);
        $coursestring = '';
        foreach ($evacourses as $evacourse) {
            // Add courses with two tabulators.
            $coursestring .= "\t\t". $evacourse . "\n";
        }
        $usercoordinator = evasys_synchronizer::get_assigned_user($course);
        $data = array(
            'name' => $course->fullname,
            'teacher' => $USER->firstname . " " . $USER->lastname,
            'start' => $startdate->format('d.m.Y H:i:s'),
            'end' => $enddate->format('d.m.Y H:i:s'),
            'evasyscourses' => $coursestring
        );
        $subject = get_string("alert_email_subject", "block_evasys_sync", $course->fullname);
        $message = get_string("alert_email_body", "block_evasys_sync", $data);

        email_to_user($usercoordinator, $USER, $subject, $message, '', '' , '',
                                    true, $USER->email, $USER->firstname . " " . $USER->lastname);
    }
}