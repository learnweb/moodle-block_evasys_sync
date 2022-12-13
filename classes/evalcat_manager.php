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

/**
 * Manager for evaluation categories.
 *
 * @package    block_evasys_sync
 * @copyright  2022 Justus Dieckmann WWU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class evalcat_manager {

    private static $instance;

    public static function get_instance(): evalcat_manager {
        if (!self::$instance) {
            self::$instance = new evalcat_manager();
        }
        return self::$instance;
    }

    private $cache;
    private $usercache;

    private function __construct() {
        $this->cache = \cache::make('block_evasys_sync', 'cats');
        $this->usercache = \cache::make('block_evasys_sync', 'user');
    }

    private function load_categories() {
        $records = evasys_category::get_records();

        $categories = [];
        foreach ($records as $record) {
            $categories[$record->get('course_category')] = $record;
        }
        return $categories;
    }

    private function load_user_categories() {
        global $USER, $DB;

        if (has_capability('block/evasys_sync:managecourses', \context_system::instance())) {
            return array_keys($this->get_categories());
        } else {
            list ($contextlimitsql, $contextlimitparams) = \core\access\get_user_capability_course_helper::get_sql(
                    $USER->id, 'block/evasys_sync:managecourses');
            if (!$contextlimitsql) {
                return [];
            }

            return $DB->get_fieldset_sql("
            SELECT c.id
              FROM {course_categories} c
              JOIN {". dbtables::CATEGORIES . "} eval ON eval.course_category = c.id 
              JOIN {context} x ON c.id = x.instanceid AND x.contextlevel = ?
            WHERE $contextlimitsql", array_merge([CONTEXT_COURSECAT], $contextlimitparams));
        }
    }

    public function get_categories(): array {
        if (!$this->cache->has('cats')) {
            $this->cache->set('cats', $this->load_categories());
        }
        return $this->cache->get('cats');
    }

    public function get_user_categories(): array {
        if (!$this->usercache->has('cats')) {
            $this->usercache->set('cats', $this->load_user_categories());
        }
        return $this->usercache->get('cats');
    }

    public function is_user_manager(): bool {
        return !empty($this->get_user_categories());
    }

    public function purge() {
        $this->cache->purge();
        $this->usercache->purge();
    }

}