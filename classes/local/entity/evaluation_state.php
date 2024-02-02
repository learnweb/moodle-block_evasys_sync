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

namespace block_evasys_sync\local\entity;

use block_evasys_sync\evaluation;

defined('MOODLE_INTERNAL') || die();

/**
 * Evaluation state
 * @package block_evasys_sync
 * @copyright 2022 Justus Dieckmann WWU
 */
class evaluation_state {

    public const PENDING = 0;
    public const PLANNED = 1;
    public const SYNCHRONIZED = 2;
    public const RUNNING = 3;
    public const FINISHED = 4;
    public const MANUAL = 5;
    public const MANUAL_VERIFIED = 6;

    public const MANUAL_STATES = [self::MANUAL, self::MANUAL_VERIFIED];

    public function is_manual(\stdClass $evaluation) {
        return in_array($evaluation->state, self::MANUAL_STATES);
    }

}