<?php
// This file is part of the Moodle plugin block_evasys_sync
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

namespace block_evasys_sync;

defined('MOODLE_INTERNAL') || die();

/**
 * Manager for eval requests.
 * @package block_evasys_sync
 * @copyright 2022 Justus Dieckmann WWU
 */
class eval_request {

    /** @var int|null ID of request. */
    public $id = null;

    /** @var int[] List of courses. */
    public array $courses;

    /** @var object[] List of evaluations. */
    public array $evaluations;

    /** @var bool Should be evaluated? */
    public bool $shouldevaluate = false;

    /** @var int|null State of the request */
    public $state = 0;

    public int $evasyscategoryid;

    public int $courseid;

    public int $timemodified;

    public int $timecreated;

    public int $usermodified;

    public static function from_id(int $id): eval_request {
        global $DB;
        $evalrequest = new eval_request();
        $record = $DB->get_record(dbtables::EVAL_REQUESTS, [
            'id' => $id
        ]);
        $evalrequest->id = $record->id;
        $evalrequest->shouldevaluate = $record->shouldevaluate;
        $evalrequest->state = $record->state;
        $evalrequest->courseid = $record->courseid;
        $evalrequest->evasyscategoryid = $record->evasyscategoryid;
        $evalrequest->timecreated = $record->timecreated;
        $evalrequest->timemodified = $record->timemodified;
        $evalrequest->usermodified = $record->usermodified;

        $courses = $DB->get_fieldset_select(dbtables::EVAL_REQUESTS_COURSES, 'courseid', 'erequestid = :ereqid', [
            'ereqid' => $record->id
        ]);
        $evalrequest->courses = $courses;
        $evalrecords = $DB->get_records(dbtables::EVAL_REQUESTS_VERANSTS, [
            'erequestid' => $record->id
        ]);
        $evaluations = [];
        foreach ($evalrecords as $evalrecord) {
            $eval = new \stdClass();
            $eval->id = $evalrecord->id;
            $eval->lsfid = $evalrecord->veranstid;
            $eval->title = $evalrecord->veransttitle;
            $eval->start = $evalrecord->starttime;
            $eval->end = $evalrecord->endtime;
            $evaluations[$eval->veranstid] = $eval;
        }
        $evalrequest->evaluations = $evaluations;
        return $evalrequest;
    }

    public static function get_evasyscategory_for($course) {
        global $DB;

        $record = $DB->get_record('block_evasys_sync_categories', array('course_category' => $course->category));
        if ($record) {
            return $record;
        }
        // Loop through parents.
        $parents = \core_course_category::get($course->category)->get_parents();
        for ($i = count($parents) - 1; $i >= 0; $i--) {
            $record = $DB->get_record('block_evasys_sync_categories', array('course_category' => $parents[$i]));
            // Stop if a parent has been assigned a custom record.
            if ($record) {
                return $record;
            }
        }
        return null;

    }

    private function update_generated_fields() {
        global $USER;
        $time = time();
        if (!isset($this->timecreated)) {
            $this->timecreated = $time;
        }
        $this->timemodified = $time;
        $this->usermodified = $USER->id;
        if (!isset($this->courseid)) {
            $this->courseid = $this->courses[0];
        }
        if (!isset($this->evasyscategoryid)) {
            $this->evasyscategoryid = self::get_evasyscategory_for(get_course($this->courseid))->id ?? null;
        }
    }

    public function save() {
        global $DB, $USER;
        $this->update_generated_fields();
        $transaction = $DB->start_delegated_transaction();
        $record = (object) [
            'id' => $this->id,
            'courseid' => $this->courseid,
            'state' => $this->state,
            'shouldevaluate' => $this->shouldevaluate,
            'evasyscategoryid' => $this->evasyscategoryid,
            'usermodified' => $this->usermodified ?? $USER->id,
            'timecreated' => $this->timecreated ?? time(),
            'timemodified' => $this->timemodified ?? time()
        ];
        $existingcourses = [];
        $existingveransts = [];
        if ($record->id) {
            $DB->update_record(dbtables::EVAL_REQUESTS, $record);

            // Delete outdated courses.
            list($insql, $inparams) = $DB->get_in_or_equal($this->courses, SQL_PARAMS_NAMED, 'param', false);
            $inparams['erequestid'] = $record->id;
            $DB->delete_records_select(dbtables::EVAL_REQUESTS_COURSES,
                "erequestid = :erequestid AND courseid $insql", $inparams);

            $existingveransts = $DB->get_records(dbtables::EVAL_REQUESTS_VERANSTS, ['erequestid' => $this->id], '', 'veranstid, id, veransttile, starttime, endttime');

            // Delete outdated lsf courses.
            list($insql, $inparams) = $DB->get_in_or_equal(array_keys($this->evaluations), SQL_PARAMS_NAMED, 'param', false);
            $inparams['erequestid'] = $record->id;
            $DB->delete_records_select(dbtables::EVAL_REQUESTS_VERANSTS,
                "erequestid = :erequestid AND veranstid $insql", $inparams);

            $existingcourses = $DB->get_records(dbtables::EVAL_REQUESTS_COURSES, ['erequestid' => $this->id], '', 'courseid, id');
        } else {
            $this->id = $DB->insert_record(dbtables::EVAL_REQUESTS, $record);
        }
        foreach ($this->courses as $courseid) {
            if (!isset($existingcourses[$courseid])) {
                $DB->insert_record(dbtables::EVAL_REQUESTS_COURSES, ['courseid' => $courseid, 'erequestid' => $this->id]);
            }
        }

        foreach ($this->evaluations as $lsfcourseid => $evaluation) {
            if (isset($existingveransts[$lsfcourseid])) {
                $existingrecord = $existingveransts[$lsfcourseid];
                if ($evaluation->title == $existingrecord->veransttitle &&
                    $evaluation->start == $existingrecord->starttime &&
                    $evaluation->end == $existingrecord->endtime) {
                    continue;
                }
                $existingrecord->veransttitle = $evaluation->title;
                $existingrecord->starttime = $evaluation->start;
                $existingrecord->endtime = $evaluation->end;
                $DB->update_record(dbtables::EVAL_REQUESTS_VERANSTS, $existingrecord);
            } else {
                $DB->insert_record(dbtables::EVAL_REQUESTS_VERANSTS, [
                    'erequestid' => $this->id,
                    'veranstid' => $lsfcourseid,
                    'veransttitle' => $evaluation->title,
                    'starttime' => $evaluation->start,
                    'endtime' => $evaluation->end
                ]);
            }
        }

        $transaction->allow_commit();
    }

}