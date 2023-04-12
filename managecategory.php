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

use block_evasys_sync\dbtables;
use block_evasys_sync\local\entity\evaluation_state;
use customfield_semester\data_controller;

require_once(__DIR__ . '/../../config.php');
global $CFG, $DB, $USER, $OUTPUT, $PAGE;
require_once($CFG->libdir . '/tablelib.php');

require_login();

$id = required_param('id', PARAM_INT);

$category = core_course_category::get($id);
$evasyscategory = \block_evasys_sync\evasys_category::for_category($id);

$PAGE->set_url(new moodle_url('/blocks/evasys_sync/managecategory.php', ['id' => $id]));
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('evasys_sync', 'block_evasys_sync'));

require_capability('block/evasys_sync:managecourses', $category->get_context());

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

$PAGE->navigation->add('EvaSys', new moodle_url('/blocks/evasys_sync/manageroverview.php'))
        ->add(
            get_string('evaluations', 'block_evasys_sync') . ' in ' . data_controller::get_name_for_semester($data->semester),
            new moodle_url('/blocks/evasys_sync/managecategory.php', ['id' => $category->id])
        )->make_active();

list($inmanualsql, $params) = $DB->get_in_or_equal(evaluation_state::MANUAL_STATES, SQL_PARAMS_NAMED);
list($incatsql, $incatparams) = $DB->get_in_or_equal($catids, SQL_PARAMS_NAMED);
$params = array_merge($params, $incatparams);

$courseamounts = $DB->get_record_sql('SELECT (COUNT(evalc.id) - COUNT(evalmanualc.id)) as autoevalcourses, ' .
        'COUNT(evalmanualc.id) as manualevalcourses, COUNT(ereqc.id) as requestcourses, ' .
        '(COUNT(*) - COUNT(COALESCE(ereqc.id, evalc.id))) as remainingcourses, COUNT(errors.id) as errorcourses ' .
        'FROM {course} c '  .
        'JOIN {customfield_data} cfd ON cfd.instanceid = c.id AND cfd.fieldid = :semesterfieldid ' .
        'LEFT JOIN {' . dbtables::EVAL_REQUESTS_COURSES . '} ereqc ON ereqc.courseid = c.id ' .
        'LEFT JOIN {' . dbtables::EVAL_COURSES . '} evalc ON evalc.courseid = c.id ' .
        'LEFT JOIN (
            SELECT c1.courseid, c1.id FROM {' . dbtables::EVAL_COURSES . '} c1
            JOIN {' . dbtables::EVAL_VERANSTS . '} evalver1 ON evalver1.evalid = c1.evalid ' .
            "WHERE evalver1.state $inmanualsql " .
        ') evalmanualc ON evalmanualc.courseid = c.id ' .
        'LEFT JOIN {' . dbtables::ERRORS . '} errors ON errors.courseid = c.id AND errors.timehandled IS NULL ' .
        "WHERE cfd.intvalue = :semester AND " .
        "c.idnumber <> '' AND " .
        "c.category $incatsql ", array_merge(['semesterfieldid' => $field->id, 'semester' => $data->semester], $params)
);

echo $OUTPUT->header();

/* @var \block_evasys_sync\output\renderer $renderer  */
$renderer = $PAGE->get_renderer('block_evasys_sync');
$renderer->print_evasys_category_header($evasyscategory);

// TODO course search
/*echo $OUTPUT->render_from_template('core/search_input', [
        'action' => (new moodle_url('/blocks/evasys_sync/coursesearch.php', ['id' => $category->id]))->out(false),
        'uniqid' => 'block_evasys_sync-search-courses',
        'inputname' => 'search',
        'extraclasses' => 'mb-3',
        'inform' => false,
        'searchstring' => get_string('search_for_courses', 'block_evasys_sync')
]);*/

$mform->display();

$table = new flexible_table('block_evasys_sync-categoryoverview');

$table->define_headers(['', 'Amount of courses']);
$table->define_columns(['table', 'courses']);
$table->define_baseurl($PAGE->url);

$table->setup();

if ($courseamounts->errorcourses) {
    $table->add_data([
        html_writer::link(new moodle_url('/blocks/evasys_sync/managecategory_errors.php', ['id' => $id]),
                get_string('courses_with_errors', 'block_evasys_sync')), $courseamounts->errorcourses
    ], 'table-warning');
}

if ($evasyscategory->is_automatic() || $courseamounts->requestcourses) {
    $table->add_data([
        html_writer::link(new moodle_url('/blocks/evasys_sync/managecategory_requests.php', ['id' => $id]),
                get_string('courses_with_requests', 'block_evasys_sync')), $courseamounts->requestcourses
    ]);
}

if ($evasyscategory->is_automatic() || $courseamounts->autoevalcourses) {
    $table->add_data([
        html_writer::link(new moodle_url('/blocks/evasys_sync/managecategory_auto.php', ['id' => $id]),
                get_string('courses_with_automatic_evals', 'block_evasys_sync')), $courseamounts->autoevalcourses
    ]);
}

if (!$evasyscategory->is_automatic() || $courseamounts->manualevalcourses) {
    $table->add_data([
        html_writer::link(new moodle_url('/blocks/evasys_sync/managecategory_manual.php', ['id' => $id]),
                get_string('courses_with_manual_evals', 'block_evasys_sync')), $courseamounts->manualevalcourses
    ]);
}

$table->add_data([
        html_writer::link(new moodle_url('/blocks/evasys_sync/managecategory_remaining.php', ['id' => $id]),
                get_string('courses_without_evals', 'block_evasys_sync')), $courseamounts->remainingcourses
]);

$table->finish_output();

echo $OUTPUT->footer();