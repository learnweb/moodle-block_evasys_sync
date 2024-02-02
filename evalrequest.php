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
$mform = new \block_evasys_sync\local\form\request_evaluation_form($course, $PAGE->url);

if ($mform->is_cancelled()) {
    redirect(course_get_url($course));
}

if ($data = $mform->get_simplified_data()) {
    $evasyscategory = \block_evasys_sync\evasys_category::for_course($course);
    if (!$evasyscategory->can_teacher_request_evaluation()) {
        throw new Exception('Teachers cannot request evaluations!');
    }
    if ($evasyscategory->is_automatic()) {
        throw new Exception('Not yet implemented!'); // TODO automatic.
    } else {
        $evaluation = \block_evasys_sync\evaluation::from_eval_request($data);
        foreach ($evaluation->evaluations as $eval) {
            $eval->state = \block_evasys_sync\local\entity\evaluation_state::MANUAL;
        }
        $errors = \block_evasys_sync\evaluation_manager::insert_participants_for_evaluation($evaluation);
        if ($errors) {
            $erroroutput = '';
            foreach ($errors as $courseid => $error) {
                $erroroutput .= $courseid . ': ' . $error . '<br>';
            }
            \core\notification::error($erroroutput);
        } else {
            \block_evasys_sync\evaluation_manager::notify_evasys_coordinator_for_evaluation($evaluation);
            $evaluation->save();
            redirect(course_get_url($course));
        }
    }

}

$mform->display();

echo $OUTPUT->footer();