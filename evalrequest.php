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
 * Page to request or edit evaluations.
 *
 * @package block_evasys_sync
 * @copyright 2022 Justus Dieckmann WWU
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use block_evasys_sync\dbtables;

require_once(__DIR__ . '/../../config.php');
global $DB, $OUTPUT, $PAGE, $CFG;
require_once($CFG->libdir . '/form/datetimeselector.php');

$courseid = required_param('cid', PARAM_INT);
$course = get_course($courseid);

require_login($course);

$PAGE->set_url(new moodle_url('/blocks/evasys_sync/evalrequest.php', ['cid' => $courseid]));
$PAGE->set_context(context_course::instance($courseid));
$PAGE->set_title(get_string('evasys_sync', 'block_evasys_sync'));
$PAGE->set_heading(get_string('evasys_sync', 'block_evasys_sync'));

require_capability('block/evasys_sync:synchronize', $PAGE->context);
echo $OUTPUT->header();
$mform = new \block_evasys_sync\request_evaluation_form($course, $PAGE->url);

if ($data = $mform->get_simplified_data()) {
    echo json_encode($data);
}

$mform->display();

echo $OUTPUT->footer();