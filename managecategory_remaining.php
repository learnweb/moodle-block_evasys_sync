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

use block_evasys_sync\local\table\remaining_courses_table;
use block_evasys_sync\task\evasys_bulk_task;
use customfield_semester\data_controller;

require_once(__DIR__ . '/../../config.php');
global $DB, $USER, $OUTPUT, $PAGE;

require_login();

$id = required_param('id', PARAM_INT);

$category = core_course_category::get($id);
$evasyscategory = \block_evasys_sync\evasys_category::for_category($id);

$PAGE->set_url(new moodle_url('/blocks/evasys_sync/managecategory_remaining.php', ['id' => $id]));
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('evasys_sync', 'block_evasys_sync'));

require_capability('block/evasys_sync:managecourses', $category->get_context());

$cachekey = 'manageroverview';
$cache = cache::make('block_evasys_sync', 'mformdata');

$data = $cache->get($cachekey);

$action = optional_param('action', null, PARAM_ALPHAEXT);
if ($action === 'seteval') {
    require_sesskey();
    $courses = required_param_array('ids', PARAM_INT);
    $queuedtasks = \core\task\manager::get_adhoc_tasks(evasys_bulk_task::class);
    $tasksofcurrentmodule = array_filter($queuedtasks, fn($task) => $task->get_custom_data()->categoryid === $id);
    if(empty($tasksofcurrentmodule)){
        $task = new evasys_bulk_task();
        $data = new stdClass();
        $data->courses = $courses;
        $data->categoryid = $id;
        $task->set_custom_data($data);
        $task->set_userid($USER->id);
        \core\task\manager::queue_adhoc_task($task, true);
    } else {
        \core\notification::warning(get_string("running_crontask", "block_evasys"));
    }
    redirect($PAGE->url);
}

$field = $DB->get_record('customfield_field', array('shortname' => 'semester', 'type' => 'semester'), '*', MUST_EXIST);
$fieldcontroller = \core_customfield\field_controller::create($field->id);
$datacontroller = \core_customfield\data_controller::create(0, null, $fieldcontroller);

if (!$data) {
    $data = new stdClass();
    $data->semester = $datacontroller->get_default_value();
}

$catids = array_merge($category->get_all_children_ids(), [$category->id]);

$queuedtasks = \core\task\manager::get_adhoc_tasks(evasys_bulk_task::class);
$tasksofcurrentmodule = array_filter($queuedtasks, fn($task) => $task->get_custom_data()->categoryid === $id);

$table = new remaining_courses_table($catids, $data->semester ?? null, $evasyscategory, $data->coursename ?? null, empty($tasksofcurrentmodule));
$table->define_baseurl($PAGE->url);

$PAGE->navigation->add('EvaSys', new moodle_url('/blocks/evasys_sync/manageroverview.php'))
        ->add(
                get_string('evaluations', 'block_evasys_sync') . ' in ' . data_controller::get_name_for_semester($data->semester),
                new moodle_url('/blocks/evasys_sync/managecategory.php', ['id' => $category->id])
        )->add(get_string('courses_without_evals', 'block_evasys_sync'), $PAGE->url)->make_active();

echo $OUTPUT->header();

/* @var \block_evasys_sync\output\renderer $renderer  */
$renderer = $PAGE->get_renderer('block_evasys_sync');
$renderer->print_evasys_category_header($evasyscategory);

if(!empty($tasksofcurrentmodule)){
    \core\notification::warning(get_string("running_crontask", "block_evasys"));
}

$table->out(48, false);

echo $OUTPUT->footer();