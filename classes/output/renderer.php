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
 * Renderer for life cycle
 *
 * @package    block_evasys_sync
 * @copyright  2023 Justus Dieckmann WWU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_evasys_sync\output;

use block_evasys_sync\evasys_category;
use html_writer;

/**
 * Renderer for life cycle
 *
 * @package    block_evasys_sync
 * @copyright  2023 Justus Dieckmann WWU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class renderer extends \plugin_renderer_base {

    function print_evasys_category_header(evasys_category $evasys_category) {
        $catid = $evasys_category->get('course_category');
        $category = \core_course_category::get($catid);

        echo $this->output->box_start('generalbox border p-3 mb-3');

        echo html_writer::tag('h2', $category->name);

        echo html_writer::start_div('text-muted');
        if ($evasys_category->get('standard_time_start') && $evasys_category->get('standard_time_end')) {
            echo html_writer::span(get_string('default_period_set_from_to', 'block_evasys_sync', [
                'start' => userdate($evasys_category->get('standard_time_start')),
                'end' => userdate($evasys_category->get('standard_time_end')),
            ])) . '<br>';
        } else {
            echo html_writer::span(get_string('no_default_period_set', 'block_evasys_sync')) . '<br>';
        }
        if ($evasys_category->is_automatic()) {
            echo html_writer::span('Evaluations are created <b>automatically</b>.');
            if ($evasys_category->can_teacher_request_evaluation()) {
                if ($evasys_category->teacher_evaluation_request_needs_approval()) {
                    echo 'Evaluations requested by teachers are created <b>after</a> your approval.' . '<br>';
                } else {
                    echo 'Teacher can create evaluations <b>without</b> your approval.' . '<br>';
                }
            } else {
                echo html_writer::span('Teachers <b>cannot</b> request evaluations.') . '<br>';
            }
        } else {
            echo html_writer::span('Evaluations have to be created <b>manually</b>.') . '<br>';
            if ($evasys_category->can_teacher_request_evaluation()) {
                echo html_writer::span('Teachers <b>can</b> request evaluations.') . '<br>';
            } else {
                echo html_writer::span('Teachers <b>cannot</b> request evaluations.') . '<br>';
            }
        }
        echo html_writer::end_div() . '<br>';
        echo html_writer::link(new \moodle_url('/blocks/evasys_sync/editcategory.php', ['id' => $catid]), get_string('edit'));

        echo $this->output->box_end();
    }

}
