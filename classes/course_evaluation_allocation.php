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
 * Class for loading/storing course-date pairs in the DB.
 *
 * @package block_evasys_sync
 * @copyright 2019 Robin Tschudi
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace block_evasys_sync;
use core\persistent;


class course_evaluation_allocation extends persistent {
    const TABLE = 'block_evasys_sync_courseeval';

    const STATE_AUTO_NOTOPENED = 0;
    const STATE_AUTO_OPENED = 1;
    const STATE_AUTO_CLOSED = 2;
    const STATE_MANUAL = 3;

    protected static function define_properties() {
        // State decoding:
        // 0: not opened.
        // 1: opened.
        // 2: closed.
        // 3: email sent.
        return [
            'course' => [
                'type' => PARAM_INT,
                'message' => new \lang_string('invalidcourse', 'block_evasys_sync'),
            ],
            'startdate' => [
                'type' => PARAM_INT,
                'message' => new \lang_string('invaliddate', 'block_evasys_sync'),
            ],
            'enddate' => [
                'type' => PARAM_INT,
                'message' => new \lang_string('invaliddate', 'block_evasys_sync'),
            ],
            'usestandardtime' => [
                'type' => PARAM_BOOL,
                'default' => false,
            ],
            'state' => [
                'type' => PARAM_INT,
                'required' => true,
                'message' => new \lang_string('invalidstate', 'block_evasys_sync'),
            ],
        ];
    }

    public static function get_record_by_course(int $course, $exception = true) {
        global $DB;
        if (!$record = $DB->get_record(self::TABLE, ['course' => $course])) {
            if (!$exception) {
                return false;
            } else {
                throw new \dml_missing_record_exception(self::TABLE);
            }
        }

        return new static(0, $record);
    }
}
