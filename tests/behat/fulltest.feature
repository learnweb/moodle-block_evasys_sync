@block @block_evasys_sync @block_evasys_sync_fulltest
Feature: Tests all use-cases of the Evasys-block

  Background:
    Given the following "users" exist:
      | username | firstname | lastname | email |
      | teacher1 | Teacher | 1 | teacher1@example.com |
      | student1 | Student | 1 | student1@example.com |
      | tutor1   | Tutor   | 1 | tutor1@example.com |
    And the following "courses" exist:
      | fullname | shortname | category |
      | Course 1 | C1 | 0 |
    And the following "course enrolments" exist:
      | user | course | role |
      | teacher1 | C1 | editingteacher |
    And I log in as "teacher1"
    And I am on "Course 1" course homepage

  Scenario: If there are no related evasys-courses I should not see any.
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And there is no idnumber mapped to course C1
    And no courses are mapped to course C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    Then I should see "Change mapping"
    And I should not see "Name:"
    And I should not see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there are invalid mappings I should see a warning
    If there are inconsistent modes I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And there is no idnumber mapped to course C1
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "manual"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    Then I should see "This evaluation was already started in another mode"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there are invalid mappings I should see a warning
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And there is no idnumber mapped to course C1
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "opened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    Then I should see "This evaluation was already started in another mode"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there are invalid mappings I should see a warning
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And there is no idnumber mapped to course C1
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "manual"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And both selectors should be disabled
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there are invalid mappings I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And there is no idnumber mapped to course C1
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And both selectors should be disabled
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there are invalid mappings I should see a warning
    Given category 1 is in manual mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And only tutors enrolled in course C1
    And there is no idnumber mapped to course C1
    And only invalid mappings are present for course C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And both selectors should be disabled
    And I should see a button named "Request evaluation"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there are invalid mappings I should see a warning
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And there is no idnumber mapped to course C1
    And only invalid mappings are present for course C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And I should see a button named "Request evaluation"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there are any mapped courses I should see those
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And there is no idnumber mapped to course C1
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to true
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "This evaluation was already started in another mode"
    And I should see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one, multi or none
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to true
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "This evaluation was already started in another mode"
    And I should see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there are any mapped courses I should see those
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And only tutors enrolled in course C1
    And there is no idnumber mapped to course C1
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to true
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "This evaluation was already started in another mode"
    And I should see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one, multi or none
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to true
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "This evaluation was already started in another mode"
    And I should see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there are any mapped courses I should see those
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And only tutors enrolled in course C1
    And there is no idnumber mapped to course C1
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "This evaluation was already started in another mode"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one, multi or none
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "This evaluation was already started in another mode"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one, multi or none
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a mixed Evasyscourse
    And there are 1 mixed Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "This evaluation was already started in another mode"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one, multi or none
    If there are inconsistent modes I should see a warning
    If there are surveys that don't match the internal state I should see a warning
    Given category 1 is in manual mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a mixed Evasyscourse
    And there are 1 mixed Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "opened"
    And the recordstandardtimemode for course C1 is set to true
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "This evaluation was already started in another mode"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one, multi or none
    If there are inconsistent modes I should see a warning
    If there are surveys that don't match the internal state I should see a warning
    Given category 1 is in manual mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a mixed Evasyscourse
    And there are 1 mixed Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "closed"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "This evaluation was already started in another mode"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there are any mapped courses I should see those
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And there is no idnumber mapped to course C1
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "This evaluation was already started in another mode"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one, multi or none
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "This evaluation was already started in another mode"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one, multi or none
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a mixed Evasyscourse
    And there are 1 mixed Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "closed"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "This evaluation was already started in another mode"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there are any mapped courses I should see those
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And there is no idnumber mapped to course C1
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "opened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "This evaluation was already started in another mode"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there are any mapped courses I should see those
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And only tutors enrolled in course C1
    And there is no idnumber mapped to course C1
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "closed"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "This evaluation was already started in another mode"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there are any mapped courses I should see those
    If there are inconsistent modes I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And there is no idnumber mapped to course C1
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "manual"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "This evaluation was already started in another mode"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one, multi or none
    If there are inconsistent modes I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "manual"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "This evaluation was already started in another mode"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one, multi or none
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "opened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "This evaluation was already started in another mode"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And there is no idnumber mapped to course C1
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "closed"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And both selectors should be disabled
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one, multi or none
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a closed Evasyscourse
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "closed"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And both selectors should be disabled
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there are any mapped courses I should see those
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And there is no idnumber mapped to course C1
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "manual"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And both selectors should be disabled
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one, multi or none
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "manual"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And both selectors should be disabled
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there are any mapped courses I should see those
    Given category 1 is in manual mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And only tutors enrolled in course C1
    And there is no idnumber mapped to course C1
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And both selectors should be disabled
    And I should see a button named "Request evaluation"
    And I should see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one, multi or none
    Given category 1 is in manual mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And both selectors should be disabled
    And I should see a button named "Request evaluation"
    And I should see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there are any mapped courses I should see those
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And there is no idnumber mapped to course C1
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And I should see a button named "Request evaluation"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one, multi or none
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And I should see a button named "Request evaluation"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there are any mapped courses I should see those
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And there is no idnumber mapped to course C1
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And both selectors should be disabled
    And I should see a button named "Request evaluation"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one, multi or none
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a closed Evasyscourse
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And both selectors should be disabled
    And I should see a button named "Request evaluation"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an invalid idnumber I should see a warning
    If there are inconsistent modes I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber for course C1 is invalid
    And no courses are mapped to course C1
    And the internal state of course C1 is "manual"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    Then I should see "This evaluation was already started in another mode"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an invalid idnumber I should see a warning
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber for course C1 is invalid
    And no courses are mapped to course C1
    And the internal state of course C1 is "opened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    Then I should see "This evaluation was already started in another mode"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an invalid idnumber I should see a warning
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber for course C1 is invalid
    And no courses are mapped to course C1
    And the internal state of course C1 is "manual"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    And both selectors should be disabled
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an invalid idnumber I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber for course C1 is invalid
    And no courses are mapped to course C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    And both selectors should be disabled
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an invalid idnumber I should see a warning
    Given category 1 is in manual mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And only tutors enrolled in course C1
    And the idnumber for course C1 is invalid
    And no courses are mapped to course C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    And both selectors should be disabled
    And I should see a button named "Request evaluation"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an invalid idnumber I should see a warning
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber for course C1 is invalid
    And no courses are mapped to course C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    And I should see a button named "Request evaluation"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an invalid idnumber I should see a warning
    If there are invalid mappings I should see a warning
    If there are inconsistent modes I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber for course C1 is invalid
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "manual"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    Then I should see "This evaluation was already started in another mode"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an invalid idnumber I should see a warning
    If there are invalid mappings I should see a warning
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber for course C1 is invalid
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "opened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    Then I should see "This evaluation was already started in another mode"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an invalid idnumber I should see a warning
    If there are invalid mappings I should see a warning
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber for course C1 is invalid
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "manual"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And both selectors should be disabled
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an invalid idnumber I should see a warning
    If there are invalid mappings I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber for course C1 is invalid
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And both selectors should be disabled
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an invalid idnumber I should see a warning
    If there are invalid mappings I should see a warning
    Given category 1 is in manual mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And only tutors enrolled in course C1
    And the idnumber for course C1 is invalid
    And only invalid mappings are present for course C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And both selectors should be disabled
    And I should see a button named "Request evaluation"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an invalid idnumber I should see a warning
    If there are invalid mappings I should see a warning
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber for course C1 is invalid
    And only invalid mappings are present for course C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And I should see a button named "Request evaluation"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an invalid idnumber I should see a warning
    If there are any mapped courses I should see those
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber for course C1 is invalid
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to true
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    Then I should see "This evaluation was already started in another mode"
    And I should see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an invalid idnumber I should see a warning
    If there are any mapped courses I should see those
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And only tutors enrolled in course C1
    And the idnumber for course C1 is invalid
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to true
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    Then I should see "This evaluation was already started in another mode"
    And I should see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an invalid idnumber I should see a warning
    If there are any mapped courses I should see those
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And only tutors enrolled in course C1
    And the idnumber for course C1 is invalid
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    Then I should see "This evaluation was already started in another mode"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an invalid idnumber I should see a warning
    If there are any mapped courses I should see those
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber for course C1 is invalid
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    Then I should see "This evaluation was already started in another mode"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an invalid idnumber I should see a warning
    If there are any mapped courses I should see those
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber for course C1 is invalid
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "opened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    Then I should see "This evaluation was already started in another mode"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an invalid idnumber I should see a warning
    If there are any mapped courses I should see those
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And only tutors enrolled in course C1
    And the idnumber for course C1 is invalid
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "opened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    Then I should see "This evaluation was already started in another mode"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an invalid idnumber I should see a warning
    If there are any mapped courses I should see those
    If there are inconsistent modes I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber for course C1 is invalid
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "manual"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    Then I should see "This evaluation was already started in another mode"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an invalid idnumber I should see a warning
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber for course C1 is invalid
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "closed"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    And both selectors should be disabled
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an invalid idnumber I should see a warning
    If there are any mapped courses I should see those
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber for course C1 is invalid
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "manual"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    And both selectors should be disabled
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an invalid idnumber I should see a warning
    If there are any mapped courses I should see those
    Given category 1 is in manual mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And only tutors enrolled in course C1
    And the idnumber for course C1 is invalid
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    And both selectors should be disabled
    And I should see a button named "Request evaluation"
    And I should see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an invalid idnumber I should see a warning
    If there are any mapped courses I should see those
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber for course C1 is invalid
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    And I should see a button named "Request evaluation"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an invalid idnumber I should see a warning
    If there are any mapped courses I should see those
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber for course C1 is invalid
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    And both selectors should be disabled
    And I should see a button named "Request evaluation"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are invalid mappings I should see a warning
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to true
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    Then I should see "This evaluation was already started in another mode"
    And I should see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are invalid mappings I should see a warning
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to true
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    Then I should see "This evaluation was already started in another mode"
    And I should see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are invalid mappings I should see a warning
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    Then I should see "This evaluation was already started in another mode"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are invalid mappings I should see a warning
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    Then I should see "This evaluation was already started in another mode"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are invalid mappings I should see a warning
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "opened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    Then I should see "This evaluation was already started in another mode"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are invalid mappings I should see a warning
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "closed"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    Then I should see "This evaluation was already started in another mode"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are invalid mappings I should see a warning
    If there are inconsistent modes I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "manual"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    Then I should see "This evaluation was already started in another mode"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are invalid mappings I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a closed Evasyscourse
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "closed"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And both selectors should be disabled
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are invalid mappings I should see a warning
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "manual"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And both selectors should be disabled
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are invalid mappings I should see a warning
    Given category 1 is in manual mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And only invalid mappings are present for course C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And both selectors should be disabled
    And I should see a button named "Request evaluation"
    And I should see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are invalid mappings I should see a warning
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And only invalid mappings are present for course C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And I should see a button named "Request evaluation"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are invalid mappings I should see a warning
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a closed Evasyscourse
    And only invalid mappings are present for course C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And both selectors should be disabled
    And I should see a button named "Request evaluation"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are invalid mappings I should see a warning
    If there are inconsistent modes I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And there is no idnumber mapped to course C1
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "manual"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    Then I should see "This evaluation was already started in another mode"
    And I should not see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are invalid mappings I should see a warning
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And there is no idnumber mapped to course C1
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "closed"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    Then I should see "This evaluation was already started in another mode"
    And I should not see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are invalid mappings I should see a warning
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And there is no idnumber mapped to course C1
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "manual"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And I should not see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are invalid mappings I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And there is no idnumber mapped to course C1
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And I should not see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are invalid mappings I should see a warning
    Given category 1 is in manual mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And students enrolled in course C1
    And there is no idnumber mapped to course C1
    And only invalid mappings are present for course C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And both selectors should be disabled
    And I should see a button named "Request evaluation"
    And I should not see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are invalid mappings I should see a warning
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And there is no idnumber mapped to course C1
    And only invalid mappings are present for course C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And I should see a button named "Request evaluation"
    And I should not see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And there are 2 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to true
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "This evaluation was already started in another mode"
    And I should see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber of course C1 links to a mixed Evasyscourse
    And there are 2 mixed Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to true
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "This evaluation was already started in another mode"
    And I should see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are any mapped courses I should see those
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And there is no idnumber mapped to course C1
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to true
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "This evaluation was already started in another mode"
    And I should see "Alter evaluationperiod for special courses"
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one or none
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to true
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "This evaluation was already started in another mode"
    And I should see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And students enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And there are 2 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to true
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "This evaluation was already started in another mode"
    And I should see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are any mapped courses I should see those
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And students enrolled in course C1
    And there is no idnumber mapped to course C1
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to true
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "This evaluation was already started in another mode"
    And I should see "Alter evaluationperiod for special courses"
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one or none
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And students enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to true
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "This evaluation was already started in another mode"
    And I should see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are any mapped courses I should see those
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And students enrolled in course C1
    And there is no idnumber mapped to course C1
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "This evaluation was already started in another mode"
    And I should not see "Alter evaluationperiod for special courses"
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one, multi or none
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And students enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "This evaluation was already started in another mode"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one, multi or none
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And students enrolled in course C1
    And the idnumber of course C1 links to a mixed Evasyscourse
    And there are 1 mixed Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "This evaluation was already started in another mode"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one, multi or none
    If there are inconsistent modes I should see a warning
    If there are surveys that don't match the internal state I should see a warning
    Given category 1 is in manual mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And students enrolled in course C1
    And the idnumber of course C1 links to a mixed Evasyscourse
    And there are 1 mixed Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "opened"
    And the recordstandardtimemode for course C1 is set to true
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "This evaluation was already started in another mode"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one, multi or none
    If there are inconsistent modes I should see a warning
    If there are surveys that don't match the internal state I should see a warning
    Given category 1 is in manual mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And students enrolled in course C1
    And the idnumber of course C1 links to a mixed Evasyscourse
    And there are 1 mixed Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "closed"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "This evaluation was already started in another mode"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are any mapped courses I should see those
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And there is no idnumber mapped to course C1
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "This evaluation was already started in another mode"
    And I should not see "Alter evaluationperiod for special courses"
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one, multi or none
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "This evaluation was already started in another mode"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one, multi or none
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber of course C1 links to a mixed Evasyscourse
    And there are 1 mixed Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "opened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "This evaluation was already started in another mode"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are any mapped courses I should see those
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And there is no idnumber mapped to course C1
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "closed"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "This evaluation was already started in another mode"
    And I should not see "Alter evaluationperiod for special courses"
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are any mapped courses I should see those
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And students enrolled in course C1
    And there is no idnumber mapped to course C1
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "opened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "This evaluation was already started in another mode"
    And I should not see "Alter evaluationperiod for special courses"
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are any mapped courses I should see those
    If there are inconsistent modes I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And there is no idnumber mapped to course C1
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "manual"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "This evaluation was already started in another mode"
    And I should not see "Alter evaluationperiod for special courses"
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one, multi or none
    If there are inconsistent modes I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "manual"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "This evaluation was already started in another mode"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one, multi or none
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "opened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "This evaluation was already started in another mode"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are any mapped courses I should see those
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And there is no idnumber mapped to course C1
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "manual"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And I should see "Evaluation has been requested"
    And the submitbutton should be enabled
    And I should not see "Alter evaluationperiod for special courses"
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one, multi or none
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "manual"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And I should see "Evaluation has been requested"
    And the submitbutton should be enabled
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And there is no idnumber mapped to course C1
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "closed"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And both selectors should be disabled
    And I should not see "Alter evaluationperiod for special courses"
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one, multi or none
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber of course C1 links to a closed Evasyscourse
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "closed"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And both selectors should be disabled
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are any mapped courses I should see those
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And there is no idnumber mapped to course C1
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "manual"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And both selectors should be disabled
    And I should not see "Alter evaluationperiod for special courses"
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one, multi or none
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber of course C1 links to a closed Evasyscourse
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "manual"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And both selectors should be disabled
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    Given category 1 is in manual mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And students enrolled in course C1
    And the idnumber of course C1 links to a mixed Evasyscourse
    And there are 2 mixed Evasyscourses mapped to course with shortname C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And both selectors should be disabled
    And I should see a button named "Request evaluation"
    And I should see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    Given category 1 is in manual mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And students enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And there are 2 open Evasyscourses mapped to course with shortname C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And both selectors should be disabled
    And I should see a button named "Request evaluation"
    And I should see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are any mapped courses I should see those
    Given category 1 is in manual mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And students enrolled in course C1
    And there is no idnumber mapped to course C1
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And both selectors should be disabled
    And I should see a button named "Request evaluation"
    And I should see "Alter evaluationperiod for special courses"
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one or none
    Given category 1 is in manual mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And students enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And both selectors should be disabled
    And I should see a button named "Request evaluation"
    And I should see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are any mapped courses I should see those
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And there is no idnumber mapped to course C1
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And the submitbutton should be enabled
    And I should see a button named "Request evaluation"
    And I should not see "Alter evaluationperiod for special courses"
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one, multi or none
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And the submitbutton should be enabled
    And I should see a button named "Request evaluation"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are any mapped courses I should see those
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And there is no idnumber mapped to course C1
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And both selectors should be disabled
    And I should see a button named "Request evaluation"
    And I should not see "Alter evaluationperiod for special courses"
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one, multi or none
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber of course C1 links to a closed Evasyscourse
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And both selectors should be disabled
    And I should see a button named "Request evaluation"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an invalid idnumber I should see a warning
    If there are inconsistent modes I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber for course C1 is invalid
    And no courses are mapped to course C1
    And the internal state of course C1 is "manual"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    Then I should see "This evaluation was already started in another mode"
    And I should not see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an invalid idnumber I should see a warning
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber for course C1 is invalid
    And no courses are mapped to course C1
    And the internal state of course C1 is "closed"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    Then I should see "This evaluation was already started in another mode"
    And I should not see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an invalid idnumber I should see a warning
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber for course C1 is invalid
    And no courses are mapped to course C1
    And the internal state of course C1 is "manual"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    And I should not see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an invalid idnumber I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber for course C1 is invalid
    And no courses are mapped to course C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    And I should not see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an invalid idnumber I should see a warning
    Given category 1 is in manual mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And students enrolled in course C1
    And the idnumber for course C1 is invalid
    And no courses are mapped to course C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    And both selectors should be disabled
    And I should see a button named "Request evaluation"
    And I should not see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an invalid idnumber I should see a warning
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber for course C1 is invalid
    And no courses are mapped to course C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    And I should see a button named "Request evaluation"
    And I should not see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an invalid idnumber I should see a warning
    If there are invalid mappings I should see a warning
    If there are inconsistent modes I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber for course C1 is invalid
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "manual"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    Then I should see "This evaluation was already started in another mode"
    And I should not see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an invalid idnumber I should see a warning
    If there are invalid mappings I should see a warning
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber for course C1 is invalid
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "closed"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    Then I should see "This evaluation was already started in another mode"
    And I should not see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an invalid idnumber I should see a warning
    If there are invalid mappings I should see a warning
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber for course C1 is invalid
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "manual"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And I should not see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an invalid idnumber I should see a warning
    If there are invalid mappings I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber for course C1 is invalid
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And I should not see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an invalid idnumber I should see a warning
    If there are invalid mappings I should see a warning
    Given category 1 is in manual mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And students enrolled in course C1
    And the idnumber for course C1 is invalid
    And only invalid mappings are present for course C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And both selectors should be disabled
    And I should see a button named "Request evaluation"
    And I should not see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an invalid idnumber I should see a warning
    If there are invalid mappings I should see a warning
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber for course C1 is invalid
    And only invalid mappings are present for course C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And I should see a button named "Request evaluation"
    And I should not see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an invalid idnumber I should see a warning
    If there are any mapped courses I should see those
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber for course C1 is invalid
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to true
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    Then I should see "This evaluation was already started in another mode"
    And I should see "Alter evaluationperiod for special courses"
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an invalid idnumber I should see a warning
    If there are any mapped courses I should see those
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And students enrolled in course C1
    And the idnumber for course C1 is invalid
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to true
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    Then I should see "This evaluation was already started in another mode"
    And I should see "Alter evaluationperiod for special courses"
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an invalid idnumber I should see a warning
    If there are any mapped courses I should see those
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And students enrolled in course C1
    And the idnumber for course C1 is invalid
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    Then I should see "This evaluation was already started in another mode"
    And I should not see "Alter evaluationperiod for special courses"
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an invalid idnumber I should see a warning
    If there are any mapped courses I should see those
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber for course C1 is invalid
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    Then I should see "This evaluation was already started in another mode"
    And I should not see "Alter evaluationperiod for special courses"
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an invalid idnumber I should see a warning
    If there are any mapped courses I should see those
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber for course C1 is invalid
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "opened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    Then I should see "This evaluation was already started in another mode"
    And I should not see "Alter evaluationperiod for special courses"
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an invalid idnumber I should see a warning
    If there are any mapped courses I should see those
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And students enrolled in course C1
    And the idnumber for course C1 is invalid
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "closed"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    Then I should see "This evaluation was already started in another mode"
    And I should not see "Alter evaluationperiod for special courses"
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an invalid idnumber I should see a warning
    If there are any mapped courses I should see those
    If there are inconsistent modes I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber for course C1 is invalid
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "manual"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    Then I should see "This evaluation was already started in another mode"
    And I should not see "Alter evaluationperiod for special courses"
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an invalid idnumber I should see a warning
    If there are any mapped courses I should see those
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber for course C1 is invalid
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "manual"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    And I should see "Evaluation has been requested"
    And the submitbutton should be enabled
    And I should not see "Alter evaluationperiod for special courses"
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an invalid idnumber I should see a warning
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber for course C1 is invalid
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "closed"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    And both selectors should be disabled
    And I should not see "Alter evaluationperiod for special courses"
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an invalid idnumber I should see a warning
    If there are any mapped courses I should see those
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber for course C1 is invalid
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "manual"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    And both selectors should be disabled
    And I should not see "Alter evaluationperiod for special courses"
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an invalid idnumber I should see a warning
    If there are any mapped courses I should see those
    Given category 1 is in manual mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And students enrolled in course C1
    And the idnumber for course C1 is invalid
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    And both selectors should be disabled
    And I should see a button named "Request evaluation"
    And I should see "Alter evaluationperiod for special courses"
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an invalid idnumber I should see a warning
    If there are any mapped courses I should see those
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber for course C1 is invalid
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    And the submitbutton should be enabled
    And I should see a button named "Request evaluation"
    And I should not see "Alter evaluationperiod for special courses"
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an invalid idnumber I should see a warning
    If there are any mapped courses I should see those
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber for course C1 is invalid
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    And both selectors should be disabled
    And I should see a button named "Request evaluation"
    And I should not see "Alter evaluationperiod for special courses"
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are invalid mappings I should see a warning
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to true
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    Then I should see "This evaluation was already started in another mode"
    And I should see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are invalid mappings I should see a warning
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And students enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to true
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    Then I should see "This evaluation was already started in another mode"
    And I should see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are invalid mappings I should see a warning
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And students enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    Then I should see "This evaluation was already started in another mode"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are invalid mappings I should see a warning
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    Then I should see "This evaluation was already started in another mode"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are invalid mappings I should see a warning
    If there are inconsistent modes I should see a warning
    If there are surveys that don't match the internal state I should see a warning
    Given category 1 is in manual mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And students enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "opened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    Then I should see "This evaluation was already started in another mode"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are invalid mappings I should see a warning
    If there are inconsistent modes I should see a warning
    If there are surveys that don't match the internal state I should see a warning
    Given category 1 is in manual mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And students enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "closed"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    Then I should see "This evaluation was already started in another mode"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are invalid mappings I should see a warning
    If there are inconsistent modes I should see a warning
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "closed"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    Then I should see "This evaluation was already started in another mode"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are invalid mappings I should see a warning
    If there are inconsistent modes I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "manual"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    Then I should see "This evaluation was already started in another mode"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are invalid mappings I should see a warning
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "manual"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And I should see "Evaluation has been requested"
    And the submitbutton should be enabled
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are invalid mappings I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber of course C1 links to a closed Evasyscourse
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "closed"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And both selectors should be disabled
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are invalid mappings I should see a warning
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber of course C1 links to a closed Evasyscourse
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "manual"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And both selectors should be disabled
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are invalid mappings I should see a warning
    Given category 1 is in manual mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And students enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And only invalid mappings are present for course C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And both selectors should be disabled
    And I should see a button named "Request evaluation"
    And I should see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are invalid mappings I should see a warning
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And only invalid mappings are present for course C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And the submitbutton should be enabled
    And I should see a button named "Request evaluation"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are invalid mappings I should see a warning
    Given category 1 is in manual mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber of course C1 links to a closed Evasyscourse
    And only invalid mappings are present for course C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And both selectors should be disabled
    And I should see a button named "Request evaluation"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there are invalid mappings I should see a warning
    If there are surveys that don't match the internal state I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And there is no idnumber mapped to course C1
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "opened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And the startselector should be disabled
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there are invalid mappings I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And there is no idnumber mapped to course C1
    And only invalid mappings are present for course C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And there is no idnumber mapped to course C1
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to true
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And both selectors should be disabled
    And I should see a button named "Set evaluation period"
    And I should see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one, multi or none
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to true
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And both selectors should be disabled
    And I should see a button named "Set evaluation period"
    And I should see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And only tutors enrolled in course C1
    And there is no idnumber mapped to course C1
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to true
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And both selectors should be disabled
    And I should see a button named "Set evaluation period"
    And I should see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one, multi or none
    Given category 1 is in auto mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to true
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And both selectors should be disabled
    And I should see a button named "Set evaluation period"
    And I should see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And only tutors enrolled in course C1
    And there is no idnumber mapped to course C1
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And both selectors should be disabled
    And I should see a button named "Set evaluation period"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one, multi or none
    Given category 1 is in auto mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And both selectors should be disabled
    And I should see a button named "Set evaluation period"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And there is no idnumber mapped to course C1
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And both selectors should be disabled
    And I should see a button named "Set evaluation period"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one, multi or none
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And both selectors should be disabled
    And I should see a button named "Set evaluation period"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And there is no idnumber mapped to course C1
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to true
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And both selectors should be disabled
    And I should see a button named "Set evaluation period"
    And I should see "There are some closed surveys, but all surveys should be open."
    And I should see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one, multi or none
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a closed Evasyscourse
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to true
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And both selectors should be disabled
    And I should see a button named "Set evaluation period"
    And I should see "There are some closed surveys, but all surveys should be open."
    And I should see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And only tutors enrolled in course C1
    And there is no idnumber mapped to course C1
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to true
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And both selectors should be disabled
    And I should see a button named "Set evaluation period"
    And I should see "There are some closed surveys, but all surveys should be open."
    And I should see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one, multi or none
    Given category 1 is in auto mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a closed Evasyscourse
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to true
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And both selectors should be disabled
    And I should see a button named "Set evaluation period"
    And I should see "There are some closed surveys, but all surveys should be open."
    And I should see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And only tutors enrolled in course C1
    And there is no idnumber mapped to course C1
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And both selectors should be disabled
    And I should see a button named "Set evaluation period"
    And I should see "There are some closed surveys, but all surveys should be open."
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one, multi or none
    Given category 1 is in auto mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a closed Evasyscourse
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And both selectors should be disabled
    And I should see a button named "Set evaluation period"
    And I should see "There are some closed surveys, but all surveys should be open."
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And there is no idnumber mapped to course C1
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And both selectors should be disabled
    And I should see a button named "Set evaluation period"
    And I should see "There are some closed surveys, but all surveys should be open."
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one, multi or none
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a closed Evasyscourse
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And both selectors should be disabled
    And I should see a button named "Set evaluation period"
    And I should see "There are some closed surveys, but all surveys should be open."
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And there is no idnumber mapped to course C1
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "opened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And the startselector should be disabled
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one, multi or none
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "opened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And the startselector should be disabled
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there are any mapped courses I should see those
    If there are surveys that don't match the internal state I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And there is no idnumber mapped to course C1
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "opened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And the startselector should be disabled
    And I should see "There are some closed surveys, but all surveys should be open."
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one, multi or none
    If there are surveys that don't match the internal state I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a closed Evasyscourse
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "opened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And the startselector should be disabled
    And I should see "There are some closed surveys, but all surveys should be open."
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there are any mapped courses I should see those
    If there are surveys that don't match the internal state I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And there is no idnumber mapped to course C1
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "closed"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And I should see "There are some open surveys, but all surveys should be closed."
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one, multi or none
    If there are surveys that don't match the internal state I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "closed"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And I should see "There are some open surveys, but all surveys should be closed."
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And only tutors enrolled in course C1
    And there is no idnumber mapped to course C1
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And I should see a button named "Set evaluation period"
    And I should see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one, multi or none
    Given category 1 is in auto mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And I should see a button named "Set evaluation period"
    And I should see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And there is no idnumber mapped to course C1
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And I should see a button named "Set evaluation period"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one, multi or none
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And I should see a button named "Set evaluation period"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And only tutors enrolled in course C1
    And there is no idnumber mapped to course C1
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And I should see a button named "Set evaluation period"
    And I should see "There are some closed surveys, but all surveys should be open."
    And I should see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one, multi or none
    Given category 1 is in auto mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a closed Evasyscourse
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And I should see a button named "Set evaluation period"
    And I should see "There are some closed surveys, but all surveys should be open."
    And I should see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And there is no idnumber mapped to course C1
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And I should see a button named "Set evaluation period"
    And I should see "There are some closed surveys, but all surveys should be open."
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one, multi or none
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a closed Evasyscourse
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And I should see a button named "Set evaluation period"
    And I should see "There are some closed surveys, but all surveys should be open."
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an invalid idnumber I should see a warning
    If there are surveys that don't match the internal state I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber for course C1 is invalid
    And no courses are mapped to course C1
    And the internal state of course C1 is "opened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    And the startselector should be disabled
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an invalid idnumber I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber for course C1 is invalid
    And no courses are mapped to course C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an invalid idnumber I should see a warning
    If there are invalid mappings I should see a warning
    If there are surveys that don't match the internal state I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber for course C1 is invalid
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "opened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And the startselector should be disabled
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an invalid idnumber I should see a warning
    If there are invalid mappings I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber for course C1 is invalid
    And only invalid mappings are present for course C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an invalid idnumber I should see a warning
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber for course C1 is invalid
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to true
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    And both selectors should be disabled
    And I should see a button named "Set evaluation period"
    And I should see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an invalid idnumber I should see a warning
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And only tutors enrolled in course C1
    And the idnumber for course C1 is invalid
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to true
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    And both selectors should be disabled
    And I should see a button named "Set evaluation period"
    And I should see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an invalid idnumber I should see a warning
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And only tutors enrolled in course C1
    And the idnumber for course C1 is invalid
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    And both selectors should be disabled
    And I should see a button named "Set evaluation period"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an invalid idnumber I should see a warning
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber for course C1 is invalid
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    And both selectors should be disabled
    And I should see a button named "Set evaluation period"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an invalid idnumber I should see a warning
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber for course C1 is invalid
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to true
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    And both selectors should be disabled
    And I should see a button named "Set evaluation period"
    And I should see "There are some closed surveys, but all surveys should be open."
    And I should see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an invalid idnumber I should see a warning
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And only tutors enrolled in course C1
    And the idnumber for course C1 is invalid
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to true
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    And both selectors should be disabled
    And I should see a button named "Set evaluation period"
    And I should see "There are some closed surveys, but all surveys should be open."
    And I should see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an invalid idnumber I should see a warning
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And only tutors enrolled in course C1
    And the idnumber for course C1 is invalid
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    And both selectors should be disabled
    And I should see a button named "Set evaluation period"
    And I should see "There are some closed surveys, but all surveys should be open."
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an invalid idnumber I should see a warning
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber for course C1 is invalid
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    And both selectors should be disabled
    And I should see a button named "Set evaluation period"
    And I should see "There are some closed surveys, but all surveys should be open."
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an invalid idnumber I should see a warning
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber for course C1 is invalid
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "opened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    And the startselector should be disabled
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an invalid idnumber I should see a warning
    If there are any mapped courses I should see those
    If there are surveys that don't match the internal state I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber for course C1 is invalid
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "opened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    And the startselector should be disabled
    And I should see "There are some closed surveys, but all surveys should be open."
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an invalid idnumber I should see a warning
    If there are any mapped courses I should see those
    If there are surveys that don't match the internal state I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber for course C1 is invalid
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "closed"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    And I should see "There are some open surveys, but all surveys should be closed."
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an invalid idnumber I should see a warning
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And only tutors enrolled in course C1
    And the idnumber for course C1 is invalid
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    And I should see a button named "Set evaluation period"
    And I should see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an invalid idnumber I should see a warning
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber for course C1 is invalid
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    And I should see a button named "Set evaluation period"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an invalid idnumber I should see a warning
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And only tutors enrolled in course C1
    And the idnumber for course C1 is invalid
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    And I should see a button named "Set evaluation period"
    And I should see "There are some closed surveys, but all surveys should be open."
    And I should see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an invalid idnumber I should see a warning
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber for course C1 is invalid
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    And I should see a button named "Set evaluation period"
    And I should see "There are some closed surveys, but all surveys should be open."
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are invalid mappings I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to true
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And both selectors should be disabled
    And I should see a button named "Set evaluation period"
    And I should see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are invalid mappings I should see a warning
    Given category 1 is in auto mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to true
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And both selectors should be disabled
    And I should see a button named "Set evaluation period"
    And I should see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are invalid mappings I should see a warning
    Given category 1 is in auto mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And both selectors should be disabled
    And I should see a button named "Set evaluation period"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are invalid mappings I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And both selectors should be disabled
    And I should see a button named "Set evaluation period"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are invalid mappings I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a closed Evasyscourse
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to true
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And both selectors should be disabled
    And I should see a button named "Set evaluation period"
    And I should see "There are some closed surveys, but all surveys should be open."
    And I should see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are invalid mappings I should see a warning
    Given category 1 is in auto mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a closed Evasyscourse
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to true
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And both selectors should be disabled
    And I should see a button named "Set evaluation period"
    And I should see "There are some closed surveys, but all surveys should be open."
    And I should see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are invalid mappings I should see a warning
    Given category 1 is in auto mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a closed Evasyscourse
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And both selectors should be disabled
    And I should see a button named "Set evaluation period"
    And I should see "There are some closed surveys, but all surveys should be open."
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are invalid mappings I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a closed Evasyscourse
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And both selectors should be disabled
    And I should see a button named "Set evaluation period"
    And I should see "There are some closed surveys, but all surveys should be open."
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are invalid mappings I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "opened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And the startselector should be disabled
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are invalid mappings I should see a warning
    If there are surveys that don't match the internal state I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a closed Evasyscourse
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "opened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And the startselector should be disabled
    And I should see "There are some closed surveys, but all surveys should be open."
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are invalid mappings I should see a warning
    If there are surveys that don't match the internal state I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "closed"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And I should see "There are some open surveys, but all surveys should be closed."
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are invalid mappings I should see a warning
    Given category 1 is in auto mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And only invalid mappings are present for course C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And I should see a button named "Set evaluation period"
    And I should see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are invalid mappings I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And only invalid mappings are present for course C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And I should see a button named "Set evaluation period"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are invalid mappings I should see a warning
    Given category 1 is in auto mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a closed Evasyscourse
    And only invalid mappings are present for course C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And I should see a button named "Set evaluation period"
    And I should see "There are some closed surveys, but all surveys should be open."
    And I should see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are only tutors I should see a warning
    This should be valid regardless of number of students being set to onlytutors or none
    If there is an idnumber I should see this evasys-course
    If there are invalid mappings I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And only tutors enrolled in course C1
    And the idnumber of course C1 links to a closed Evasyscourse
    And only invalid mappings are present for course C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And I should see a button named "Set evaluation period"
    And I should see "There are some closed surveys, but all surveys should be open."
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "This course does not contain any students that can evaluate it."
    And I should see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are invalid mappings I should see a warning
    If there are surveys that don't match the internal state I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And there is no idnumber mapped to course C1
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "opened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And the startselector should be disabled
    And I should not see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are invalid mappings I should see a warning
    If there are surveys that don't match the internal state I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And there is no idnumber mapped to course C1
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "closed"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And both selectors should be disabled
    And I should not see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And there is no idnumber mapped to course C1
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to true
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And I should see "Evaluationperiod has been set"
    And the submitbutton should be enabled
    And I should see a button named "Set evaluation period"
    And I should see "Alter evaluationperiod for special courses"
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one or none
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to true
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And I should see "Evaluationperiod has been set"
    And the submitbutton should be enabled
    And I should see a button named "Set evaluation period"
    And I should see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And there are 2 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to true
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And I should see "Evaluationperiod has been set"
    And the submitbutton should be enabled
    And I should see a button named "Set evaluation period"
    And I should see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And students enrolled in course C1
    And there is no idnumber mapped to course C1
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to true
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And I should see "Evaluationperiod has been set"
    And the submitbutton should be enabled
    And I should see a button named "Set evaluation period"
    And I should see "Alter evaluationperiod for special courses"
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one or none
    Given category 1 is in auto mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And students enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to true
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And I should see "Evaluationperiod has been set"
    And the submitbutton should be enabled
    And I should see a button named "Set evaluation period"
    And I should see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And students enrolled in course C1
    And there is no idnumber mapped to course C1
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And I should see "Evaluationperiod has been set"
    And the submitbutton should be enabled
    And I should see a button named "Set evaluation period"
    And I should not see "Alter evaluationperiod for special courses"
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one, multi or none
    Given category 1 is in auto mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And students enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And I should see "Evaluationperiod has been set"
    And the submitbutton should be enabled
    And I should see a button named "Set evaluation period"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And there is no idnumber mapped to course C1
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And I should see "Evaluationperiod has been set"
    And the submitbutton should be enabled
    And I should see a button named "Set evaluation period"
    And I should not see "Alter evaluationperiod for special courses"
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one, multi or none
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And I should see "Evaluationperiod has been set"
    And the submitbutton should be enabled
    And I should see a button named "Set evaluation period"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And there is no idnumber mapped to course C1
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to true
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And I should see "Evaluationperiod has been set"
    And the submitbutton should be enabled
    And I should see a button named "Set evaluation period"
    And I should see "There are some closed surveys, but all surveys should be open."
    And I should see "Alter evaluationperiod for special courses"
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one, multi or none
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber of course C1 links to a closed Evasyscourse
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to true
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And I should see "Evaluationperiod has been set"
    And the submitbutton should be enabled
    And I should see a button named "Set evaluation period"
    And I should see "There are some closed surveys, but all surveys should be open."
    And I should see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And students enrolled in course C1
    And there is no idnumber mapped to course C1
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to true
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And I should see "Evaluationperiod has been set"
    And the submitbutton should be enabled
    And I should see a button named "Set evaluation period"
    And I should see "There are some closed surveys, but all surveys should be open."
    And I should see "Alter evaluationperiod for special courses"
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one, multi or none
    Given category 1 is in auto mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And students enrolled in course C1
    And the idnumber of course C1 links to a closed Evasyscourse
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to true
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And I should see "Evaluationperiod has been set"
    And the submitbutton should be enabled
    And I should see a button named "Set evaluation period"
    And I should see "There are some closed surveys, but all surveys should be open."
    And I should see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And students enrolled in course C1
    And there is no idnumber mapped to course C1
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And I should see "Evaluationperiod has been set"
    And the submitbutton should be enabled
    And I should see a button named "Set evaluation period"
    And I should see "There are some closed surveys, but all surveys should be open."
    And I should not see "Alter evaluationperiod for special courses"
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one, multi or none
    Given category 1 is in auto mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And students enrolled in course C1
    And the idnumber of course C1 links to a closed Evasyscourse
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And I should see "Evaluationperiod has been set"
    And the submitbutton should be enabled
    And I should see a button named "Set evaluation period"
    And I should see "There are some closed surveys, but all surveys should be open."
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And there is no idnumber mapped to course C1
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And I should see "Evaluationperiod has been set"
    And the submitbutton should be enabled
    And I should see a button named "Set evaluation period"
    And I should see "There are some closed surveys, but all surveys should be open."
    And I should not see "Alter evaluationperiod for special courses"
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one, multi or none
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber of course C1 links to a closed Evasyscourse
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And I should see "Evaluationperiod has been set"
    And the submitbutton should be enabled
    And I should see a button named "Set evaluation period"
    And I should see "There are some closed surveys, but all surveys should be open."
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And there is no idnumber mapped to course C1
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "opened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And the startselector should be disabled
    And the submitbutton should be enabled
    And I should not see "Alter evaluationperiod for special courses"
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one, multi or none
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "opened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And the startselector should be disabled
    And the submitbutton should be enabled
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are any mapped courses I should see those
    If there are surveys that don't match the internal state I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And there is no idnumber mapped to course C1
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "opened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And the startselector should be disabled
    And the submitbutton should be enabled
    And I should see "There are some closed surveys, but all surveys should be open."
    And I should not see "Alter evaluationperiod for special courses"
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one, multi or none
    If there are surveys that don't match the internal state I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber of course C1 links to a closed Evasyscourse
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "opened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And the startselector should be disabled
    And the submitbutton should be enabled
    And I should see "There are some closed surveys, but all surveys should be open."
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are any mapped courses I should see those
    If there are surveys that don't match the internal state I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And there is no idnumber mapped to course C1
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "closed"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And I should see "There are some open surveys, but all surveys should be closed."
    And I should not see "Alter evaluationperiod for special courses"
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one, multi or none
    If there are surveys that don't match the internal state I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "closed"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And I should see "There are some open surveys, but all surveys should be closed."
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And students enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And there are 2 open Evasyscourses mapped to course with shortname C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And the submitbutton should be enabled
    And I should see a button named "Set evaluation period"
    And I should see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And students enrolled in course C1
    And there is no idnumber mapped to course C1
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And the submitbutton should be enabled
    And I should see a button named "Set evaluation period"
    And I should see "Alter evaluationperiod for special courses"
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one or none
    Given category 1 is in auto mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And students enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And the submitbutton should be enabled
    And I should see a button named "Set evaluation period"
    And I should see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And there is no idnumber mapped to course C1
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And the submitbutton should be enabled
    And I should see a button named "Set evaluation period"
    And I should not see "Alter evaluationperiod for special courses"
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one, multi or none
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And the submitbutton should be enabled
    And I should see a button named "Set evaluation period"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And students enrolled in course C1
    And there is no idnumber mapped to course C1
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And I should see a button named "Set evaluation period"
    And I should see "There are some closed surveys, but all surveys should be open."
    And I should see "Alter evaluationperiod for special courses"
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one, multi or none
    Given category 1 is in auto mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And students enrolled in course C1
    And the idnumber of course C1 links to a closed Evasyscourse
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And I should see a button named "Set evaluation period"
    And I should see "There are some closed surveys, but all surveys should be open."
    And I should see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And there is no idnumber mapped to course C1
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And I should see a button named "Set evaluation period"
    And I should see "There are some closed surveys, but all surveys should be open."
    And I should not see "Alter evaluationperiod for special courses"
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are any mapped courses I should see those
    This should be valid regardless of mapped courses being set to one, multi or none
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber of course C1 links to a closed Evasyscourse
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    And I should see a button named "Set evaluation period"
    And I should see "There are some closed surveys, but all surveys should be open."
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an invalid idnumber I should see a warning
    If there are surveys that don't match the internal state I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber for course C1 is invalid
    And no courses are mapped to course C1
    And the internal state of course C1 is "opened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    And the startselector should be disabled
    And I should not see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an invalid idnumber I should see a warning
    If there are surveys that don't match the internal state I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber for course C1 is invalid
    And no courses are mapped to course C1
    And the internal state of course C1 is "closed"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    And both selectors should be disabled
    And I should not see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an invalid idnumber I should see a warning
    If there are invalid mappings I should see a warning
    If there are surveys that don't match the internal state I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber for course C1 is invalid
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "opened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And the startselector should be disabled
    And I should not see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an invalid idnumber I should see a warning
    If there are invalid mappings I should see a warning
    If there are surveys that don't match the internal state I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber for course C1 is invalid
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "closed"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And both selectors should be disabled
    And I should not see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an invalid idnumber I should see a warning
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber for course C1 is invalid
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to true
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    And I should see "Evaluationperiod has been set"
    And the submitbutton should be enabled
    And I should see a button named "Set evaluation period"
    And I should see "Alter evaluationperiod for special courses"
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an invalid idnumber I should see a warning
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And students enrolled in course C1
    And the idnumber for course C1 is invalid
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to true
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    And I should see "Evaluationperiod has been set"
    And the submitbutton should be enabled
    And I should see a button named "Set evaluation period"
    And I should see "Alter evaluationperiod for special courses"
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an invalid idnumber I should see a warning
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And students enrolled in course C1
    And the idnumber for course C1 is invalid
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    And I should see "Evaluationperiod has been set"
    And the submitbutton should be enabled
    And I should see a button named "Set evaluation period"
    And I should not see "Alter evaluationperiod for special courses"
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an invalid idnumber I should see a warning
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber for course C1 is invalid
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    And I should see "Evaluationperiod has been set"
    And the submitbutton should be enabled
    And I should see a button named "Set evaluation period"
    And I should not see "Alter evaluationperiod for special courses"
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an invalid idnumber I should see a warning
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber for course C1 is invalid
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to true
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    And I should see "Evaluationperiod has been set"
    And the submitbutton should be enabled
    And I should see a button named "Set evaluation period"
    And I should see "There are some closed surveys, but all surveys should be open."
    And I should see "Alter evaluationperiod for special courses"
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an invalid idnumber I should see a warning
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And students enrolled in course C1
    And the idnumber for course C1 is invalid
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to true
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    And I should see "Evaluationperiod has been set"
    And the submitbutton should be enabled
    And I should see a button named "Set evaluation period"
    And I should see "There are some closed surveys, but all surveys should be open."
    And I should see "Alter evaluationperiod for special courses"
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an invalid idnumber I should see a warning
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And students enrolled in course C1
    And the idnumber for course C1 is invalid
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    And I should see "Evaluationperiod has been set"
    And the submitbutton should be enabled
    And I should see a button named "Set evaluation period"
    And I should see "There are some closed surveys, but all surveys should be open."
    And I should not see "Alter evaluationperiod for special courses"
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an invalid idnumber I should see a warning
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber for course C1 is invalid
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    And I should see "Evaluationperiod has been set"
    And the submitbutton should be enabled
    And I should see a button named "Set evaluation period"
    And I should see "There are some closed surveys, but all surveys should be open."
    And I should not see "Alter evaluationperiod for special courses"
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an invalid idnumber I should see a warning
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber for course C1 is invalid
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "opened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    And the startselector should be disabled
    And the submitbutton should be enabled
    And I should not see "Alter evaluationperiod for special courses"
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an invalid idnumber I should see a warning
    If there are any mapped courses I should see those
    If there are surveys that don't match the internal state I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber for course C1 is invalid
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "opened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    And the startselector should be disabled
    And the submitbutton should be enabled
    And I should see "There are some closed surveys, but all surveys should be open."
    And I should not see "Alter evaluationperiod for special courses"
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an invalid idnumber I should see a warning
    If there are any mapped courses I should see those
    If there are surveys that don't match the internal state I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber for course C1 is invalid
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And the internal state of course C1 is "closed"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    And I should see "There are some open surveys, but all surveys should be closed."
    And I should not see "Alter evaluationperiod for special courses"
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an invalid idnumber I should see a warning
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And students enrolled in course C1
    And the idnumber for course C1 is invalid
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    And the submitbutton should be enabled
    And I should see a button named "Set evaluation period"
    And I should see "Alter evaluationperiod for special courses"
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an invalid idnumber I should see a warning
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber for course C1 is invalid
    And there are 1 open Evasyscourses mapped to course with shortname C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    And the submitbutton should be enabled
    And I should see a button named "Set evaluation period"
    And I should not see "Alter evaluationperiod for special courses"
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an invalid idnumber I should see a warning
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And students enrolled in course C1
    And the idnumber for course C1 is invalid
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    And I should see a button named "Set evaluation period"
    And I should see "There are some closed surveys, but all surveys should be open."
    And I should see "Alter evaluationperiod for special courses"
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an invalid idnumber I should see a warning
    If there are any mapped courses I should see those
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber for course C1 is invalid
    And there are 1 closed Evasyscourses mapped to course with shortname C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "The hard linked evasys-course is invalid!"
    And I should see a button named "Set evaluation period"
    And I should see "There are some closed surveys, but all surveys should be open."
    And I should not see "Alter evaluationperiod for special courses"
    And I should not see "IdnumberSurvey"
    And I should see "DynamicSurvey0"
    And I should not see "DynamicSurvey1"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are invalid mappings I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to true
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And I should see "Evaluationperiod has been set"
    And the submitbutton should be enabled
    And I should see a button named "Set evaluation period"
    And I should see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are invalid mappings I should see a warning
    Given category 1 is in auto mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And students enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And I should see "Evaluationperiod has been set"
    And the submitbutton should be enabled
    And I should see a button named "Set evaluation period"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are invalid mappings I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And I should see "Evaluationperiod has been set"
    And the submitbutton should be enabled
    And I should see a button named "Set evaluation period"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are invalid mappings I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber of course C1 links to a closed Evasyscourse
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to true
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And I should see "Evaluationperiod has been set"
    And the submitbutton should be enabled
    And I should see a button named "Set evaluation period"
    And I should see "There are some closed surveys, but all surveys should be open."
    And I should see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are invalid mappings I should see a warning
    Given category 1 is in auto mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And students enrolled in course C1
    And the idnumber of course C1 links to a closed Evasyscourse
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to true
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And I should see "Evaluationperiod has been set"
    And the submitbutton should be enabled
    And I should see a button named "Set evaluation period"
    And I should see "There are some closed surveys, but all surveys should be open."
    And I should see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are invalid mappings I should see a warning
    Given category 1 is in auto mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And students enrolled in course C1
    And the idnumber of course C1 links to a closed Evasyscourse
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And I should see "Evaluationperiod has been set"
    And the submitbutton should be enabled
    And I should see a button named "Set evaluation period"
    And I should see "There are some closed surveys, but all surveys should be open."
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are invalid mappings I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber of course C1 links to a closed Evasyscourse
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "notopened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And I should see "Evaluationperiod has been set"
    And the submitbutton should be enabled
    And I should see a button named "Set evaluation period"
    And I should see "There are some closed surveys, but all surveys should be open."
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are invalid mappings I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "opened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And the startselector should be disabled
    And the submitbutton should be enabled
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are invalid mappings I should see a warning
    If there are surveys that don't match the internal state I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber of course C1 links to a closed Evasyscourse
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "opened"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And the startselector should be disabled
    And the submitbutton should be enabled
    And I should see "There are some closed surveys, but all surveys should be open."
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are invalid mappings I should see a warning
    If there are surveys that don't match the internal state I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And only invalid mappings are present for course C1
    And the internal state of course C1 is "closed"
    And the recordstandardtimemode for course C1 is set to false
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And I should see "There are some open surveys, but all surveys should be closed."
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are invalid mappings I should see a warning
    Given category 1 is in auto mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And students enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And only invalid mappings are present for course C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And the submitbutton should be enabled
    And I should see a button named "Set evaluation period"
    And I should see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are invalid mappings I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber of course C1 links to a open Evasyscourse
    And only invalid mappings are present for course C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And the submitbutton should be enabled
    And I should see a button named "Set evaluation period"
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are invalid mappings I should see a warning
    Given category 1 is in auto mode
    And category 1 is in standardtime mode with dates 1581043586 1582043586
    And students enrolled in course C1
    And the idnumber of course C1 links to a closed Evasyscourse
    And only invalid mappings are present for course C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And I should see a button named "Set evaluation period"
    And I should see "There are some closed surveys, but all surveys should be open."
    And I should see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should not see "DynamicSurvey"

  Scenario: If there is no standardtimemode I should not see the option to use it
    If there is an idnumber I should see this evasys-course
    If there are invalid mappings I should see a warning
    Given category 1 is in auto mode
    And category 1 is not in standardtime mode
    And students enrolled in course C1
    And the idnumber of course C1 links to a closed Evasyscourse
    And only invalid mappings are present for course C1
    And there is no internal record of course C1
    And the recordstandardtimemode for course C1 is not set
    And I turn editing mode on
    And I add the "EvaSys Sync" block
    And I turn editing mode off
    And I load the evasys block
    Then I should see "One of the dynamically mapped evasys-courses is invalid"
    And I should see a button named "Set evaluation period"
    And I should see "There are some closed surveys, but all surveys should be open."
    And I should not see "Alter evaluationperiod for special courses"
    And I should see "IdnumberSurvey"
    And I should not see "DynamicSurvey"
