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

defined('MOODLE_INTERNAL') || die();

$capabilities = [

  'block/evasys_sync:addinstance' => [
    'riskbitmask' => RISK_SPAM,

    'captype' => 'write',
    'contextlevel' => CONTEXT_BLOCK,
    'archetypes' => [
      'editingteacher' => CAP_ALLOW,
      'manager' => CAP_ALLOW,
    ],

    'clonepermissionsfrom' => 'moodle/site:manageblocks',
  ],

  'block/evasys_sync:mayevaluate' => [
    'captype' => 'write',
    'contextlevel' => CONTEXT_BLOCK,
    'archetypes' => [
      'student' => CAP_ALLOW,
      // Prohibit manager and teacher intentionally.
    ],
  ],

  'block/evasys_sync:synchronize' => [
    'riskbitmask' => RISK_SPAM | RISK_PERSONAL,
    'captype' => 'write',
    'contextlevel' => CONTEXT_BLOCK,
    'archetypes' => [
      'editingteacher' => CAP_ALLOW,
      'manager' => CAP_ALLOW,
    ],
  ],
  'block/evasys_sync:modifymapping' => [
      'riskbitmap' => RISK_XSS,
      'captype' => 'write',
      'contextlevel' => CONTEXT_BLOCK,
      'archetypes' => [
          'editingteacher' => CAP_ALLOW,
          'manager' => CAP_ALLOW,
      ],
  ],
  'block/evasys_sync:managecourses' => [
      'captype' => 'write',
      'contextlevel' => CONTEXT_COURSECAT,
  ],
  'block/evasys_sync:teacherforcourse' => [
      'contextlevel' => CONTEXT_COURSE,
      'captype' => 'read',
      'archetypes' => [
          'editingteacher' => CAP_ALLOW,
      ],
  ],
];
