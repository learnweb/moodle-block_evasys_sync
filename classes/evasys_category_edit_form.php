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
namespace block_evasys_sync;

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

    /**
     * Defines form elements.
     */
    protected function definition () {
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

        $mform->addElement('checkbox', 'teacher_can_request_evaluation',
                get_string('teacher_can_request_evaluation', 'block_evasys_sync'));

        $mform->addElement('checkbox', 'teacher_evaluation_request_needs_approval',
                get_string('teacher_evaluation_request_needs_approval', 'block_evasys_sync'));
        $mform->disabledIf('teacher_evaluation_request_needs_approval', 'teacher_can_request_evaluation');

        $mform->addElement('checkbox', 'teacher_can_change_evaluation',
                get_string('teacher_can_change_evaluation', 'block_evasys_sync'));

        $mform->addElement('checkbox', 'teacher_evaluation_change_needs_approval',
                get_string('teacher_evaluation_change_needs_approval', 'block_evasys_sync'));
        $mform->disabledIf('teacher_evaluation_change_needs_approval', 'teacher_can_change_evaluation');

        $this->add_action_buttons();
    }

}