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
 * This file keeps track of upgrades to the evasys_sync block
 *
 * Sometimes, changes between versions involve alterations to database
 * structures and other major things that may break installations. The upgrade
 * function in this file will attempt to perform all the necessary actions to
 * upgrade your older installation to the current version. If there's something
 * it cannot do itself, it will tell you what you need to do.  The commands in
 * here will all be database-neutral, using the functions defined in DLL libraries.
 *
 * @package   blocks_evasys_sync
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use block_evasys_sync\dbtables;

defined('MOODLE_INTERNAL') || die();

/**
 * Execute evasys_sync upgrade from the given old version
 *
 * @param int $oldversion
 * @return bool
 */
function xmldb_block_evasys_sync_upgrade ($oldversion) {
    global $DB;
    $dbman = $DB->get_manager();
    if ($oldversion < 2017121403) {

        // Define table block_evasys_sync_categories to be created.
        $table = new xmldb_table('block_evasys_sync_categories');

        // Adding fields to table block_evasys_sync_categories.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('course_category', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('usermodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table block_evasys_sync_categories.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_key('course_category', XMLDB_KEY_FOREIGN_UNIQUE, array('course_category'), 'course_categories', array('id'));
        $table->add_key('userid', XMLDB_KEY_FOREIGN, array('userid'), 'user', array('id'));

        // Conditionally launch create table for block_evasys_sync_categories.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Evasys_sync savepoint reached.
        upgrade_block_savepoint(true, 2017121403, 'evasys_sync');
    }

    if ($oldversion < 2019032600) {

        // Define table block_evasys_sync_categories to be created.
        $table = new xmldb_table('block_evasys_sync_categories');
        $table->add_field('category_mode', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, 0);

        $coursetable = new xmldb_table('block_evasys_sync_surveys');
        $coursetable->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $coursetable->add_field('course', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $coursetable->add_field('survey', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $coursetable->add_field('startdate', XMLDB_TYPE_INTEGER, '10', null, false, null, null);
        $coursetable->add_field('enddate', XMLDB_TYPE_INTEGER, '10', null, false, null, null);
        $coursetable->add_field('usermodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $coursetable->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $coursetable->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

        $coursetable->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $coursetable->add_key('survey', XMLDB_KEY_FOREIGN_UNIQUE, array('survey'), 'course', array('id'));

        $multitable = new xmldb_table('block_evasys_sync_courses');
        $multitable->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $multitable->add_field('course', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $multitable->add_field('evasyscourses', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
        $multitable->add_field('usermodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $multitable->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $multitable->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

        $multitable->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $multitable->add_key('course', XMLDB_KEY_FOREIGN_UNIQUE, array('course'), 'course', array('id'));
        // Conditionally launch create table for block_evasys_sync_categories.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }
        if (!$dbman->table_exists($coursetable)) {
            $dbman->create_table($coursetable);
        }
        if (!$dbman->table_exists($multitable)) {
            $dbman->create_table($multitable);
        }

        // Add new fields to existing table.
        foreach ($table->getFields() as $item) {
            if (!$dbman->field_exists($table, $item)) {
                $dbman->add_field($table, $item);
            }
        }
        foreach ($coursetable->getFields() as $item) {
            if (!$dbman->field_exists($coursetable, $item)) {
                $dbman->add_field($coursetable, $item);
            }
        }
        foreach ($multitable->getFields() as $item) {
            if (!$dbman->field_exists($multitable, $item)) {
                $dbman->add_field($multitable, $item);
            }
        }

        // Evasys_sync savepoint reached.
        upgrade_block_savepoint(true, 2019032600, 'evasys_sync');

    }
    if ($oldversion < 2019172402) {
        $coursetable = new xmldb_table('block_evasys_sync_surveys');
        $coursetable->add_field('state', XMLDB_TYPE_INTEGER, '2', null, true, null, 0);
        foreach ($coursetable->getFields() as $item) {
            if (!$dbman->field_exists($coursetable, $item)) {
                $dbman->add_field($coursetable, $item);
            }
        }
        $time = time();
        $DB->execute("UPDATE {block_evasys_sync_surveys} SET state=1 WHERE startdate <= $time");
        $DB->execute("UPDATE {block_evasys_sync_surveys} SET state=2 WHERE enddate <= $time");
        upgrade_block_savepoint(true, 2019172402, 'evasys_sync');
    }

    if ($oldversion < 2019180500) {
        $coursetable = new xmldb_table('block_evasys_sync_courseeval');

        $coursetable->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $coursetable->add_field('course', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $coursetable->add_field('startdate', XMLDB_TYPE_INTEGER, '10', null, false, null, null);
        $coursetable->add_field('enddate', XMLDB_TYPE_INTEGER, '10', null, false, null, null);
        $coursetable->add_field('state', XMLDB_TYPE_INTEGER, '3', null, XMLDB_NOTNULL, null, 0);
        $coursetable->add_field('usermodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $coursetable->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $coursetable->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

        $coursetable->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $coursetable->add_key('course', XMLDB_KEY_FOREIGN_UNIQUE, array('course'), 'course', array('id'));

        if (!$dbman->table_exists($coursetable)) {
            $dbman->create_table($coursetable);
        }

        $oldtable = new xmldb_table('block_evasys_sync_surveys');
        if ($dbman->table_exists($oldtable)) {
            // Migrate data.
            $DB->execute("INSERT INTO {block_evasys_sync_courseeval}".
                " (course, startdate, enddate, usermodified, timecreated, timemodified)".
                " SELECT course, startdate, enddate, usermodified, timecreated, timemodified FROM {block_evasys_sync_surveys}");

            // Drop old table.
            $dbman->drop_table($oldtable);
        }
        upgrade_block_savepoint(true, 2019180500, 'evasys_sync');
    }

    if ($oldversion < 2019190000) {
        // Changing type of field evasyscourses on table block_evasys_sync_courses to text.
        $table = new xmldb_table('block_evasys_sync_courses');
        $field = new xmldb_field('evasyscourses', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null, 'course');

        // Launch change of type for field evasyscourses.
        $dbman->change_field_type($table, $field);

        // Evasys_sync savepoint reached.
        upgrade_block_savepoint(true, 2019190000, 'evasys_sync');
    }

    if ($oldversion < 2019200800) {
        $table = new xmldb_table('block_evasys_sync_categories');
        $field = new xmldb_field('standard_time_mode', XMLDB_TYPE_INTEGER, '10', null, false, null, 0);
        $dbman->add_field($table, $field);

        upgrade_block_savepoint(true, 2019200800, 'evasys_sync');
    }

    if ($oldversion < 2019201400) {

        $table = new xmldb_table('block_evasys_sync_categories');
        $field = new xmldb_field('standard_time_mode', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'standard_time_end');

        if ($dbman->field_exists($table, $field)) {
            $dbman->drop_field($table, $field);
        }

        // Define field standard_time_start to be added to block_evasys_sync_categories.
        $field = new xmldb_field('standard_time_start', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'standard_time_end');

        // Conditionally launch add field standard_time_start.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field standard_time_end to be added to block_evasys_sync_categories.
        $field = new xmldb_field('standard_time_end', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'timemodified');

        // Conditionally launch add field standard_time_end.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Evasys_sync savepoint reached.
        upgrade_block_savepoint(true, 2019201400, 'evasys_sync');
    }

    if ($oldversion < 2019203100) {

        // Define field usestandardtime to be added to block_evasys_sync_courseeval.
        $table = new xmldb_table('block_evasys_sync_courseeval');
        $field = new xmldb_field('usestandardtime', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0', 'timemodified');

        // Conditionally launch add field usestandardtime.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Evasys_sync savepoint reached.
        upgrade_block_savepoint(true, 2019203100, 'evasys_sync');
    }

    if ($oldversion < 2022092400) {

        /// Create Evasys Manager Role. ///
        require_once(__DIR__ . '/install.php');
        xmldb_block_evasys_sync_create_role();

        /// Create new tables. ///

        // Define table block_evasys_sync_ereq to be created.
        $table = new xmldb_table('block_evasys_sync_ereq');

        // Adding fields to table block_evasys_sync_ereq.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('courseid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('state', XMLDB_TYPE_INTEGER, '4', null, XMLDB_NOTNULL, null, null);
        $table->add_field('shouldevaluate', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '1');
        $table->add_field('evasyscategoryid', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('usermodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

        // Adding keys to table block_evasys_sync_ereq.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('courseid', XMLDB_KEY_UNIQUE, ['courseid']);
        $table->add_key('evasyscategoryid', XMLDB_KEY_FOREIGN, ['evasyscategoryid'], 'block_evasys_sync_categories', ['id']);
        $table->add_key('usermodified', XMLDB_KEY_FOREIGN, ['usermodified'], 'user', ['id']);

        // Conditionally launch create table for block_evasys_sync_ereq.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Define table block_evasys_sync_ereq_cours to be created.
        $table = new xmldb_table('block_evasys_sync_ereq_cours');

        // Adding fields to table block_evasys_sync_ereq_cours.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('erequestid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('courseid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table block_evasys_sync_ereq_cours.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('erequestid', XMLDB_KEY_FOREIGN, ['erequestid'], 'block_evasys_sync_ereq', ['id']);
        $table->add_key('courseid', XMLDB_KEY_FOREIGN_UNIQUE, ['courseid'], 'course', ['id']);

        // Conditionally launch create table for block_evasys_sync_ereq_cours.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Define table block_evasys_sync_ereq_veran to be created.
        $table = new xmldb_table('block_evasys_sync_ereq_veran');

        // Adding fields to table block_evasys_sync_ereq_veran.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('erequestid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('veranstid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('veransttitle', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('starttime', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('endtime', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table block_evasys_sync_ereq_veran.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('erequestid', XMLDB_KEY_FOREIGN, ['erequestid'], 'block_evasys_sync_ereq', ['id']);
        $table->add_key('veranstid', XMLDB_KEY_UNIQUE, ['veranstid']);

        // Conditionally launch create table for block_evasys_sync_ereq_veran.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Define table block_evasys_sync_eval to be created.
        $table = new xmldb_table('block_evasys_sync_eval');

        // Adding fields to table block_evasys_sync_eval.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('initialcourse', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table block_evasys_sync_eval.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Conditionally launch create table for block_evasys_sync_eval.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Define table block_evasys_sync_eval_cours to be created.
        $table = new xmldb_table('block_evasys_sync_eval_cours');

        // Adding fields to table block_evasys_sync_eval_cours.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('evalid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('courseid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table block_evasys_sync_eval_cours.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('evalid', XMLDB_KEY_FOREIGN, ['evalid'], 'block_evasys_sync_eval', ['id']);
        $table->add_key('courseid', XMLDB_KEY_FOREIGN, ['courseid'], 'course', ['id']);

        // Conditionally launch create table for block_evasys_sync_eval_cours.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Define table block_evasys_sync_eval_veran to be created.
        $table = new xmldb_table('block_evasys_sync_eval_veran');

        // Adding fields to table block_evasys_sync_eval_veran.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('evalid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('veranstid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('veransttitle', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('starttime', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('endtime', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('state', XMLDB_TYPE_INTEGER, '4', null, XMLDB_NOTNULL, null, null);
        $table->add_field('usermodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

        // Adding keys to table block_evasys_sync_eval_veran.
        $table->add_key('id', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('evalid', XMLDB_KEY_FOREIGN, ['evalid'], 'block_evasys_sync_eval', ['id']);
        $table->add_key('veranstid', XMLDB_KEY_UNIQUE, ['veranstid']);
        $table->add_key('usermodified', XMLDB_KEY_FOREIGN, ['usermodified'], 'user', ['id']);

        // Conditionally launch create table for block_evasys_sync_eval_veran.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        /// Migrate data. ///

        $recordset = $DB->get_recordset_sql(
            'SELECT ce.*, c.idnumber, ec.evasyscourses FROM {block_evasys_sync_courseeval} ce ' .
            'JOIN {course} c ON ce.course = c.id ' .
            'LEFT JOIN {block_evasys_sync_courses} ec ON ce.course = ec.course'
        );
        $existingveranst = [];
        while ($recordset->valid()) {
            $record = $recordset->current();

            // Create array of associated veranstaltungen.
            $veransts = [];
            if ($record->evasyscourses) {
                $veransts = explode('#', $record->evasyscourses);
            }
            if ($record->idnumber) {
                $veransts[] = $record->idnumber;
            }

            // Check if any veranstaltungen already exist in the database.
            list($insql, $inparams) = $DB->get_in_or_equal($veransts);
            $evalgroups = $DB->get_fieldset_sql(
                'SELECT evalid FROM {' . dbtables::EVAL_VERANSTS . '} ' .
                'WHERE veranstid ' . $insql . ' ' .
                'ORDER BY evalid ASC',
                $inparams
            );
            if (empty($evalgroups)) {
                // If not, create new evaluation group.
                $evalid = $DB->insert_record(dbtables::EVAL, ['initialcourse' => $record->course]);
            } else {
                // If true, merge all evaluation groups with veranstaltungen of this course into the one.
                $evalid = array_shift($evalgroups);
                list($insql, $inparams) = $DB->get_in_or_equal($evalgroups, SQL_PARAMS_NAMED);
                $inparams['evalid'] = $evalid;
                $DB->execute(
                    'UPDATE {' . dbtables::EVAL_COURSES . '} ' .
                    'SET evalid = :evalid ' .
                    'WHERE evalid ' . $insql,
                    $inparams
                );
                $DB->execute(
                    'UPDATE {' . dbtables::EVAL_VERANSTS . '} ' .
                    'SET evalid = :evalid ' .
                    'WHERE evalid ' . $insql,
                    $inparams
                );
                unset($inparams['evalid']);
                $DB->execute(
                    'DELETE FROM {' . dbtables::EVAL . '} ' .
                    'WHERE id ' . $insql,
                    $inparams
                );
            }
            $DB->insert_record(dbtables::EVAL_COURSES, [
                'evalid' => $evalid,
                'courseid' => $record->course
            ]);
            foreach($veransts as $veranst) {
                if (!isset($existingveranst[$veranst])) {
                    // TODO soap, get course info with title + maybe state.
                    $id = $DB->insert_record('block_evasys_sync_eval', [
                        'evalid' => $evalid,
                        'veranstid' => $veranst,
                        'veransttitle' => null, // TODO thing.
                        'starttime' => $record->startdate,
                        'endtime' => $record->enddate,
                        'state' => $record->state, // TODO correct?
                        'usermodified' => $record->usermodified,
                        'timecreated' => $record->timecreated,
                        'timemodified' => $record->timemodified
                    ], true, true);
                    $existingveranst[$veranst] = $id;
                }
            }
            $recordset->next();
        }

        /// Drop old tables. ///

        // Define table block_evasys_sync_courseeval to be dropped.
        $table = new xmldb_table('block_evasys_sync_courseeval');

        // Conditionally launch drop table for block_evasys_sync_courseeval.
        if ($dbman->table_exists($table)) {
            $dbman->drop_table($table);
        }

        // Define table block_evasys_sync_courses to be dropped.
        $table = new xmldb_table('block_evasys_sync_courses');

        // Conditionally launch drop table for block_evasys_sync_courses.
        if ($dbman->table_exists($table)) {
            $dbman->drop_table($table);
        }

        // Evasys_sync savepoint reached.
        upgrade_block_savepoint(true, 2022092400, 'evasys_sync');
    }

    return true;
}
