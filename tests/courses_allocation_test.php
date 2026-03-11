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
 * Tests the class evasys_courses_allocation
 * @package block_evasys_sync
 * @category test
 * @copyright 2019 T Reischmann
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * PHPUnit evasys testcase
 * @package block_evasys_sync
 * @category test
 * @copyright 2019 T Reischmann
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class courses_allocation_test extends advanced_testcase {

    /**
     * Tests if a course has a idnumber, but no allocation entry.
     */
    public function test_idnumber_only(): void {
        $this->resetAfterTest(true);

        $idnumber = '123456789';

        $courseone = $this->getDataGenerator()->create_course(['name' => 'First course', 'idnumber' => $idnumber]);

        $result = \block_evasys_sync\course_evasys_courses_allocation::raw_get_evasyscourses($courseone->id);

        $this->assertCount(1, $result);
        $this->assertEquals($idnumber, array_pop($result));
    }

    /**
     * Tests if a course has neither an idnumber, nor an allocation entry.
     */
    public function test_no_idnumber(): void {
        $this->resetAfterTest(true);

        $courseone = $this->getDataGenerator()->create_course(['name' => 'First course']);

        $result = \block_evasys_sync\course_evasys_courses_allocation::raw_get_evasyscourses($courseone->id);

        $this->assertCount(0, $result);
    }

    /**
     * Tests if a course has an idnumber and an allocation entry.
     */
    public function test_idnumber_and_allocation_entries(): void {
        $this->resetAfterTest(true);

        $idnumber = 'idnumber';
        $evasysid1 = 'evasysid1';
        $evasysid2 = 'evasysid2';

        $courseone = $this->getDataGenerator()->create_course(['name' => 'First course', 'idnumber' => $idnumber]);

        $allocation = new \block_evasys_sync\course_evasys_courses_allocation();

        // Add first evasysid.
        $evasysids = [];
        $evasysids[] = $evasysid1;
        $allocation->set('course', $courseone->id);
        $allocation->set('evasyscourses', implode('#', $evasysids));
        $allocation->save();

        // Query evasyscourses and test them.
        $result = \block_evasys_sync\course_evasys_courses_allocation::raw_get_evasyscourses($courseone->id);

        $this->assertCount(2, $result);
        $this->assertContains($idnumber, $result);
        $this->assertContains($evasysid1, $result);

        // Add a second evasysid.
        $evasysids[] = $evasysid2;
        $allocation->set('evasyscourses', implode('#', $evasysids));
        $allocation->save();

        // Query evasyscourses and test them.
        $result = \block_evasys_sync\course_evasys_courses_allocation::raw_get_evasyscourses($courseone->id);

        $this->assertCount(3, $result);
        $this->assertContains($idnumber, $result);
        $this->assertContains($evasysid1, $result);
        $this->assertContains($evasysid2, $result);
    }

    /**
     * Tests if a course has no idnumber, but an allocation entry.
     */
    public function test_only_allocation_entries(): void {
        $this->resetAfterTest(true);

        $evasysid1 = 'evasysid1';
        $evasysid2 = 'evasysid2';

        $courseone = $this->getDataGenerator()->create_course(['name' => 'First course']);

        $allocation = new \block_evasys_sync\course_evasys_courses_allocation();

        // Add first evasysid.
        $evasysids = [];
        $evasysids[] = $evasysid1;
        $allocation->set('course', $courseone->id);
        $allocation->set('evasyscourses', implode('#', $evasysids));
        $allocation->save();

        // Query evasyscourses and test them.
        $result = \block_evasys_sync\course_evasys_courses_allocation::raw_get_evasyscourses($courseone->id);

        $this->assertCount(1, $result);
        $this->assertContains($evasysid1, $result);

        // Add a second evasysid.
        $evasysids[] = $evasysid2;
        $allocation->set('evasyscourses', implode('#', $evasysids));
        $allocation->save();

        // Query evasyscourses and test them.
        $result = \block_evasys_sync\course_evasys_courses_allocation::raw_get_evasyscourses($courseone->id);

        $this->assertCount(2, $result);
        $this->assertContains($evasysid1, $result);
        $this->assertContains($evasysid2, $result);
    }
}
