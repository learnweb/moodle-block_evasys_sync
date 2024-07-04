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

$string['pluginname'] = 'EvaSys Sync Block';
$string['evasys_sync'] = 'EvaSys Sync';
$string['evasys_sync:addinstance'] = 'Add a new EvaSys Sync Block';
$string['invitestudents'] = 'Request evaluation';
$string['checkstatus'] = 'Show status of surveys';
$string['countparticipants'] = 'Number of participants: ';
$string['surveystatus'] = 'Survey status:';
$string['finishedforms'] = 'Completed forms:';
$string['evacourseid'] = 'EvaSys course ID:';
$string['evainternalid'] = 'Internal EvaSys ID:';
$string['evacoursename'] = 'Name:';
$string['surveys'] = 'Surveys: ';
$string['nocourse'] = 'Problem finding course, please try again later.';
$string['nosurveys'] = 'Currently there are no surveys available.';
$string['syncnotpossible'] = 'Unfortunately, course participants could not be synchronized to EvaSys due to a technical problem. Please get in touch with the support contact.';
$string['syncsucessful'] = 'Sync to EvaSys was successful';
$string['syncalreadyuptodate'] = 'Users were already up to date';
$string['syncnostudents'] = 'This course does not contain any students that can evaluate it.';
$string['taskname'] = 'Open and close Evasys surveys';
$string['begin'] = 'beginn';
$string['end'] = 'end';
$string['change_mapping'] = "Change mapping";
$string['semester'] = "Semester";
$string['direct_invite_checkbox'] = 'Start evaluation immediately';
$string['reactivate_invite'] = 'Invite students again';
$string['reactivate_startdate'] = 'Modify start date';
$string['warning_inconsistent_states'] = "There are some open surveys, but all surveys should be closed.";
$string['optional'] = '(optional)';
$string['standard_period'] = "Standard evaluationperiod:";
$string['different_period'] = "Exceptional evaluationperiod:";
$string['activate_nonstandard_time'] = "Alter evaluationperiod for special courses";
$string['time_set'] = "Standard evaluationperiod set";

// Multi allocation strings.

$string['selection_success'] = "Courses have been mapped successfully";
$string['add_course_header'] = "Choose LSF courses that should be synchronized together with this Moodle course";
$string['coursename'] = "Course name";
$string['associated'] = "associated";
$string['forbidden'] = "This action is currently prohibited.";
$string['nohisconnection_error'] = "To be able to use this feature, the local plugin lsf_unification needs to be installed.";
$string['maincoursepredefined'] = 'Fixed association.';
$string['maincoursepredefined_help'] = 'This LSF course is fixed, as it has been directly associated with this Moodle course. If you think that this is incorrect, please contact the support team.';

// Direct invite strings.

$string['planorstartevaluation'] = 'Set evaluation period';
$string['startevaluationnow'] = 'Start evaluation now';
$string['requestagain'] = 'Request or invite again';
$string['title_send_success'] = "Evaluation started";
$string['content_send_success'] = '{$a->sent} of {$a->total} emails have been send. <br />'.
                                  '{$a->queued} jobs have been queued';
$string['send_error'] = "There was an error while trying to send emails. Please contact Your local support, or send the Emails manually via EvaSys";

// Form strings.

$string['startplaceholder'] = "Pick a startdate";
$string['endplaceholder'] = "Pick an enddate";


// Information box strings.
$string['not_enough_dates'] = "Please provide dates for ALL Surveys!";
$string['content_nostudents'] = 'This course does not contain any students that can evaluate it.';
$string['title_send_failure'] = "Error while sending";

$string['content_failure'] = "Unfortunately we weren't able to request the start of the evaluation. <br />" .
    "For help please contact your local support team";

$string['title_send_invalid'] = "Invalid time period";
$string['content_send_invalid'] = "An evaluationperiod is set to start after it ends. <br />" .
    "All other evlautionperiods have been changed normally.";


$string['confirm_box'] = "OK";

$string['title_success']  = "Successfully requested the evaluation";
$string['title_uptodate'] = "The evaluation has already been requested";
$string['title_failure']  = "The evaluation could not be requested";

$string['content_success'] = "Your evaluation coordinator has been instructed to start the evaluation.<br />" .
    "!!!THE EVALUATION HAS NOT STARTED YET!!! <br />" .
    "However, you have done your part.".
    "The coordinator will process your request in accordance with the regulations of your organization.";

$string['content_successandinfo'] = "Your evaluation coordinatior has been instructed to start the evaluation again.<br />" .
    "There already exists an evaluation reqest for this course; you have synchronized the participants for this evaluation.<br />>" .
    "In order to change the dates of the evaluation, please contact your evaluation coordinator.";

$string['content_uptodate'] = "Your evaluation coordinator has already been instructed to start the evaluation. <br />" .
    "If you have questions regarding the status of the evaluation please contact your evaluation coordinator.";

$string['confirm_box'] = "OK";

$string['direct_already'] = "You have already sent invitations to all students.".
                            "No new invitations have been send";

$string['direct_title_info'] = "Invitation already complete";
$string['title_send_rejected'] = "Invalid Date";
$string['content_send_rejected'] = "One or more dates have been set to a date in the past. <br />" .
    "This is not allowed. Some evaluation periods may have been altered.<br />";
$string['content_confirm_reactivate_automated_closed'] = 'Are you sure you want to change the start date?';
$string['content_confirm_reactivate_automated_open'] = 'Are you sure you want to restart the evaluation?';
$string['content_confirm_reactivate_manual_closed'] = 'Are you sure you want to restart the evaluation? ' .
    'Submitting the form will cause the coordinator to be notified of the change, who is then able to restart the evaluation.';
$string['content_confirm_reactivate_manual_open'] = 'Are you sure you want to change the start date? ' .
    'Submitting the form will cause the coordinator to be notified of the change, but there is no guarantee ' .
    'that the dates will be modified in time.';

// Alert Coordinator mail.
$string['alert_email_subject'] = 'Evaluationszeitraum gesetzt für {$a}';
$string['alert_email_body'] = 'Sehr geehrte*r Evaluationskoordinator*in, ' . "\n" .
    'Sie erhalten diese E-Mail da im Learnweb-Kurs "{$a->name}" der Evaluationszeitraum wie folgt festgesetzt wurde:' . "\n\n" .
    "\t".'Start: {$a->start}' . "\n" .
    "\t".'Ende:  {$a->end}' . "\n" .
    "\t".'Verantwortliche*r: {$a->teacher}' . "\n" .
    "\t".'EvaSys-IDs:' . "\n" .
    '{$a->evasyscourses}' . "\n" .
    'Mit freundlichen Grüßen' . "\n" .
    'Ihr Learnweb-Support';

// New invite strings.
$string['title_success'] = "Success";
$string['content_success_invite'] = "Evaluation has been started";
$string['content_success_direct'] = "Evaluation period has been set";
$string['title_date_invalid'] = "Invalid Date";
$string['content_invalidstart'] = "Start is in the past";
$string['content_invalidend'] = "End is in the past";
$string['content_start_after_end'] = "Start is after end";
$string['title_wrong_mode'] = "Invalid operation";
$string['content_wrong_mode'] = "This course is not in the requested mode!";

// Sync date problems.
$string['syncendinthepast'] = 'Participants were not synchronised: The requested end date has already passed.';
$string['syncstartafterend'] = 'Participants were not synchronised: The start date must be before the end date.';

// Survey status.
$string['surveystatusopen'] = 'open';
$string['surveystatusclosed'] = 'closed';

// Capabilities.
$string['evasys_sync:mayevaluate'] = 'Evaluate a course using EvaSys';
$string['evasys_sync:synchronize'] = 'Export participants to EvaSys';
$string['evasys_sync:modifymapping'] = 'Modify mapping of EvaSys courses to moodle course';
$string['evasys_sync:managecourses'] = 'EvaSys manage course';
$string['evasys_sync:teacherforcourse'] = 'Get emails regarding your course.';

// Settings.
$string['settings'] = 'EvaSys Sync Block Settings';
$string['settings_username'] = 'EvaSys Username';
$string['settings_password'] = 'EvaSys Password';
$string['settings_soap_url'] = 'EvaSys SOAP URL';
$string['settings_wsdl_url'] = 'EvaSys WSDL URL';
$string['settings_moodleuser'] = 'Default user ID of mail recipient after sync';
$string['settings_mode'] = 'Default mode for categories';
$string['his_connection'] = 'Enable multiple evaluations (Caution, this requires an external plugin)';
$string['his_connection_help'] = 'Optional Feature which enables to assign multiple evasys evaluations to one course. This
requires the external local plugin lsf_unification (https://github.com/learnweb/his_unification) and a connection to the CMS Software HIS LSF
to identify further lectures of the corresponding teachers.';
$string['settings_moodleuser_select'] = 'Course categories';
$string['settings_cc_select'] = 'Select course category';
$string['settings_cc_user'] = 'Recipient (moodle user id) for selected course category';
$string['submit'] = 'Save changes';
$string['hd_user_cat'] = 'User-Category Allocation';
$string['addcat'] = 'Add Category';
$string['delete_confirm'] = 'Are you sure you want to delete the user for this course category?';
$string['auto_mode'] = 'Automatic mode';
$string['standard_time_mode'] = "Offer standard period of time";
$string['activate_standard_time'] = "Define default evaluation period";
$string['edit_time'] = "Edit standard timeframe";
$string['save_failure'] = "Error saving";
$string['usetimecheckbox'] = "Use standard timeframe";

// Settings - category table.
$string['category_name'] = 'Course Category';
$string['responsible_user'] = 'Moodle User ID';
$string['tablecaption'] = 'Custom mail recipients after sync';
$string['default'] = 'Default';
$string['delete_category_user'] = 'Delete Entry';
$string['delete'] = 'Delete';

// Persistance class.
$string['invalidcoursecat'] = 'Invalid course category';
$string['invalidmode'] = 'Invalid category mode';
$string['invalidcourse'] = "Invalid course";
$string['invalidsurvey'] = "Invalid survey";
$string['invaliddate'] = "Invalid date";
$string['invalidstate'] = "Invalid state code";
$string['invalid_standard_time_mode'] = "Invalid information whether standard periods of time may be used.";


// Privacy API.
$string['privacy:metadata'] = 'Invite students to participate in course quality evaluations performed using an EvaSys installation.';
$string['privacy:metadata:username'] = 'Usernames of students enrolled in a course (disguised as e-mail addresses to satisfy EvaSys requirements).';

// Events.
$string['eventevaluationperiod_set'] = 'Evaluation period has been set';
$string['eventevaluation_opened'] = 'Evaluation has been started';
$string['eventevaluation_closed'] = 'Evaluation has been closed';
$string['eventevaluation_requested'] = 'Evaluation has been requested';

// Months.
$string['January'] = 'January';
$string['February'] = 'February';
$string['March'] = 'March';
$string['April'] = 'April';
$string['May'] = 'May';
$string['June'] = 'June';
$string['July'] = 'July';
$string['August'] = 'August';
$string['September'] = 'September';
$string['October'] = 'October';
$string['November'] = 'November';
$string['December'] = 'December';


// From...to.
$string['evaluationperiod'] = 'Evaluation period';
$string['evaluationperiod_for'] = 'Evaluation period for "{$a}"';
$string['startondate'] = 'From';
$string['endondate'] = 'until';
$string['evaluations'] = 'Evaluations';
$string['title'] = 'Title';

// Notices.
$string['evalperiodsetnotice'] = 'Evaluationperiod has been set';
$string['emailsentnotice'] = 'Evaluation has been requested';

$string['apply'] = 'Apply';

$string['useoneevalperiod'] = 'Use same evaluation period for all evaluations';
$string['eval_additional_lsfcourses'] = 'I want multiple (lsf) course units to be evaluated.';
$string['eval_additional_courses'] = 'I want students from multiple (Learnweb) courses to evaluate this class.';
$string['course_units'] = 'Course units';
$string['use_default_evalperiod'] = 'Use the default evaluation period:<br>{$a}';
$string['err_endbeforestart'] = 'The end date is before the start date';
$string['dont_evaluate_course'] = 'This course should NOT be evaluated';

$string['standart_time_start'] = 'Start of default evaluation period';
$string['standart_time_end'] = 'End of default evaluation period';

$string['teacher_can_request_evaluation'] = 'Teachers can request evaluation';
$string['teacher_evaluation_request_needs_approval'] = 'Teacher\'s evaluation requests needs approval from you';
$string['automatic_task_creation'] = 'Planned tasks are created automatically in EvaSys';
$string['teacher_can_change_evaluation'] = 'Teachers can change existing evaluation';
$string['teacher_evaluation_change_needs_approval'] = 'Teacher\'s evaluation changes needs approval from you';

$string['search_for_courses'] = 'Search for courses';

$string['evasys_settings_for'] = 'Evasys settings for {$a}';
$string['no_default_period_set'] = 'No default evaluation period has been set.';
$string['default_period_set_from_to'] = 'The default evaluation period has been set from <b>{$a->start}</b> to <b>{$a->end}</b>.';
$string['teachers_can_request_evaluation'] = 'Teachers <b>can</b> request an evaluation.';
$string['teachers_cannot_request_evaluation'] = 'Teachers <b>cannot</b> request an evaluation';

$string['courses_with_errors'] = 'Courses with errors!';
$string['courses_with_requests'] = 'Courses with pending requests';
$string['courses_with_automatic_evals'] = 'Courses with automatic evaluations';
$string['courses_with_manual_evals'] = 'Courses with already requested evaluations';
$string['courses_without_evals'] = 'Courses without evaluations';
$string['courses_without_idnumber'] = 'Courses for which no evaluation is possible (missing Course ID)';
$string['courses_without_idnumber_help'] = 'These courses have no ID set. Any to-be-evaluated courses have to have an ID in order to be able to be identified in EvaSys. If you can\'t edit course IDs, please reach out to the support!';

$string['set_default_eval'] = 'Set default evaluation';
$string['set_default_eval_for_selected'] = 'Set default evaluation for selected';
$string['set_default_eval_for_all'] = 'Set default evaluation for all';
$string['set_default_period_for_default_eval'] = 'Set a default evaluation period to be able to plan default evaluations.';

$string['clear_error'] = 'Clear error';
$string['clear_selected_errors'] = 'Clear selected errors';
$string['clear_all_errors'] = 'Clear all errors';

$string['notify_teacher_email_subject'] = '{$a->courseshort}: A evaluation was planned for your course.';
$string['notify_teacher_email_body'] = 'Dear teacher,

this is an automatically generated mail, caused by you evasys coordinator planning an evaluation for your course <b>{$a->coursefull}</b>
from {$a->start}
to {$a->end}.

In case of questions or issues regarding this evaluation, please contact your evasys coordinator via {$a->coordinator}.

Sincerely,
Your Learnweb-Support';

$string['request_eval'] = 'Request evaluation';

$string['no_eval_planned'] = 'There is no evaluation planned.';
$string['teacher_request_disabled'] = 'Teachers are not allowed to request evaluations. An evaluation will be created for you.';

$string['missing_course_id'] = 'Missing course id! Please contact the Learnweb support with a link to the course in HIS LSF which should be evaluated.';
$string['no_evasys_course_found'] = 'No matching EvaSys-Course found! Please contact your evaluation coordinator.';
$string['not_inside_evaluation_category'] = 'This course isn\'t inside any evaluation category! Please contact the Learnweb support.';

$string["running_crontask"] = 'The synchronization of the selected courses of {$a} to evasys are currently being processed. This may take a few minutes. Please wait and reload the page.';
