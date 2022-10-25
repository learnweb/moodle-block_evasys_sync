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

$PAGE->set_url(new moodle_url('/blocks/evasys_sync/manageroverview.php'));
$PAGE->set_context($category->get_context());
$PAGE->set_title(get_string('evasys_sync', 'block_evasys_sync'));

require_capability('block/evasys_sync:managecourses', $PAGE->context);

$cachekey = 'manageroverview';
$cache = cache::make('block_evasys_sync', 'mformdata');
$mform = new \block_evasys_sync\course_manager_filter_form();

if ($data = $mform->get_data()) {
    $cache->set($cachekey, $data);
    redirect($PAGE->url);
} else if ($mform->is_cancelled()) {
    $cache->delete($cachekey);
    redirect($PAGE->url);
}

$data = $cache->get($cachekey);

if ($data) {
    $mform->set_data($data);
} else {
    $data = new stdClass();
    if ($field = $DB->get_record('customfield_field', array('shortname' => 'semester', 'type' => 'semester'))) {
        $fieldcontroller = \core_customfield\field_controller::create($field->id);
        $datacontroller = \core_customfield\data_controller::create(0, null, $fieldcontroller);
        $data->semester = $datacontroller->get_default_value();
    }
    $data->coursename = '';
}

$catids = array_merge($category->get_all_children_ids(), [$category->id]);

$table = new \block_evasys_sync\course_manager_table($catids, $data->semester ?? null,
        $data->coursename ?? null);
$table->define_baseurl($PAGE->url);

echo $OUTPUT->header();

$mform->display();

$table->out(48, false);

echo $OUTPUT->footer();