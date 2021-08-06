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
 * Steps definitions related with the evasys_sync block.
 *
 * @package    block_evasys_sync
 * @category   test
 * @copyright  2019 Robin Tschudi
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../../vendor/behat/behat/src/Behat/Behat/Context/Context.php');
require_once(__DIR__ . '/../../../../vendor/behat/mink-extension/src/Behat/MinkExtension/Context/MinkAwareContext.php');
require_once(__DIR__ . '/../../../../vendor/behat/mink-extension/src/Behat/MinkExtension/Context/RawMinkContext.php');
require_once(__DIR__ . '/../../../../lib/behat/behat_base.php');
require_once(__DIR__. '/SelectorNotDisabledException.php');
require_once(__DIR__. '/SubmitButtonDisabledException.php');

use behat_block_evasys_sync\SelectorNotDisabledException;
use behat_block_evasys_sync\SubmitButtonDisabledException;

/**
 * evasys_sync-related steps definitions.
 *
 * @package    block_evasys_sync
 * @category   test
 * @copyright  2019 Robin Tschudi
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class behat_block_evasys_sync extends behat_base {

    private $evasysapi;
    private $generator;
    public static $coursedata = array();
    public static $evalsfkeyassoc = array();
    public const ERRORSAVEPATH = "/var/www/public/moodle/errorbackend.html";

    /**
     * Store Page content on failure
     *
     * @AfterStep
     */
    public function take_screenshot_after_failed_step (Behat\Behat\Hook\Scope\AfterStepScope $scope) {
        $logall = false;
        if (99 === $scope->getTestResult()->getResultCode() || $logall) {
            if (file_exists(self::ERRORSAVEPATH)) {
                $img = $this->getSession()->getDriver()->getContent();
                file_put_contents(self::ERRORSAVEPATH, $img);
            }
        }
    }

    public function __construct() {
        // The required classes have a "moodle internal" check. If they are loaded before the Scenario, this will not be set.
        require_once(__DIR__ . "/../../classes/lsf_api_mock_testable.php");
        require_once(__DIR__ . '/../../classes/evasys_api.php');
        $this->evasysapi = \block_evasys_sync\evasys_api_testable::get_instance();
        $this->generator = new \behat_data_generators();
    }

    public static function &get_coursedata_by_shortname($shortname) {
        $courseid = self::get_courseid_by_shortname($shortname);
        $coursedata = &self::get_coursedata_by_courseid($courseid);
        return $coursedata;
    }

    public static function &get_coursedata_by_courseid($courseid) {
        if (!array_key_exists($courseid, self::$coursedata)) {
            $jsondata = get_course($courseid)->summary;
            $localcoursedata = json_decode($jsondata);
            if (!is_object($localcoursedata)) {
                $localcoursedata = new \stdClass(); // If there is no data initialize a data object.
            }
            self::$coursedata[$courseid] = $localcoursedata;

        }
        return self::$coursedata[$courseid];
    }

    public static function write_coursedata_to_persistent_storage() {
        global $DB;
        foreach (self::$coursedata as $courseid => $courseinfo) {
            $jsondata = json_encode($courseinfo);
            $DB->execute("UPDATE {course} SET summary=? WHERE id=?", array($jsondata, $courseid));
        }
    }

    private static function get_courseid_by_shortname($shortname) {
        global $DB;
        return $DB->get_field("course", "id", array("shortname" => $shortname));
    }

    private function add_lsf_evasys_course($moodlecourse, $valid, $veranstnr = null, $semestertxt = null, $studentcount = null,
                                            $title = null, $surveycount = null, $surveystatus = null) {
        if ($surveycount and $surveycount < 2 and $surveystatus == "mixed") {
            throw new \Exception("Impossible to satisfy parameters (mixed surveystatus but just one survey)");
        }

        $lsfevasyscourse = new \stdClass();
        $surveys = array();

        for ($i = 0; $i < $surveycount; $i++) {
            $survey = new \stdClass();
            $survey->num = $i;
            $survey->formid = 1;
            $survey->is_open = ($surveystatus == "open" or ($surveystatus == "mixed" and $i == 0)) ? "t" : "f";
            $survey->form_count = 20;
            $survey->pswd_count = 200;
            array_push($surveys, $survey);
        }

        $lsfevasyscourse->valid = $valid;
        $lsfevasyscourse->veranstnr = $veranstnr;
        $lsfevasyscourse->semestertxt = $semestertxt;
        $lsfevasyscourse->studentcount = $studentcount;
        $lsfevasyscourse->title = $title;
        $lsfevasyscourse->surveys = $surveys;

        $coursedata = &self::get_coursedata_by_shortname($moodlecourse);
        if (!property_exists($coursedata, "evacourses")) {
            $coursedata->evacourses = array();
        }
        $position = count($coursedata->evacourses);
        array_push($coursedata->evacourses, $lsfevasyscourse);
        return $position;
    }

    /**
     * @Given I load the evasys block
     */
    public function i_load_the_evasys_block () {
        self::write_coursedata_to_persistent_storage();
        $this->execute('behat_general::i_click_on', ["Show status of surveys", 'button']);
    }

    /**
     * @Given /^there are ([0-9]*) (open|closed|mixed) Evasyscourses mapped to course with shortname ([^"]*)$/
     */
    public function there_are_evasyscourses_mapped_to_course_with_shortname($number, $state, $shortname) {
        $count = intval($number);
        $lsfids = array();
        for ($i = 0; $i < $count; $i++) {
            $surveystatus = $state == "mixed" ? ($i == 0 ? "closed" : "open)") : $state; // If mixed first will be closed, rest open.
            $lsfid = $this->add_lsf_evasys_course($shortname, true, $i, "WS 2017/18", 100,
                "DynamicSurvey" . $i, 1, $surveystatus);
            array_push($lsfids, $lsfid);
        }

        global $DB;
        $courseid = self::get_courseid_by_shortname($shortname);
        $pid = $DB->get_field('block_evasys_sync_courses', 'id', array('course' => $courseid));
        $persistent = \block_evasys_sync\course_evasys_courses_allocation::get_record_by_course($pid, false);
        if (!$persistent) {
            $persistent = new \block_evasys_sync\course_evasys_courses_allocation(0);
        }
        $magicstring = implode("#", $lsfids);
        // If the mapping is empty, remove it.
        if (empty($magicstring) && $persistent->get('id') != 0) {
            $persistent->delete();
        }
        // Only save the mapping, if the mapping string is not empty.
        if (!empty($magicstring)) {
            $persistent->set('course', $courseid);
            $persistent->set('evasyscourses', $magicstring);
            $persistent->save();
        }
    }

    /**
     * Sets a courses Idnumber
     *
     * @Given The course :coursename has idnumber :idnum
     */
    public function the_course_has_idnumber($name, $idnum) {
        global $DB;
        $DB->execute("UPDATE {course} SET idnumber=$idnum WHERE shortname='$name'");
    }
    /**
     * @Given only invalid mappings are present for course :shortname
     */
    public function only_invalid_mappings_are_present_for_course ($shortname) {
        $courseids = "";
        $courseids .= $this->add_lsf_evasys_course($shortname, false) . "#";
        $courseids .= $this->add_lsf_evasys_course($shortname, false) . "#";
        $courseids .= $this->add_lsf_evasys_course($shortname, false);
        $id = $this->get_courseid_by_shortname($shortname);
        $record = \block_evasys_sync\course_evasys_courses_allocation::get_record_by_course($id, false);
        if (!$record) {
            $record = new \block_evasys_sync\course_evasys_courses_allocation(0);
        }
        $record->set("course", $id);
        $record->set("evasyscourses", $courseids);
        $record->save();
    }

    /**
     * @Given the idnumber of course :shortname links to a :state Evasyscourse
     */
    public function the_idnumber_of_course_links_to_a_evasyscourse($shortname, $state) {
        // If the state is mixed, the course linked by the idnumber should be "open" because we know there is at least
        // one "closed" course mapped.
        $surveystatus = $state == "mixed" ? "open" : $state;
        $lsfid = $this->add_lsf_evasys_course($shortname, true, 1, "SS 2018", 100,
                                                "IdnumberSurvey", 1, $surveystatus);
        $this->the_course_has_idnumber($shortname, $lsfid);
    }

    /**
     * @Given the idnumber for course :course is invalid
     */
    public function the_idnumber_for_course_is_invalid($shortname) {
        $courseid = $this->add_lsf_evasys_course($shortname, false);
        $this->the_course_has_idnumber($shortname, $courseid);
    }

    /**
     * @Given there is no idnumber mapped to course :shortname
     */
    public function there_is_no_idnumber_mapped($shortname) {
        // nothing to be done. But sure reads better.
    }


    // Categorymode manipulation
    /**
     * @Given category :x is in manual mode
     */

    public function category_is_in_manual_mode ($id) {
        if ($cc = \block_evasys_sync\user_cat_allocation::get_record(array("course_category" => $id))) {
            $cc->set('category_mode', 0);
            $cc->update();
            $cc->save();
        } else {
            $record = new stdClass();
            $record->userid = 2;
            $record->course_category = $id;
            $record->category_mode = 0;
            $persistent = new \block_evasys_sync\user_cat_allocation(0, $record);
            $persistent->create();
            $persistent->save();
        }
    }

    /**
     * @Given category :x is in automatic mode
     */

    public function category_is_in_automatic_mode ($id) {
        if ($cc = \block_evasys_sync\user_cat_allocation::get_record(array("course_category" => $id))) {
            $cc->set('category_mode', 1);
            $cc->update();
            $cc->save();
        } else {
            $record = new stdClass();
            $record->userid = 1;
            $record->course_category = strval($id);
            $record->category_mode = 1;
            $persistent = new \block_evasys_sync\user_cat_allocation(0, $record);
            $persistent->create();
            $persistent->save();
        }
    }

    /**
     * @Given category :x is in standardtime mode with dates :start :end
     */

    public function category_is_in_standardtime_mode ($cat, $start, $end) {
        global $DB;
        $sql = 'UPDATE {block_evasys_sync_categories} SET standard_time_start = ?, standard_time_end = ? ' . 'WHERE course_category = ?';
        print($sql);
        $DB->execute($sql, array($start, $end, $cat));
    }

    /**
     * @Given category :x is not in standardtime mode
     */

    public function category_is_not_in_nonstandard_mode ($cat) {
        $this->category_is_in_standardtime_mode($cat, null, null);
    }

    /**
     * @Given there is no internal record of course :shortname
     */
    public function and_there_is_no_internal_record_of_course ($shortname) {
        global $DB;
        $id = $this->get_courseid_by_shortname($shortname);
        $DB->execute("DELETE FROM {block_evasys_sync_courseeval} WHERE course = ?", array($id));
    }

    /**
     * @Given the internal state of course :shortname is :mode
     */
    public function the_internal_state_of_course_is($shortname, $mode) {
        global $DB;
        $id = $this->get_courseid_by_shortname($shortname);
        $record = \block_evasys_sync\course_evaluation_allocation::get_record_by_course($id, false);
        if (!$record) {
            $record = new \block_evasys_sync\course_evaluation_allocation(0);
            $record->set('course', $id);
        }
        if ($mode == "manual") {
            $record->set('state', \block_evasys_sync\course_evaluation_allocation::STATE_MANUAL);
        } else if ($mode == "notopened") {
            $record->set('state', \block_evasys_sync\course_evaluation_allocation::STATE_AUTO_NOTOPENED);
        } else if ($mode == "opened") {
            $record->set('state', \block_evasys_sync\course_evaluation_allocation::STATE_AUTO_OPENED);
        } else if ($mode == "closed") {
            $record->set('state', \block_evasys_sync\course_evaluation_allocation::STATE_AUTO_CLOSED);
        } else {
            throw new \Exception("Didnt't recognize state");
        }
        $record->set('startdate', 1595714400);
        $record->set('enddate', 1595800800);
        $record->save();
    }

    /**
     * @Given the recordstandardtimemode for course :shortname is set to :value
     */
    public function the_recorstandardtime_for_course_is_set($shortname, $mode) {
        global $DB;
        $id = $this->get_courseid_by_shortname($shortname);
        $record = \block_evasys_sync\course_evaluation_allocation::get_record_by_course($id, false);
        if (!$record) {
            $record = new \block_evasys_sync\course_evaluation_allocation(0);
            $record->set('course', $id);
        }
        $record->set('usestandardtime', $mode == "true");
        $record->save();
    }

    /**
     * @Given the recordstandardtimemode for course :shortname is not set
     */
    public function the_recordstandardtimemode_for_course_is_not_set($shortname) {
        return;
    }

    /**
     * @Given no courses are mapped to course :shortname
     */
    public function no_courses_are_mapped_to_course($shortname) {
        global $DB;
        $courseid = $this->get_courseid_by_shortname($shortname);
        $pid = $DB->get_field('block_evasys_sync_courses', 'id', array('course' => $courseid));
        if (!$pid) {
            return;
        }
        $persistent = new \block_evasys_sync\course_evasys_courses_allocation($pid);
        $persistent->delete();
    }

    /**
     * @Given no students enrolled in course :course
     */
    public function no_students_enrolled_in_course($shortname) {
        // nothing to be done. But sure reads better.
    }

    /**
     * @Given only tutors enrolled in course :shortname
     */
    public function only_tutors_enrolled_in_course($shortname) {
        $head = array("user", "course", "role");
        $teacher = array("teacher1", $shortname, "editingteacher");
        $student = array("tutor1", $shortname, "teacher");
        $node = new \Behat\Gherkin\Node\TableNode(array($head, $teacher, $student));
        $this->generator->the_following_entities_exist("course enrolments", $node);
    }

    /**
     * @Given students enrolled in course :shortname
     */
    public function students_enrolled_in_course($shortname) {
        $head = array("user", "course", "role");
        $teacher = array("teacher1", $shortname, "editingteacher");
        $student = array("student1", $shortname, "student");
        $node = new \Behat\Gherkin\Node\TableNode(array($head, $teacher, $student));
        $this->generator->the_following_entities_exist("course enrolments", $node);
    }

    /**
     * @Then I should see a button named :name
     */
    public function i_should_see_a_button_named($name) {
        $this->find_button($name);
    }

    /**
     * @Given category :category is in auto mode
     */
    public function category_is_in_auto_mode ($id) {
        $this->category_is_in_automatic_mode($id);
    }

    /**
     * @Given the startselector should be disabled
     */
    public function the_startselector_should_be_disabled () {
        try {
            $startday = $this->find_field("day_start");
        } catch (\Behat\Mink\Exception\ElementNotFoundException $e) {
            // selector can be disabled or not visible
            return;
        }
        if (is_null($startday->getAttribute('disabled'))) {
            throw new SelectorNotDisabledException();
        }
    }

    /**
     * @Given both selectors should be disabled
     */
    public function both_selectors_should_be_disabled () {
        try {
            $startday = $this->find_field("day_start");
            $endday = $this->find_field("day_end");
        } catch (\Behat\Mink\Exception\ElementNotFoundException $e) {
            // selector can be disabled or not visible
            return;
        }
        if (is_null($startday->getAttribute('disabled')) or is_null($endday->getAttribute('disabled'))) {
            throw new SelectorNotDisabledException();
        }
    }

    /**
     * @Given the submitbutton should be enabled
     */
    public function the_submitbutton_should_be_enabled () {
        $button = $this->find_button("evasyssubmitbutton");
        if (! is_null($button->getAttribute('disabled'))) {
            throw new SubmitButtonDisabledException();
        }
    }


}