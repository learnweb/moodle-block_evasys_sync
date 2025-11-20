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

class date_decoder {
    private static $instance = null;
    public $decoder;

    private static function get_instance(): date_decoder {
        if (self::$instance === null) {
            self::$instance = new date_decoder();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->decoder = [
            get_string('January', 'block_evasys_sync') => 1,
            get_string('February', 'block_evasys_sync') => 2,
            get_string('March', 'block_evasys_sync') => 3,
            get_string('April', 'block_evasys_sync') => 4,
            get_string('May', 'block_evasys_sync') => 5,
            get_string('June', 'block_evasys_sync') => 6,
            get_string('July', 'block_evasys_sync') => 7,
            get_string('August', 'block_evasys_sync') => 8,
            get_string('September', 'block_evasys_sync') => 9,
            get_string('October', 'block_evasys_sync') => 10,
            get_string('November', 'block_evasys_sync') => 11,
            get_string('December', 'block_evasys_sync') => 12,
        ];
    }

    public static function decode_from_localised_string(string $month): int {
        $d = self::get_instance();
        return $d->decoder[$month];
    }
}
