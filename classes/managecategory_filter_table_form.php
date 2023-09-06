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
use stdClass;

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir . '/formslib.php');

/**
 * Filter moodle form for course manager overview table.
 *
 * @package      block_evasys_sync
 * @copyright    2023 Irina Hoppe Uni Muenster
 * @license      http://www.gnu.org/copyleft/gpl.htmlGNUGPLv3 or later
 */

class managecategory_filter_table_form extends moodleform {

    /**
     * Defines formelements.
     */
    protected function definition(){
        global $DB;
        $mform = $this->_form;

        $courses = $this->_customdata['table']->get_all_courses();
        $mform->addElement('autocomplete', 'coursesearches', get_string('searcharea','search'), $courses, array('multiple' => true));

        $searchbutton = $mform->createElement('submit', 'search', get_string('search', 'block_evasys_sync'));
        $mform->addElement($searchbutton);
    }

    public function get_data() {

        $mform = $this->_form;
        $data = parent::get_data();

        // Function is_array is neccessary to avoid filtering if no course is selected.
        if ($submit = $mform->getSubmitValue('coursesearches')) {
            if (is_array($submit)) {
                $data->searchcourse = $submit;
                return $data;
            } else {
                $data = new stdClass();
                $data->searchcourse = null;
                return $data;
            }
        }
        return null;
    }
}
