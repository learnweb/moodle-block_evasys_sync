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

require_once('../../config.php');

require_login();
require_sesskey();
require_capability('moodle/site:config', context_system::instance());

$starttime = optional_param('starttime', null, PARAM_INT);
$endtime = optional_param('endtime', null, PARAM_INT);
$cat = required_param('category', PARAM_INT);

global $DB;
$sql = 'UPDATE {block_evasys_sync_categories} SET standard_time_start = ?, standard_time_end = ? ' .
    'WHERE id = ?';
$DB->execute($sql, [$starttime, $endtime, $cat]);
