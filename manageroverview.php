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
 * Overview for evasys course managers.
 *
 * @package block_evasys_sync
 * @copyright 2022 Justus Dieckmann WWU
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use block_evasys_sync\evalcat_manager;

require_once(__DIR__ . '/../../config.php');
global $CFG, $OUTPUT, $PAGE;
require_once($CFG->libdir . '/tablelib.php');

require_login();

$usercats = evalcat_manager::get_instance()->get_user_categories();
if (count($usercats) === 0) {
    throw new coding_exception('You do not have permission to manage any course evaluations!');
} else if (count($usercats) === 1) {
    redirect(new moodle_url('/blocks/evasys_sync/managecategory.php', ['id' => $usercats[0]]));
}

$PAGE->set_url(new moodle_url('/blocks/evasys_sync/manageroverview.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('evasys_sync', 'block_evasys_sync'));

$PAGE->navigation->add('EvaSys', new moodle_url('/blocks/evasys_sync/manageroverview.php'));

$categories = evalcat_manager::get_instance()->get_categories();

$table = new flexible_table('block_evasys_sync-manageroverview');

echo $OUTPUT->header();

$table->define_headers(['']);
$table->define_columns(['column']);
$table->define_baseurl($PAGE->url);

$table->setup();

foreach ($usercats as $usercat) {
    $table->add_data([
            html_writer::link(new moodle_url('/blocks/evasys_sync/managecategory.php', ['id' => $usercat]),
                    core_course_category::get($usercat)->name)
    ]);
}

$table->finish_output();

echo $OUTPUT->footer();
