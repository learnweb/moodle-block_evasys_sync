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

namespace block_evasys_sync\task;

use block_evasys_sync\evasys_inviter;



class update_survey_status extends \core\task\scheduled_task {
    /**
     * Get a descriptive name for this task (shown to admins).
     *
     * @return string
     * @throws \coding_exception
     */
    public function get_name() {
        return get_string('taskname', 'block_evasys_sync');
    }
    /**
     * This will start and close surveys.
     */
    // It's important to note that this might use alot of space and computational time
    // ...if the Plugin data is never deleted. There would be multiple solutions for this.
    // At the moment manual removal is advised. However since you're here I assume you have some problems...
    // My personal advice is to either move records that are in the past to another table, so you can still display the dates,
    // ...or to simply remove a record once the survey is closed.
    public function execute() {
        // Obtain all moodle-controlled surveys and open and/or close them.
        // This should never affect manual surveys (with state = 3).
        $time = time();
        $inviter = evasys_inviter::get_instance();
        $startcourseobjects = \block_evasys_sync\course_evaluation_allocation::get_records_select("startdate <= $time AND state = 0");
        $startcourses = [];
        foreach ($startcourseobjects as $object) {
            $startcourses[] = $object->get("course");
        }
        $closecourseobjects = \block_evasys_sync\course_evaluation_allocation::get_records_select("enddate <= $time AND state < 2");
        $closecourses = [];
        foreach ($closecourseobjects as $object) {
            $closecourses[] = $object->get("course");
        }
        $inviter->open_moodle_courses($startcourses);
        $inviter->close_moodle_course($closecourses);
    }
}
