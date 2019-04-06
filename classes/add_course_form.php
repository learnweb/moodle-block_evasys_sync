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

namespace block_evasys_sync;

use moodleform;
use html_writer;

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir . '/formslib.php');
require_once($CFG->dirroot . '/local/lsf_unification/lib_his.php');
require_once($CFG->dirroot . '/local/lsf_unification/class_pg_lite.php');

class add_course_form extends moodleform {

    protected function definition () {
        global $pgDB;
        $mform = $this->_form;

        $mform->addElement('html', '<h3> Wählen sie Kurse die dem aktuellen moodle Kurs zugeordnet werden sollen </h3>');
        $pgDB = new \pg_lite();
        $pgDB->connect();
        $availablecourselist = get_teachers_course_list('herbert', false);
        // Add Table.
        $mform->addElement('html', $this->tablehead());
        $this->table_body($availablecourselist);
        $mform->addElement('submit', 'submitbutton', get_string('submit', 'block_evasys_sync'));
    }

    public function addid($id) {
        $mform = $this->_form;
        $mform->addElement('html', '<input type="hidden" name="id" value="'. $id .'">');
    }


    /**
     * Prints the table head (e.g. column names).
     * @return string
     */
    public function tablehead() {
        $attributes['class'] = 'generaltable';
        $attributes['id'] = 'evasys_course_map_table';
        $output = html_writer::start_tag('table', $attributes);

        $output .= html_writer::start_tag('thead', array());
        $output .= html_writer::start_tag('tr', array());

        $attributes = array();
        $attributes['class'] = 'header c0';
        $attributes['scope'] = 'col';
        $output .= html_writer::tag('th', 'Kursname', $attributes);
        $attributes = array();
        $attributes['class'] = 'header c1 lastcol';
        $attributes['scope'] = 'col';
        $output .= html_writer::tag('th', 'zugeordnet', $attributes);
        $output .= html_writer::end_tag('tr');
        $output .= html_writer::end_tag('thead');

        return $output;
    }

    /**
     * Prints course categories and assigned moodle users.
     * @return string
     */
    private function table_body($courses) {
        $mform = $this->_form;

        $mform->addElement('html', '<tbody>');
        foreach ($courses as $course) {
            $mform->addElement('html', '<tr>');
            $mform->addElement('html', '<td class="cell c0"><div>' .
                                     trim($course->info) .
                                     '</div></td>');
            $mform->addElement('html', '<td class="cell c1">');

            $name = $course->veranstid;
            $mform->addElement('checkbox', $name);
            $mform->setType($name, PARAM_BOOL);
            $mform->setDefault($name, false);

            $mform->addElement('html', '</td></tr>');
        }
        $mform->addElement('html', '</tbody>');
        $mform->addElement('html', '</table>');
    }
}