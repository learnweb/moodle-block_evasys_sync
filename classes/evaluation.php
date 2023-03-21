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

use block_evasys_sync\local\entity\evaluation_state;

defined('MOODLE_INTERNAL') || die();

/**
 * Evaluation entity.
 * @package block_evasys_sync
 * @copyright 2022 Justus Dieckmann WWU
 */
class evaluation {

    /** @var int|null ID of request. */
    public $id = null;

    /** @var int[] List of courses. */
    public array $courses;

    /** @var object[] List of evaluations. */
    public array $evaluations;

    public ?int $initialcourse = null;

    public static function from_eval_request(evaluation_request $request): evaluation {
        global $USER;
        $evaluation = new evaluation();
        $evaluation->courses = $request->courses;
        $evaluation->evaluations = [];
        $evaluation->initialcourse = $request->courseid;
        $time = time();
        foreach ($request->evaluations as $evalrequest) {
            $eval = new \stdClass();
            $eval->lsfid = $evalrequest->lsfid;
            $eval->title = $evalrequest->title;
            $eval->start = $evalrequest->start;
            $eval->end = $evalrequest->end;
            $eval->state = evaluation_state::PENDING;
            $eval->timecreated = $time;
            $eval->timemodified = $time;
            $eval->usermodified = $USER->id;
            $evaluation->evaluations[$eval->lsfid] = $eval;
        }
        return $evaluation;
    }

    public static function from_id(int $id): evaluation {
        global $DB;
        $evaluation = new evaluation();
        $record = $DB->get_record(dbtables::EVAL, [
            'id' => $id
        ]);
        $evaluation->id = $record->id;
        $evaluation->initialcourse = $record->initialcourse;

        $courses = $DB->get_fieldset_select(dbtables::EVAL_COURSES, 'courseid', 'evalid = :evalid', [
            'evalid' => $record->id
        ]);
        $evaluation->courses = $courses;
        $evalrecords = $DB->get_records(dbtables::EVAL_VERANSTS, [
            'evalid' => $record->id
        ]);
        $evaluations = [];
        foreach ($evalrecords as $evalrecord) {
            $eval = new \stdClass();
            $eval->id = $evalrecord->id;
            $eval->lsfid = $evalrecord->veranstid;
            $eval->title = $evalrecord->veransttitle;
            $eval->start = $evalrecord->starttime;
            $eval->end = $evalrecord->endtime;
            $eval->state = $evalrecord->state;
            $eval->usermodified = $evalrecord->modified;
            $eval->timecreated = $evalrecord->timecreated;
            $eval->timemodified = $evalrecord->timemodified;
            $evaluations[$eval->lsfid] = $eval;
        }
        $evaluation->evaluations = $evaluations;
        return $evaluation;
    }

    public static function for_course($courseid): ?evaluation {
        global $DB;
        $id = $DB->get_field(dbtables::EVAL_REQUESTS_COURSES, 'erequestid', ['courseid' => $courseid]);
        if ($id) {
            return self::from_id($id);
        } else {
            return null;
        }
    }

    private function update_generated_fields() {
        global $USER;
        $time = time();

        foreach ($this->evaluations as $eval) {
            if (!isset($eval->timecreated)) {
                $eval->timecreated = $time;
            }
            $eval->timemodified = $time;
            $eval->usermodified = $USER->id;
        }

        if (!isset($this->initialcourse)) {
            $this->initialcourse = $this->courses[0];
        }
    }

    public function save() {
        global $DB;
        $this->update_generated_fields();
        $transaction = $DB->start_delegated_transaction();
        $record = (object) [
            'id' => $this->id,
            'initialcourse' => $this->initialcourse,
        ];
        $existingcourses = [];
        $existingveransts = [];
        if ($record->id) {
            $DB->update_record(dbtables::EVAL, $record);

            // Delete outdated courses.
            list($insql, $inparams) = $DB->get_in_or_equal($this->courses, SQL_PARAMS_NAMED, 'param', false);
            $inparams['evalid'] = $record->id;
            $DB->delete_records_select(dbtables::EVAL_COURSES,
                "evalid = :evalid AND courseid $insql", $inparams);

            $existingveransts = $DB->get_records(dbtables::EVAL_VERANSTS, ['evalid' => $this->id], '', 'veranstid, id, veransttile, starttime, endttime');

            // Delete outdated lsf courses.
            list($insql, $inparams) = $DB->get_in_or_equal(array_keys($this->evaluations), SQL_PARAMS_NAMED, 'param', false);
            $inparams['evalid'] = $record->id;
            $DB->delete_records_select(dbtables::EVAL_VERANSTS,
                "evalid = :evalid AND veranstid $insql", $inparams);

            $existingcourses = $DB->get_records(dbtables::EVAL_COURSES, ['evalid' => $this->id], '', 'courseid, id');
        } else {
            $this->id = $DB->insert_record(dbtables::EVAL, $record);
        }
        foreach ($this->courses as $courseid) {
            if (!isset($existingcourses[$courseid])) {
                $DB->insert_record(dbtables::EVAL_COURSES, ['courseid' => $courseid, 'evalid' => $this->id]);
            }
        }

        foreach ($this->evaluations as $lsfcourseid => $evaluation) {
            if (isset($existingveransts[$lsfcourseid])) {
                $existingrecord = $existingveransts[$lsfcourseid];
                $existingrecord->veransttitle = $evaluation->title;
                $existingrecord->starttime = $evaluation->start;
                $existingrecord->endtime = $evaluation->end;
                $existingrecord->state = $evaluation->state;
                $existingrecord->usermodified = $evaluation->usermodified;
                $existingrecord->timecreated = $evaluation->timecreated;
                $existingrecord->timemodified = $evaluation->timemodified;
                $DB->update_record(dbtables::EVAL_VERANSTS, $existingrecord);
            } else {
                $DB->insert_record(dbtables::EVAL_VERANSTS, [
                    'evalid' => $this->id,
                    'veranstid' => $lsfcourseid,
                    'veransttitle' => $evaluation->title,
                    'starttime' => $evaluation->start,
                    'endtime' => $evaluation->end,
                    'state' => $evaluation->state,
                    'usermodified' => $evaluation->usermodified,
                    'timecreated' => $evaluation->timecreated,
                    'timemodified' => $evaluation->timemodified
                ]);
            }
        }

        $transaction->allow_commit();
    }

}