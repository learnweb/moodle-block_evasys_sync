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

/**
 * Class for loading/storing user-category pairs in the DB.
 *
 * @package block_evasys_sync
 * @copyright 2017 Tamara Gunkel
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_evasys_sync;

defined('MOODLE_INTERNAL') || die;

use core\persistent;

/**
 * Class for loading/storing user-category pairs in the DB.
 *
 * @copyright 2017 Tamara Gunkel
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class evasys_category extends persistent {

    const TABLE = 'block_evasys_sync_categories';

    const MASK_TEACHER_CAN_REQUEST_EVALUATION = 1 << 0;
    const MASK_EVALUATION_REQUEST_NEEDS_APPROVAL = 1 << 1;
    const MASK_TEACHER_CAN_CHANGE_EVALUATION  = 1 << 2;
    const MASK_EVALUATION_CHANGE_NEEDS_APPROVAL = 1 << 3;

    /**
     * Return the definition of the properties of this model.
     *
     * @return array
     */
    protected static function define_properties() {
        return array(
            'userid' => array(
                'type' => PARAM_INT,
                'message' => new \lang_string('invaliduserid', 'error')
            ),
            'course_category' => array(
                'type' => PARAM_INT,
                'message' => new \lang_string('invalidcoursecat', 'block_evasys_sync')
            ),
            'category_mode' => array(
                'type' => PARAM_INT,
                'message' => new \lang_string('invalidmode', 'block_evasys_sync')
            ),
            'mode_flags' => array(
                'type' => PARAM_INT
            ),
            'standard_time_start' => array (
                'type' => PARAM_INT,
                'message' => new \lang_string('invalid_standard_time_mode', 'block_evasys_sync'),
                'null' => NULL_ALLOWED,
                'default' => null
            ),
            'standard_time_end' => array (
                'type' => PARAM_INT,
                'message' => new \lang_string('invalid_standard_time_mode', 'block_evasys_sync'),
                'null' => NULL_ALLOWED,
                'default' => null
            )
        );
    }

    public static function for_course($course): ?evasys_category {
        return self::for_category($course->category);
    }

    public static function for_category($categoryid): ?evasys_category {
        $record = evasys_category::get_record(['course_category' => $categoryid]);
        if ($record) {
            return $record;
        }
        // Loop through parents.
        $parents = \core_course_category::get($categoryid)->get_parents();
        for ($i = count($parents) - 1; $i >= 0; $i--) {
            $record = evasys_category::get_record(['course_category' => $parents[$i]]);
            // Stop if a parent has been assigned a custom record.
            if ($record) {
                return $record;
            }
        }
        return null;
    }

    /**
     * Validate the user ID.
     *
     * @param $value
     * @return bool|\lang_string
     */
    protected function validate_userid($value) {
        if (!\core_user::is_real_user($value, true)) {
            return new \lang_string('invaliduserid', 'error');
        }
        return true;
    }

    public function can_teacher_request_evaluation() : bool {
        return $this->get('mode_flags') & self::MASK_TEACHER_CAN_REQUEST_EVALUATION;
    }
}