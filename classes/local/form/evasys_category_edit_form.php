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
 * Filter moodle form for course manager overview table.
 *
 * @package    block_evasys_sync
 * @copyright  2022 Justus Dieckmann WWU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace block_evasys_sync\local\form;

use block_evasys_sync\evasys_category;
use moodleform;

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir . '/formslib.php');

/**
 * Moodle form for course manager to edit their category settings.
 *
 * @package    block_evasys_sync
 * @copyright  2022 Justus Dieckmann WWU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class evasys_category_edit_form extends moodleform {

    private ?evasys_category $evasys_category;

    public function __construct(int $evasyscategoryid, $action = null, $customdata = null, $method = 'post', $target = '', $attributes = null, $editable = true, $ajaxformdata = null) {
        $this->evasys_category = new evasys_category($evasyscategoryid);
        parent::__construct($action, $customdata, $method, $target, $attributes, $editable, $ajaxformdata);
    }

    /**
     * Defines form elements.
     */
    protected function definition() {
        $mform = $this->_form;

        $mform->addElement('checkbox', 'activate_standard_time',
                get_string('activate_standard_time', 'block_evasys_sync')
        );

        $mform->addElement('date_time_selector', 'standard_time_start',
                get_string('standart_time_start', 'block_evasys_sync'));
        $mform->addElement('date_time_selector', 'standard_time_end',
                get_string('standart_time_end', 'block_evasys_sync'));
        $mform->disabledIf('standard_time_start', 'activate_standard_time');
        $mform->disabledIf('standard_time_end', 'activate_standard_time');

        $mform->addElement('html', '<br><br>');

        $mform->addElement('checkbox', 'teacher_can_request_evaluation',
                get_string('teacher_can_request_evaluation', 'block_evasys_sync'));
        $mform->setDefault('teacher_can_request_evaluation', true);

        $mform->addElement('checkbox', 'teacher_evaluation_request_needs_approval',
                get_string('teacher_evaluation_request_needs_approval', 'block_evasys_sync'));
        $mform->disabledIf('teacher_evaluation_request_needs_approval', 'teacher_can_request_evaluation');

        $mform->addElement('checkbox', 'automatic_task_creation',
            get_string('automatic_task_creation', 'block_evasys_sync'));

        /*$mform->addElement('checkbox', 'teacher_can_change_evaluation',
                get_string('teacher_can_change_evaluation', 'block_evasys_sync'));

        $mform->addElement('checkbox', 'teacher_evaluation_change_needs_approval',
                get_string('teacher_evaluation_change_needs_approval', 'block_evasys_sync'));
        $mform->disabledIf('teacher_evaluation_change_needs_approval', 'teacher_can_change_evaluation');*/

        $timeperiodset = $this->evasys_category->get('standard_time_start') && $this->evasys_category->get('standard_time_end');
        $mform->setDefault('activate_standard_time', $timeperiodset);
        if ($timeperiodset) {
            $mform->setDefault('standard_time_start', $this->evasys_category->get('standard_time_start'));
            $mform->setDefault('standard_time_end', $this->evasys_category->get('standard_time_end'));
        }
        $mform->setDefault('teacher_can_request_evaluation', $this->evasys_category->can_teacher_request_evaluation());
        $mform->setDefault('teacher_evaluation_request_needs_approval', $this->evasys_category->teacher_evaluation_request_needs_approval());
        $mform->setDefault('automatic_task_creation', $this->evasys_category->has_automatic_task_creation());

        $this->add_action_buttons();
    }

    function get_data_transformed(): ?evasys_category {
        if (!$data = $this->get_data()) {
            return null;
        }

        $evasyscat = new evasys_category($this->evasys_category->get('id'));
        $flags = 0;
        if ($data->teacher_can_request_evaluation ?? false) {
            $flags |= evasys_category::MASK_TEACHER_CAN_REQUEST_EVALUATION;
        }
        if ($data->teacher_evaluation_needs_approval ?? false) {
            $flags |= evasys_category::MASK_EVALUATION_REQUEST_NEEDS_APPROVAL;
        }
        if ($data->automatic_task_creation ?? false) {
            $flags |= evasys_category::MASK_AUTOMATIC_TASK_CREATION;
        }
        $evasyscat->set_many([
            'standard_time_start' => $data->standard_time_start,
            'standard_time_end' => $data->standard_time_end,
            'mode_flags' => $flags
        ]);
        return $evasyscat;
    }

    public function validation($data, $files) {
        $errors = [];
        if ($data['standard_time_start'] >= $data['standard_time_end']) {
            $errors['standard_time_end'] = get_string('err_endbeforestart', 'block_evasys_sync');
        }
        return $errors;
    }

}