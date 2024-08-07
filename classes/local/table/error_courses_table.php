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
class error_courses_table extends \table_sql {

    private evasys_category $evasyscategory;

    private array $str;

    private array $allcourseids;

    /**
     * Constructor for course_manager_table.
     */
    public function __construct($categoryids, $semester, evasys_category $evasyscategory, $coursefullname = null) {
        parent::__construct('block_evasys_sync-course_manager_table');
        global $DB, $PAGE, $OUTPUT;

        $this->evasyscategory = $evasyscategory;
        $this->str = [
                'timeformat' => get_string('strftimedatetimeshort', 'langconfig')
        ];

        $fields = 'err.id, c.id as courseid, c.fullname as coursename, err.lsfid, err.text, err.timecreated as time';

        $semesterfield = $DB->get_record('customfield_field',
            ['shortname' => 'semester', 'type' => 'semester'], '*', MUST_EXIST);


        $from = '{' . dbtables::ERRORS . '} err ' .
            'LEFT JOIN {course} c ON err.courseid = c.id ' .
            'LEFT JOIN {customfield_data} cfd ON cfd.instanceid = c.id AND cfd.fieldid = :semesterfieldid ';

        $params = ['semesterfieldid' => $semesterfield->id];
        $where = ['err.timehandled IS NULL'];

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
        $this->define_columns(['select', 'courseid', 'teacher', 'lsfid', 'text', 'time', 'tools']);
        $this->define_headers([
                $OUTPUT->render(new \core\output\checkbox_toggleall('evasys-errors', true, [
                        'id' => 'select-all-evasys-errors',
                        'name' => 'select-all-evasys-errors',
                        'label' => get_string('selectall'),
                        'labelclasses' => 'sr-only',
                        'classes' => 'm-1',
                        'checked' => false,
                ])),
                get_string('course'),
                get_string('teachers'),
                'LSF-ID',
                get_string('error'),
                get_string('time'),
                ''
        ]);

        $PAGE->requires->js_call_amd('block_evasys_sync/tablebulkactions', 'init');
    }

    public function col_select($row) {
        global $OUTPUT;
        $checkbox = new \core\output\checkbox_toggleall('evasys-errors', false, [
                'classes' => 'usercheckbox m-1',
                'id' => 'evasys-errors' . $row->id,
                'name' => 'bulkcheckbox-select',
                'value' => $row->id,
                'checked' => false,
                'label' => get_string('selectitem', 'moodle', $row->id),
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

    public function col_time($row) {
        return userdate($row->time, $this->str['timeformat']);
    }

    /**
     * Render tools column.
     *
     * @param object $row Row data.
     * @return string
     */
    public function col_tools($row) {
        global $PAGE, $OUTPUT;
        return $OUTPUT->render(new \single_button(new moodle_url($PAGE->url, ['action' => 'clearerror', 'ids[]' => $row->id]),
                get_string('clear_error', 'block_evasys_sync')));
    }

    /**
     * Hook that can be overridden in child classes to wrap a table in a form
     * for example. Called only when there is data to display and not
     * downloading.
     */
    public function wrap_html_finish() {
        global $OUTPUT;
        parent::wrap_html_finish();
        echo "<br>";

        echo $OUTPUT->render(new \single_button(new moodle_url(''),
                get_string('clear_selected_errors', 'block_evasys_sync'), 'post', false,
                ['data-evasys-action' => 'clearerror']
        ));

        echo $OUTPUT->render(new \single_button(new moodle_url(''),
                get_string('clear_all_errors', 'block_evasys_sync'), 'post', false,
                ['data-evasys-action' => 'clearerror', 'data-evasys-forall' => 1]
        ));
    }

    /**
     * Returns all ids of courses that do not have an evaluation yet
     *
     * @return array
     */
    public function get_all_error_courseids() {
        $ids = array();
        foreach ($this->allcourseids as $courseid) {
            $ids[] = $courseid->id;
        }
        return $ids;
    }
}
