<?php
// This file is part of Moodle - http://moodle.org/
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

use block_evasys_sync\local\entity\evaluation_state;

/**
 * Manager for evaluations.
 *
 * @package    block_evasys_sync
 * @copyright  2022 Justus Dieckmann WWU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class evaluation_manager {

    private static $instance;

    public static function get_instance(): evaluation_manager {
        if (!self::$instance) {
            self::$instance = new evaluation_manager();
        }
        return self::$instance;
    }

    public static function approve_eval_request(int $evalrequestid) {
        $request = evaluation_request::from_id($evalrequestid);
        $evaluation = evaluation::from_eval_request($request);
        $evaluation->save();
    }

    public static function set_default_evaluation_for($courseids, evasys_category $category) {
        global $DB, $USER;
        $childids = \core_course_category::get($category->get('course_category'))->get_all_children_ids();
        $childids[] = $category->get('course_category');
        $errors = [];
        $coordinatoruser = \core_user::get_user($category->get('userid'));
        foreach ($courseids as $courseid) {
            $course = get_course($courseid, false);
            if (!in_array($course->category, $childids)) {
                $errors[$course->id] = 'Not in the evasys_category!';
                continue;
            }
            if (empty($course->idnumber)) {
                $errors[$course->id] = 'Course does not have an idnumber!';
                continue;
            }
            if ($DB->record_exists(dbtables::EVAL_COURSES, ['courseid' => $course->id])) {
                $errors[$course->id] = 'Evaluation already exists!';
                continue;
            }
            if ($DB->record_exists(dbtables::EVAL_REQUESTS_COURSES, ['courseid' => $course->id])) {
                $errors[$course->id] = 'Evaluation request already exists!';
                continue;
            }

            $synchronizer = new evasys_synchronizer($course->id);
            $title = $synchronizer->get_raw_course_name($course->idnumber);

            if ($title === null) {
                self::insert_error([
                                'courseid' => $course->id,
                                'lsfid' => $course->idnumber,
                                'evasyscategoryid' => $category->get('id'),
                                'text' => 'Course does not exist in EvaSys!',
                                'type' => 1,
                                'usermodified' => $USER->id,
                                'timecreated' => time(),
                                'timemodified' => time()
                ]);
                $errors[$course->id] = 'Course does not exist in EvaSys!';
                continue;
            }

            $evaluation = new evaluation();
            $evaluation->initialcourse = $course->id;
            $evaluation->courses = [$course->id];

            $evaluation->evaluations = [$course->idnumber =>
                    (object)[
                        'lsfid' => $course->idnumber,
                        'title' => $title,
                        'start' => $category->get('standard_time_start'),
                        'end' => $category->get('standard_time_end'),
                        'state' => evaluation_state::MANUAL,
                    ]
            ];

            $synchronizer->sync_students();
            $teachers = get_users_by_capability(\context_course::instance($course->id), 'block/evasys_sync:teacherforcourse');

            $data = new \stdClass();
            $data->coordinator = $coordinatoruser->email;
            $data->start = userdate($category->get('standard_time_start'));
            $data->end = userdate($category->get('standard_time_end'));
            $data->courseshort = $course->shortname;
            $data->coursefull = $course->fullname;

            foreach ($teachers as $teacher) {
                email_to_user($teacher, $coordinatoruser,
                        get_string('notify_teacher_email_subject', 'block_evasys_sync', $data),
                        get_string('notify_teacher_email_body', 'block_evasys_sync', $data)
                );
            }

            self::clear_error($course->id);

            $evaluation->save();
        }
        return $errors;
    }

    public static function set_re_evaluation_for($courseids, evasys_category $category)
    {
        global $DB, $USER;
        $childids = \core_course_category::get($category->get('course_category'))->get_all_children_ids();
        $errors = [];
        foreach ($courseids as $courseid) {
            $course = get_course($courseid, false);

            if (!$eval = $DB->get_record(dbtables::EVAL_COURSES, ['courseid' => $course->id], 'evalid')) {
                $errors[$course->id] = 'Evaluation doesnt exist yet!';
                continue;
            }

            $DB->delete_records(dbtables::EVAL_VERANSTS, ['evalid' => $eval]);
            $DB->delete_records(dbtables::EVAL_COURSES, ['evalid' => $eval]);
            $DB->delete_records(dbtables::EVAL, ['id' => $eval]);

        }

        $errors[] = self::set_default_evaluation_for($courseids, $category);

        return $errors;
    }

    public static function insert_participants_for_evaluation(evaluation $evaluation) {
        $personlist = [];
        $errors = [];
        $soapclient = evasys_soap_client::get();
        foreach ($evaluation->courses as $courseid) {
            $courseusers = get_users_by_capability(\context_course::instance($courseid), 'block/evasys_sync:mayevaluate');
            foreach ($courseusers as $courseuser) {
                $personlist[$courseuser->id] = new \SoapVar([
                        new \SoapVar($courseuser->email, XSD_STRING, null, null, 'm_sIdentifier', null),
                        new \SoapVar($courseuser->email, XSD_STRING, null, null, 'm_sEmail', null),
                ], SOAP_ENC_OBJECT, null, null, 'Persons', null);
            }
        }
        $personlist = array_values($personlist);

        foreach ($evaluation->evaluations as $eval) {
            $soapresult = $soapclient->InsertParticipants($personlist, $eval->lsfid, 'PUBLIC', false);

            if (is_soap_fault($soapresult)) {
                $errors[$eval->lsfid] = 'Could not insert participants for courses ' . json_encode($evaluation->courses);
                continue;
            }

            $course = $soapclient->GetCourse($eval->lsfid, 'PUBLIC', true, true); // Update usercount.
            $usercountnow = $course->m_nCountStud;
            // The m_aSurveys element might be an empty object!
            if (!empty((array) $course->m_oSurveyHolder->m_aSurveys)) {
                if (is_array($course->m_oSurveyHolder->m_aSurveys->Surveys)) {
                    foreach ($course->m_oSurveyHolder->m_aSurveys->Surveys as $survey) {
                        $id = $survey->m_nSurveyId;
                        $soapclient->GetPswdsBySurvey($id, $usercountnow, 1, true, false); // Create new TANs.
                    }
                } else {
                    $id = $course->m_oSurveyHolder->m_aSurveys->Surveys->m_nSurveyId;
                    $soapclient->GetPswdsBySurvey($id, $usercountnow, 1, true, false); // Create new TANs.
                }
            }
        }
        return $errors;
    }

    public static function notify_evasys_coordinator_for_evaluation(evaluation $evaluation) {
        global $USER;
        $course = get_course($evaluation->courses[0]);
        $evasyscategory = evasys_category::for_course($course);
        $userto = \core_user::get_user($evasyscategory->get('userid'));

        $notiftext = "Sehr geehrte*r Evaluationskoordinator*in,\n\n" .
                "Dies ist eine automatisch generierte Mail, ausgelöst dadurch, dass ein*e Dozent*in die Evaluation " .
                "der nachfolgenden Veranstaltung beantragt hat.\n".
                "Bitte passen Sie die Evaluationszeiträume dem untenstehenden Wunsch an.\n";

        foreach ($evaluation->evaluations as $eval) {
            $notiftext .= "Veranstaltung: $eval->title\n" .
                    "ID: $eval->lsfid\n" .
                    "Evaluationszeitraum: " . userdate($eval->start) . " - " . userdate($eval->end) . "\n\n";
        }

        $notiftext .= "Mit freundlichen Grüßen\n";
        $notiftext .= "Learnweb-Support";

        email_to_user($userto, $USER, "Evaluation für $course->fullname beantragt", $notiftext);
    }

    public static function insert_error(array $error) {
        global $DB;
        if ($DB->record_exists(dbtables::ERRORS, ['courseid' => $error['courseid'], 'type' => $error['type'], 'timehandled' => null])) {
            return;
        }
        $DB->insert_record(dbtables::ERRORS, $error);
    }

    public static function clear_error($courseid) {
        global $DB;
        $DB->delete_records(dbtables::ERRORS, ['courseid' => $courseid]);
    }

}