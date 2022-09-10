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
 * Filter moodle form for course manager overview table.
 *
 * @package    block_evasys_sync
 * @copyright  2022 Justus Dieckmann WWU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class course_manager_filter_form extends moodleform {

    /**
     * Defines form elements.
     */
    protected function definition () {
        global $DB;
        $mform = $this->_form;

        if ($field = $DB->get_record('customfield_field', array('shortname' => 'semester', 'type' => 'semester'))) {
            $fieldcontroller = \core_customfield\field_controller::create($field->id);
            $datacontroller = \core_customfield\data_controller::create(0, null, $fieldcontroller);
            $datacontroller->instance_form_definition($mform);
        }

        $mform->addElement('text', 'coursename', get_string('coursename', 'block_evasys_sync'));
        $mform->setType('coursename', PARAM_TEXT);

        $this->add_action_buttons(true, get_string('apply', 'block_evasys_sync'));
    }

}