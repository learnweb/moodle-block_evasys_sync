<?php
// This file replaces:
//   * STATEMENTS section in db/install.xml
//   * lib.php/modulename_install() post installation hook
//   * partially defaults.php
defined('MOODLE_INTERNAL') || die();

/**
 * install.php
 *
 * @package block_evasys_sync
 * @copyright 2022 Justus Dieckmann WWU
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

function xmldb_block_evasys_sync_install() {
    xmldb_block_evasys_sync_create_role();
}

function xmldb_block_evasys_sync_create_role() {
    global $DB;

    if ($DB->record_exists('role', ['shortname' => 'evasysmanager'])) {
        return;
    }

    // Just reload access.php, doesn't happen until later by default.
    update_capabilities('block_evasys_sync');

    // Set up the course manager role.
    $rid = create_role('EvaSys course manager', 'evasysmanager', '');
    assign_capability('block/evasys_sync:managecourses', CAP_ALLOW, $rid, context_system::instance());
    set_role_contextlevels($rid, [CONTEXT_COURSECAT]);
}