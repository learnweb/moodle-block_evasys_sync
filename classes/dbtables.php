<?php
// This file is part of the Moodle plugin block_evasys_sync
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

defined('MOODLE_INTERNAL') || die();

/**
 * Class with constants for tables
 * @package block_evasys_sync
 * @copyright 2022 Justus Dieckmann WWU
 */
class dbtables {

    const CATEGORIES = 'block_evasys_sync_categories';
    const EVAL_REQUESTS = 'block_evasys_sync_ereq';
    const EVAL_REQUESTS_COURSES = 'block_evasys_sync_ereq_cours';
    const EVAL_REQUESTS_VERANSTS = 'block_evasys_sync_ereq_veran';
    const EVAL = 'block_evasys_sync_eval';
    const EVAL_COURSES = 'block_evasys_sync_eval_cours';
    const EVAL_VERANSTS = 'block_evasys_sync_eval_veran';
    const ERRORS = 'block_evasys_sync_errors';

}
