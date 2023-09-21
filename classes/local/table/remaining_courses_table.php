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
 * Table listing all courses to manage for the evasys course manager.
 *
 * @package    block_evasys_sync
 * @copyright  2022 Justus Dieckmann WWU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace block_evasys_sync\local\table;

use block_evasys_sync\dbtables;
use block_evasys_sync\evasys_category;
use moodle_url;

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir . '/tablelib.php');

/**
 * Table listing all courses to manage for the evasys course manager.
 *
 * @package    block_evasys_sync
 * @copyright  2022 Justus Dieckmann WWU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class remaining_courses_table extends \table_sql {

    private evasys_category $evasyscategory;

    private bool $showbuttons;

    private $allcourseids;

    /**
     * Constructor for course_manager_table.
     */
    public function __construct($categoryids, $semester, evasys_category $evasyscategory, $coursefullname = null, bool $showbuttons = true) {
        parent::__construct('block_evasys_sync-course_manager_table');
        global $DB, $PAGE, $OUTPUT;

        $this->evasyscategory = $evasyscategory;
        $this->showbuttons = $showbuttons;

        $fields = 'c.id as courseid, c.fullname as coursename, cfd.intvalue as semester';

        $semesterfield = $DB->get_record('customfield_field',
            ['shortname' => 'semester', 'type' => 'semester'], '*', MUST_EXIST);

        $from = '{course} c ' .
            'JOIN {customfield_data} cfd ON cfd.instanceid = c.id AND cfd.fieldid = :semesterfieldid ' .
            'LEFT JOIN {' . dbtables::EVAL_REQUESTS_COURSES . '} evreqc ON evreqc.courseid = c.id ' .
            'LEFT JOIN {' . dbtables::EVAL_COURSES . '} evalc ON evalc.courseid = c.id ';
        $params = ['semesterfieldid' => $semesterfield->id];
        $where = ["evreqc.id IS NULL and evalc.id IS NULL and c.idnumber <> ''"];

        if ($categoryids != null) {
            list($insql, $inparams) = $DB->get_in_or_equal($categoryids, SQL_PARAMS_NAMED);
            $where[] = "c.category $insql";
            $params = array_merge($params, $inparams);
        }

        if ($semester != null) {
            $where[] = 'cfd.intvalue = :semester';
            $params['semester'] = $semester;
        }

        if ($coursefullname) {
            $where[] = 'c.fullname LIKE :cname';
            $params['cname'] = '%' . $DB->sql_like_escape($coursefullname) . '%';
        }
        $where = join(" AND ", $where);

        $this->allcourseids = $DB->get_records_sql('SELECT c.id as id FROM ' . $from . ' WHERE ' . $where, $params);

        $this->set_sql($fields, $from, $where, $params);

        $this->column_nosort = ['select', 'teacher', 'tools'];
        $this->define_columns(['select', 'courseid', 'teacher', 'tools']);
        $this->define_headers([
                $OUTPUT->render(new \core\output\checkbox_toggleall('evasys-remaining', true, [
                        'id' => 'select-all-evasys-remaining',
                        'name' => 'select-all-evasys-remaining',
                        'label' => get_string('selectall'),
                        'labelclasses' => 'sr-only',
                        'classes' => 'm-1',
                        'checked' => false,
                ])),
                get_string('course'),
                get_string('teachers'),
                ''
        ]);

        $PAGE->requires->js_call_amd('block_evasys_sync/tablebulkactions', 'init');
    }

    public function col_select($row) {
        global $OUTPUT;
        $checkbox = new \core\output\checkbox_toggleall('evasys-remaining', false, [
                'classes' => 'usercheckbox m-1',
                'id' => 'evasys-remaining' . $row->courseid,
                'name' => 'bulkcheckbox-select',
                'value' => $row->courseid,
                'checked' => false,
                'label' => get_string('selectitem', 'moodle', $row->courseid),
                'labelclasses' => 'accesshide',
        ]);
        return $OUTPUT->render($checkbox);
    }

    public function col_courseid($row) {
        return \html_writer::link(course_get_url($row->courseid), $row->coursename);
    }

    public function col_teacher($row) {
        $users = get_users_by_capability(\context_course::instance($row->courseid), 'block/evasys_sync:teacherforcourse');
        $users = array_map(function($user) {
            return \html_writer::link(new moodle_url('/user/profile.php', ['id' => $user->id]), fullname($user));
        }, $users);
        return join(', ', $users);
    }

    /**
     * Render tools column.
     *
     * @param object $row Row data.
     * @return string
     */
    public function col_tools($row) {
        global $PAGE, $OUTPUT;
        if (!$this->evasyscategory->default_period_set() || !$this->showbuttons) {
            return '';
        }
        return $OUTPUT->render(new \single_button(new moodle_url($PAGE->url, ['action' => 'seteval', 'ids[]' => $row->courseid, 'id' => $this->evasyscategory->get('course_category')]),
                get_string('set_default_eval', 'block_evasys_sync')));
    }

    public function wrap_html_start() {
        global $OUTPUT;
        parent::wrap_html_start();

        if (!$this->evasyscategory->default_period_set()) {
            echo $OUTPUT->heading(get_string('set_default_period_for_default_eval', 'block_evasys_sync'), 5);
        }
    }

    public function wrap_html_finish() {
        global $OUTPUT, $PAGE;
        parent::wrap_html_finish();
        echo "<br>";

        if (!$this->evasyscategory->default_period_set() || !$this->showbuttons) {
            return;
        }

        echo $OUTPUT->render(new \single_button(new moodle_url($PAGE->url),
                get_string('set_default_eval_for_selected', 'block_evasys_sync'), 'post', \single_button::BUTTON_SECONDARY,
                ['data-evasys-action' => 'seteval']
        ));

        echo $OUTPUT->render(new \single_button(new moodle_url($PAGE->url),
                 get_string('set_default_eval_for_all', 'block_evasys_sync'), 'post', \single_button::BUTTON_SECONDARY,
                ['data-evasys-action' => 'seteval', 'data-evasys-forall' => 1]
        ));
    }

    /**
     * Returns all courses that are displayed in this table by courseid => coursename
     *
     * @return array
     */
    public function get_all_displayed_courses() {
        global $DB;

        $allcourses = $DB->get_records_sql('SELECT c.id as courseid, c.fullname as coursename FROM ' . $this->sql->from . ' WHERE ' . $this->sql->where, $this->sql->params);

        $courses = array();
        foreach ($allcourses as $course) {
            $courses[$course->courseid] = $course->coursename;
        }
        return $courses;
    }

    /**
     * Returns all ids of courses that do not have an evaluation yet
     *
     * @return array of courseids
     */
    public function get_all_remaining_courseids(): array
    {
        $ids = array();
        foreach ($this->allcourseids as $courseid) {
            $ids[] = $courseid->id;
        }
        return $ids;
    }

    /**
     * Filters the table by using set_sql()
     *
     * @param $courses array of type courseid => coursename
     * @return void
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function filter_courses($courses) {

        global $DB;

        list($insql, $inparams) = $DB->get_in_or_equal($courses, SQL_PARAMS_NAMED);
        $where = "c.id $insql";
        $params = array_merge($this->sql->params, $inparams);

        $where = $this->sql->where . ' AND ' . $where;
        $this->set_sql($this->sql->fields, $this->sql->from, $where, $params);
    }
}
