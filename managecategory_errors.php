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

use block_evasys_sync\local\table\error_courses_table;
use customfield_semester\data_controller;

require_once(__DIR__ . '/../../config.php');
global $DB, $USER, $OUTPUT, $PAGE;

require_login();

$id = required_param('id', PARAM_INT);

$category = core_course_category::get($id);
$evasyscategory = \block_evasys_sync\evasys_category::for_category($id);

$PAGE->set_url(new moodle_url('/blocks/evasys_sync/managecategory_errors.php', ['id' => $id]));
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('evasys_sync', 'block_evasys_sync'));

require_capability('block/evasys_sync:managecourses', $category->get_context());

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

$table = new error_courses_table($catids, $data->semester ?? null, $evasyscategory, $data->coursename ?? null);
$table->define_baseurl($PAGE->url);

$action = optional_param('action', null, PARAM_ALPHAEXT);
$forall = optional_param('all', 0, PARAM_INT);
if ($action === 'clearerror') {
    require_sesskey();
    if ($forall === 1) {
        $ids = $table->get_all_error_courseids();
    } else {
        $ids = required_param_array('ids', PARAM_INT);
    }
    list($insql, $params) = $DB->get_in_or_equal($ids, SQL_PARAMS_NAMED);
    $params['time'] = time();
    $params['evasyscat'] = $evasyscategory->get('id');
    $DB->execute('DELETE FROM {' . \block_evasys_sync\dbtables::ERRORS . '} ' .
        " WHERE evasyscategoryid = :evasyscat AND id $insql", $params);
    redirect($PAGE->url);
}

$PAGE->navigation->add('EvaSys', new moodle_url('/blocks/evasys_sync/manageroverview.php'))
        ->add(
                get_string('evaluations', 'block_evasys_sync') . ' in ' . data_controller::get_name_for_semester($data->semester),
                new moodle_url('/blocks/evasys_sync/managecategory.php', ['id' => $category->id])
        )->add(get_string('courses_with_errors', 'block_evasys_sync'), $PAGE->url)->make_active();

echo $OUTPUT->header();

/* @var \block_evasys_sync\output\renderer $renderer  */
$renderer = $PAGE->get_renderer('block_evasys_sync');
$renderer->print_evasys_category_header($evasyscategory);

$table->out(48, false);

echo $OUTPUT->footer();