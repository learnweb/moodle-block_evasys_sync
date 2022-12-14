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
 * Category page for evasys course managers.
 *
 * @package block_evasys_sync
 * @copyright 2022 Justus Dieckmann WWU
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
global $DB, $USER, $OUTPUT, $PAGE;

require_login();

$id = required_param('id', PARAM_INT);

$category = core_course_category::get($id);

$title = get_string('evasys_settings_for', 'block_evasys_sync', $category->name);
$PAGE->set_url(new moodle_url('/blocks/evasys_sync/editcategory.php', ['id' => $id]));
$PAGE->set_context($category->get_context());
$PAGE->set_title($title);

require_capability('block/evasys_sync:managecourses', $PAGE->context);

$evasyscat = \block_evasys_sync\evasys_category::for_category($id);
if (!$evasyscat) {
    throw new coding_exception('No evasys category found for that course cat id!');
}

$mform = new \block_evasys_sync\evasys_category_edit_form($PAGE->url);

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/blocks/evasys_sync/managecategory.php', ['id' => $id]));
}

if ($data = $mform->get_data()) {
    // TODO Save and load.
}

echo $OUTPUT->header();

echo $OUTPUT->heading($title, 2);

$mform->display();

echo $OUTPUT->footer();
