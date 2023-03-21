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
$evasyscategory = \block_evasys_sync\evasys_category::for_category($id);

$PAGE->set_url(new moodle_url('/blocks/evasys_sync/managecategory.php', ['id' => $id]));
$PAGE->set_context($category->get_context());
$PAGE->set_title(get_string('evasys_sync', 'block_evasys_sync'));

require_capability('block/evasys_sync:managecourses', $PAGE->context);

$cachekey = 'manageroverview';
$cache = cache::make('block_evasys_sync', 'mformdata');
$mform = new \block_evasys_sync\course_manager_filter_form($PAGE->url);

if ($data = $mform->get_data()) {
    $cache->set($cachekey, $data);
    redirect($PAGE->url);
} else if ($mform->is_cancelled()) {
    $cache->delete($cachekey);
    redirect($PAGE->url);
}

$data = $cache->get($cachekey);

$field = $DB->get_record('customfield_field', array('shortname' => 'semester', 'type' => 'semester'), '*', MUST_EXIST);
$fieldcontroller = \core_customfield\field_controller::create($field->id);
$datacontroller = \core_customfield\data_controller::create(0, null, $fieldcontroller);

if ($data) {
    $mform->set_data($data);
} else {
    $data = new stdClass();
    $data->semester = $datacontroller->get_default_value();
}

$catids = array_merge($category->get_all_children_ids(), [$category->id]);

$categorynode = $PAGE->navigation->find($category->id, navigation_node::TYPE_CATEGORY);
$evasysnode = $categorynode->add('Evaluations', new moodle_url('/blocks/evasys_sync/managecategory.php', ['id' => $category->id]));
$evasysnode->add(\customfield_semester\data_controller::get_name_for_semester($data->semester))->make_active();

$courseamounts = $DB->get_record_sql('SELECT COUNT(evalc.id) as evalcourses, COUNT(ereqc.id) as requestcourses, (COUNT(*) - COUNT(COALESCE(ereqc.id, evalc.id))) as remainingcourses FROM {course} c '  .
        'JOIN {customfield_data} cfd ON cfd.instanceid = c.id AND cfd.fieldid = :semesterfieldid ' .
        'LEFT JOIN {' . \block_evasys_sync\dbtables::EVAL_REQUESTS_COURSES . '} ereqc ON ereqc.courseid = c.id ' .
        'LEFT JOIN {' . \block_evasys_sync\dbtables::EVAL_COURSES . '} evalc ON evalc.courseid = c.id ' .
        "WHERE cfd.intvalue = :semester AND c.idnumber <> ''", ['semesterfieldid' => $field->id, 'semester' => $data->semester]
);

echo $OUTPUT->header();

/* @var \block_evasys_sync\output\renderer $renderer  */
$renderer = $PAGE->get_renderer('block_evasys_sync');
$renderer->print_evasys_category_header($evasyscategory);

echo $OUTPUT->render_from_template('core/search_input', [
        'action' => (new moodle_url('/blocks/evasys_sync/coursesearch.php', ['id' => $category->id]))->out(false),
        'uniqid' => 'block_evasys_sync-search-courses',
        'inputname' => 'search',
        'extraclasses' => 'mb-3',
        'inform' => false,
        'searchstring' => 'Search for courses'
]);

$mform->display();

$table = new flexible_table('block_evasys_sync-categoryoverview');

$table->define_headers(['', 'Amount of courses']);
$table->define_columns(['table', 'courses']);
$table->define_baseurl($PAGE->url);

$table->setup();

$table->add_data([
        html_writer::link(new moodle_url('/blocks/evasys_sync/managecategory_requests.php', ['id' => $id]),
                'Courses with pending requests'), $courseamounts->requestcourses
]);

$table->add_data([
        html_writer::link(new moodle_url('/blocks/evasys_sync/managecategory.php', ['id' => $id]),
                'Courses with (planned) evaluations'), $courseamounts->evalcourses
]);

$table->add_data([
        html_writer::link(new moodle_url('/blocks/evasys_sync/managecategory.php', ['id' => $id]),
                'Courses without evaluations or evaluation requests'), $courseamounts->remainingcourses
]);

$table->finish_output();

echo $OUTPUT->footer();