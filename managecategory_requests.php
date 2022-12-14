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

$PAGE->set_url(new moodle_url('/blocks/evasys_sync/managecategory_requests.php', ['id' => $id]));
$PAGE->set_context($category->get_context());
$PAGE->set_title(get_string('evasys_sync', 'block_evasys_sync'));

require_capability('block/evasys_sync:managecourses', $PAGE->context);

$cachekey = 'manageroverview';
$cache = cache::make('block_evasys_sync', 'mformdata');

$data = $cache->get($cachekey);

$field = $DB->get_record('customfield_field', array('shortname' => 'semester', 'type' => 'semester'), '*', MUST_EXIST);
$fieldcontroller = \core_customfield\field_controller::create($field->id);
$datacontroller = \core_customfield\data_controller::create(0, null, $fieldcontroller);

if (!$data) {
    $data = new stdClass();
    $data->semester = $datacontroller->get_default_value();
}

$catids = array_merge($category->get_all_children_ids(), [$category->id]);

$table = new \block_evasys_sync\course_manager_table($catids, $data->semester ?? null,
        $data->coursename ?? null);
$table->define_baseurl($PAGE->url);

$categorynode = $PAGE->navigation->find($category->id, navigation_node::TYPE_CATEGORY);
$evasysnode = $categorynode->add('Evaluations', new moodle_url('/blocks/evasys_sync/managecategory.php', ['id' => $category->id]));
$semesternode = $evasysnode->add(\customfield_semester\data_controller::get_name_for_semester($data->semester));
$semesternode->add('Requests', $PAGE->url)->make_active();

echo $OUTPUT->header();

echo $OUTPUT->box_start('generalbox border p-3 mb-3');

echo html_writer::tag('h2', $category->name);

echo html_writer::start_div('text-muted');
echo html_writer::span('No default evaluation period set.') . '<br>';
echo html_writer::span('Teachers can plan an evaluation <b>with</b> your approval.') . '<br>';
echo html_writer::span('Teachers can change an existing evaluation <b>without</b> your approval.') . '<br>';
echo html_writer::end_div() . '<br>';
echo html_writer::link(new moodle_url('/blocks/evasys_sync/editcategory.php', ['id' => $id]), get_string('edit'));

echo $OUTPUT->box_end();

$table->out(48, false);

echo $OUTPUT->footer();