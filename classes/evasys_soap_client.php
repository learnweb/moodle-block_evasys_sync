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

use stdClass;

/**
 * Singleton for evasys soap client.
 * @package block_evasys_sync
 * @copyright 2022 Justus Dieckmann WWU
 */
class evasys_soap_client {
    private static $instance = null;

    public static function get(): \SoapClient {
        if (!self::$instance) {
            self::$instance = self::create_soap_client();
        }
        return self::$instance;
    }

    private static function create_soap_client(): \SoapClient {
        $soapclient = new \SoapClient(get_config('block_evasys_sync', 'evasys_wsdl_url'), [
            'trace' => 1,
            'exceptions' => 0,
            'location' => get_config('block_evasys_sync', 'evasys_soap_url'),
        ]);

        $headerbody = new \SoapVar([
            new \SoapVar(get_config('block_evasys_sync', 'evasys_username'), XSD_STRING, null, null, 'Login', null),
            new \SoapVar(get_config('block_evasys_sync', 'evasys_password'), XSD_STRING, null, null, 'Password', null),
        ], SOAP_ENC_OBJECT);
        $header = new \SoapHeader('soap', 'Header', $headerbody);
        $soapclient->__setSoapHeaders($header);
        return $soapclient;
    }

    public function userids(): mixed {
        $soapclient = self::get();
        $result = $soapclient->GetUserIdsByParams([]);
        if ($result instanceof SoapFault) {
            return null;
        } else {
            return $result->Strings;
        }
    }

    public function courses_by_user(stdClass $user): array {
        $soapclient = self::get();
        $result = $soapclient->GetUserIdsByParams(['Email' => $user->email]);
        if ($result instanceof SoapFault) {
            throw $result;
        } else if (empty($result->Strings)) {
            throw \moodle_exception('evasysusernotfound');
        } else {
            foreach ($result->Strings as $id) {
                $result = $soapclient->GetCoursesByUserId((int) $id);
                var_dump($result);
                if ($result instanceof SoapFault) {
                    throw $result;
                } else {
                    return $result->Courses;
                }
            }

            return [];
        }
    }
}
