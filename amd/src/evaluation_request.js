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
 * Dynamically edits the evaluation_request mform.
 *
 * @module     block_evasys_sync/evaluation_request
 * @copyright  2022 Justus Dieckmann WWU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import Templates from 'core/templates';
import Prefetch from 'core/prefetch';
import {get_string as getString} from "core/str";

const savedDates = {};

/**
 * Returns the date that is currently selected in the given date_time_selector.
 * @param {string} id
 * @returns {Date}
 */
function getSelectorTime(id) {
    const d = new Date();
    d.setFullYear(
        document.getElementById(id + '_year').value,
        document.getElementById(id + '_month').value - 1,
        document.getElementById(id + '_day').value
    );
    d.setHours(
        document.getElementById(id + '_hour').value,
        document.getElementById(id + '_minute').value,
    );
    return d;
}

/**
 * Sets the date_time_selector time.
 * @param {string} id
 * @param {Date} date
 */
function setSelectorTime(id, date) {
    document.getElementById(id + '_year').value = date.getFullYear();
    document.getElementById(id + '_month').value = date.getMonth() + 1;
    document.getElementById(id + '_day').value = date.getDate();
    document.getElementById(id + '_hour').value = date.getHours();
    document.getElementById(id + '_minute').value = date.getMinutes();
}

/**
 * Initializes things.
 */
export async function init() {
    const select = document.getElementById('id_lsfcourses');
    const evaltimes = document.getElementById('fitem_id_evaltimes');

    const dateselectortemplate = document.getElementById('evasys-dateselectortemplate').innerHTML;

    Prefetch.prefetchTemplate('block_evasys_sync/form');

    const strings = await Promise.all([
        getString('startondate', 'block_evasys_sync'),
        getString('endondate', 'block_evasys_sync')
    ]);
    const startdateselectortemplate = dateselectortemplate.replaceAll('MyEvasysElementLabel', strings[0]);
    const enddateselectortemplate = dateselectortemplate.replaceAll('MyEvasysElementLabel', strings[1]);

    select.onchange = async() => {
        for (const lsfcourse of evaltimes.children) {
            const id = lsfcourse.getAttribute('data-evasys-lsfid');
            savedDates[id] = {
                start: getSelectorTime('id_evaltimestart_' + id),
                end: getSelectorTime('id_evaltimeend_' + id)
            };
        }
        let html = '';
        evaltimes.disabled = true;
        for (const option of select.selectedOptions) {
            html += await Templates.render('block_evasys_sync/form', {
                html: startdateselectortemplate.replaceAll('myevasyselementname', 'evaltimestart_' + option.value)
                    + enddateselectortemplate.replaceAll('myevasyselementname', 'evaltimeend_' + option.value),
                lsfid: option.value,
                coursename: option.text
            });
        }
        evaltimes.innerHTML = html;
        for (const option of select.selectedOptions) {
            if (option.value in savedDates) {
                setSelectorTime('id_evaltimestart_' + option.value, savedDates[option.value].start);
                setSelectorTime('id_evaltimeend_' + option.value, savedDates[option.value].end);
            } else {
                setSelectorTime('id_evaltimestart_' + option.value, new Date());
                setSelectorTime('id_evaltimeend_' + option.value, new Date());
            }
        }
        evaltimes.disabled = false;
    };
}