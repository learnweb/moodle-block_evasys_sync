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
 * Evaluation request form
 *
 * @package    block_evasys_sync
 * @copyright  2022 Justus Dieckmann WWU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace block_evasys_sync;

use moodleform;

defined('MOODLE_INTERNAL') || die;

global $CFG;
require_once($CFG->libdir . '/formslib.php');
require_once($CFG->dirroot . '/local/lsf_unification/lib_his.php');

/**
 * Evaluation request form
 *
 * @package    block_evasys_sync
 * @copyright  2022 Justus Dieckmann WWU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class request_evaluation_form extends moodleform {

    private $course;
    private $lsfcourses;
    private $defaulttimeframe;
    private $defaultdata;

    public function __construct($course, $action = null) {
        $this->course = $course;
        $this->init();
        parent::__construct($action, null, 'post', '', ['class' => 'evasys-request-mform']);
    }

    private function init() {
        global $USER, $PAGE;
        establish_secondary_DB_connection();
        $this->lsfcourses = [];
        foreach (get_teachers_course_list($USER->username) as $v) {
            $this->lsfcourses[$v->veranstid] = $v->info;
        }
        close_secondary_DB_connection();
        $this->defaulttimeframe = evasys_synchronizer::get_standard_timemode($this->course->category);
        $this->defaultdata = $this->build_expanded_default_data(null);
        $PAGE->requires->js_call_amd('block_evasys_sync/evaluation_request', 'init');
    }

    public function display()
    {
        parent::display();

        // Create template for date time selector for JS. This way instead of js argument in order to get rid of...
        // "Too much data"-warning. This is not nice, but if this is what it takes to have no warning...
        $a = new \MoodleQuickForm_date_time_selector('myevasyselementname', 'MyEvasysElementLabel', [], []);
        $a->setMoodleForm(new \MoodleQuickForm('', '', ''));
        $a->_createElements();
        $renderer = new \MoodleQuickForm_Renderer();
        $a->accept($renderer);
        echo '<div id="evasys-dateselectortemplate" hidden>';
        echo $renderer->_html;
        echo '</div>';
    }

    /**
     * Defines form elements.
     */
    protected function definition() {
        $mform = $this->_form;

        $mform->addElement('checkbox', 'donotevaluate', get_string('dont_evaluate_course', 'block_evasys_sync'));

        $mform->addElement('html', '<fieldset id="fitem_id_sectionone" class="fitem grouponly" name="sectionone">');
        $mform->disabledIf('sectionone', 'donotevaluate', 'checked');
        $mform->disabledIf('sectiontwo', 'donotevaluate', 'checked');

        $mform->addElement('checkbox', 'additionalveranstaltungen',
            get_string('eval_additional_lsfcourses', 'block_evasys_sync'));

        $mform->addElement('autocomplete', 'lsfcourses',
            get_string('course_units', 'block_evasys_sync'),
            $this->lsfcourses, ['multiple' => true]);

        $mform->hideIf('lsfcourses', 'additionalveranstaltungen', 'notchecked');

        $mform->addElement('checkbox', 'additionalcourses',
            get_string('eval_additional_courses', 'block_evasys_sync'));

        $mform->addElement('course', 'courses', get_string('courses'), ['multiple' => true]);
        $mform->hideIf('courses', 'additionalcourses', 'notchecked');

        $mform->addElement('html', '</fieldset>'); // Sectionone fieldset.

        $mform->addElement('header', 'evalperiod', get_string('evaluationperiod', 'block_evasys_sync'));

        $mform->addElement('html', '<fieldset id="fitem_id_sectiontwo" class="fitem grouponly" name="sectiontwo">');

        if ($this->defaulttimeframe) {
            $mform->addElement('checkbox', 'usedefaultevalperiod',
                get_string('use_default_evalperiod', 'block_evasys_sync',
                    userdate($this->defaulttimeframe['start']) . ' - ' . userdate($this->defaulttimeframe['end'])));
            $mform->setDefault('usedefaultevalperiod', 1);
        }

        $mform->addElement('checkbox', 'useoneevalperiod', get_string('useoneevalperiod', 'block_evasys_sync'));
        $mform->setDefault('useoneevalperiod', 1);

        $mform->addElement('date_time_selector', 'evaltimestartmulticourse',
            get_string('startondate', 'block_evasys_sync'));
        $mform->addElement('date_time_selector', 'evaltimeendmulticourse',
            get_string('endondate', 'block_evasys_sync'));

        $mform->addElement('date_time_selector', 'evaltimestartsinglecourse',
            get_string('startondate', 'block_evasys_sync'));
        $mform->addElement('date_time_selector', 'evaltimeendsinglecourse',
            get_string('endondate', 'block_evasys_sync'));

        if ($this->defaulttimeframe) {
            $mform->hideIf('useoneevalperiod', 'usedefaultevalperiod', 'checked');
            $mform->hideIf('evaltimestartmulticourse', 'usedefaultevalperiod', 'checked');
            $mform->hideIf('evaltimeendmulticourse', 'usedefaultevalperiod', 'checked');
            $mform->hideIf('evaltimestartsinglecourse', 'usedefaultevalperiod', 'checked');
            $mform->hideIf('evaltimeendsinglecourse', 'usedefaultevalperiod', 'checked');
            $mform->hideIf('evaltimes', 'usedefaultevalperiod', 'checked');
        }
        $mform->hideIf('evaltimestartmulticourse', 'useoneevalperiod', 'notchecked');
        $mform->hideIf('evaltimeendmulticourse', 'useoneevalperiod', 'notchecked');
        $mform->hideIf('evaltimes', 'useoneevalperiod', 'checked');

        $mform->hideIf('useoneevalperiod', 'additionalveranstaltungen', 'notchecked');
        $mform->hideIf('evaltimes', 'additionalveranstaltungen', 'notchecked');

        $mform->hideIf('evaltimestartsinglecourse', 'additionalveranstaltungen', 'checked');
        $mform->hideIf('evaltimeendsinglecourse', 'additionalveranstaltungen', 'checked');

        $mform->hideIf('evaltimestartmulticourse', 'additionalveranstaltungen', 'notchecked');
        $mform->hideIf('evaltimeendmulticourse', 'additionalveranstaltungen', 'notchecked');

        foreach (get_object_vars($this->defaultdata) as $key => $value) {
            $mform->setDefault($key, $value);
        }
    }

    public function definition_after_data() {
        $mform = $this->_form;
        $mform->addElement('html', '<fieldset id="fitem_id_evaltimes" class="fitem grouponly" name="evaltimes">');

        $selectedlsfcourses = [];
        if ($this->get_submitted_data() && $this->get_submitted_data()->lsfcourses) {
            foreach ($this->get_submitted_data()->lsfcourses as $id) {
                $selectedlsfcourses[$id] = $this->lsfcourses[$id];
            }
        } else {
            foreach ($this->defaultdata->lsfcourses as $id) {
                $selectedlsfcourses[$id] = $this->lsfcourses[$id];
            }
        }
        foreach ($selectedlsfcourses as $id => $title) {
            $mform->addElement('html', '<div class="pl-5" data-evasys-lsfid="' . $id . '"><h4 class="mb-3 mt-4">' .
                get_string('evaluationperiod_for', 'block_evasys_sync', $title) . '</h4>');
            $mform->addElement('date_time_selector', "evaltimestart_$id",
                get_string('startondate', 'block_evasys_sync'));
            $mform->addElement('date_time_selector', "evaltimeend_$id",
                get_string('endondate', 'block_evasys_sync'));
            $mform->addElement('html', '</div>');
        }

        $mform->addElement('html', '</fieldset>'); // Evaltimes fieldset.
        $mform->addElement('html', '</fieldset>'); // Sectiontwo fieldset.

        $this->add_action_buttons();
    }

    public function validation($data, $files) {
        // Unchecked checkboxes are not submitted, so we have to '?? false' them.
        $errors = [];
        if ($data['donotevaluate'] ?? false) {
            return $errors;
        }
        if (!$this->defaulttimeframe || !($data['usedefaultevalperiod'] ?? false)) {
            if ($data['additionalveranstaltungen'] ?? false) {
                if ($data['useoneevalperiod'] ?? false) {
                    if ($data['evaltimestartmulticourse'] >= $data['evaltimeendmulticourse']) {
                        $errors['evaltimeendmulticourse'] = get_string('err_endbeforestart', 'block_evasys_sync');
                    }
                } else {
                    foreach ($data['lsfcourses'] as $id) {
                        if ($data["evaltimestart_$id"] >= $data["evaltimeend_$id"]) {
                            $errors["evaltimeend_$id"] = get_string('err_endbeforestart', 'block_evasys_sync');
                        }
                    }
                }
            } else {
                if ($data['evaltimestartsinglecourse'] >= $data['evaltimeendsinglecourse']) {
                    $errors['evaltimeendsinglecourse'] = get_string('err_endbeforestart', 'block_evasys_sync');
                }
            }
        }
        return $errors;
    }


    private function build_expanded_default_data($data) {
        if (!$data) {
            $evaluations = [];
            if ($this->course->idnumber) {
                $evaluations = [
                    $this->course->idnumber => (object) [
                        'start' => $this->defaulttimeframe ? $this->defaulttimeframe['start'] : time(),
                        'end' => $this->defaulttimeframe ? $this->defaulttimeframe['end'] : time()
                    ]
                ];
            }
            $data = (object) [
                'evaluations' => $evaluations,
                'courses' => [$this->course->id],
                'donotevaluate' => false
            ];
        }
        $formdata = new \stdClass();
        $formdata->donotevaluate = $data->donotevaluate;
        $formdata->courses = $data->courses;
        $formdata->lsfcourses = array_keys($data->evaluations);
        $formdata->additionalcourses = count($data->courses) !== 1 || $data->courses[0] != $this->course->id;
        $formdata->additionalveranstaltungen = count($data->evaluations) !== 1 || array_keys($data->evaluations)[0] != $this->course->idnumber;
        if ($this->defaulttimeframe) {
            $formdata->usedefaultevalperiod = true;
        }
        $formdata->useoneevalperiod = true;
        $lasteval = null;
        foreach ($evaluations as $lsfcourseid => $evaluation) {
            if ($this->defaulttimeframe && ($evaluation->start != $this->defaulttimeframe['start'] || $evaluation->end != $this->defaulttimeframe['end'])) {
                $formdata->usedefaultevalperiod = false;
            }
            if ($lasteval && ($lasteval->start != $evaluation->start || $lasteval->end != $evaluation->end)) {
                $formdata->useoneevalperiod = false;
            }
            $startkey = "evaltimestart_$lsfcourseid";
            $endkey = "evaltimeend_$lsfcourseid";
            $formdata->$startkey = $evaluation->start;
            $formdata->$endkey = $evaluation->end;
            $lasteval = $evaluation;
        }
        $formdata->evaltimestartsinglecourse = $formdata->useoneevalperiod ? $lasteval->start : null;
        $formdata->evaltimeendsinglecourse = $formdata->useoneevalperiod ? $lasteval->end : null;
        $formdata->evaltimestartmulticourse = $formdata->useoneevalperiod ? $lasteval->start : null;
        $formdata->evaltimeendmulticourse = $formdata->useoneevalperiod ? $lasteval->end : null;
        return $formdata;
    }

    public function get_simplified_data() {
        // Force definition_after_data() to be called if not done yet.
        $this->is_validated();
        $formdata = $this->get_data();
        if (!$formdata) {
            return null;
        }
        $data = new eval_request();
        $data->shouldevaluate = !($formdata->donotevaluate ?? false);
        $data->courses = $formdata->courses;
        $data->evaluations = [];
        $defaultstart = $this->defaulttimeframe && ($formdata->usedefaultevalperiod ?? false) ? $this->defaulttimeframe['start'] : (
            ($formdata->additionalveranstaltungen ?? false) ? (
                ($formdata->useoneevalperiod ?? false) ? $formdata->evaltimestartmulticourse : null
            ) : $formdata->evaltimestartsinglecourse
        );
        $defaultend = $this->defaulttimeframe && ($formdata->usedefaultevalperiod ?? false) ? $this->defaulttimeframe['end'] : (
            ($formdata->additionalveranstaltungen ?? false) ? (
                ($formdata->useoneevalperiod ?? false) ? $formdata->evaltimeendmulticourse : null
            ) : $formdata->evaltimeendsinglecourse
        );
        foreach ($formdata->lsfcourses as $lsfcourseid) {
            $startkey = "evaltimestart_$lsfcourseid";
            $endkey = "evaltimeend_$lsfcourseid";
            $data->evaluations[$lsfcourseid] = (object) [
                'lsfid' => $lsfcourseid,
                'title' => $this->lsfcourses[$lsfcourseid],
                'start' => $defaultstart ?? $formdata->$startkey,
                'end' => $defaultend ?? $formdata->$endkey
            ];
        }
        return $data;
    }

}