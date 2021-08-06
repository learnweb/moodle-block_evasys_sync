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

defined('MOODLE_INTERNAL') || die();
global $prefix;
$prefix = "behaterino";

function establish_secondary_DB_connection() { // phpcs:ignore @codingStandardsIgnoreLine
    return true;
}

function close_secondary_DB_connection() { // phpcs:ignore @codingStandardsIgnoreLine
    return true;
}

function set_up () {
    // Use a table to store behat mock data, so it's preserved over multiple site calls.
    global $DB, $prefix;
    $DB->execute("CREATE TABLE IF NOT EXISTS {$prefix}_lsf_mock_data (courseid INT PRIMARY KEY, veranstnr INT UNIQUE, semestertxt VARCHAR (50))");
}

function tear_down () {
    global $DB, $prefix;
    $DB->execute("DROP TABLE IF EXISTS {$prefix}_lsf_mock_data");
}

function get_course_by_veranstid ($courseid) {
     // Only return used values. fill the rest with dummys.
     global $DB, $prefix;
     // Moodle seemingly checks wether the requested relation exists according to the definitons of all plugins for functions like
     // get_record(). Since our mocktable is never defined to moodle, we have to use get_records_sql() which skips this check.
     $data = $DB->get_records_sql("SELECT * FROM {$prefix}_lsf_mock_data WHERE courseid = $courseid");
     $result = new \stdClass();
     $result->veranstid = 1;
     $result->veranstnr = $data[$courseid]->veranstnr;
     $result->semester = 1;
     $result->semestertxt = $data[$courseid]->semestertxt;
     $result->veranstaltungsart = 1;
     $result->titel = 1;
     $result->urlveranst = 1;
     return $result;
}

function set_course_to_veranstid ($id, $veranstnr, $semestertxt) {
    global $DB, $prefix;
    $id = intval($id);
    $veranstnr = intval($veranstnr);
    $DB->execute("INSERT INTO {$prefix}_lsf_mock_data VALUES ($id, $veranstnr, '$semestertxt')");
}