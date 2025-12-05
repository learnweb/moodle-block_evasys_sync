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


defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) {
    $ADMIN->add('blocksettings', new admin_externalpage('block_evasys_sync',
        get_string('evasys_sync', 'block_evasys_sync'),
        new moodle_url('/blocks/evasys_sync/adminsettings.php')));

    $ADMIN->add('blocksettings', new admin_category(
        'block_evasys_sync_settings',
        new lang_string('evasys_sync', 'block_evasys_sync')));
    $settingspage = new admin_settingpage(
        'manageevasyssync', new lang_string('evasys_sync', 'block_evasys_sync'));

    if ($ADMIN->fulltree) {
        $settingspage->add(new admin_setting_heading(
            'block_evasys_connection_settings',
            'Connection settings', 'These are the necessary settings to connect to the external EvaSys instance.'));
        $settingspage->add(new admin_setting_configtext(
            'block_evasys_sync/evasys_username',
            get_string('settings_username', 'block_evasys_sync'),
            '',
            '',
            PARAM_TEXT
        ));

        $settingspage->add(new admin_setting_configpasswordunmask(
            'block_evasys_sync/evasys_password',
            get_string('settings_password', 'block_evasys_sync'),
            '',
            ''
        ));

        $settingspage->add(new admin_setting_configtext(
            'block_evasys_sync/evasys_soap_url',
            get_string('settings_soap_url', 'block_evasys_sync'),
            '',
            '',
            PARAM_TEXT
        ));

        $settingspage->add(new admin_setting_configtext(
            'block_evasys_sync/evasys_wsdl_url',
            get_string('settings_wsdl_url', 'block_evasys_sync'),
            '',
            '',
            PARAM_TEXT
        ));
    }

    $ADMIN->add('blocksettings', $settingspage);
}
$settings = null;
