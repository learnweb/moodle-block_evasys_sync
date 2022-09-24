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
 * Course overview for teachers.
 *
 * @package block_evasys_sync
 * @copyright 2022 Justus Dieckmann WWU
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use block_evasys_sync\dbtables;

require_once(__DIR__ . '/../../config.php');
global $DB, $OUTPUT, $PAGE;

$courseid = required_param('id', PARAM_INT);

require_login($courseid);

$PAGE->set_url(new moodle_url('/blocks/evasys_sync/courseoverview.php', ['id' => $courseid]));
$PAGE->set_context(context_course::instance($courseid));
$PAGE->set_title(get_string('evasys_sync', 'block_evasys_sync'));
$PAGE->set_heading(get_string('evasys_sync', 'block_evasys_sync'));

require_capability('block/evasys_sync:synchronize', $PAGE->context);

$evalid = $DB->get_record(dbtables::EVAL_COURSES, ['courseid' => $courseid])->evalid ?? null;
if ($evalid) {
    $evalcourses = $DB->get_records(dbtables::EVAL_COURSES, ['evalid' => $evalid]);
    $evalveransts = $DB->get_records(dbtables::EVAL_VERANSTS, ['evalid' => $evalid]);
}
$evalrequestid = $DB->get_record(dbtables::EVAL_REQUESTS_COURSES, ['courseid' => $courseid])->erequestid ?? null;
if ($evalrequestid) {
    $evalrequest = $DB->get_record(dbtables::EVAL_REQUESTS, ['id' => $evalrequestid]);
    $evalrequestcourses = $DB->get_records(dbtables::EVAL_REQUESTS_COURSES, ['erequestid' => $evalrequestid]);
    $evalrequestveransts = $DB->get_records(dbtables::EVAL_REQUESTS_VERANSTS, ['erequestid' => $evalrequestid]);
}

echo $OUTPUT->header();

echo $OUTPUT->footer();