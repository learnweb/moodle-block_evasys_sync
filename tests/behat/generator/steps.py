from generator import coursename
import random
# Background
background = '  Background:\n\
    Given the following "users" exist:\n\
      | username | firstname | lastname | email |\n\
      | teacher1 | Teacher | 1 | teacher1@example.com |\n\
      | student1 | Student | 1 | student1@example.com |\n\
      | tutor1   | Tutor   | 1 | tutor1@example.com |\n\
    And the following "courses" exist:\n\
      | fullname | shortname | category |\n\
      | Course 1 | C1 | 0 |\n\
    And the following "course enrolments" exist:\n\
      | user | course | role |\n\
      | teacher1 | C1 | editingteacher |\n\
    And I log in as "teacher1"\n\
    And I am on "Course 1" course homepage'

feature_desc = "Tests all use-cases of the Evasys-block"

# Descriptions
students_descriptions = {
    "none": "If there are no students I should see a warning\n",
    "onlytutors": "If there are only tutors I should see a warning\n",
    "multi": ""
}

standardtimemode_descriptions = {
    0: "If there is no standardtimemode I should not see the option to use it\n",
    1: "If there is a standartimemode I should see the option to use it\n"
}

idnumber_descriptions = {
    "none": "",
    "invalid": "If there is an invalid idnumber I should see a warning\n",
    "one": "If there is an idnumber I should see this evasys-course\n"
}

mapped_descriptions = {
    "none": "",
    "invalid": "If there are invalid mappings I should see a warning\n",
    "one": "If there are any mapped courses I should see those\n",
    "multi": "If there are any mapped courses I should see those\n"
}

# Environment configuration

step_manual_auto_mode = {
    "manual": "Given category {{category}} is in manual mode",
    "auto": "Given category {{category}} is in auto mode"
}

step_standardtime_mode = {
    0: "And category {{category}} is not in standardtime mode",
    1: "And category {{category}} is in standardtime mode with dates 1581043586 1582043586"
}

step_internal_state = {
    "notopened": "And the internal state of course {{course}} is \"notopened\"",
    "opened": "And the internal state of course {{course}} is \"opened\"",
    "closed": "And the internal state of course {{course}} is \"closed\"",
    "manual": "And the internal state of course {{course}} is \"manual\"",
    "none": "And there is no internal record of course {{course}}"
}

step_record_standardtimemode = {
    "norecordstandardtimemode": "And the recordstandardtimemode for course {{course}} is not set",
    "recordstandardtimemode": "And the recordstandardtimemode for course {{course}} is set to true",
    "recordnonstandardtimemode": "And the recordstandardtimemode for course {{course}} is set to false",
}

step_students_state = {
    "none": "And no students enrolled in course " + coursename,
    "onlytutors": "And only tutors enrolled in course " + coursename,
    "multi": "And students enrolled in course " + coursename
}

inconsistent_mode = "Then I should see \"This evaluation was already started in another mode\""

# Checks

automode_checks = {
    "manual": "And I should see a button named \"Request evaluation\"",
    "auto": 'And I should see a button named "Set evaluation period"'
}

standardtimemode_checks = {
    0: "And I should not see \"Alter evaluationperiod for special courses\"",
    1: 'And I should see "Alter evaluationperiod for special courses"'
}

student_checks = {
    "none": "And I should see \"This course does not contain any students that can evaluate it.\"\n",
    "onlytutors": 'And I should see \"This course does not contain any students that can evaluate it.\"\n',
    "multi": ""
}

idnumber_checks = {
    "none": "",
    "invalid": "Then I should see \"The hard linked evasys-course is invalid!\"\n",
    "one": ""
}

mapped_checks = {
    "none": "",
    "invalid": "Then I should see \"One of the dynamically mapped evasys-courses is invalid\"\n",
    "one": "",
    "multi": ""
}

internal_state_checks = {
    "notopened": "And I should see \"Evaluationperiod has been set\"\n",
    "opened": "And the startselector should be disabled\n",
    "closed": "And both selectors should be disabled\n",
    "manual": "And I should see \"Evaluation has been requested\"\n",
    "none": ""
}

selector_disabled_checks = {
    "both": "And both selectors should be disabled\n",
    "start": "And the startselector should be disabled\n",
    "none": ""
}


# The following Methods help to construct Behat steps.
# There are only methods when using a dict (like above) is either unnecessarry complicated
# needs preparation (from a state like "multi" to an array of surveys)
# or something has to be added programatically (like coursenames or ids)


def step_idnumberstate(state, openstate):
    """
    :param state: idnumber state (none/invalid/one)
    :return: String either creating none, an invalid or one evasys-course - moodle-course link via idnumber
    """
    if state == "none":
        return "And there is no idnumber mapped to course " + coursename + "\n"
    elif state == "invalid":
        return "And the idnumber for course " + coursename + " is invalid\n"
    elif state == "one":
        x = "And the idnumber of course " + coursename + " links to a " + openstate + " Evasyscourse" + "\n"
        return x


def step_mappedstate(state, openstate):
    """
    :param state: mapped state (none/invalid/one/multi)
    :return: a string creating none, only invalid, one or multiple mappings of a moodle-course to evasys-courses
    """
    if state == "none":
        return "And no courses are mapped to course " + coursename + "\n"
    elif state == "invalid":
        return "And only invalid mappings are present for course " + coursename + "\n"
    elif state == "one":
        x = "And there are 1 " + openstate + " Evasyscourses mapped to course with shortname " + coursename + "\n"
        return x
    elif state == "multi":
        x = "And there are 2 " + openstate + " Evasyscourses mapped to course with shortname " + coursename + "\n"
        return x


def checks_standardtimemode(standardtime, auto_mode, internal_state, recordstandardtimemode):
    """
    The standardtimemode check is a little tricky since the checkbox showing is dependent on 3 states
    :param standardtime: standardtimemode
    :param recordstandardtimemode: the recordstandardtimemode may overwrite the standardtimemode for the category
    :param internal_state: internal state
    :return: A string checking for the presence or absence of the standardtime checkbox
    """
    if not recordstandardtimemode == "norecordstandardtimemode":
        standardtime = 1 if recordstandardtimemode == "recordstandardtimemode" else 0
    if standardtime == 1 and (internal_state == "none" or internal_state == "notopened"):
        return standardtimemode_checks[1]
    return standardtimemode_checks[0]


def checks_internalstate(internal_state, actual_state, student_state, no_valid_mappings, mode, standardtime):
    checks = ""
    if standardtime and mode == "manual" and internal_state == "none":
        return selector_disabled_checks["both"]
    if no_valid_mappings:
        actual_state = "does_never_get_called"
    if ((internal_state == "opened" or internal_state == "closed" or (student_state == "none" or student_state == "onlytutors")
            and internal_state != "none")
            and not (internal_state == "closed" and (actual_state == "mixed" or actual_state == "open"))):
        if internal_state == "opened":
            checks += selector_disabled_checks["start"]
            if not (student_state == "none" or student_state == "onlytutors") and not no_valid_mappings:
                checks += "And the submitbutton should be enabled\n"
        else:
            checks += selector_disabled_checks["both"]
    elif (internal_state == "manual" or internal_state == "none") and \
            (actual_state == "mixed" or actual_state == "closed") and mode == "manual":
        checks += selector_disabled_checks["both"]
        if not actual_state == "closed" and not actual_state == "mixed":
            checks += internal_state_checks[internal_state]
    elif not no_valid_mappings and (internal_state == "notopened" or internal_state == "manual" or internal_state == "none"):
        if (internal_state == "manual" or internal_state == "none") and (actual_state == "closed" or actual_state == "mixed"):
            return ""
        checks += internal_state_checks[internal_state]
        if not (student_state == "none" or student_state == "onlytutors") and not no_valid_mappings:
            checks += "And the submitbutton should be enabled\n"
    return checks


def postcheck_idnumber(idnumber_state):
    if idnumber_state == "one":
        return 'And I should see "IdnumberSurvey"\n'
    else:
        return 'And I should not see "IdnumberSurvey"\n'


def postcheck_mappedstate(mapped_state):
    postchecks = ""
    if mapped_state == "one":
        postchecks += 'And I should see "DynamicSurvey0"\n'
        postchecks += 'And I should not see "DynamicSurvey1"\n'
    elif mapped_state == "multi":
        postchecks += 'And I should see "DynamicSurvey0"\n'
        postchecks += 'And I should see "DynamicSurvey1"\n'
    else:
        postchecks += 'And I should not see "DynamicSurvey"\n'
    return postchecks


def description_modeconsistency(mode, internal_state, actual_state):
    description = ""
    # check if there is a mismatch in internal_state and mode (i.E. auto mode but manually opened internal state)
    if ("manual" not in internal_state and "none" not in internal_state) and mode == {"manual"} or (
            internal_state == {"manual"} and mode != {"manual"}):
        description += "If there are inconsistent modes I should see a warning\n"
    # check if there is a mismatch between the internal state and the actual state (i.E. internal opened actually closed)
    if (actual_state != {"open"} and internal_state == {"opened"}) or (
            internal_state == {"closed"} and actual_state != {"closed"}):
        description += "If there are surveys that don't match the internal state I should see a warning\n"
    return description
