<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

namespace block_evasys_sync\task;

use core\task\adhoc_task;

class evasys_bulk_task extends adhoc_task {
    public function execute() {
        global $DB;
        $data = $this->get_custom_data();
        $courses = $data->courses;
        $categoryid = $data->categoryid;
        if (empty($categoryid) || empty($courses)) {
            mtrace("No category or courses specified, exiting.");
        }
        $evasyscategory = \block_evasys_sync\evasys_category::for_category($categoryid);
        if ($data->reeval) {
            $errors = \block_evasys_sync\evaluation_manager::set_re_evaluation_for($courses, $evasyscategory);
        } else {
            $errors = \block_evasys_sync\evaluation_manager::set_default_evaluation_for($courses, $evasyscategory);
        }
        if ($errors) {
            $erroroutput = '';
            foreach ($errors as $courseid => $error) {
                $erroroutput .= $courseid . ': ' . $error . '<br>';
            }
            mtrace($erroroutput);
        }
    }
}
