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

$string['pluginname'] = 'EvaSys-Export-Block';
$string['evasys_sync'] = 'EvaSys-Export';
$string['evasys_sync:addinstance'] = 'EvaSys-Export-Block hinzufügen';
$string['invitestudents'] = 'Evaluation beauftragen';
$string['checkstatus'] = 'Status der Evaluationen anzeigen';
$string['countparticipants'] = 'Anzahl Teilnehmer*innen: ';
$string['surveystatus'] = 'Evaluationsstatus:';
$string['finishedforms'] = 'Ausgefüllt:';
$string['evacourseid'] = 'EvaSys-Kurs-ID:';
$string['evainternalid'] = 'EvaSys-interne ID:';
$string['evacoursename'] = 'Veranstaltungsname:';
$string['surveys'] = 'Evaluationen: ';
$string['nocourse'] = 'Kurs konnte zur Zeit nicht gefunden werden, bitte versuchen Sie es später erneut.';
$string['nosurveys'] = 'Zur Zeit sind keine Evaluationen verfügbar.';
$string['syncnotpossible'] = 'Auf Grund technischer Schwierigkeiten konnte die Teilnehmerliste nicht zu EvaSys exportiert werden. Bitte wenden Sie sich an den Support.';
$string['syncsucessful'] = 'Sync zu EvaSys war erfolgreich.';
$string['syncalreadyuptodate'] = 'Teilnehmerliste war bereits auf dem aktuellen Stand.';
$string['syncnostudents'] = 'Es gibt in diesem Kurs keine Teilnehmer*innen, die evaluieren könnten.';
$string['taskname'] = 'EvaSys-Umfragen öffnen und schließen';
$string['begin'] = 'Beginn';
$string['end'] = 'Ende';
$string['direct_invite_checkbox'] = 'Evaluation sofort starten';
$string['reactivate_invite'] = 'Einladung erneut durchführen';
$string['reactivate_startdate'] = 'Startdatum anpassen';
$string['warning_inconsistent_states'] = "Einige Umfragen sind geöffnet, aber alle Umfragen sollten geschlossen sein.";
$string['change_mapping'] = "Weitere Veranstaltungen zuordnen";
$string['semester'] = "Semester";
$string['optional'] = "(Optional)";
$string['activate_nonstandard_time'] = "Evaluationszeitraum wegen Sonderveranstaltung ändern";
$string['activate_standard_time'] = "Standardzeitraum verwenden";
$string['standard_period'] = "Standard-Evaluationszeitraum:";
$string['different_period'] = "Abweichender Evaluationszeitraum:";
$string['time_set'] = "Standard-Evaluationszeitraum gesetzt";

// Multi allocation strings.

$string['selection_success'] = "Die Kurse wurden erfolgreich zugeordnet";
$string['add_course_header'] = "Wählen Sie die LSF-Veranstaltungen, die gemeinsam mit diesem Learnweb-Kurs evaluiert werden sollen";
$string['coursename'] = "Kursname";
$string['associated'] = "zugeordnet";
$string['forbidden'] = "Die Aktion ist im aktuellen Status des Kurses nicht zulässig";
$string['hisconnection_error'] = "Um diese Funktion nutzen zu können, muss das lokale Plugin lsf_unification installiert werden";
$string['maincoursepredefined'] = 'Vordefinierte Zuordnung.';
$string['maincoursepredefined_help'] = 'Dieser LSF-Kurs ist fest zugeordnet, da dies der entsprechende Learnweb-Kurs ist. Falls Sie dies für nicht korrekt halten, wenden Sie sich bitte an den Learnweb-Support.';

// Direct invite strings.

$string['planorstartevaluation'] = 'Evaluationszeitraum festlegen';
$string['startevaluationnow'] = 'Evaluation sofort beginnen';
$string['requestagain'] = 'Erneut einladen oder beauftragen';
$string['title_send_success'] = "Evaluation erfolgreich gestartet";
$string['content_send_success'] = 'Es wurden {$a->sent} von {$a->total} Einladungsmails versendet. <br />' .
                                  '{$a->queued} Evaluationsperioden wurden festgelegt.';
$string['title_send_failure'] = "Fehler beim Versand";
$string['send_error'] = "Es gab einen Fehler beim automatischen Versenden, bitte kontaktieren Sie Ihren Support, oder benutzen Sie den manuellen Versand von EvaSys";
$string['not_enough_dates'] = "Bitte geben Sie Daten für ALLE Umfragen an!";
$string['content_nostudents'] = "Dieser Kurs enthält keine Teilnehmer*innen, die an der Evaluation teilnehmen könnten.";
$string['direct_already'] = "Sie haben die Evaluation bereits gestartet. <br />" .
    "Es wurden keine neuen Einladungen versendet";
$string['direct_title_info'] = "Einladungen bereits versandt";
$string['title_send_rejected'] = "Unzulässiges Datum";
$string['content_send_rejected'] = "Ein Datum wurde in die Vergangenheit geändert. <br />" .
    "Dies ist nicht zulässig! Es können einzelne Evaluationsperioden geändert worden sein.<br />";
$string['title_send_invalid'] = "Fehlerhafter Zeitraum";
$string['content_send_invalid'] = "Eine Evaluationsperiode beginnt nachdem sie endet! <br />" .
    "Alle anderen Evaluationsperioden wurden wie gewohnt geändert.";

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
$string['title_success'] = "Erfolgreich";
$string['content_success_invite'] = "Die Evaluation wurde erfolgreich gestartet";
$string['content_success_direct'] = "Die Evaluationsperiode wurde erfolgreich gesetzt";
$string['title_date_invalid'] = "Unzulässiges Datum";
$string['content_invalidstart'] = "Der Start ist in der Vergangenheit";
$string['content_invalidend'] = "Das Ende ist in der Vergangenheit";
$string['content_start_after_end'] = "Der Start ist nach dem Ende";
$string['title_wrong_mode'] = "Unzulässige Operation";
$string['content_wrong_mode'] = "Dieser Kurs ist nicht im angenommenen Modus. Bitte wenden Sie sich an das Support-Team.";

// Sync date problems.
$string['syncendinthepast'] = 'Teilnehmer*innen nicht übertragen: Das gewünschte Enddatum liegt in der Vergangenheit.';
$string['syncstartafterend'] = 'Teilnehmer*innen nicht übertragen: Das Startdatum muss vor dem Enddatum liegen.';

// Form strings.
$string['startplaceholder'] = "Startdatum für die Evaluation";
$string['endplaceholder'] = "Enddatum für die Evaluation";

// Information box strings.

$string['title_success']  = "Evaluation erfolgreich beauftragt";
$string['title_uptodate'] = "Evaluation bereits beauftragt";
$string['title_failure']  = "Evaluation nicht beauftragt";

$string['content_success'] = "Sie haben die Evaluation erfolgreich beantragt.<br />" .
                             "!!!DIE EVALUATION HAT NOCH NICHT BEGONNEN!!!<br />" .
                             "Sie müssen nichts weiter tun, ".
                             "Ihr*e Evaluationsbeauftragte*r wird nach den Richtlinien Ihres Fachbereichs weiter verfahren.";

$string['content_uptodate'] = "Ihr*e Evaluationsbeauftragte*r hat bereist einen Auftrag zum Durchführen der Evaluation von Ihnen erhalten.<br />" .
                              "Für Fragen zum Status Ihrer Evaluation kontaktieren Sie bitte Ihre*n Evaluationsbeauftragte*n.";

$string['content_failure'] = "Leider konnte die Evaluation nicht beauftragt werden.<br />" .
                             "Bitte wenden Sie sich an den Support.";

$string['confirm_box'] = "Verstanden";
$string['content_confirm_reactivate_automated_closed'] = 'Sind Sie sicher, dass Sie die Evaluation erneut beginnen möchten?';
$string['content_confirm_reactivate_automated_open'] = 'Sind Sie sicher, dass Sie die Startzeit ändern möchten?';
$string['content_confirm_reactivate_manual_closed'] = 'Sind Sie sicher, dass Sie die Evaluation erneut beginnen möchten? ' .
    'Evaluationskoordinator*innen werden über die Änderung informiert und können die Evaluation anschließend neu starten.';
$string['content_confirm_reactivate_manual_open'] = 'Sind Sie sicher, dass Sie die Startzeit ändern möchten? ' .
    'Evaluationskoordinator*innen werden über die Änderung informiert und können die Evaluation anpassen, ' .
    'aber es ist nicht sicher, dass dies rechtzeitig geschieht.';

// Survey status.
$string['surveystatusopen'] = 'offen';
$string['surveystatusclosed'] = 'geschlossen';

// Capabilities.
$string['evasys_sync:mayevaluate'] = 'An Kursevaluation teilnehmen';
$string['evasys_sync:synchronize'] = 'Teilnehmer zu EvaSys synchronisieren';
$string['evasys_sync:modifymapping'] = 'Verknüpfung von EvaSys Veranstaltungen zu Moodle Kurs bearbeiten';

// Settings.
$string['settings'] = 'EvaSys Sync Block Einstellungen';
$string['settings_username'] = 'EvaSys-API-Nutzername';
$string['settings_password'] = 'EvaSys-API-Password';
$string['settings_soap_url'] = 'EvaSys SOAP URL';
$string['settings_wsdl_url'] = 'EvaSys WSDL URL';
$string['settings_moodleuser'] = 'Standard Nutzer-ID des Benachrichtigungsempfängers nach Sync';
$string['settings_mode'] = 'Standardmodus für Kategorien';
$string['his_connection'] = 'Aktiviere Mehrfach-Evaluationen (Achtung, externes Plugin notwendig!)';
$string['his_connection_help'] = 'Optionales Feature, dass es ermöglicht einem Kurs mehrere evasys Evaluationen zuzuordnen.
Dazu wird ein externes lokale Plugin lsf_unification (https://github.com/learnweb/his_unification) und 
eine Anbindung an das CMS Software HIS LSF benötigt um weitere Veranstaltungen von den verantwortlichen Lehrenden zu identifizieren.';
$string['settings_moodleuser_select'] = 'Kurskategorien';
$string['settings_cc_select'] = 'Kurskategorie auswählen';
$string['settings_cc_user'] = 'Nutzer-ID des Empfängers für die gewählte Kurskategorie';
$string['submit'] = 'Speichern';
$string['hd_user_cat'] = 'Benutzer-Kategorie Zuweisung';
$string['addcat'] = 'Kategorie hinzufügen';
$string['delete_confirm'] = 'Sind Sie sicher, dass der Nutzer für diese Kurskategorie gelöscht werden soll?';
$string['auto_mode'] = 'Automatischer Modus';
$string['standard_time_mode'] = "Standardzeitraum anbieten";
$string['edit_time'] = "Standardzeitraum bearbeiten";
$string['save_failure'] = "Fehler beim speichern";
$string['usetimecheckbox'] = "Standardzeitraum verwenden";


// Settings - category table.
$string['category_name'] = 'Kurskategorie';
$string['responsible_user'] = 'Moodle-Benutzer ID';
$string['tablecaption'] = 'Benutzerdefinierter Mail-Empfänger nach Synchronisation';
$string['default'] = 'Standard';
$string['delete_category_user'] = 'Eintrag löschen';
$string['delete'] = 'Löschen';

// Persistance class.
$string['invalidcoursecat'] = 'Ungültige Kurskategorie';
$string['invalidmode'] = "Ungültiger Modus";
$string['invalidcourse'] = 'Ungültiger Kurs';
$string['invalidsurvey'] = "Ungültige Umfrage";
$string['invaliddate'] = "Ungültiges Datum";
$string['invalidstate'] = "Ungültiger Statuscode";
$string['invalid_standard_time_mode'] = "Ungültige Angabe ob Standardzeiten vorhanden sind";

// Privacy API.
$string['privacy:metadata'] = 'Lade Studierende ein, an Erhebungen zur Qualität der Lehre mit EvaSys-Umfragen teilzunehmen.';
$string['privacy:metadata:username'] = 'Benutzernamen von Studierenden, welche in einem Kurs eingeschrieben sind (as E-Mail-Adresse dargestellt, um EvaSys-Erfordernisse zu erfüllen).';

// Events.
$string['eventevaluationperiod_set'] = 'Evaluationszeitraum wurde festgelegt';
$string['eventevaluation_opened'] = 'Evaluation wurde gestartet';
$string['eventevaluation_closed'] = 'Evaluation wurde beendet';
$string['eventevaluation_requested'] = 'Evaluation wurde angefragt';

// Months.
$string['January'] = 'Januar';
$string['February'] = 'Februar';
$string['March'] = 'März';
$string['April'] = 'April';
$string['May'] = 'Mai';
$string['June'] = 'Juni';
$string['July'] = 'Juli';
$string['August'] = 'August';
$string['September'] = 'September';
$string['October'] = 'Oktober';
$string['November'] = 'November';
$string['December'] = 'Dezember';

// From...to.
$string['evaluationperiod'] = 'Evaluationszeitraum';
$string['evaluationperiod_for'] = 'Evaluationszeitraum für "{$a}"';
$string['startondate'] = 'Von';
$string['endondate'] = 'bis';
$string['evaluations'] = 'Evaluationen';
$string['title'] = 'Titel';

// Notices.
$string['evalperiodsetnotice'] = 'Evaluationszeitraum gesetzt';
$string['emailsentnotice'] = 'Evaluation beauftragt';

$string['apply'] = 'Anwenden';

$string['useoneevalperiod'] = 'Den gleichen Evaluationszeitraum für alle Veranstaltungen nutzen';
$string['eval_additional_lsfcourses'] = 'Ich möchte mehrere Veranstaltungen auf einmal evaluieren lassen';
$string['eval_additional_courses'] = 'Ich möchte, dass Studierende mehrerer (Learnweb-)Kurse zusammen diese Veranstaltung evaluieren';
$string['course_units'] = 'Veranstaltungen';
$string['use_default_evalperiod'] = 'Den Standardevaluationszeitraum verwenden:<br>{$a}';
$string['err_endbeforestart'] = 'Der Endzeitpunkt liegt vor dem Startzeitpunkt';
$string['dont_evaluate_course'] = 'Dieser Kurs soll NICHT evaluiert werden';

$string['standart_time_start'] = 'Start des Standardevaluationszeitraums';
$string['standart_time_end'] = 'Ende des Standardevaluationszeitraums';

$string['teacher_can_request_evaluation'] = 'Lehrende können Evaluationen beantragen';
$string['teacher_evaluation_request_needs_approval'] = 'Evaluationsanträge von Lehrenden müssen von Ihnen bestätigt werden';
$string['automatic_task_creation'] = 'Geplante Vorgänge werden automatisch in EvaSys erstellt.';
$string['teacher_can_change_evaluation'] = 'Lehrende können bestehende Evaluationen verändern';
$string['teacher_evaluation_change_needs_approval'] = 'Änderungen an bestehenden Evaluationen müssen von Ihnen bestätigt werden';

$string['search_for_courses'] = 'Nach Kursen suchen';

$string['evasys_settings_for'] = 'Evasys-Einstellungen für {$a}';
$string['no_default_period_set'] = 'Es wurde kein Standardevaluationszeitraum festgelegt.';
$string['default_period_set_from_to'] = 'Der Standardevaluationszeitraum wurde von <b>{$a->start}</b> bis <b>{$a->end}</b> gesetzt.';
$string['teachers_can_request_evaluation'] = 'Lehrende <b>können</b> Evaluationen beantragen.';
$string['teachers_cannot_request_evaluation'] = 'Lehrende können <b>keine</b> Evaluationen beantragen.';

$string['courses_with_errors'] = 'Kurse mit Fehlern!';
$string['courses_with_requests'] = 'Kurse mit ausstehenden Evaluationsanträgen';
$string['courses_with_automatic_evals'] = 'Kurse mit automatischen Evaluationen';
$string['courses_with_manual_evals'] = 'Kurse mit manuellen Evaluationen';
$string['courses_without_evals'] = 'Kurse ohne Evaluationen';

$string['set_default_eval'] = 'Standardevaluation planen';
$string['set_default_eval_for_selected'] = 'Standardevaluation für alle ausgewählten planen';
$string['set_default_eval_for_all'] = 'Standardevaluation für alle Kurse planen';

$string['notify_teacher_email_subject'] = '{$a->courseshort}: Eine Evaluation wurde für Ihren Kurs geplant.';
$string['notify_teacher_email_body'] = 'Sehr geehrte*r Lehrende*r,

eine Evaluation wurde für Ihren Kurs {$a->coursefull}
von {$a->start} bis {$a->end}
geplant.

Falls Sie Probleme mit dem Zeitraum der Evaluation haben, wenden Sie sich bitte an Ihre*n Evaluationskoordinator*in {$a->coordinator}.

Mit freundlichen Grüßen,
Ihr Learnweb-Support';