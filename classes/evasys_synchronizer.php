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

class evasys_synchronizer {
    private $courseid;
    private $blockcontext;
    private $courseinformation;
    private $lsfcourses;
    private $evasysapi;

    public function __construct($courseid) {
        $this->courseid = $courseid;
        $this->evasysapi = evasys_api::get_instance();
        $this->blockcontext = \context_course::instance($courseid); // TODO Course context or block context? Check caps.
        $this->courseinformation = $this->get_course_information();
    }

    public function get_courses_from_lsf() {
        global $DB;
        if ($this->lsfcourses !== null) {
            return $this->lsfcourses;
        }
        $course = get_course($this->courseid);
        // Fetch persistent object id.
        $pid = $DB->get_field('block_evasys_sync_courses', 'id', array('course' => $this->courseid));
        // Get all associated courses.
        if (!$pid === false) {
            $extras = new \block_evasys_sync\course_evasys_courses_allocation($pid);
            $extras = explode('#', $extras->get('evasyscourses'));
        } else {
            $extras = [];
        }
        // If noone has associated the course itself, we force that.
        if (!(is_null($course->idnumber)) && !($course->idnumber == '')) {
            if (!in_array($course->idnumber, $extras)) {
                $extras[] = $course->idnumber;
            }
        }
        establish_secondary_DB_connection();
        // Fetch metadata (id, title) for the courses.
        $result = array();
        foreach ($extras as $lsfcourse) {
            $courseinfo = get_course_by_veranstid(intval($lsfcourse));
            $result[] = array(
                'title' => $courseinfo->titel,
                'id' => trim($courseinfo->veranstnr) . ' ' . trim($courseinfo->semestertxt),
                'lsf_id' => $lsfcourse);
        }
        close_secondary_DB_connection();
        $this->lsfcourses = $result;
        return $this->lsfcourses;
    }

    private function get_course_information() {
        $result = [];
        foreach ($this->get_courses_from_lsf() as $course) {
            $soapresult = $this->evasysapi->get_course($course['id']);
            if (is_soap_fault($soapresult)) {
                // This happens e.g. if there is no corresponding course in EvaSys.
                return null;
            }
            $result[$course['id']] = $soapresult;
        }
        return $result;
    }

    /**
     * Builds array with all surveys and additional information to surveys
     * @return array of surveys with additional information
     */
    public function get_surveys($courseid) {
        if ($this->courseinformation[$courseid] === null) {
            return array();
        }
        if (!isset($this->courseinformation[$courseid]->m_oSurveyHolder->m_aSurveys->Surveys)) {
            return array();
        }
        $rawsurveys = $this->courseinformation[$courseid]->m_oSurveyHolder->m_aSurveys->Surveys;
        if (count((array)$rawsurveys) == 0) {
            // No surveys available.
            return array();
        }

        if (is_object($rawsurveys)) {
            // Course only has one associated survey.
            return [$this->enrich_survey($rawsurveys)];
        }

        $enrichedsurveys = array();

        foreach ($rawsurveys as &$survey) {
            $enrichedsurveys[] = $this->enrich_survey($survey);
        }
        return $enrichedsurveys;
    }

    public function get_course_name($coursekey) {
        if (isset($this->courseinformation[$coursekey])) {
            return $this->courseinformation[$coursekey]->m_sCourseTitle;
        }
        return "Unknown";
    }

    public function get_course_id($coursekey) {
        if (isset($this->courseinformation[$coursekey])) {
            return $this->courseinformation[$coursekey]->m_nCourseId;
        }
        return "Unknown";
    }

    /**
     * Enriches Surveys with Information
     * @param \stdClass $rawsurvey Survey without additional information
     * @return \stdClass Survey with additional information
     */
    private function enrich_survey($rawsurvey) {
        $enrichedsurvey = new \stdClass();
        $enrichedsurvey->id = $rawsurvey->m_nSurveyId;
        $enrichedsurvey->amountOfCompletedForms = $rawsurvey->m_nFormCount;
        $enrichedsurvey->surveyStatus = $this->get_survey_status($rawsurvey->m_nOpenState);
        $enrichedsurvey->formName = $this->get_form_name($rawsurvey->m_nFrmid);
        $enrichedsurvey->formIdPub = $this->get_public_formid($rawsurvey->m_nFrmid);
        $enrichedsurvey->formId = $rawsurvey->m_nFrmid;
        return $enrichedsurvey;
    }

    private function get_survey_status($statusnumber) {
        if ($statusnumber === 1) {
            return 'open';
        } else {
            return 'closed';
        }
    }

    private function get_public_formid($formid) {
        $soapresult = $this->evasysapi->get_form($formid);
        $formidpub = $soapresult->FormName;
        return $formidpub;
    }

    private function get_form_name($formid) {
        $soapresult = $this->evasysapi->get_form($formid);
        $formname = $soapresult->FormTitle;
        return $formname;
    }

    public function get_amount_participants($courseid) {
        if ($this->courseinformation[$courseid] === null || !property_exists($this->courseinformation[$courseid]->m_aoParticipants, "Persons")) {
            return 0;
        }
        if (is_object($this->courseinformation[$courseid]->m_aoParticipants->Persons)) {
            return 1;
        }

        return count($this->courseinformation[$courseid]->m_aoParticipants->Persons);
    }

    /**
     * Gets all email addresses of enrolled students.
     * @return array of e-mail addresses of all enrolled students
     */
    private function get_enrolled_student_email_adresses_from_usernames() {
        $emailadresses = array();

        $enrolledusers = get_users_by_capability($this->blockcontext, 'block/evasys_sync:mayevaluate');

        foreach ($enrolledusers as $user) {
            $emailadresses[] = $user->username . "@uni-muenster.de";
        }

        return $emailadresses;
    }

    /**
     * Updates the students who can participate in the survey.
     */
    public function sync_students() {
        if ($this->courseinformation === null) {
            throw new \Exception('Cannot sync: Course not known to EvaSys');
        }

        $emailadresses = $this->get_enrolled_student_email_adresses_from_usernames();
        $students = array();

        foreach ($emailadresses as $emailadress) {
            $soapmsidentifier = new \SoapVar($emailadress, XSD_STRING, null, null, 'm_sIdentifier', null);
            $soapmsemail = new \SoapVar($emailadress, XSD_STRING, null, null, 'm_sEmail', null);
            $student = new \SoapVar(array($soapmsidentifier, $soapmsemail), SOAP_ENC_OBJECT, null, null, 'Persons', null);
            $students[] = $student;
        }
        $personlist = new \SoapVar($students, SOAP_ENC_OBJECT, null, null, 'PersonList', null);
        $this->courseinformation = $this->get_course_information();
        foreach ($this->courseinformation as $course) {
            $soapresult = $this->evasysapi->insert_participants($personlist, $course->m_sPubCourseId);
            $course = $this->evasysapi->get_course($course->m_sPubCourseId); // Update usercount.
            $usercountnow = $course->m_nCountStud;
            // The m_aSurveys element might be an empty object!
            if (!empty((array) $course->m_oSurveyHolder->m_aSurveys)) {
                if (is_array($course->m_oSurveyHolder->m_aSurveys->Surveys)) {
                    foreach ($course->m_oSurveyHolder->m_aSurveys->Surveys as $survey) {
                        $id = $survey->m_nSurveyId;
                        $this->evasysapi->create_passwords($id, $usercountnow);
                    }
                } else {
                    $id = $course->m_oSurveyHolder->m_aSurveys->Surveys->m_nSurveyId;
                    $this->evasysapi->create_passwords($id, $usercountnow); // Create new TAN's.
                }
            }
            if (is_soap_fault($soapresult)) {
                throw new \Exception('Sending list of participants to evasys server failed.');
            }
        }
        return $soapresult;
    }

    /**
     * Sends an e-mail with the request to start a Evaluation for a course.
     * @throws \Exception when e-mail request fails
     */
    public function notify_evaluation_responsible_person($dates, $newparticipantsadded, $datechanged) {
        global $USER;
        $course = get_course($this->courseid);

        $userto = $this->get_assigned_user($course);

        if (!$userto) {
            throw new \Exception('Could not find the specified user to send an email to.');
        }
        $userfrom =& $USER;

        $notifsubject = "Evaluation für '" . $course->fullname . "' beauftragt";

        $textdatechanged = $datechanged ? ' (Zeitraum geändert!)' : '';

        $notiftext = "Sehr geehrte*r Evaluationskoordinator*in,\r\n\r\n";
        $notiftext .= "Dies ist eine automatisch generierte Mail, ausgelöst dadurch, dass ein*e Dozent*in die Evaluation " .
            "der nachfolgenden Veranstaltung beantragt hat. \r\n".
            "Bitte passen Sie die Evaluationszeiträume dem untenstehenden Wunsch an. \r\n".
            "Bitte versenden Sie die TANs im EvaSys-Menü " .
            "unter dem Menüpunkt 'TANs per E-Mail an Befragte versenden' für die Veranstaltungen.\r\n".
            "Falls Sie für diesen Kurs bereits eine E-Mail erhalten haben, wurden gerade neue Teilnehmer*innen ".
            "hinzugefügt oder der Zeitraum angepasst. Dies ist ggf. unten angegeben.\r\n\r\n";

        $addstandardtimestring = false;
        $standarddates = self::get_standard_timemode($course->category);
        // If the dates are not the standard dates and there are standard dates for this course:
        if ($standarddates !== false) {
            if ($dates == "Standard") {
                $addstandardtimestring = true;
                $dates = $standarddates;
            } else {
                $notifsubject = '[SONDERWUNSCH] ' . $notifsubject;
            }
        }
        $startdate = new \DateTime('@' . $dates["start"], \core_date::get_server_timezone_object());
        $startdate->setTimezone(\core_date::get_user_timezone_object($userto));
        $formattedstartdate = $startdate->format('d.m.Y H:i');
        $enddate = new \DateTime('@' . $dates["end"], \core_date::get_server_timezone_object());
        $enddate->setTimezone(\core_date::get_user_timezone_object($userto));
        $formattedenddate = $enddate->format('d.m.Y H:i');

        $notiftext .= "Gewünschter Evaluationszeitraum: " . $formattedstartdate . " bis " .
            $formattedenddate . $textdatechanged . ($addstandardtimestring ? " (Standardzeitraum)" : "") . "\r\n\r\n";
        if ($newparticipantsadded) {
            $notiftext .= "Der Evaluation wurden neue Teilnehmer*innen hinzugefügt.\r\n\r\n";
        }

        foreach ($this->courseinformation as $course) {
            $notiftext .= "Name: " . $course->m_sCourseTitle . "\r\n";
            $notiftext .= "EvaSys-ID: " . $course->m_sPubCourseId ."\r\n";
            $notiftext .= "Die Veranstaltung hat folgende Fragebögen:\r\n\r\n";

            $surveys = $this->get_surveys($course->m_sPubCourseId);
            foreach ($surveys as &$survey) {
                $notiftext .= "\tFragebogen-ID: " . $survey->formIdPub . " (" . $survey->formId . ")\r\n";
                $notiftext .= "\tFragebogenname: " . $survey->formName . "\r\n\r\n";
            }
        }

        $notiftext .= "Mit freundlichen Grüßen\r\n";
        $notiftext .= "Learnweb-Support";

        $mailresult = email_to_user($userto, $userfrom, $notifsubject, $notiftext, '', '' , '',
            true, $userfrom->email, $userfrom->firstname . " " . $userfrom->lastname);
        if (!$mailresult) {
            throw new \Exception('Could not send e-mail to person responsible for evaluation');
        }
    }

    /**
     * Returns the user to whom the email is sent.
     * @param $course
     * @return bool|\stdClass user
     */
    static public function get_assigned_user($course) {
        global $DB;

        $user = $DB->get_record('block_evasys_sync_categories', array('course_category' => $course->category));
        // Custom user has not been set.
        if (!$user) {
            // Loop through parents.
            $parents = \core_course_category::get($course->category)->get_parents();
            // Start with direct parent.
            for ($i = count($parents) - 1; $i >= 0; $i--) {
                $user = $DB->get_record('block_evasys_sync_categories', array('course_category' => $parents[$i]));
                // Stop if a parent has been assigned a custom user.
                if ($user) {
                    $userto = \core_user::get_user($user->userid);
                    break;
                }
            }
            // Custom user has not been set for parents.
            if (!$user) {
                // User default user.
                $userto = \core_user::get_user(get_config('block_evasys_sync', 'default_evasys_moodleuser'));
            }
        } else {
            // Use custom user of the course category of the course.
            $userto = \core_user::get_user($user->userid);
        }
        return $userto;
    }

    /**
     * Set time period for evaluation.
     *
     * @param array $dates expects keys `start' and `end' with timestamp values.
     * @return bool true if the dates have changed or if the record is new.
     * @throws \coding_exception
     * @throws \dml_missing_record_exception
     */
    public function set_evaluation_period($dates) : bool {
        $usestandardtime = ($dates == 'Standard');
        if ($usestandardtime) {
            $course = get_course($this->courseid);
            $dates = self::get_standard_timemode($course->category);
        }
        $changed = false;
        $data = course_evaluation_allocation::get_record_by_course($this->courseid, false);
        if (!$data) {
            $data = new course_evaluation_allocation(0);
            $data->set('course', $this->courseid);
            $data->set('state', course_evaluation_allocation::STATE_MANUAL);
        } else {
            // Don't display date changed warning on first sync.
            if ($data->get('startdate') != $dates['start'] || $data->get('enddate') != $dates['end']) {
                $changed = true;
            }
        }

        $data->set('startdate', $dates['start']);
        $data->set('enddate', $dates['end']);
        $data->set('usestandardtime', $usestandardtime);
        $data->save();

        return $changed;
    }

    /**
     * Returns a set standard timeframe, if one is set for the category of this course or any parent category.
     *
     * @param $category int id of the category
     * @return array|false returns an array containing the start and end timestamp of a defined standard timeframe.
     *    false if no standard timeframe is set.
     * @throws \dml_exception
     * @throws \moodle_exception
     */
    public static function get_standard_timemode($category) {
        global $DB;
        $mode = $DB->get_record('block_evasys_sync_categories', array('course_category' => $category));
        if ($mode !== false) {
            if ($mode->standard_time_start != null) {
                return array('start' => $mode->standard_time_start, 'end' => $mode->standard_time_end);
            } else {
                return false;
            }
        } else {
            $parents = \core_course_category::get($category)->get_parents();
            for ($i = count($parents) - 1; $i >= 0; $i--) {
                $mode = $DB->get_record('block_evasys_sync_categories', array('course_category' => $parents[$i]));
                if ($mode !== false) {
                    if ($mode->standard_time_start != null) {
                        return array('start' => $mode->standard_time_start, 'end' => $mode->standard_time_end);
                    } else {
                        return false;
                    }
                }
            }
        }
        $default = false;
        return $default;
    }
}
