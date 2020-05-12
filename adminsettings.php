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
 * Settings for the evasys_sync block
 *
 * @package block_evasys_sync
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->dirroot . '/course/lib.php');
$delid = optional_param('d', 0, PARAM_INT);
$confirm = optional_param('c', 0, PARAM_INT);
require_login();

// Set the URL that should be used to return to this page.
$PAGE->set_url('/blocks/evasys_sync/adminsettings');

if (has_capability('moodle/site:config', context_system::instance())) {
    admin_externalpage_setup('block_evasys_sync');

    if (!empty($delid) && !empty($confirm)) {
        // Course category user is deleted.
        $persistent = new \block_evasys_sync\user_cat_allocation($delid);
        $persistent->delete();

        redirect($PAGE->url);
        exit();
    }

    $mform = new block_evasys_sync\admin_form();

    if (!empty($delid)) {
        // Deletion has to be confirmed.
        // Print a confirmation message.
        echo $OUTPUT->header();
        echo $OUTPUT->heading(get_string('settings', 'block_evasys_sync'));
        echo $OUTPUT->confirm(get_string("delete_confirm", 'block_evasys_sync'),
            "adminsettings.php?d=$delid&c=$delid",
            'adminsettings.php');
        echo $OUTPUT->footer();
        exit();
    } else if ($data = $mform->get_data()) {
        // Form is submitted.
        // Added course category.
        if (isset($data->addcatbutton)) {
            // Insert new record.
            $record = new stdClass();
            $record->userid = $data->evasys_cc_user;
            $record->course_category = $data->evasys_cc_select;
            if (isset($data->evasys_cc_mode) && $data->evasys_cc_mode == 1) {
                $record->category_mode = 1;
            } else {
                $record->category_mode = 0;
            }
            $persistent = new \block_evasys_sync\user_cat_allocation(0, $record);
            $persistent->create();
            // redirect($PAGE->url);
            exit();
        } else if (isset($data->submitbutton)) {
            if (isset($data->evasys_wsdl_url)) {
                set_config('evasys_wsdl_url', $data->evasys_wsdl_url, 'block_evasys_sync');
            }
            if (isset($data->evasys_soap_url)) {
                set_config('evasys_soap_url', $data->evasys_soap_url, 'block_evasys_sync');
            }
            if (isset($data->evasys_username)) {
                set_config('evasys_username', $data->evasys_username, 'block_evasys_sync');
            }
            if (isset($data->evasys_password)) {
                set_config('evasys_password', $data->evasys_password, 'block_evasys_sync');
            }
            if (isset($data->default_evasys_moodleuser)) {
                set_config('default_evasys_moodleuser', $data->default_evasys_moodleuser, 'block_evasys_sync');
            }
            // Checkboxes will only get submitted if they're checked...
            if (isset($data->default_evasys_mode)) {
                set_config('default_evasys_mode', $data->default_evasys_mode, 'block_evasys_sync');
            } else {
                set_config('default_evasys_mode', 0, 'block_evasys_sync');
            }

            $records = \block_evasys_sync\user_cat_allocation::get_records();
            foreach ($records as $allocation) {
                $newvalue = 'category_' . $allocation->get('id');
                $oldvalue = $allocation->get('userid');
                $newvaluemodename = 'category_mode_' . $allocation->get('id');

                // Checkboxex only get submitted if they're checked.
                if (isset($data->$newvaluemodename) && $data->$newvaluemodename) {
                    $newvaluemode = 1; // Category uses automated mode.
                } else {
                    $newvaluemode = 0; // Category uses manual mode.
                }
                try {
                    $oldvaluemode = $allocation->get('category_mode');
                } catch (coding_exception $e) {
                    $oldvaluemode = $newvaluemode + 1; // Make the below if statement execute.
                }
                // Update db entry.
                if ($data->$newvalue != $oldvalue or
                    $newvaluemode != $oldvaluemode) {

                    $allocation->set('userid', $data->$newvalue);
                    $allocation->set('category_mode', $newvaluemode);
                    $allocation->update();
                }
            }
        }
    }

    echo $OUTPUT->header();
    echo $OUTPUT->heading(get_string('settings', 'block_evasys_sync'));

    // Load existing setttings.
    if (empty($entry->id)) {
        $entry = new stdClass;
        $entry->id = 0;
    }

    $entry->evasys_username = get_config('block_evasys_sync', 'evasys_username');
    $entry->evasys_password = get_config('block_evasys_sync', 'evasys_password');
    $entry->evasys_soap_url = get_config('block_evasys_sync', 'evasys_soap_url');
    $entry->evasys_wsdl_url = get_config('block_evasys_sync', 'evasys_wsdl_url');
    $entry->default_evasys_moodleuser = get_config('block_evasys_sync', 'default_evasys_moodleuser');

    $mform->set_data($entry);
    $mform->display();
    echo $OUTPUT->footer();
}
