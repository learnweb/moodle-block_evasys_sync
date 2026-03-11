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
use core\output\html_writer;

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir . '/formslib.php');
require_once($CFG->dirroot . '/local/lsf_unification/lib_his.php');
require_once($CFG->dirroot . '/local/lsf_unification/class_pg_lite.php');

class add_course_form extends moodleform {
    // Todo make mustache form.
    // Todo check if course has been started.

    protected function definition() {
    }

    /**
     * Initializes the table.
     * It loads all courses from the lsf to display them in a table,
     * from which a teacher can select the evasys courses mapped to this moodle course.
     * @param int $courseid Id of the moodle course.
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function init($courseid) {
        // Variable violates moodle codestyle but this is required by the lsf-plugin.
        global $pgDB, $USER, $DB; // phpcs:ignore // @codingStandardsIgnoreLine
        $mform = $this->_form;

        $mform->addElement('html', '<h3>' . get_string('add_course_header', 'block_evasys_sync') . '</h3>');
        $pgDB = new \pg_lite(); // phpcs:ignore // @codingStandardsIgnoreLine
        $pgDB->connect(); // phpcs:ignore // @codingStandardsIgnoreLine
        $lsfid = $DB->get_field('course', 'idnumber', ['id' => $courseid]);
        if ($lsfid) {
            $maincourse = (array)get_course_by_veranstid($lsfid);
        } else {
            $maincourse = null;
        }
        $courses = $this->courses_proposed_for_association($courseid);
        $pgDB->dispose(); // phpcs:ignore // @codingStandardsIgnoreLine
        $availablecourselist = [];
        foreach ($courses as $veranstid => $course) {
            $result = [];
            $result['veranstid'] = $course->veranstid;
            $result['info'] = utf8_encode($course->titel);
            $result['semestertxt'] = utf8_encode($course->semestertxt);
            $result['semester'] = $course->semester;
            $availablecourselist[$course->veranstid] = $result;
        }
        $sorter = function ($a, $b) {
            if ($a['semester'] == $b['semester']) {
                return strcasecmp($a['info'], $b['info']);
            }
            return ($a['semester'] < $b['semester']) ? 1 : -1;
        };
        unset($availablecourselist[$lsfid]);
        usort($availablecourselist, $sorter);

        // Add Table.
        $mform->addElement('html', $this->tablehead());
        $this->table_body($availablecourselist);
        $this->addpubid($maincourse);
        $this->addid($courseid);
    }

    /**
     * Returns the list of Moodle courses that the user might want to
     * associate to the current course for evaluation.
     *
     * @param int $courseid the id of the current course
     * @return array the list of proposed courses
     */
    private function courses_proposed_for_association(int $courseid): array {
        global $USER;
        $veranstids = [];
        if (!is_siteadmin()) {
            $veranstids = get_veranstids_by_teacher(get_teachers_pid($USER->username));
        } else {
            $teachers = get_users_by_capability(
                \context_course::instance($courseid),
                'block/evasys_sync:modifymapping'
            );
            foreach ($teachers as $teacher) {
                $personalid = get_teachers_pid($teacher->username);
                if (!$personalid) {
                    continue;
                }
                $veranstids = array_merge($veranstids, get_veranstids_by_teacher($personalid));
            }
        }
        return get_courses_by_veranstids($veranstids);
    }

    private function addid($id) {
        $mform = $this->_form;
        $mform->addElement('html', '<input type="hidden" name="id" value="' . $id . '">');
    }


    /**
     * Prints the table head (e.g. column names).
     * @return string
     */
    public function tablehead() {
        $attributes['class'] = 'generaltable';
        $attributes['id'] = 'evasys_course_map_table';
        $output = html_writer::start_tag('table', $attributes);

        $output .= html_writer::start_tag('thead', []);
        $output .= html_writer::start_tag('tr', []);

        $attributes = [];
        $attributes['class'] = 'header c0';
        $attributes['scope'] = 'col';
        $output .= html_writer::tag('th', get_string('coursename', 'block_evasys_sync'), $attributes);
        $attributes = [];
        $attributes['class'] = 'header c1';
        $attributes['scope'] = 'col';
        $output .= html_writer::tag('th', get_string('semester', 'block_evasys_sync'), $attributes);
        $attributes = [];
        $attributes['class'] = 'header c2 lastcol';
        $attributes['scope'] = 'col';
        $output .= html_writer::tag('th', get_string('associated', 'block_evasys_sync'), $attributes);
        $output .= html_writer::end_tag('tr');
        $output .= html_writer::end_tag('thead');

        return $output;
    }

    /**
     * Prints course categories and assigned moodle users.
     */
    private function table_body($courses) {
        $mform = $this->_form;

        $mform->addElement('html', '<tbody>');
        foreach ($courses as $course) {
            $mform->addElement('html', '<tr>');
            $mform->addElement('html', '<td class="cell c0"><div>' .
                                     trim($course['info']) .
                                     '</div></td>');
            $mform->addElement('html', '<td class="cell c1">');

            $mform->addElement('html', '<div>' .
                                     trim($course['semestertxt']) .
                                     '</div></td>');

            $mform->addElement('html', '<td class="cell c2">');

            $name = $course['veranstid'];
            $mform->addElement('checkbox', $name);
            $mform->setType($name, PARAM_BOOL);
            $mform->setDefault($name, false);

            $mform->addElement('html', '</td></tr>');
        }
    }

    private function addpubid($course) {
        $mform = $this->_form;
        if ($course) {
            $mform->addElement('html', '<tr disabled="disabled">');
            $mform->addElement('html', '<td class="cell c0"><div>' .
                                     trim($course['titel']) .
                                     '</div></td>');
            $mform->addElement('html', '<td class="cell c1">');

            $mform->addElement('html', '<div>' .
                                     trim($course['semestertxt']) .
                                     '</div></td>');

            $mform->addElement('html', '<td class="cell c2">');

            $name = $course['veranstid'];
            $mform->addElement('checkbox', $name, '', '', ['disabled' => 'disabled']);
            $mform->setType($name, PARAM_BOOL);
            $mform->setDefault($name, true);
            $mform->addHelpButton($name, 'maincoursepredefined', 'block_evasys_sync');

            $mform->addElement('html', '</td></tr>');
        }
        $mform->addElement('html', '</tbody>');
        $mform->addElement('html', '</table>');
        $mform->addElement('submit', 'submitbutton', get_string('submit', 'block_evasys_sync'));
    }
}
