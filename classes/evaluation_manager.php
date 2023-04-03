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
            if (!isset($course->idnumber)) {
                $errors[$course->id] = 'Course does not have an idnumber!';
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
            $teacherroleid = $DB->get_record('role', ['shortname' => 'editingteacher'])->id;
            $teachers = get_role_users($teacherroleid, \context_course::instance($course->id));

            $data = new \stdClass();
            $data->coordinator = fullname($coordinatoruser);
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

            $evaluation->save();
        }
        return $errors;
    }

}