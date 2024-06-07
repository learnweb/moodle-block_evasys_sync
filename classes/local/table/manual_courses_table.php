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
use block_evasys_sync\local\entity\evaluation_state;
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
class manual_courses_table extends \table_sql {

    /**
     * Constructor for course_manager_table.
     */
    public function __construct($categoryids, $semester, $coursefullname = null) {
        parent::__construct('block_evasys_sync-course_manager_table');
        global $DB;

        $fields = 'c.id as courseid, c.fullname as course, ' .
            'cfd.intvalue as semester,' .
            'eval.id as evalid, ' .
            'evalccount.coursecount, ' .
            'evalvcount.veranstcount, ' .
            'evalv.veranstid, evalv.veransttitle, evalv.starttime, evalv.endtime';

        $semesterfield = $DB->get_record('customfield_field',
            ['shortname' => 'semester', 'type' => 'semester'], '*', MUST_EXIST);

        $from = '{course} c ' .
            'JOIN {customfield_data} cfd ON cfd.instanceid = c.id AND cfd.fieldid = :semesterfieldid ' .
            'JOIN {' . dbtables::EVAL_COURSES . '} evalc ON evalc.courseid = c.id ' .
            'LEFT JOIN {' . dbtables::EVAL . '} eval ON evalc.evalid = eval.id ' .
            'LEFT JOIN (' .
                'SELECT evalid, COUNT(courseid) as coursecount FROM {' . dbtables::EVAL_COURSES . '} ' .
                'GROUP BY evalid' .
            ') evalccount ON evalccount.evalid = eval.id ' .
            'LEFT JOIN (' .
                'SELECT evalid, MIN(id) as minveranstid, COUNT(veranstid) as veranstcount FROM {' . dbtables::EVAL_VERANSTS . '} ' .
                'GROUP BY evalid' .
            ') evalvcount ON evalvcount.evalid = eval.id ' .
            'LEFT JOIN {' . dbtables::EVAL_VERANSTS . '} evalv ON evalv.id = evalvcount.minveranstid';
        $params = ['semesterfieldid' => $semesterfield->id];

        list($insql, $inparams) = $DB->get_in_or_equal(evaluation_state::MANUAL_STATES, SQL_PARAMS_NAMED);
        $params = array_merge($params, $inparams);
        $where = ['evalv.state ' . $insql];

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
        $this->column_nosort = ['teacher', 'evalinfo', 'tools'];
        $this->define_columns(['course', 'teacher', 'evalinfo', 'tools']);
        $this->define_headers([
                get_string('course'),
                get_string('teachers'),
                get_string('status'),
                ''
        ]);
    }

    public function col_course($row) {
        global $DB;
        $output = \html_writer::link(course_get_url($row->courseid), $row->course);
        if (!$row->coursecount || $row->coursecount == 1) {
            return $output;
        }

        $othercourses = $DB->get_records_sql('SELECT c.id, c.fullname FROM {course} c ' .
        'JOIN {' . dbtables::EVAL_COURSES . '} evalc ON evalc.courseid = c.id ' .
        'WHERE evalc.evalid = :evalid', ['evalid' => $row->evalid]);
        foreach ($othercourses as $othercourse) {
            if ($othercourse->id == $row->courseid) {
                continue;
            }
            $output .= '<br>' . \html_writer::link(course_get_url($othercourse->id), $othercourse->fullname, ['class' => 'ml-3 small']);
        }
        return $output;
    }

    public function col_teacher($row) {
        $users = get_users_by_capability(\context_course::instance($row->courseid), 'block/evasys_sync:teacherforcourse');
        $users = array_map(function($user) {
            return \html_writer::link(new moodle_url('/user/profile.php', ['id' => $user->id]), fullname($user));
        }, $users);
        return join(', ', $users);
    }

    public function col_evalinfo($row) {
        global $DB;

        if (!$row->evalid) {
            return '';
        }
        $output = '';
        /*if ($row->state == 0) {
            $output .= 'Pending your approval:<br>';
        } else {
            $output .= 'Pending the teachers approval:<br>';
        }*/

        if ($row->veranstcount > 1) {
            $evaluations = $DB->get_records(dbtables::EVAL_VERANSTS, ['evalid' => $row->erequestid]);
        } else {
            $evaluations = [$row];
        }

        foreach ($evaluations as $evaluation) {
            $output .= $evaluation->veransttitle . '<br><span class="d-inline-block small ml-3">' .
             userdate($evaluation->starttime) . ' - ' . userdate($evaluation->endtime) . '</span><br>';
        }

        return $output;
    }

    /**
     * Render tools column.
     *
     * @param object $row Row data.
     * @return string
     */
    public function col_tools($row) {
        return '';
    }
}
