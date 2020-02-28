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
/**
 * Created by PhpStorm.
 * User: robintschudi
 * Date: 08.11.19
 * Time: 15:56
 */

namespace block_evasys_sync;

use gradereport_singleview\local\screen\select;

defined('MOODLE_INTERNAL') || die();

class evasys_api {

    private $soapclient;
    private static $instance;

    private function __construct() {
        $this->soapclient = $this->init_soap_client();
    }

    public static function get_instance() {
        if (defined("BEHAT_SITE_RUNNING")) {
            return evasys_api_testable::get_instance();
        }
        if (!self::$instance) {
            self::$instance = new evasys_api();
        }
        return self::$instance;
    }

    public function get_evasys_course($evasyskennung) {
        $soapresult = $this->soapclient->GetCourse($evasyskennung, 'PUBLIC', true, false);
        return $soapresult;
    }

    public function send_invitation_to_participants_of_survey($surveyid) {
        $this->soapclient->SendInvitationToParticipants($surveyid);
    }

    public function insert_participants ($personlist, $evasyscourse) {
        $soapresult = $this->soapclient->InsertParticipants($personlist, $evasyscourse, 'PUBLIC', false);
        return $soapresult;
    }

    /** Makes sure a course has at least as many passwords as users.
     * Will create passwords up to the usercount provided.
     * @param $evasysid mixed Id of evasys course.
     * @param $usercount int number of users.
     */
    public function create_passwords_for_all_users ($evasysid, $usercount) {
        $this->soapclient->GetPswdsBySurvey($evasysid, $usercount, 1, true, false);
    }

    public function insert_close_task ($task) {
        return $this->soapclient->InsertCloseTask($task);
    }

    public function close_survey($surveyid) {
        return $this->soapclient->CloseSurvey($surveyid);
    }

    public function get_form($formid ) {
        $soapresult = $this->soapclient->GetForm($formid, 'INTERNAL', false);
        return $soapresult;
    }

    private function init_soap_client() {
        $soapclient = new \SoapClient(get_config('block_evasys_sync', 'evasys_wsdl_url'), [
            'trace' => 1,
            'exceptions' => 0,
            'location' => get_config('block_evasys_sync', 'evasys_soap_url')
        ]);

        $headerbody = new \SoapVar([
                                       new \SoapVar(get_config('block_evasys_sync', 'evasys_username'),
                                                    XSD_STRING, null, null, 'Login', null),
                                       new \SoapVar(get_config('block_evasys_sync', 'evasys_password'),
                                                    XSD_STRING, null, null, 'Password', null),
                                   ], SOAP_ENC_OBJECT);
        $header = new \SOAPHEADER('soap', 'Header', $headerbody);
        $soapclient->__setSoapHeaders($header);
        return $soapclient;
    }

}

class evasys_api_testable extends evasys_api {
    private static $instance;
    private static $prefix = "behaterino";

    private function __construct() {
        global $DB;
        $prefix = self::$prefix;
        // Store data in tables, so it's preserved over multiple site calls.
        $DB->execute("CREATE TABLE IF NOT EXISTS {$prefix}_evasys_mock_courses (identifier VARCHAR (50) PRIMARY KEY,
                                                                                    title VARCHAR(50),
                                                                                    studcount INTEGER)");
        $DB->execute("CREATE TABLE IF NOT EXISTS {$prefix}_evasys_mock_surveys (id INTEGER PRIMARY KEY,
                                                                                    title VARCHAR(20),
                                                                                    formid INTEGER,
                                                                                    is_open BOOLEAN,
                                                                                    form_count INTEGER,
                                                                                    pswd_count INTEGER,
                                                                                    related_course_identifier VARCHAR(50))");
        $DB->execute("CREATE TABLE IF NOT EXISTS {$prefix}_evasys_mock_forms (frmid INTEGER PRIMARY KEY,
                                                                                    formname VARCHAR(50),
                                                                                    formtitle VARCHAR(50))");
    }

    public function tear_down () {
        global $DB;
        $prefix = self::$prefix;
        $DB->execute("DROP TABLE IF EXISTS {$prefix}_evasys_mock_courses");
        $DB->execute("DROP TABLE IF EXISTS {$prefix}_evasys_mock_surveys");
        $DB->execute("DROP TABLE IF EXISTS {$prefix}_evasys_mock_forms");
    }

    public static function get_instance() {
        if (!self::$instance) {
            self::$instance = new evasys_api_testable();
        }
        return self::$instance;
    }

    public function set_course($identifier, $title, $studcount) {
        global $DB;
        $prefix = self::$prefix;
        print("INSERT INTO {$prefix}_evasys_mock_courses VALUES ('$identifier', '$title', $studcount)");
        $DB->execute("INSERT INTO {$prefix}_evasys_mock_courses VALUES ('$identifier', '$title', $studcount)");
    }

    /* Format: (var dump of SINGLE! survey evasys course)
    object(stdClass)[402]
        public 'm_nCourseId' => int 166410
        public 'm_sProgramOfStudy' => string '' (length=0)
        public 'm_sCourseTitle' => string 'AutoMultiSurvey' (length=15)
        public 'm_sRoom' => string '' (length=0)
        public 'm_nCourseType' => int 1
        public 'm_sPubCourseId' => string '1002 WS 2018/19' (length=15)
        public 'm_sExternalId' => string '' (length=0)
        public 'm_nCountStud' => int 2
        public 'm_sCustomFieldsJSON' => string '{}' (length=2)
        public 'm_nUserId' => int 73350
        public 'm_nFbid' => int 338
        public 'm_nPeriodId' => int 40
        public 'm_aoParticipants' =>
          object(stdClass)[404]
        public 'm_aoSecondaryInstructors' =>
          object(stdClass)[401]
        public 'm_oSurveyHolder' =>
          object(stdClass)[411]
            public 'm_aSurveys' =>
                object(stdClass)[412]
                  public 'Surveys' =>
                    object(stdClass)[413]
                      public 'm_nSurveyId' => int 330416933
                      public 'm_nState' => int 0
                      public 'm_sTitle' => string 'AutoMultiSurvey' (length=15)
                      public 'm_cType' => string 'o' (length=1)
                      public 'm_nFrmid' => int 832
                      public 'm_nStuid' => int 34763
                      public 'm_nVerid' => int 166410
                      public 'm_nOpenState' => int 1
                      public 'm_nFormCount' => int 0
                      public 'm_nPswdCount' => int 2
                      public 'm_sLastDataCollectionDate' => string '' (length=0)
                      public 'm_nPageLinkOffset' => int 0
                      public 'm_sMaskTan' => string '' (length=0)
                      public 'm_nMaskState' => int 0
                      public 'm_oPeriod' =>
                            object(stdClass)[414]
                                public 'm_nPeriodId' => int 40
                                public 'm_sTitel' => string 'WS 2018/19' (length=10)
                                public 'm_sStartDate' => string '2018-10-01' (length=10)
                                public 'm_sEndDate' => string '2019-03-31' (length=10)
    */
    /*
     * Single survey:
        public 'm_aSurveys' =>
            object(stdClass)[412]
                public 'Surveys' =>
                    object(stdClass)[413]
                        public 'm_nSurveyId' => int 330416933
                        public 'm_nState' => int 0
                        public 'm_sTitle' => string 'AutoMultiSurvey' (length=15)
                        public 'm_cType' => string 'o' (length=1)
                        public 'm_nFrmid' => int 832
                        public 'm_nStuid' => int 34763
                        public 'm_nVerid' => int 166410
                        public 'm_nOpenState' => int 1
                        public 'm_nFormCount' => int 0
                        public 'm_nPswdCount' => int 2
                        public 'm_sLastDataCollectionDate' => string '' (length=0)
                        public 'm_nPageLinkOffset' => int 0
                        public 'm_sMaskTan' => string '' (length=0)
                        public 'm_nMaskState' => int 0
                        public 'm_oPeriod' =>
                            object(stdClass)[414]
                                public 'm_nPeriodId' => int 40
                                public 'm_sTitel' => string 'WS 2018/19' (length=10)
                                public 'm_sStartDate' => string '2018-10-01' (length=10)
                                public 'm_sEndDate' => string '2019-03-31' (length=10)
      Multi survey:
        public 'm_aSurveys' =>
            object(stdClass)[412]
                public 'Surveys' =>
                    array (size=2)
                      0 =>
                        object(stdClass)[409]
                          public 'm_nSurveyId' => int 330416933
                          public 'm_nState' => int 0
                          public 'm_sTitle' => string 'AutoMultiSurvey' (length=15)
                          public 'm_cType' => string 'o' (length=1)
                          public 'm_nFrmid' => int 832
                          public 'm_nStuid' => int 34763
                          public 'm_nVerid' => int 166410
                          public 'm_nOpenState' => int 1
                          public 'm_nFormCount' => int 0
                          public 'm_nPswdCount' => int 2
                          public 'm_sLastDataCollectionDate' => string '' (length=0)
                          public 'm_nPageLinkOffset' => int 0
                          public 'm_sMaskTan' => string '' (length=0)
                          public 'm_nMaskState' => int 0
                          public 'm_oPeriod' =>
                            object(stdClass)[410]
                              ...
                      1 =>
                        object(stdClass)[411]
                          public 'm_nSurveyId' => int 2114887341
                          public 'm_nState' => int 0
                          public 'm_sTitle' => string 'AutoMultiSurvey' (length=15)
                          public 'm_cType' => string 'o' (length=1)
                          public 'm_nFrmid' => int 784
                          public 'm_nStuid' => int 34763
                          public 'm_nVerid' => int 166410
                          public 'm_nOpenState' => int 1
                          public 'm_nFormCount' => int 0
                          public 'm_nPswdCount' => int 2
                          public 'm_sLastDataCollectionDate' => string '' (length=0)
                          public 'm_nPageLinkOffset' => int 0
                          public 'm_sMaskTan' => string '' (length=0)
                          public 'm_nMaskState' => int 0
                          public 'm_oPeriod' =>
                            object(stdClass)[410]
    */
    public function get_evasys_course($evasyskennung) {
        // Get data into the structure that would have been returned by the evasys-api SOAP.
        if (!$evasyskennung) {
            throw new \SoapFault(101, "Testerror");
        }
        global $DB;
        $prefix = self::$prefix;
        $coursedata = $DB->get_records_sql("SELECT * FROM {$prefix}_evasys_mock_courses WHERE identifier = '$evasyskennung'")[$evasyskennung];
        $courseclass = new \stdClass();
        $courseclass->m_nCourseId = 1;
        $courseclass->m_sCourseTitle = $coursedata->title;
        $courseclass->m_Pub_CourseId = intval($coursedata->identifier);
        $courseclass->m_nCountStud = intval($coursedata->studcount);
        $courseclass->m_aoParticipants = new \stdClass();
        $courseclass->m_aoParticipants->Persons = array();
        for ($i = 0; $i < $coursedata->studcount; $i++) {
            array_push($courseclass->m_aoParticipants->Persons, "I'm a person");
        }
        $surveyholder = new \stdClass();
        $surveydata = $DB->get_records_sql("SELECT * FROM {$prefix}_evasys_mock_surveys WHERE related_course_identifier = '$evasyskennung'");
        if (count($surveydata) == 1) {
            // If it's just one survey it's an object.
            $surveys = new \stdClass();
            foreach ($surveydata as $singlesurveydata) {
                $surveys->m_nSurveyId = intval($singlesurveydata->id);
                $surveys->m_nState = 0;
                $surveys->m_sTitle = $singlesurveydata->title;
                $surveys->m_nFrmid = intval($singlesurveydata->formid);
                $surveys->m_nOpenState = $singlesurveydata->is_open == 't' ? 1 : 0;
                $surveys->m_nFormCount = intval($singlesurveydata->form_count);
                $surveys->m_nPswdCount = intval($singlesurveydata->pswd_count);
            }
        } else {
            // Otherwise it's an array. Best design ever.
            $surveys = array();
            foreach ($surveydata as $singlesurveydata) {
                $survey = new \stdClass();
                $survey->m_nSurveyId = $singlesurveydata->id;
                $survey->m_nState = 0;
                $survey->m_sTitle = $singlesurveydata->title;
                $survey->m_nFrmid = $singlesurveydata->formid;
                $survey->m_nOpenState = $singlesurveydata->is_open == 't' ? 1 : 0;
                $survey->m_nFormCount = $singlesurveydata->form_count;
                $survey->m_nPswdCount = $singlesurveydata->pswd_count;
                array_push($surveys, $survey);
            }
        }
        $surveyobject = new \stdClass();
        $surveyobject->Surveys = $surveys;
        $surveyholder->m_aSurveys = $surveyobject;
        $courseclass->m_oSurveyHolder = $surveyholder;
        return $courseclass;
    }

    public function clear_courses() {
        global $DB;
        $prefix = self::$prefix;
        print("DELETE FROM {$prefix}_evasys_mock_courses");
        $DB->execute("DELETE FROM {$prefix}_evasys_mock_courses");
    }

    public function add_survey ($evasysidentifier, $id, $title, $formid, $open, $formcount, $pswdcount) {
        global $DB;
        $prefix = self::$prefix;
        $opensql = $open ? 't' : 'f';
        $DB->execute("INSERT INTO {$prefix}_evasys_mock_surveys VALUES ($id, '$title', $formid, '$opensql',
                                                    $formcount, $pswdcount, '$evasysidentifier')");
    }

    public function clear_surveys() {
        global $DB;
        $prefix = self::$prefix;
        $DB->execute("DELETE FROM {$prefix}_evasys_mock_surveys");
    }


    public function send_invitation_to_participants_of_survey($surveyid) {
        return true;
    }

    public function insert_participants ($personlist, $evasyscourse) {
        return true;
    }

    public function create_passwords_for_all_users ($evasysid, $usercount) {
        return true;
    }

    public function insert_close_task ($task) {
        return true;
    }

    public function close_survey($surveyid) {
        return true;
    }


    public function get_form($formid ) {
        global $DB;
        $prefix = self::$prefix;
        $data = $DB->get_records_sql("SELECT * FROM {$prefix}_evasys_mock_forms WHERE frmid = $formid")[$formid];
        $formclass = new \stdClass();
        $formclass->FormId = $data->frmid;
        $formclass->FormName = $data->formname;
        $formclass->FormTitle = $data->formtitle;
        return $formclass;
    }


    public function set_form($frmid, $formname, $formtitle) {
        global $DB;
        $prefix = self::$prefix;
        $DB->execute("INSERT INTO {$prefix}_evasys_mock_forms VALUES ($frmid, '$formname', '$formtitle')");
    }

    public function clear_forms() {
        global $DB;
        $prefix = self::$prefix;
        $DB->execute("DELETE FROM {$prefix}_evasys_mock_forms");
    }
}