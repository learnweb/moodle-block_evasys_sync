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
namespace block_evasys_sync;

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

    private int $teacherroleid;

    /**
     * Constructor for course_manager_table.
     */
    public function __construct($categoryids, $semester, $coursefullname = null) {
        parent::__construct('block_evasys_sync-course_manager_table');
        global $DB;

        $this->teacherroleid = $DB->get_record('role', ['shortname' => 'editingteacher'])->id;

        $fields = 'c.id as courseid, c.fullname as coursename, cfd.intvalue as semester';

        $semesterfield = $DB->get_record('customfield_field',
            ['shortname' => 'semester', 'type' => 'semester'], '*', MUST_EXIST);

        $from = '{course} c ' .
            'JOIN {customfield_data} cfd ON cfd.instanceid = c.id AND cfd.fieldid = :semesterfieldid ' .
            'LEFT JOIN {' . dbtables::EVAL_REQUESTS_COURSES . '} evreqc ON evreqc.courseid = c.id ' .
            'LEFT JOIN {' . dbtables::EVAL_COURSES . '} evalc ON evalc.courseid = c.id ';
        $params = ['semesterfieldid' => $semesterfield->id];
        $where = ['evreqc.id IS NULL and evalc.id IS NULL'];

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

        $this->set_sql($fields, $from, $where, $params);
        $this->column_nosort = ['tools'];
        $this->define_columns(['course', 'teacher', 'tools']);
        $this->define_headers([
                get_string('course'),
                get_string('teachers'),
                ''
        ]);
    }

    public function col_course($row) {
        return \html_writer::link(course_get_url($row->courseid), $row->coursename);
    }

    public function col_teacher($row) {
        $users = get_role_users($this->teacherroleid, \context_course::instance($row->courseid));
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
        return '';
    }
}
