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

/**
 * Created by PhpStorm.
 * User: robintschudi
 * Date: 08.11.19
 * Time: 16:11
 */

namespace block_evasys_sync;

use Behat\Behat\Tester\Exception\PendingException;
use core_privacy\local\deprecated;
use Matrix\Exception;

defined('MOODLE_INTERNAL') || die();

function establish_secondary_DB_connection() { // phpcs:ignore @codingStandardsIgnoreLine
    return true;
}

function close_secondary_DB_connection() { // phpcs:ignore @codingStandardsIgnoreLine
    return true;
}

function get_course_by_veranstid ($courseid) {
    // Only return used values. fill the rest with dummys.
    global $COURSE;
    $moodleid = $COURSE->id;
    require_once(__DIR__. './../tests/behat/behat_block_evasys_sync.php');
    $fulldata = \behat_block_evasys_sync::get_coursedata_by_courseid($moodleid);
    $coursedata = $fulldata->evacourses[$courseid];

    $result = new \stdClass();
    if (!$coursedata->valid) {
        $result->veranstid = null;
        $result->veranstnr = null;
        $result->semester = null;
        $result->semestertxt = null;
        $result->veranstaltungsart = null;
        $result->titel = null;
        $result->urlveranst = null;
        // If the coursedata is not valid we dont assign the evasyskey to lsfkey.
        // First this could easily be overwritten because all invalid cousrses have evasyskey " ".
        // Second we check for " " being the evasyskey in the evasysapi before ever accessing $coursedata->valid.
    } else {
        $result->veranstid = 1;
        $result->veranstnr = $coursedata->veranstnr;
        $result->semester = 1;
        $result->semestertxt = $coursedata->semestertxt;
        $result->veranstaltungsart = 1;
        $result->titel = 1;
        $result->urlveranst = 1;
        // Associate evasyskey with lsfkey for easier reverse lookup.
        \behat_block_evasys_sync::$evalsfkeyassoc[$coursedata->veranstnr . " " . $coursedata->semestertxt] = $courseid;
    }
    return $result;
}
