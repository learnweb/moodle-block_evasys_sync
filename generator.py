import copy
from steps import *

PATH = "../fulltest.feature"
CONDENSE_TESTS = True
# parameters
category = 1
coursename = "C1"
tags = "@block @block_evasys_sync @block_evasys_sync_fulltest"
# options
OPTIONS = {
    "automode": ["manual", "auto"],
    "standardtimemode": [0, 1],
    "students": ["none", "onlytutors", "multi"],
    "idnumber": ["none", "invalid", "one"],
    "mapped": ["none", "invalid", "one", "multi"],
    "internalstate": ["notopened", "opened", "closed", "manual", "none"],
    "actualstate": ["open", "closed", "mixed"],
    "recordstandardtime": ["recordstandardtimemode", "recordnonstandardtimemode", "norecordstandardtimemode"]
}

ILLEGALSTATES = [
    {"recordstandardtime": "recordstandardtimemode", "internalstate": "none"},
    {"recordstandardtime": "recordnonstandardtimemode", "internalstate": "none"},
    {"recordstandardtime": "norecordstandardtimemode", "internalstate": "notopened"},
    {"recordstandardtime": "norecordstandardtimemode", "internalstate": "opened"},
    {"recordstandardtime": "norecordstandardtimemode", "internalstate": "closed"},
    {"recordstandardtime": "norecordstandardtimemode", "internalstate": "manual"},
]


def illegal_state(scenario):
    for fullillegalstate in ILLEGALSTATES:
        illegal = True
        for illegalstate in fullillegalstate.keys():
            if scenario[list(OPTIONS.keys()).index(illegalstate)] != fullillegalstate[illegalstate]:
                illegal = False
                break
        if illegal:
            return True
    if scenario[5] == "none" and scenario[7] != "norecordstandardtimemode":
        print(scenario)
    return False


def get_checks(mode, standardtime, students_state, idnumber_state, mapped_state, internal_state, actual_state, recordstandardtimemode):
    """
    This Function will take the scenario parameters and construct the resulting checks.
    :return: The combined checks for the parameters.
    """
    if not recordstandardtimemode == "norecordstandardtimemode":
        standardtime = 1 if recordstandardtimemode == "recordstandardtimemode" else 0
    # If there are no mapped courses the Block not offer the option to "Show surveys"
    if (idnumber_state == "none") and (mapped_state == "none"):
        return "Then I should see \"Change mapping\"\n    And I should not see \"Name:\"\n    "

    checks = ""
    # In all other cases (even if there are only invalid entries) we need to click the button to start code execution
    checks += "And I load the evasys block\n"

    # First we want to check for idnumber and mapped checks. If there are invalid entries in either of these
    # there should be a warning. However, it should not disable the ability to request a evaluation or set a timeframe
    # in manual mode this was explicitly requested, and there might be an analog usecase in automatic mode.
    checks += idnumber_checks[idnumber_state]
    checks += mapped_checks[mapped_state]

    no_valid_mappings = ((idnumber_state == "none" or idnumber_state == "invalid")
                         and (mapped_state == "none" or mapped_state == "invalid"))
    # if there is an internal state that is reserved for manual or automode we want to check we want to output
    # a warning if this is not in fact the correct mode
    if ((internal_state != "manual" and internal_state != "none") and mode == "manual") or (
            internal_state == "manual" and mode == "auto"):
        checks += inconsistent_mode + "\n"
    else:
        # if the internal state isn't conflicting we want to check, if the block is correctly displaying for that state
        checks += checks_internalstate(internal_state, actual_state, students_state, no_valid_mappings, mode, standardtime)
        # finally if there is no entry yet or the planned date is in the future we want to check that
        # there is an option to set the date for the evaluation
        if (internal_state == "none" and (not no_valid_mappings or mode == "manual")) \
                or (internal_state == "notopened" and mode == "auto" and not no_valid_mappings):
            checks += automode_checks[mode] + "\n"
    # If our own states don't match those of evasys we want to output a warning
    if mode != "manual" and not no_valid_mappings:
        # In manual mode we don't check for matching states because we have no way of knowing what the state should be.
        if internal_state == "closed" and actual_state != "closed":
            checks += "And I should see \"There are some open surveys, but all surveys should be closed.\"" + "\n"
        elif internal_state != "closed" and internal_state != "manual" and actual_state != "open":
            checks += "And I should see \"There are some closed surveys, but all surveys should be open.\"" + "\n"

    # if the standardtimemodecheckbox should be present from the start we also want to check that
    if not no_valid_mappings:
        checks += checks_standardtimemode(standardtime, mode, internal_state,  recordstandardtimemode) + "\n"
    # if there are no students that are eligible to evaluate we want to output a warning
    checks += student_checks[students_state]
    checks = checks.replace("\n", "\n    ")
    return checks
=======
	checks = ""
	if (idnumber_state == "none" or idnumber_state == "invalid") and (mapped_state == "none" or mapped_state == "invalid"):
		if idnumber_state == "invalid" or mapped_state == "invalid":
			return "And I press \"Show status of surveys\"\n" + "Then I should see \"Change mapping\"\nAnd I should not see \"Name:\"\n"
		return "Then I should see \"Change mapping\"\nAnd I should not see \"Name:\"\n"


	checks += "And I press \"Show status of surveys\"\n"
	checks += idnumber_checks[idnumber_state]
	checks += mapped_checks[mapped_state]
	if ((internal_state != "manual" and internal_state != "none") and mode == "manual") or (internal_state == "manual" and mode == "auto"):
		checks += inconsistent_mode + "\n"
	else:
		checks += internal_state_checks[internal_state]
		if internal_state == "none" or (internal_state == "notopened" and mode == "auto"):
			checks += automode_checks[mode] + "\n"

	if internal_state == "closed" and actual_state != "closed":
		checks += "And I should see \"There are some open surveys, but all surveys should be closed.\"" + "\n"
	elif internal_state != "closed" and actual_state != "open":
		checks += "And I should see \"Some of the surveys have been closed ahead of schedule!\"" + "\n"
	checks += standardtimemode_checks[standardtime] + "\n"
	checks += student_checks[students_state]
	return checks
>>>>>>> cc0cc54... corrected mistake


def get_postcondensing_checks(idnumber_state, mapped_state):
    """
    :return: checks for a scenario that should NOT be considered in the condense step
    """
    postchecks = ""
    # Check that surveys are actually shown.
    # We don't want to include this in the sorting, because it only tests a for loop, but multiplies the tests by 2.
    postchecks += postcheck_idnumber(idnumber_state)
    postchecks += postcheck_mappedstate(mapped_state)

    postchecks = postchecks.replace("\n", "\n    ")
    return postchecks


def get_combi_sentence(set, dictionary, param_name):
    """
    This function takes a set of states for a scenario parameter and checks whether they all result in the same
    description based on the provided dictionary. If they do it'll return this shared description.
    Otherwise it'll return a description to reflect that the result should be the same for both options.
    :param set: Set of states
    :param dictionary: dictionary that holds the descriptions for the parametertype
    :param param_name: name of the parameter
    :return: String description for the set of parameters
    """
    set2 = set.copy()
    last_response = dictionary[set.pop()]
    while 1:
        if len(set) == 0:
            return last_response
        if not last_response == dictionary[set.pop()]:
            break
    sentence = last_response
    sentence += "This should be valid regardless of " + param_name + " being set to "
    while len(set2) > 1:
        sentence += str(set2.pop()) + ", "
    sentence = sentence[:len(sentence) - 2]
    sentence += " or " + str(set2.pop()) + "\n"
    return sentence


def get_combi_description(mode, standardtime, students_state, idnumber_state, mapped_state, internal_state,
                          actual_state, recordstandardtimemode):
    """
    This will construct the description for a scenario that covers multiple states.
    :return: String the description of the scenario.
    """
    if "none" in mapped_state and "none" in idnumber_state:
        return "If there are no related evasys-courses I should not see any.\n    "
    if not recordstandardtimemode == "norecordstandardtimemode":
        standardtime = {1} if recordstandardtimemode == "recordstandardtimemode" else {0}
    description = ""
    description += get_combi_sentence(standardtime, standardtimemode_descriptions, "standardtime")
    description += get_combi_sentence(students_state, students_descriptions, "number of students")
    description += get_combi_sentence(idnumber_state, idnumber_descriptions, "idnumber")
    description += get_combi_sentence(mapped_state, mapped_descriptions, "mapped courses")
    description += description_modeconsistency(mode, internal_state, actual_state)
    description = description.replace("\n", "\n    ")
    return description


def get_scenario(mode, standardtime, students_state, idnumber_state, mapped_state, internal_state, actual_state, recordstandardtimemode):
    """
    This constructs the actual scenario by building all courses, states etc.
    :return: string the combined enviroment specifiing string for a scenario
    """
    behat_scenario = step_manual_auto_mode[mode].replace("{{category}}", str(category)) + "\n"
    behat_scenario += step_standardtime_mode[standardtime].replace("{{category}}", str(category)) + "\n"
    behat_scenario += step_students_state[students_state] + "\n"
    behat_scenario += step_idnumberstate(idnumber_state, actual_state)
    behat_scenario += step_mappedstate(mapped_state, actual_state)
    behat_scenario += step_internal_state[internal_state].replace("{{course}}", str(coursename)) + "\n"
    behat_scenario += step_record_standardtimemode[recordstandardtimemode].replace("{{course}}", str(coursename)) + "\n"
    behat_scenario += "And I turn editing mode on\n"
    behat_scenario += "And I add the \"EvaSys Sync\" block\n"
    behat_scenario += "And I turn editing mode off\n"
    behat_scenario = behat_scenario.replace("\n", "\n    ")

    if actual_state == "mixed" and not ((idnumber_state == "one" and (mapped_state == "multi" or mapped_state == "one"))
                                        or mapped_state == "multi"):
        # a mixed state is impossible with less than 2 lsfscourseids, so we skip this.
        return False
    return behat_scenario


def make_scenario(description, scenario, checks):
    """
    :return: Scenarion specified by its description, enviroment and checks
    """
    return "  Scenario: " + description + scenario + checks + "\n"


def count_full_scenarios(condesed_array):
    """
    Given an array of condenses scenarios (aka. scenarios with sets instead of single values) this function will
    compute how many unique scenarios those condensed scenarios hold.
    :param condesed_array: array of scenarios with sets of parameters
    :return: number of unique scenarios covered by all condensed scenarios.
    """
    i = 0
    for array in condesed_array:
        j = 1
        for param in array:
            j *= len(param)
        i += j
    return i


def array_diff(array1, array2):
    """
    :param array1: first array
    :param array2: second array
    :return: returns the number of missmatches between array1 and 2 aswell as the index of the last missmatch
    """
    diff = 0
    position = 0
    for i in range(0, len(array1)):
        if not array1[i] == array2[i]:
            position = i
            diff += 1
    return diff, position


def condense_keep_options(fullarray):
    """
    Given an array of scenarios this function will combine these into new scenarios that cover the exact same range
    of scenarios, but will combine them.
    { off, off, on } and { on, off, on } will be combined to { on/off, off, on}
    The array of scenarios passed should all have the same result ( checks )
    :param fullarray: array of scenarios
    :return: condensed array of scenarios
    """
    changed = True
    for i in range(0, len(fullarray)):
        for j in range(0, len(fullarray[i])):
            fullarray[i][j] = set([fullarray[i][j]])  # Do not replace with literal!
    while changed:
        new_array = copy.deepcopy(fullarray)
        changed = False
        for array in fullarray:
            for array2 in fullarray:
                diffcount, index = array_diff(array, array2)
                if diffcount == 1:
                    changed = True
                    if array2 in new_array:
                        new_array.remove(array2)
                    if array in new_array:
                        new_array.remove(array)
                    tmp = copy.deepcopy(array)
                    tmp[index] = array[index].union(array2[index])
                    new_array.append(tmp)
                    break
            if changed:
                break
        fullarray = new_array

    return fullarray


def build_cartesian_product(array_dict):
    cartesian_product = []
    for key in array_dict:
        if len(cartesian_product) == 0:
            cartesian_product = [[option] for option in array_dict[key]]
            continue
        else:
            cartesian_product = [old_options + [new_options]
                                 for old_options in cartesian_product
                                 for new_options in array_dict[key]]
    return cartesian_product


def main():
    # open feature file
    behattestfile = open(PATH, "w")
    output = ""
    i = 0
    # initialize feature data
    output += tags + "\n"
    output += "Feature: " + feature_desc + "\n\n"
    output += background + "\n\n"

    # Build cartesian product of all options
    cartesianoptions = build_cartesian_product(OPTIONS)

    # get the checks for all options to be able to condense options with the same expected outcome
    uncondensed_dict = {}
    for scenario in cartesianoptions:
        check = get_checks(scenario[0], scenario[1], scenario[2], scenario[3], scenario[4], scenario[5], scenario[6], scenario[7])
        if not check in uncondensed_dict.keys():
            uncondensed_dict[check] = []
        uncondensed_dict[check].append(scenario)

    # condense those scenarios
    condensed_dict = {}
    if CONDENSE_TESTS:
        i = 0
        for check in uncondensed_dict.keys():
            scenarios = uncondensed_dict[check]
            condensed_dict[check] = condense_keep_options(scenarios)
            i += 1
            print("Condensed " + str(i) + " of " + str(len(uncondensed_dict.keys())))
    else:
        for check in uncondensed_dict.keys():
            fullarray = uncondensed_dict[check]
            for i in range(0, len(fullarray)):
                for j in range(0, len(fullarray[i])):
                    fullarray[i][j] = set([fullarray[i][j]])
            condensed_dict[check] = fullarray

    testcases = 0
    endcases = 0
    # check that the number of cases stayed the same
    for check in condensed_dict.keys():
        testcases += len(condensed_dict[check])  # get original number of cases
        endcases += count_full_scenarios(condensed_dict[check])  # get number of cases that can be built from condesed scenarios

    # finally build the actual behat-suite
    for check in condensed_dict.keys():
        for scenario in condensed_dict[check]:
            # do a deep-copy of the scenario because sets can only be accessed by pop which alters the set itself
            scenario_copy = copy.deepcopy(scenario)
            desc = get_combi_description(scenario[0], scenario[1], scenario[2], scenario[3], scenario[4], scenario[5],
                                         scenario[6], scenario[7])
            mode = scenario_copy[0].pop()
            standardtime = scenario_copy[1].pop()
            students_state = scenario_copy[2].pop()
            idnumber_state = scenario_copy[3].pop()
            mapped_state = scenario_copy[4].pop()
            internal_state = scenario_copy[5].pop()
            actual_state = scenario_copy[6].pop()
            recordstandardtime = scenario_copy[7].pop()
            if actual_state == "mixed" and len(scenario_copy[6]) > 0:
                actual_state = scenario_copy[6].pop()

            corrected = True
            illegal = False
            illegals = []
            while corrected:
                corrected = False
                if illegal_state([mode, standardtime, students_state, idnumber_state, mapped_state, internal_state,
                                     actual_state, recordstandardtime]):
                    corrected = True
                    illegals.append([mode, standardtime, students_state, idnumber_state, mapped_state, internal_state,
                                     actual_state, recordstandardtime])
                    if len(scenario_copy[5]) >= 1:
                        internal_state = scenario_copy[5].pop()
                    elif len(scenario_copy[7]) >= 1:
                        recordstandardtime = scenario_copy[7].pop()
                    else:
                        if len(illegals) > 1:
                            print("Illegal multiscenario")
                            print(illegals)
                        illegal = True
                        corrected = False
            if illegal:
                continue
            scen_text = get_scenario(mode, standardtime, students_state, idnumber_state, mapped_state, internal_state,
                                     actual_state, recordstandardtime)
            if not scen_text:
                print("Warning impossible scenario!!!")
                continue
            postcheck = get_postcondensing_checks(idnumber_state, mapped_state)
            output += make_scenario(desc, scen_text, check + postcheck)

    output = output.replace("\n    \n", "\n\n")  # remove whitespaces on empty lines
    output = output[:len(output) - 1]  # remove last newline
    # write actual data
    behattestfile.write(output)
    behattestfile.close()
    # output statistics
    print("Startcases: %i, Endcases: %i" % (len(cartesianoptions), endcases))
    print("Condensed %i scenarios to %i testcases with %i scenarios" % (len(cartesianoptions), len(condensed_dict), testcases))


if __name__ == "__main__":
    main()
