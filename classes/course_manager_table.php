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
class course_manager_table extends \table_sql {

    /**
     * Constructor for course_manager_table.
     */
    public function __construct($categoryids, $semester, $coursefullname = null) {
        parent::__construct('block_evasys_sync-course_manager_table');
        global $DB;

        $fields = 'c.id as courseid, c.fullname as coursename, ' .
            'cfd.intvalue as semester, ' .
            'evreq.id as erequestid, evreq.state, evreq.shouldevaluate, ' .
            'evreqccount.coursecount, ' .
            'evreqvcount.veranstcount, ' .
            'evreqv.veranstid, evreqv.veransttitle, evreqv.starttime, evreqv.endtime';

        $semesterfield = $DB->get_record('customfield_field',
            ['shortname' => 'semester', 'type' => 'semester'], '*', MUST_EXIST);

        $from = '{course} c ' .
            'LEFT JOIN {customfield_data} cfd ON cfd.instanceid = c.id AND cfd.fieldid = :semesterfieldid ' .
            'LEFT JOIN {' . dbtables::EVAL_REQUESTS_COURSES . '} evreqc ON evreqc.courseid = c.id ' .
            'LEFT JOIN {' . dbtables::EVAL_REQUESTS . '} evreq ON evreqc.erequestid = evreq.id ' .
            'LEFT JOIN (' .
                'SELECT erequestid, COUNT(courseid) as coursecount FROM {' . dbtables::EVAL_REQUESTS_COURSES . '} ' .
                'GROUP BY erequestid' .
            ') evreqccount ON evreqccount.erequestid = evreq.id ' .
            'LEFT JOIN (' .
                'SELECT erequestid, MIN(id) as minveranstid, COUNT(veranstid) as veranstcount FROM {' . dbtables::EVAL_REQUESTS_VERANSTS . '} ' .
                'GROUP BY erequestid' .
            ') evreqvcount ON evreqvcount.erequestid = evreq.id ' .
            'LEFT JOIN {' . dbtables::EVAL_REQUESTS_VERANSTS . '} evreqv ON evreqv.id = evreqvcount.minveranstid';
        $params = ['semesterfieldid' => $semesterfield->id];
        $where = ['TRUE'];

        if ($categoryids != null) {
            list($insql, $inparams) = $DB->get_in_or_equal($categoryids, SQL_PARAMS_NAMED);
            $where[] = "c.categoryid $insql";
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
        $this->define_columns(['course', 'teacher', 'requestinfo', 'tools']);
        $this->define_headers([
                get_string('course'),
                get_string('teachers'),
                get_string('status'),
                ''
        ]);
    }

    public function col_course($row) {
        global $DB;
        $output = \html_writer::link(course_get_url($row->courseid), $row->coursename);
        if (!$row->coursecount || $row->coursecount == 1) {
            return $output;
        }

        $othercourses = $DB->get_records_sql('SELECT c.id, c.fullname FROM {course} c ' .
        'JOIN {' . dbtables::EVAL_REQUESTS_COURSES . '} evreqc ON evreqc.courseid = c.id ' .
        'WHERE evreqc.erequestid = :erequestid', ['erequestid' => $row->erequestid]);
        foreach ($othercourses as $othercourse) {
            if ($othercourse->id == $row->courseid) {
                continue;
            }
            $output .= '<br>' . \html_writer::link(course_get_url($othercourse->id), $othercourse->fullname, ['class' => 'ml-3 small']);
        }
        return $output;
    }

    public function col_teacher($row) {
        $users = get_users_by_capability(\context_course::instance($row->courseid), 'moodle/course:update');
        $users = array_map(function($user) {
            return \html_writer::link(new moodle_url('/user/profile.php', ['id' => $user->id]), fullname($user));
        }, $users);
        return join(', ', $users);
    }

    public function col_requestinfo($row) {
        global $DB;

        if (!$row->erequestid) {
            return '';
        }
        $output = '';
        if ($row->state == 0) {
            $output .= 'Pending your approval:<br>';
        } else {
            $output .= 'Pending the teachers approval:<br>';
        }
        if (!$row->shouldevaluate) {
            $output .= 'Should not be evalutated!';
            return $output;
        }

        if ($row->veranstcount > 1) {
            $evaluations = $DB->get_records(dbtables::EVAL_REQUESTS_VERANSTS, ['erequestid' => $row->erequestid]);
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
        global $PAGE, $OUTPUT;
        if (!$row->erequestid) {
            return '';
        }
        if ($row->state == 0) {
            return $OUTPUT->render(new \single_button(new moodle_url(''), get_string('approve'), 'post', true)).
                \html_writer::link(new moodle_url(''), get_string('details'), ['class' => 'ml-2 btn btn-secondary']);
        }
        return '';
    }
}
