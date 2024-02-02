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
 * PHPUnit evasys testcase
 * @package block_evasys_sync
 * @category test
 * @copyright 2017 T Gunkel
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

/**
 * PHPUnit evasys testcase
 * @package block_evasys_sync
 * @category test
 * @copyright 2017 T Gunkel
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_evasys_sync_notify_testcase extends advanced_testcase {
    /**
     * @runInSeparateProcess
     */
    public function test_notify_person () {
        global $DB;
        $this->resetAfterTest(true);

        $generator = $this->getDataGenerator();
        $categoryone = $generator->create_category();
        $categorytwo = $generator->create_category();
        $subcategoryone = $generator->create_category(array('parent' => $categoryone->id));
        $subsubcategoryone = $generator->create_category(array('parent' => $subcategoryone->id));
        $defaultuser = $generator->create_user();
        $userone = $this->getDataGenerator()->create_user();
        $usersubone = $this->getDataGenerator()->create_user();
        $usersubsubone = $this->getDataGenerator()->create_user();
        set_config('default_evasys_moodleuser', $defaultuser->id, 'block_evasys_sync');

        $courseone = $this->getDataGenerator()->create_course(array('name' => 'First course',
                                                                  'category' => $categoryone->id));
        $coursesubsubone = $this->getDataGenerator()->create_course(array('name' => 'Sub course',
                                                                        'category' => $subsubcategoryone->id));
        $coursetwo = $this->getDataGenerator()->create_course(array('name' => 'Second course',
                                                                  'category' => $categorytwo->id));

        // Test default user.
        $userto = \block_evasys_sync\evasys_synchronizer::get_assigned_user($courseone);
        self::assertEquals($userto, $defaultuser);

        // Test subcategory user without parent user.
        self::assertEquals($defaultuser, \block_evasys_sync\evasys_synchronizer::get_assigned_user($coursesubsubone));

        // Insert new record.
        $data = new \stdClass();
        $data->course_category = $categoryone->id;
        $data->userid = $userone->id;
        $data->category_mode = 0;
        $record = new \block_evasys_sync\evasys_category(0, $data);
        $record->create();

        // Test custom user.
        $userto = \block_evasys_sync\evasys_synchronizer::get_assigned_user($courseone);
        self::assertEquals($userto, $userone);

        self::assertEquals($defaultuser, \block_evasys_sync\evasys_synchronizer::get_assigned_user($coursetwo));

        // Test subcategory user with parent user.
        self::assertEquals($userone, \block_evasys_sync\evasys_synchronizer::get_assigned_user($coursesubsubone));

        // Insert new record for subcategory.
        $data = new \stdClass();
        $data->course_category = $subcategoryone->id;
        $data->userid = $usersubone->id;
        $data->category_mode = 0;
        $record = new \block_evasys_sync\evasys_category(0, $data);
        $record->create();

        self::assertEquals($usersubone, \block_evasys_sync\evasys_synchronizer::get_assigned_user($coursesubsubone));

        // Insert new record for subsubcategory.
        $data = new \stdClass();
        $data->course_category = $subsubcategoryone->id;
        $data->userid = $usersubsubone->id;
        $data->category_mode = 0;
        $record = new \block_evasys_sync\evasys_category(0, $data);
        $record->create();

        self::assertEquals($usersubsubone, \block_evasys_sync\evasys_synchronizer::get_assigned_user($coursesubsubone));
    }
}