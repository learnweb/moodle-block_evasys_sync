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
 * Category page displaying search results from managecategory.php for evasys course managers
 *
 * @package block_evasys_sync
 * @copyright 2023 Irina Hoppe Uni Münster
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use block_evasys_sync\course_manager_table;
use block_evasys_sync\local\table\error_courses_table;
use block_evasys_sync\local\table\invalid_courses_table;
use block_evasys_sync\local\table\manual_courses_table;
use block_evasys_sync\local\table\remaining_courses_table;
use block_evasys_sync\task\evasys_bulk_task;
use customfield_semester\data_controller;

require_once(__DIR__ . '/../../config.php');
global $CFG, $DB, $USER, $OUTPUT, $PAGE;
require_once($CFG->libdir . '/tablelib.php');

require_login();
$id = required_param('id', PARAM_INT);
$search = optional_param('search', null, PARAM_ALPHAEXT);

$category = core_course_category::get($id);
$evasyscategory = \block_evasys_sync\evasys_category::for_category($id);

$PAGE->set_url(new moodle_url('/blocks/evasys_sync/coursesearch.php', ['id' => $id]));
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('evasys_sync', 'block_evasys_sync'));

require_capability('block/evasys_sync:managecourses', $category->get_context());

$cache = cache::make('block_evasys_sync', 'mformdata');

$data = $cache->get('manageroverview');

$field = $DB->get_record('customfield_field', array('shortname' => 'semester', 'type' => 'semester'), '*', MUST_EXIST);
$fieldcontroller = \core_customfield\field_controller::create($field->id);
$datacontroller = \core_customfield\data_controller::create(0, null, $fieldcontroller);

if (!$data) {
    $data = new stdClass();
    $data->semester = $datacontroller->get_default_value();
}

$catids = array_merge($category->get_all_children_ids(), [$category->id]);

$errtable = new error_courses_table($catids, $data->semester ?? null, $evasyscategory, $search);
$errtable->define_baseurl($PAGE->url);

$reqtable = new course_manager_table($catids, $data->semester ?? null, $search);
$reqtable->define_baseurl($PAGE->url);

$remtable = new remaining_courses_table($catids, $data->semester ?? null, $evasyscategory,$search);
$remtable->define_baseurl($PAGE->url);

$mantable = new manual_courses_table($catids, $data->semester ?? null, $search);
$mantable->define_baseurl($PAGE->url);

$invtable = new invalid_courses_table($catids, $data->semester ?? null, $evasyscategory, $search);
$invtable->define_baseurl($PAGE->url);

$action = optional_param('action', null, PARAM_ALPHAEXT);
$forall = optional_param('all', 0, PARAM_INT);

switch ($action) {
    case 'clearerror':
        require_sesskey();
        if ($forall === 1) {
            $ids = $errtable->get_all_error_courseids();
        } else {
            $ids = required_param_array('ids', PARAM_INT);
        }
        list($insql, $params) = $DB->get_in_or_equal($ids, SQL_PARAMS_NAMED);
        $params['time'] = time();
        $params['evasyscat'] = $evasyscategory->get('id');
        $DB->execute('DELETE FROM {' . \block_evasys_sync\dbtables::ERRORS . '} ' .
            " WHERE evasyscategoryid = :evasyscat AND id $insql", $params);
        redirect($PAGE->url);
        break;
    case 'seteval':
        require_sesskey();
        if ($forall === 1) {
            $courses = $remtable->get_all_remaining_courseids();
        } else {
            $courses = required_param_array('ids', PARAM_INT);
        }
        $queuedtasks = \core\task\manager::get_adhoc_tasks(evasys_bulk_task::class);
        $tasksofcurrentmodule = array_filter($queuedtasks, fn($task) => $task->get_custom_data()->categoryid === $id);
        if(empty($tasksofcurrentmodule)){
            $task = new evasys_bulk_task();
            $data = new stdClass();
            $data->courses = (array) $courses;
            $data->categoryid = $id;
            $task->set_custom_data($data);
            $task->set_userid($USER->id);
            \core\task\manager::queue_adhoc_task($task, true);
        } else {
            \core\notification::warning(get_string("running_crontask", "block_evasys_sync"));
        }
        redirect($PAGE->url);
}

$PAGE->navigation->add('EvaSys', new moodle_url('/blocks/evasys_sync/manageroverview.php'))
    ->add(
        get_string('evaluations', 'block_evasys_sync') . ' in ' . data_controller::get_name_for_semester($data->semester),
        new moodle_url('/blocks/evasys_sync/managecategory.php', ['id' => $category->id])
    )->add(
        get_string('searchresults', 'block_evasys_sync'), new moodle_url('/blocks/evasys_sync/coursesearch.php', ['id' => $category->id, 'search' => $search])
    )->make_active();

echo $OUTPUT->header();

/* @var \block_evasys_sync\output\renderer $renderer  */
$renderer = $PAGE->get_renderer('block_evasys_sync');
$renderer->print_evasys_category_header($evasyscategory);

echo $OUTPUT->render_from_template('core/search_input', [
    'action' => (new moodle_url('/blocks/evasys_sync/coursesearch.php', array('id' => $id)))->out(false),
    'uniqid' => 'block_evasys_sync-search-courses',
    'inputname' => 'search',
    'extraclasses' => 'mb-3',
    'inform' => false,
    'searchstring' => get_string('search_for_courses', 'block_evasys_sync'),
    // Id as url param doesn't work, so set as hidden field.
    'hiddenfields' => [
        (object) ['type' => 'hidden', 'name' => 'id', 'value' => $id]
    ],
    'query' => $search
]);


// Output search results ordered by tables the courses are in.

$somethingfound = false;

$errtable->setup();
$errtable->query_db(48, false);
$errtable->build_table();
$errtable->close_recordset();
if ($errtable->started_output) {
    echo $OUTPUT->heading('<br>' . get_string('courses_with_errors', 'block_evasys_sync') . '<br>');
    $errtable->finish_output();
    $somethingfound = true;
}

$reqtable->setup();
$reqtable->query_db(48, false);
$reqtable->build_table();
$reqtable->close_recordset();
if ($reqtable->started_output) {
    echo $OUTPUT->heading('<br>' . get_string('courses_with_requests', 'block_evasys_sync') . '<br>');
    $reqtable->finish_output();
    $somethingfound = true;
}

$remtable->setup();
$remtable->query_db(48, false);
$remtable->build_table();
$remtable->close_recordset();
if ($remtable->started_output) {
    echo $OUTPUT->heading('<br>' . get_string('courses_without_evals', 'block_evasys_sync') . '<br>');
    $remtable->finish_output();
    $somethingfound = true;
}

$mantable->setup();
$mantable->query_db(48, false);
$mantable->build_table();
$mantable->close_recordset();
if ($mantable->started_output) {
    echo $OUTPUT->heading('<br>' . get_string('courses_with_manual_evals', 'block_evasys_sync') . '<br>');
    $mantable->finish_output();
    $somethingfound = true;
}

$invtable->setup();
$invtable->query_db(48, false);
$invtable->build_table();
$invtable->close_recordset();
if ($invtable->started_output) {
    echo $OUTPUT->heading('<br>' . get_string('courses_without_idnumber', 'block_evasys_sync') . '<br>');
    echo html_writer::tag('p', get_string('courses_without_idnumber_help', 'block_evasys_sync'));
    $invtable->finish_output();
    $somethingfound = true;
}

if (!$somethingfound) {
    echo $OUTPUT->heading(get_string('no_searchresults_found', 'block_evasys_sync'));
}

echo $OUTPUT->footer();
