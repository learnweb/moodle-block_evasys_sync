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
        $status = optional_param('status', "", PARAM_TEXT);

        // Return cached content if there is any.
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

        // If there has been a status in the url, show the prompt.
        $this->display_status($status);

        $evasyscategory = \block_evasys_sync\evasys_category::for_course($this->page->course);

        if (!$evasyscategory) {
            $this->content->text = 'This course isn\'t inside any evaluation category!';
            return $this->content;
        }

        // If we are not in sync mode, we display either the course mapping or the check status button.
        if ($evasyssynccheck !== 1) {
            $inlsf = !empty($this->page->course->idnumber);

            // Display the check status button.
            if ($inlsf) {
                $href = new moodle_url('/course/view.php', array('id' => $this->page->course->id, "evasyssynccheck" => true));
                $this->content->text .= $OUTPUT->single_button($href, get_string('checkstatus', 'block_evasys_sync'), 'get');
            } else {
                $this->content->text .= get_string('missing_course_id', 'block_evasys_sync');
            }
            return $this->content;
        }

        $evalrequest = \block_evasys_sync\evaluation_request::for_course($this->page->course->id);
        $evaluations = \block_evasys_sync\evaluation::for_course($this->page->course->id);

        $categoryhasstandardtime = $evasyscategory->default_period_set();
        // Only use standardtime js if no record exists.
        if (!$evaluations) {
            $this->page->requires->js_call_amd('block_evasys_sync/standardtime', 'init');
        }

        $evasyssynchronizer = new block_evasys_sync\evasys_synchronizer($this->page->course->id);
        try {
            $evasyscourses = $evasyssynchronizer->get_allocated_courses();
        } catch (Exception $exception) {
            \core\notification::warning(get_string('syncnotpossible', 'block_evasys_sync'));
            $this->content->text .= html_writer::div(get_string('syncnotpossible', 'block_evasys_sync'));
            return $this->content;
        }

        $href = new moodle_url('/blocks/evasys_sync/sync.php');

        // Initialize data for mustache template.
        $startdisabled = false;
        $enddisabled = false;
        $emailsentnotice = false;
        $periodsetnotice = false;

        // Set start to today and end to a week from now.
        $start = time();
        $oneweeklater = $time = new \DateTime();
        $oneweeklater->add(new \DateInterval("P7D"));
        $end = $oneweeklater->getTimestamp();

        // See if there are any students that can evaluate.
        // If there are no students we disable all controls.
        $nostudents = (count_enrolled_users(
                        context_course::instance($this->page->course->id), 'block/evasys_sync:mayevaluate') == 0);

        if ($evaluations) {
            $emailsentnotice = true;
            $first = array_key_first($evaluations->evaluations);
            $start = $evaluations->evaluations[$first]->start;
            $end = $evaluations->evaluations[$first]->end;
        } elseif ($evasyscategory->default_period_set()) {
            $start = $evasyscategory->get('standard_time_start');
            $end = $evasyscategory->get('standard_time_end');
        }

        // This javascript module sets the start and end fields to the correct values.
        $jsmodestring = 'manual';
        $jsmodestring .= $enddisabled ? '_closed' : '_open';
        $this->page->requires->js_call_amd('block_evasys_sync/initialize', 'init', array($start, $end, $jsmodestring));


        // Initialize variables to pass to mustache.
        $courses = array();
        $hassurveys = false;
        $startoption = ($startdisabled xor $enddisabled);
        $warning = false;
        $invalidcourses = false;
        // Query course data (put in function).
        foreach ($evasyscourses as $evasyscourseinfo) {
            $course = array();
            $course['evasyscoursetitle'] = $evasyssynchronizer->get_course_name($evasyscourseinfo);
            $course['technicalid'] = $evasyssynchronizer->get_course_id($evasyscourseinfo);
            $course['evasyscourseid'] = $evasyscourseinfo;
            $course['c_participants'] = format_string($evasyssynchronizer->get_amount_participants($evasyscourseinfo));
            $rawsurveys = $evasyssynchronizer->get_surveys($evasyscourseinfo);
            $surveys = array();
            foreach ($rawsurveys as $rawsurvey) {
                $survey = array();
                $survey['formName'] = format_string($rawsurvey->formName);
                $survey['surveystatus'] = get_string('surveystatus' . $rawsurvey->surveyStatus, 'block_evasys_sync');
                $survey['amountOfCompleteForms'] = format_string($rawsurvey->amountOfCompletedForms);

                if (!$evaluations && $rawsurvey->surveyStatus == 'closed') {
                    // In case of a manual evaluation get the status of the evaluation by...
                    // checking whether the evaluations are closed.
                    $emailsentnotice = false;
                    $startdisabled = true;
                    $enddisabled = true;
                    $startoption = true;
                }

                // Append this survey.
                $surveys[] = $survey;
                $hassurveys = true;
            }
            // If any course has an unkown technical id, we don't want to allow synchronization.
            if ($course['technicalid'] == "Unknown") {
                $invalidcourses = true;
            }

            $course['surveys'] = $surveys;
            // Append this course.
            $courses[] = $course;
        }

    $standardttimemode = !$evaluations && $categoryhasstandardtime;
        $hisconnection = get_config('block_evasys_sync', 'default_his_connection');

        // Create the data object for the mustache table.
        $data = array(
                'href' => $href,
                'sesskey' => sesskey(),
                'courseid' => $this->page->course->id,
                'courses' => $courses,
            /* In case of the manual workflow, we can start synchronisation also, if no surveys are registered, yet.
            * In case of the automated workflow, we require surveys
            * in order to be able to automatically trigger the evaluation. */
                'showcontrols' => count($evasyscourses) > 0 && !$invalidcourses && $evasyscategory->can_teacher_request_evaluation(),
                'usestandardtimelayout' => $standardttimemode,
            // Choose mode.
                'direct' => false,
                'startdisabled' => $startdisabled || $standardttimemode,
                'enddisabled' => $enddisabled || $standardttimemode,
                'onlyend' => $startdisabled && !$standardttimemode,
                'disablesubmit' => $enddisabled,
            // If the evaluation hasn't ended yet, display option to restart it.
                'startoption' => $startoption,
            // Only allow coursemapping before starting an evaluation.
                'coursemappingenabled' => $hisconnection and (!$startdisabled or is_siteadmin()),
                'nostudents' => $nostudents,
                'emailsentnotice' => $emailsentnotice,
                'evaluationperiodsetnotice' => $periodsetnotice,
            // Defines if an lsf course is already mapped to the moodle course.
                'optional' => !empty($evasyscourses),
            // Outputs a warning that there are open course when there shouldn't.
                'warning' => $warning
        );

        $this->content->text .= $OUTPUT->render_from_template("block_evasys_sync/block", $data);

        if (!$evasyscategory->can_teacher_request_evaluation()) {
            $this->content->text .= get_string('teacher_request_disabled', 'block_evasys_sync');
        }

        $this->content->footer = '';
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

