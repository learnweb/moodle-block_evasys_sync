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

use block_evasys_sync\course_evaluation_allocation;

defined('MOODLE_INTERNAL') || die();

class block_evasys_sync extends block_base{

    /**
     * Initializes the block.
     */
    public function init() {
        $this->title = get_string('evasys_sync', 'block_evasys_sync');
    }

    /**
     * Returns the content object
     *
     * @return object
     */
    public function get_content() {
        global $OUTPUT;
        $evasyssynccheck = optional_param('evasyssynccheck', 0, PARAM_BOOL);

        // There should never be content, so if there is, we want to output that.
        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->text = '';

        // Students shouldn't see the evasys-block, so we'll output empty html.
        $access = has_capability('block/evasys_sync:synchronize', context_course::instance($this->page->course->id));
        if (!$access) {
            return $this->content;
        }

        $evasyscategory = \block_evasys_sync\evasys_category::for_course($this->page->course);
        if (!$evasyscategory) {
            return $this->content;
        }

        $evalrequest = \block_evasys_sync\evaluation_request::for_course($this->page->course->id);
        $evaluations = \block_evasys_sync\evaluation::for_course($this->page->course->id);

        $stringformat = get_string('strftimedatetime', 'langconfig');

        if ($evaluations) {
            $this->content->text .= html_writer::tag('h5', get_string('evaluations', 'block_evasys_sync'));
            foreach ($evaluations->evaluations as $evaluation) {
                $this->content->text .= html_writer::tag('p', '<b>' . $evaluation->title . '</b><br>' .
                        get_string('startondate', 'block_evasys_sync') . ': ' . userdate($evaluation->start, $stringformat) . '<br>' .
                        get_string('endondate', 'block_evasys_sync') . ': ' . userdate($evaluation->end, $stringformat));
            }
        } else if ($evalrequest) {
            $this->content->text .= '<p>Request pending approval: </p><pre>' . json_encode($evalrequest, JSON_PRETTY_PRINT) . '</pre>';
            $this->content->text .= html_writer::link(
                new moodle_url('/blocks/evasys_sync/evalrequest.php', ['cid' => $this->page->course->id]),
                'Change evaluation request', ['class' => 'btn btn-secondary']
            );
        } else {
            $this->content->text .= html_writer::tag('p', get_string('no_eval_planned', 'block_evasys_sync'));
            if ($evasyscategory->can_teacher_request_evaluation()) {
                $this->content->text .= html_writer::link(
                    new moodle_url('/blocks/evasys_sync/evalrequest.php', ['cid' => $this->page->course->id]),
                    get_string('request_eval', 'block_evasys_sync'), ['class' => 'btn btn-primary']
                );
            } else {
                $this->content->text .= html_writer::tag('p', get_string('evaluation_will_be_created_for_you', 'block_evasys_sync'));
            }
        }
        return $this->content;
    }


    /**
     * Display a helpful prompt for a given status
     * @param $status String is supposed to be success uptodate nostudents or failure.
     */
    public function display_status($status) {
        if ($status === 'success') {
            $this->page->requires->js_call_amd('block_evasys_sync/post_dialog', 'show_dialog_success');
        } else if ($status === 'uptodate') {
            $this->page->requires->js_call_amd('block_evasys_sync/post_dialog', 'show_dialog_up_to_date');
        } else if ($status === 'nostudents') {
            $this->page->requires->js_call_amd('block_evasys_sync/post_dialog', 'show_dialog_no_students');
        } else if ($status === 'failure') {
            $this->page->requires->js_call_amd('block_evasys_sync/post_dialog', 'show_dialog_failure');
        }
    }

    /**
     * The Block is only available at course-view pages
     *
     * @return array
     */
    public function applicable_formats() {
        return array('course-view' => true, 'mod' => false, 'my' => false);
    }

    /**
     * Allow the block to have a configuration page
     *
     * @return boolean
     */
    public function has_config() {
        return true;
    }
}

