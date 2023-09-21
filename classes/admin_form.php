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
 * Evasys sync block admin form.
 *
 * @package block_evasys_sync
 * @copyright 2017 Tamara Gunkel
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_evasys_sync;

use moodleform;
use html_writer;

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir . '/formslib.php');

class admin_form extends moodleform {
    protected function definition() {
        $mform = $this->_form;

        $mform->addElement('html', '<div id="adminsettings">');

        // Username.
        $name = 'evasys_username';
        $title = get_string('settings_username', 'block_evasys_sync');
        $mform->addElement('text', $name, $title);
        $mform->setType($name, PARAM_TEXT);

        // Password.
        $name = 'evasys_password';
        $title = get_string('settings_password', 'block_evasys_sync');
        $mform->addElement('passwordunmask', $name, $title);

        // SOAP URL.
        $name = 'evasys_soap_url';
        $title = get_string('settings_soap_url', 'block_evasys_sync');
        $mform->addElement('text', $name, $title);
        $mform->setType($name, PARAM_TEXT);

        // WSDL URL.
        $name = 'evasys_wsdl_url';
        $title = get_string('settings_wsdl_url', 'block_evasys_sync');
        $mform->addElement('text', $name, $title);
        $mform->setType($name, PARAM_TEXT);

        // Default Learnweb user for notifications.
        $name = 'default_evasys_moodleuser';
        $title = get_string('settings_moodleuser', 'block_evasys_sync');
        $mform->addElement('text', $name, $title);
        $mform->setType($name, PARAM_INT);
        $mform->setDefault($name, 25989);

        // Default Mode.
        $name = 'default_evasys_mode';
        $title = get_string('settings_mode', 'block_evasys_sync');
        $mform->addElement('checkbox', $name, $title);
        $mform->setType($name, PARAM_BOOL);
        $mform->setDefault($name, get_config('block_evasys_sync', 'default_evasys_mode'));

        // Default Mode.
        $name = 'default_his_connection';
        $title = get_string('his_connection', 'block_evasys_sync');
        $mform->addElement('checkbox', $name, $title);
        $mform->setType($name, PARAM_BOOL);
        $mform->addHelpButton('default_his_connection','his_connection', 'block_evasys_sync');
        $mform->setDefault($name, get_config('block_evasys_sync', 'default_his_connection'));

        // Heading Add Category.
        $mform->addElement('html', '<h3>' . get_string('hd_user_cat', 'block_evasys_sync') . '</h3>');

        // Course category select (autocomplete).
        $name = 'evasys_cc_select';
        $title = get_string('settings_cc_select', 'block_evasys_sync');
        $options = array(
            'multiple' => false
        );
        $mform->addElement('autocomplete', $name, $title, $this->getunassignedcats(), $options);

        $name = 'evasys_cc_user';
        $title = get_string('settings_cc_user', 'block_evasys_sync');
        $mform->addElement('text', $name, $title);
        $mform->setType($name, PARAM_INT);

        $name = 'evasys_cc_mode';
        $title = get_string('auto_mode', 'block_evasys_sync');
        $mform->addElement('checkbox', $name, $title);
        $mform->setType($name, PARAM_BOOL);

        // Add Button.
        $mform->addElement('submit', 'addcatbutton', get_string('addcat', 'block_evasys_sync'));

        // Add Table.
        $mform->addElement('html', $this->tablehead());
        $this->table_body();

        $mform->addElement('submit', 'submitbutton', get_string('submit', 'block_evasys_sync'));

        $mform->addElement('html', '</div>');
    }


    /**
     * Prints the table head (e.g. column names).
     * @return string
     */
    public function tablehead() {
        $attributes['class'] = 'generaltable';
        $attributes['id'] = 'course_category_table';
        $output = html_writer::start_tag('table', $attributes);

        $output .= html_writer::start_tag('thead', array());
        $output .= html_writer::start_tag('tr', array());

        $attributes = array();
        $attributes['class'] = 'header c0';
        $attributes['scope'] = 'col';
        $output .= html_writer::tag('th', get_string('category_name', 'block_evasys_sync'), $attributes);
        $attributes = array();
        $attributes['class'] = 'header c1';
        $attributes['scope'] = 'col';
        $output .= html_writer::tag('th', get_string('responsible_user', 'block_evasys_sync'), $attributes);
        $attributes = array();
        $attributes['class'] = 'header c2';
        $attributes['scope'] = 'col';
        $output .= html_writer::tag('th', get_string('auto_mode', 'block_evasys_sync'), $attributes);
        $attributes = array();
        $attributes['class'] = 'header c3';
        $attributes['scope'] = 'col';
        $output .= html_writer::tag('th', get_string('standard_time_mode', 'block_evasys_sync'), $attributes);
        $attributes = array();
        $attributes['class'] = 'header c4 lastcol';
        $attributes['scope'] = 'col';
        $output .= html_writer::tag('th', get_string('delete_category_user', 'block_evasys_sync'), $attributes);
        $output .= html_writer::end_tag('tr');
        $output .= html_writer::end_tag('thead');

        return $output;
    }

    /**
     * Prints course categories and assigned moodle users.
     * @return string
     */
    private function table_body() {
        global $USER;
        $mform = $this->_form;

        $mform->addElement('html', '<tbody>');
        $records = $this->getrecords();
        $i = 0;
        $startdates = [];
        $enddates = [];
        foreach ($records as $record) {
            $mform->addElement('html', '<tr>');
            $mform->addElement('html', '<td class="cell c0"><div>' .
                $this->getcategoryhierachie($record->get('course_category')) .
                '</div></td>');
            $mform->addElement('html', '<td class="cell c1">');

            // Input field.
            $name = 'category_' . $record->get('id');
            $mform->addElement('text', $name, null);
            $mform->setType($name, PARAM_TEXT);
            $mform->setDefault($name, $record->get('userid'));

            $mform->addElement('html', '</td><td class="cell c2">');
            // Auto mode checkbox.
            try {
                $mode = $record->get('category_mode');
            } catch (\coding_exception $e) {
                // Backwards compatibility.
                $mode = false;
            }

            $namecatmode = 'category_mode_' . $record->get('id');
            $mform->addElement('checkbox', $namecatmode);
            $mform->setType($namecatmode, PARAM_BOOL);
            $mform->setDefault($namecatmode, $mode);

            $mform->addElement('html', '</td><td class="cell c3">');
            $timeeditlink = 'javascript:void(0)';
            $timeediturl = new \moodle_url($timeeditlink, array('id' => $record->get('id')));
            $text = get_string('edit_time', 'block_evasys_sync');
            $htmlurl = "<a id='timeediturl_{$i}' href='{$timeediturl->out()}'>$text</a>";
            $mform->addElement('html', $htmlurl);
            $startdate = $record->get('standard_time_start');
            $enddate = $record->get('standard_time_end');
            if ($startdate) {
                $mform->addElement('html', "<br/><div id='timehint_$i'>" .
                                         get_string('time_set', 'block_evasys_sync'). "</div>");
            } else {
                $mform->addElement('html', "<br/><div id='timehint_$i'></div>");
            }
            $mform->addElement('html', '</td><td class="cell c4 lastcol">');
            $link = '/blocks/evasys_sync/adminsettings.php';
            $editurl = new \moodle_url($link, array('d' => $record->get('id')));
            $text = get_string('delete', 'block_evasys_sync');
            $mform->addElement('html', '<a href="' . $editurl->out() . '">' . $text . '</a></td></tr>');
            $startdates[] = $startdate;
            $enddates[] = $enddate;
            $i++;
        }
        global $PAGE;
        $PAGE->requires->js_call_amd('block_evasys_sync/edit_timeframe', 'initialize', array($startdates, $enddates));
        $mform->addElement('html', '</tbody>');
        $mform->addElement('html', '</table>');
    }

    /**
     * Returns all course categories to which a custom user is assigned.
     * @return array
     */
    private function getrecords() {
        $records = \block_evasys_sync\evasys_category::get_records();
        return $records;
    }

    /**
     * Returns all course categories to which no custom user is assigend.
     * @return array
     */
    private function getunassignedcats() {
        global $DB;
        $categories = $DB->get_records_sql('SELECT {course_categories}.id, {course_categories}.name
                                                FROM {course_categories}
                                                LEFT JOIN {block_evasys_sync_categories}
                                                ON {block_evasys_sync_categories}.course_category = {course_categories}.id
                                                WHERE {block_evasys_sync_categories}.course_category IS NULL
                                                ORDER BY name ASC');

        $cat = array();
        foreach ($categories as $category) {
            $cat[$category->id] = $category->name;
        }
        return $cat;
    }

    /**
     * Returns the hierachie of a category as string.
     * @return string
     */
    private function getcategoryhierachie($catid) {
        $text = '';
        $spaces = '';
        $cat = \core_course_category::get($catid);
        $parents = $cat->get_parents();
        foreach ($parents as $pcat) {
            $name = \core_course_category::get($pcat)->name;
            $text .= $spaces . ' ' . $name . '<br/>';
            $spaces .= '-';
        }
        if (empty($parents)) {
            $text = $cat->name;
        } else {
            $text .= $spaces . ' ' . $cat->name;
        }
        return $text;
    }

    /**
     * Validates the user ids.
     *
     * @param array $data
     * @param array $files
     * @return array
     */
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);

        // Validate user ids.
        $records = \block_evasys_sync\evasys_category::get_records();
        foreach ($records as $allocation) {

            $newvalue = 'category_' . $allocation->get('id');

            if (!\core_user::is_real_user($data[$newvalue], true)) {
                $errors[$newvalue] = get_string('invaliduserid', 'error');
            }
        }

        if (!empty($data['evasys_cc_user'])) {
            if (!\core_user::is_real_user($data['evasys_cc_user'], true)) {
                $errors['evasys_cc_user'] = get_string('invaliduserid', 'error');
            }
        }

        return $errors;
    }
}
