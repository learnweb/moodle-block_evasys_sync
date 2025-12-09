<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * German language strings for this plugin.
 *
 * @package   block_evasys_sync
 * @copyright 2016 Jan Dageförde
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['April'] = 'April';
$string['August'] = 'August';
$string['December'] = 'Dezember';
$string['February'] = 'Februar';
$string['January'] = 'Januar';
$string['July'] = 'Juli';
$string['June'] = 'Juni';
$string['March'] = 'März';
$string['May'] = 'Mai';
$string['November'] = 'November';
$string['October'] = 'Oktober';
$string['September'] = 'September';
$string['activate_nonstandard_time'] = "Evaluationszeitraum wegen Sonderveranstaltung ändern";
$string['activate_standard_time'] = "Standardzeitraum verwenden";
$string['add_course_header'] = "Wählen Sie die LSF-Veranstaltungen, die gemeinsam mit diesem Learnweb-Kurs evaluiert werden sollen";
$string['addcat'] = 'Kategorie hinzufügen';
$string['alert_email_body'] = 'Sehr geehrte*r Evaluationskoordinator*in, ' . "\n" .
    'Sie erhalten diese E-Mail da im Learnweb-Kurs "{$a->name}" der Evaluationszeitraum wie folgt festgesetzt wurde:' . "\n\n" .
    "\t".'Start: {$a->start}' . "\n" .
    "\t".'Ende:  {$a->end}' . "\n" .
    "\t".'Verantwortliche*r: {$a->teacher}' . "\n" .
    "\t".'EvaSys-IDs:' . "\n" .
    '{$a->evasyscourses}' . "\n" .
    'Mit freundlichen Grüßen' . "\n" .
    'Ihr Learnweb-Support';
$string['alert_email_subject'] = 'Evaluationszeitraum gesetzt für {$a}';
$string['apply'] = 'Anwenden';
$string['associated'] = "zugeordnet";
$string['auto_mode'] = 'Automatischer Modus';
$string['automatic_task_creation'] = 'Geplante Vorgänge werden automatisch in EvaSys erstellt.';
$string['begin'] = 'Beginn';
$string['category_name'] = 'Kurskategorie';
$string['change_mapping'] = "Weitere Veranstaltungen zuordnen";
$string['checkstatus'] = 'Status der Evaluationen anzeigen';
$string['clear_all_errors'] = 'Alle Fehler löschen';
$string['clear_error'] = 'Fehler löschen';
$string['clear_selected_errors'] = 'Ausgewählte Fehler löschen';
$string['confirm_box'] = "Verstanden";
$string['content_confirm_reactivate_automated_closed'] = 'Sind Sie sicher, dass Sie die Evaluation erneut beginnen möchten?';
$string['content_confirm_reactivate_automated_open'] = 'Sind Sie sicher, dass Sie die Startzeit ändern möchten?';
$string['content_confirm_reactivate_manual_closed'] = 'Sind Sie sicher, dass Sie die Evaluation erneut beginnen möchten? ' .
    'Evaluationskoordinator*innen werden über die Änderung informiert und können die Evaluation anschließend neu starten.';
$string['content_confirm_reactivate_manual_open'] = 'Sind Sie sicher, dass Sie die Startzeit ändern möchten? ' .
    'Evaluationskoordinator*innen werden über die Änderung informiert und können die Evaluation anpassen, ' .
    'aber es ist nicht sicher, dass dies rechtzeitig geschieht.';
$string['content_failure'] = "Leider konnte die Evaluation nicht beauftragt werden.<br />" .
                             "Bitte wenden Sie sich an den Support.";
$string['content_invalidend'] = "Das Ende ist in der Vergangenheit";
$string['content_invalidstart'] = "Der Start ist in der Vergangenheit";
$string['content_nostudents'] = "Dieser Kurs enthält keine Teilnehmer*innen, die an der Evaluation teilnehmen könnten.";
$string['content_send_invalid'] = "Eine Evaluationsperiode beginnt nachdem sie endet! <br />" .
    "Alle anderen Evaluationsperioden wurden wie gewohnt geändert.";
$string['content_send_rejected'] = "Ein Datum wurde in die Vergangenheit geändert. <br />" .
    "Dies ist nicht zulässig! Es können einzelne Evaluationsperioden geändert worden sein.<br />";
$string['content_send_success'] = 'Es wurden {$a->sent} von {$a->total} Einladungsmails versendet. <br />' .
                                  '{$a->queued} Evaluationsperioden wurden festgelegt.';
$string['content_start_after_end'] = "Der Start ist nach dem Ende";
$string['content_success'] = "Sie haben die Evaluation erfolgreich beantragt.<br />" .
                             "!!!DIE EVALUATION HAT NOCH NICHT BEGONNEN!!!<br />" .
                             "Sie müssen nichts weiter tun, ".
                             "Ihr*e Evaluationsbeauftragte*r wird nach den Richtlinien Ihres Fachbereichs weiter verfahren.";
$string['content_success_direct'] = "Die Evaluationsperiode wurde erfolgreich gesetzt";
$string['content_success_invite'] = "Die Evaluation wurde erfolgreich gestartet";
$string['content_successandinfo'] = "Sie haben die Evaluation erfolgreich erneut beantragt.<br />" .
                             "Es existiert bereits ein Evaluationsantrag für diesen Kurs; Sie haben lediglich Teilnehmer*innen synchronisiert.<br />" .
                             "Zum Ändern des Evaluationszeitraums wenden Sie sich bitte an Ihre*n Evaluationsbeauftragte*n.";
$string['content_uptodate'] = "Ihr*e Evaluationsbeauftragte*r hat bereits einen Auftrag zum Durchführen der Evaluation von Ihnen erhalten.<br />" .
                              "Für Fragen zum Status Ihrer Evaluation kontaktieren Sie bitte Ihre*n Evaluationsbeauftragte*n.";
$string['content_wrong_mode'] = "Dieser Kurs ist nicht im angenommenen Modus. Bitte wenden Sie sich an das Support-Team.";
$string['countparticipants'] = 'Anzahl Teilnehmer*innen: ';
$string['course_units'] = 'Veranstaltungen';
$string['coursename'] = "Kursname";
$string['courses_with_automatic_evals'] = 'Kurse mit automatischen Evaluationen';
$string['courses_with_errors'] = 'Kurse mit Fehlern!';
$string['courses_with_manual_evals'] = 'Kurse mit bereits beantragten Evaluationen';
$string['courses_with_requests'] = 'Kurse mit ausstehenden Evaluationsanträgen';
$string['courses_without_evals'] = 'Kurse ohne Evaluationen';
$string['courses_without_idnumber'] = 'Kurse, bei denen keine Evaluation möglich ist (fehlende Kurs-ID)';
$string['courses_without_idnumber_help'] = 'Diese Kurse haben keine ID gesetzt. Jeder Kurs, der evaluiert werden soll, braucht auch eine Kurs-ID, um im EvaSys-System identifiziert werden zu können. Falls Sie Kurs-IDs nicht bearbeiten können, wenden Sie sich bitte an den Support!';
$string['default'] = 'Standard';
$string['default_period_set_from_to'] = 'Der Standardevaluationszeitraum wurde von <b>{$a->start}</b> bis <b>{$a->end}</b> gesetzt.';
$string['delete'] = 'Löschen';
$string['delete_category_user'] = 'Eintrag löschen';
$string['delete_confirm'] = 'Sind Sie sicher, dass der Nutzer für diese Kurskategorie gelöscht werden soll?';
$string['different_period'] = "Abweichender Evaluationszeitraum:";
$string['direct_already'] = "Sie haben die Evaluation bereits gestartet. <br />" .
    "Es wurden keine neuen Einladungen versendet";
$string['direct_invite_checkbox'] = 'Evaluation sofort starten';
$string['direct_title_info'] = "Einladungen bereits versandt";
$string['dont_evaluate_course'] = 'Dieser Kurs soll NICHT evaluiert werden';
$string['edit_time'] = "Standardzeitraum bearbeiten";
$string['emailsentnotice'] = 'Evaluation beauftragt';
$string['end'] = 'Ende';
$string['endondate'] = 'bis';
$string['endplaceholder'] = "Enddatum für die Evaluation";
$string['err_endbeforestart'] = 'Der Endzeitpunkt liegt vor dem Startzeitpunkt';
$string['evacourseid'] = 'EvaSys-Kurs-ID:';
$string['evacoursename'] = 'Veranstaltungsname:';
$string['evainternalid'] = 'EvaSys-interne ID:';
$string['eval_additional_courses'] = 'Ich möchte, dass Studierende mehrerer (Learnweb-)Kurse zusammen diese Veranstaltung evaluieren';
$string['eval_additional_lsfcourses'] = 'Ich möchte mehrere Veranstaltungen auf einmal evaluieren lassen';
$string['evalperiodsetnotice'] = 'Evaluationszeitraum gesetzt';
$string['evaluationperiod'] = 'Evaluationszeitraum';
$string['evaluationperiod_for'] = 'Evaluationszeitraum für "{$a}"';
$string['evaluations'] = 'Evaluationen';
$string['evasys_settings_for'] = 'Evasys-Einstellungen für {$a}';
$string['evasys_sync'] = 'EvaSys-Export';
$string['evasys_sync:addinstance'] = 'EvaSys-Export-Block hinzufügen';
$string['evasys_sync:mayevaluate'] = 'An Kursevaluation teilnehmen';
$string['evasys_sync:modifymapping'] = 'Verknüpfung von EvaSys Veranstaltungen zu Moodle Kurs bearbeiten';
$string['evasys_sync:synchronize'] = 'Teilnehmer zu EvaSys synchronisieren';
$string['eventevaluation_closed'] = 'Evaluation wurde beendet';
$string['eventevaluation_opened'] = 'Evaluation wurde gestartet';
$string['eventevaluation_requested'] = 'Evaluation wurde angefragt';
$string['eventevaluationperiod_set'] = 'Evaluationszeitraum wurde festgelegt';
$string['finishedforms'] = 'Ausgefüllt:';
$string['forbidden'] = "Die Aktion ist im aktuellen Status des Kurses nicht zulässig";
$string['hd_user_cat'] = 'Benutzer-Kategorie Zuweisung';
$string['his_connection'] = 'Aktiviere Mehrfach-Evaluationen (Achtung, externes Plugin notwendig!)';
$string['his_connection_help'] = 'Optionales Feature, dass es ermöglicht einem Kurs mehrere evasys Evaluationen zuzuordnen.
Dazu wird ein externes lokale Plugin lsf_unification (https:
eine Anbindung an das CMS Software HIS LSF benötigt um weitere Veranstaltungen von den verantwortlichen Lehrenden zu identifizieren.';
$string['invalid_standard_time_mode'] = "Ungültige Angabe ob Standardzeiten vorhanden sind";
$string['invalidcourse'] = 'Ungültiger Kurs';
$string['invalidcoursecat'] = 'Ungültige Kurskategorie';
$string['invaliddate'] = "Ungültiges Datum";
$string['invalidmode'] = "Ungültiger Modus";
$string['invalidstate'] = "Ungültiger Statuscode";
$string['invalidsurvey'] = "Ungültige Umfrage";
$string['invitestudents'] = 'Evaluation beauftragen';
$string['maincoursepredefined'] = 'Vordefinierte Zuordnung.';
$string['maincoursepredefined_help'] = 'Dieser LSF-Kurs ist fest zugeordnet, da dies der entsprechende Learnweb-Kurs ist. Falls Sie dies für nicht korrekt halten, wenden Sie sich bitte an den Learnweb-Support.';
$string['missing_course_id'] = 'Fehlende Kurs-ID! Bitte wenden Sie sich an den Learnweb Support mit einem Link zu der Veranstaltung im HIS LSF, die evaluiert werden soll.';
$string['no_default_period_set'] = 'Es wurde kein Standardevaluationszeitraum festgelegt.';
$string['no_eval_planned'] = 'Es ist keine Evaluation geplant.';
$string['no_evasys_course_found'] = 'Kein passender EvaSys-Kurs gefunden! Bitte wenden Sie sich an Ihren Evaluationskoordinator.';
$string['no_searchresults_found'] = 'Keine Suchergebnisse gefunden';
$string['nocourse'] = 'Kurs konnte zur Zeit nicht gefunden werden, bitte versuchen Sie es später erneut.';
$string['nohisconnection_error'] = "Um diese Funktion nutzen zu können, muss das lokale Plugin lsf_unification installiert werden";
$string['nosurveys'] = 'Zur Zeit sind keine Evaluationen verfügbar.';
$string['not_enough_dates'] = "Bitte geben Sie Daten für ALLE Umfragen an!";
$string['not_inside_evaluation_category'] = 'Dieser Kurs ist in keiner Evaluationskategorie! Bitte wenden Sie sich an den Learnweb Support.';
$string['notify_teacher_email_body'] = 'Sehr geehrte*r Lehrende*r,

dies ist eine automatisch generierte Mail, ausgelöst dadurch, dass Ihr Evaluationskoordinator eine Evaluation für ihren Kurs <b>{$a->coursefull}</b>
von {$a->start} bis {$a->end}
geplant hat.

Bei Fragen oder Rückmeldungen wenden Sie sich bitte an Ihre*n Evaluationskoordinator*in via {$a->coordinator}.

Mit freundlichen Grüßen
Ihr Learnweb-Support';
$string['notify_teacher_email_subject'] = '{$a->courseshort}: Eine Evaluation wurde für Ihren Kurs geplant.';
$string['optional'] = "(Optional)";
$string['planorstartevaluation'] = 'Evaluationszeitraum festlegen';
$string['pluginname'] = 'EvaSys-Export-Block';
$string['privacy:metadata'] = 'Lade Studierende ein, an Erhebungen zur Qualität der Lehre mit EvaSys-Umfragen teilzunehmen.';
$string['privacy:metadata:username'] = 'Benutzernamen von Studierenden, welche in einem Kurs eingeschrieben sind (as E-Mail-Adresse dargestellt, um EvaSys-Erfordernisse zu erfüllen).';
$string['reactivate_invite'] = 'Einladung erneut durchführen';
$string['reactivate_startdate'] = 'Startdatum anpassen';
$string['request_eval'] = 'Evaluation beantragen';
$string['requestagain'] = 'Erneut einladen oder beauftragen';
$string['responsible_user'] = 'Moodle-Benutzer ID';
$string["running_crontask"] = 'Die Synchronization der ausgewählten Kurse von {$a} zu Evasys wird derzeit durchgeführt. Dies kann evt. einige Minuten dauern. Bitte warten Sie einige Zeit und laden die Seite neu.';
$string['save_failure'] = "Fehler beim speichern";
$string['search'] = 'Suchen';
$string['search_for_courses'] = 'Nach Kursen suchen';
$string['searchresults'] = 'Suchergebnisse';
$string['selection_success'] = "Die Kurse wurden erfolgreich zugeordnet";
$string['semester'] = "Semester";
$string['send_error'] = "Es gab einen Fehler beim automatischen Versenden, bitte kontaktieren Sie Ihren Support, oder benutzen Sie den manuellen Versand von EvaSys";
$string['send_mail_to_teacher'] = 'Sende Bestätigungsmail an Lehrende';
$string['send_mail_to_teacher_help'] = 'Falls ausgewählt, wird eine Benachrichtigungsmail an Lehrende geschickt, wenn über die evasys-Überblick-Seite eine Evaluation für ihren Kurs beantragt wird.';
$string['set_default_eval'] = 'Standardevaluation planen';
$string['set_default_eval_for_all'] = 'Standardevaluation für alle Kurse planen';
$string['set_default_eval_for_selected'] = 'Standardevaluation für alle ausgewählten planen';
$string['set_default_period_for_default_eval'] = 'Legen Sie einen Standardevaluationszeitraum fest, um Standardevaluationen planen zu können.';
$string['set_re_eval'] = 'Evaluation erneut ausführen';
$string['settings'] = 'EvaSys Sync Block Einstellungen';
$string['settings_cc_select'] = 'Kurskategorie auswählen';
$string['settings_cc_user'] = 'Nutzer-ID des Empfängers für die gewählte Kurskategorie';
$string['settings_mode'] = 'Standardmodus für Kategorien';
$string['settings_moodleuser'] = 'Standard Nutzer-ID des Benachrichtigungsempfängers nach Sync';
$string['settings_moodleuser_select'] = 'Kurskategorien';
$string['settings_password'] = 'EvaSys-API-Password';
$string['settings_soap_url'] = 'EvaSys SOAP URL';
$string['settings_username'] = 'EvaSys-API-Nutzername';
$string['settings_wsdl_url'] = 'EvaSys WSDL URL';
$string['standard_period'] = "Standard-Evaluationszeitraum:";
$string['standard_time_mode'] = "Standardzeitraum anbieten";
$string['standart_time_end'] = 'Ende des Standardevaluationszeitraums';
$string['standart_time_start'] = 'Start des Standardevaluationszeitraums';
$string['startevaluationnow'] = 'Evaluation sofort beginnen';
$string['startondate'] = 'Von';
$string['startplaceholder'] = "Startdatum für die Evaluation";
$string['submit'] = 'Speichern';
$string['surveys'] = 'Evaluationen: ';
$string['surveystatus'] = 'Evaluationsstatus:';
$string['surveystatusclosed'] = 'geschlossen';
$string['surveystatusopen'] = 'offen';
$string['syncalreadyuptodate'] = 'Teilnehmerliste war bereits auf dem aktuellen Stand.';
$string['syncendinthepast'] = 'Teilnehmer*innen nicht übertragen: Das gewünschte Enddatum liegt in der Vergangenheit.';
$string['syncnostudents'] = 'Es gibt in diesem Kurs keine Teilnehmer*innen, die evaluieren könnten.';
$string['syncnotpossible'] = 'Auf Grund technischer Schwierigkeiten konnte die Teilnehmerliste nicht zu EvaSys exportiert werden. Bitte wenden Sie sich an den Support.';
$string['syncstartafterend'] = 'Teilnehmer*innen nicht übertragen: Das Startdatum muss vor dem Enddatum liegen.';
$string['syncsucessful'] = 'Sync zu EvaSys war erfolgreich.';
$string['tablecaption'] = 'Benutzerdefinierter Mail-Empfänger nach Synchronisation';
$string['taskname'] = 'EvaSys-Umfragen öffnen und schließen';
$string['teacher_can_change_evaluation'] = 'Lehrende können bestehende Evaluationen verändern';
$string['teacher_can_request_evaluation'] = 'Lehrende können Evaluationen beantragen';
$string['teacher_evaluation_change_needs_approval'] = 'Änderungen an bestehenden Evaluationen müssen von Ihnen bestätigt werden';
$string['teacher_evaluation_request_needs_approval'] = 'Evaluationsanträge von Lehrenden müssen von Ihnen bestätigt werden';
$string['teacher_request_disabled'] = 'Lehrende können keine Evaluationen beantragen. Eine Evaluation wird für Sie erstellt.';
$string['teachers_can_request_evaluation'] = 'Lehrende <b>können</b> Evaluationen beantragen.';
$string['teachers_cannot_request_evaluation'] = 'Lehrende können <b>keine</b> Evaluationen beantragen.';
$string['time_set'] = "Standard-Evaluationszeitraum gesetzt";
$string['title'] = 'Titel';
$string['title_date_invalid'] = "Unzulässiges Datum";
$string['title_failure']  = "Evaluation nicht beauftragt";
$string['title_send_failure'] = "Fehler beim Versand";
$string['title_send_invalid'] = "Fehlerhafter Zeitraum";
$string['title_send_rejected'] = "Unzulässiges Datum";
$string['title_send_success'] = "Evaluation erfolgreich gestartet";
$string['title_success']  = "Evaluation erfolgreich beauftragt";
$string['title_uptodate'] = "Evaluation bereits beauftragt";
$string['title_wrong_mode'] = "Unzulässige Operation";
$string['use_default_evalperiod'] = 'Den Standardevaluationszeitraum verwenden:<br>{$a}';
$string['useoneevalperiod'] = 'Den gleichen Evaluationszeitraum für alle Veranstaltungen nutzen';
$string['usetimecheckbox'] = "Standardzeitraum verwenden";
$string['warning_inconsistent_states'] = "Einige Umfragen sind geöffnet, aber alle Umfragen sollten geschlossen sein.";
