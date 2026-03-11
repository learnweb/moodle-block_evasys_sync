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


use core\persistent;


/**
 * @property mixed course
 * @property string evasyscourses
 * @package block_evasys_sync
 */
class course_evasys_courses_allocation  extends persistent {
    const TABLE = 'block_evasys_sync_courses';
    /**
     * Return the definition of the properties of this model.
     *
     * @return array
     */
    protected static function define_properties() {
        return [
            'course' => [
                'type' => PARAM_INT,
                'message' => new \lang_string('invalidcourse', 'block_evasys_sync'),
            ],
            'evasyscourses' => [
                'type' => PARAM_TEXT,
                'message' => new \lang_string('invalidcourse', 'block_evasys_sync'),
            ],
        ];
    }

    /** Returns array of evasysids that belong to this course.
     * @param $courseid int id of moodle course
     * @return array of evasysids
     * @throws \dml_exception
     */
    public static function raw_get_evasyscourses($courseid) {
        global $DB;
        $courses = $DB->get_field(self::TABLE, 'evasyscourses', ['course' => $courseid]);
        $idcourse = $DB->get_field('course', 'idnumber', ['id' => $courseid]);
        $coursearray = [];
        if ($courses) {
            $coursearray = explode('#', $courses);
        }
        if ($idcourse) {
            if (!in_array($idcourse, $coursearray)) {
                $coursearray[] = $idcourse;
            }
        }
        array_unique($coursearray);
        return $coursearray;
    }
}
