# moodle-block_evasys_sync *(release_candidate)* 
[![Build Status](https://travis-ci.org/learnweb/moodle-block_evasys_sync.svg?branch=master)](https://travis-ci.org/learnweb/moodle-block_evasys_sync)
[![codecov](https://codecov.io/gh/learnweb/moodle-block_evasys_sync/branch/master/graph/badge.svg)](https://codecov.io/gh/learnweb/moodle-block_evasys_sync)

:100: Anonyme Lehrevaluation mit EvaSys und Moodle

Entwickelt von: Max Inden, Dennis Grzyb, [Nina Herrmann](https://github.com/NinaHerrmann), [Tobias Reischmann](https://github.com/tobiasreischmann) und [Jan Dageförde](https://github.com/Dagefoerde) (WWU Münster).
 
## Zweck

Zur Durchführung einer fairen Online-Lehrevaluation durch den Teilnehmerkreis einer Veranstaltung wird die Liste der berechtigten Teilnehmer benötigt. Diese Liste kann mit Hilfe dieses Plugins aus laufenden Learnweb-Kursen generiert werden.
Dadurch können nur berechtigte Teilnehmer evaluieren und es wird sichergestellt, dass jeder höchstens einmal evaluiert.
 
*Die zur Evaluation verwendete Software EvaSys stellt sicher, dass jeder Teilnehmer nur genau einmal an einer Befragung teilnehmen kann (TAN). Weiterhin stellt die Software sicher, dass durch ausgefüllte Fragebögen **kein Rückschluss** auf einzelne Teilnehmer möglich ist; d.h. die TAN wird bloß für den Beginn des Fragebogens verwendet.*
 

## Voraussetzungen

* Prüfung der [Annahmen](#annahmen).
* Moodle 3.1
* EvaSys-Server mit konfiguriertem API-Benutzer.
* ID eines Moodle-Nutzers, an welchen Benachrichtigungen versandt werden, muss bekannt sein (ideal: Evaluationskoordinator/in der Einrichtung).

## Installation

1. Plugin nach `blocks/evasys_sync` kopieren.
2. Moodle öffnen.
3. Einstellungen für EvaSys-API-Endpunkt und -Benutzer festlegen. User-ID benennen, deren E-Mail-Adresse für den Versand von Benachrichtigungen verwendet wird.  

## Annahmen

Das Plugin wurde auf Basis der folgenden Annahmen entwickelt.
Falls diese Annahmen bei Ihnen nicht zutreffen, sind möglicherweise
 Veränderungen am Plugin notwendig. Bitte erstellen sie ein
 [GitHub-Issue](https://github.com/learnweb/moodle-block_evasys_sync/issues), 
 welches Ihre Anforderungen erläutert.
 Noch mehr freuen wir uns, wenn Sie zur Lösung Ihres Anliegens einen
 [Pull Request](https://github.com/learnweb/moodle-block_evasys_sync/pulls)
 vorbereiten, den wir gerne gegenlesen und ggf. einpflegen.

- [x] Zur Evaluation wird EvaSys verwendet.
- [x] Es gibt eine Person, die für die Evaluationskoordination an der Einrichtung zuständig ist.
- [x] Manuelle Interaktion: Diese Person ist dafür verantwortlich, in EvaSys die generierten Teilnehmerlisten je Kurs in TAN-Listen zu überführen und Einladungen zu erstellen.
- [x] Lehrende entscheiden, zu welchem Zeitpunkt die Teilnehmer ihres Kurses zur Umfrage hinzugefügt werden (Push-Prinzip; keine Automatik).
- [x] Moodle und EvaSys sind (zumindest oberflächlich) mit LSF integriert: 
    * Zur Synchronisation muss das courseid-Feld des Moodle-Kurses die sog. "publishid" des LSF enthalten.
    * EvaSys-Umfragen sind nach dem Schema "[Veranstaltungsnummer] [Semester]" nach der Nomenklatur des LSF benannt. *publishid ist **ungleich** mit Veranstaltungsnummer!*
    * Moodle hat jederzeit Zugriff auf einen LSF-View, um ad hoc die Zuordnung *publishid --> Veranstaltungsnummer* vornehmen zu können. Dazu nutzt dieses Plugin [local_lsf_unification](https://github.com/learnweb/his_unification).
- [x] Der Moodle-Webserver kann den EvaSys-Webserver erreichen.

Die Liste der Annahmen ist möglicherweise nicht vollständig.
     
## Screenshots

* Für Studierende hat dieser Block keine Funktion. Er wird nicht angezeigt, auch wenn er zum Kurs hinzugefügt ist.

* Für Lehrende hat der Block im Kurs zwei Ansichten:

Initial wird nur ein Button angezeigt. Erst nach Klick wird Kontakt zum EvaSys-Webserver aufgenommen. Dabei wird zunächst nur abgefragt, ob Befragungen anstehen.  
![Screenshot: Initiale Ansicht](https://cloud.githubusercontent.com/assets/432117/21270915/ef71e874-c3b8-11e6-922e-e071767a9b02.png)

Nach Klick auf obigen Button wird eine Übersicht über Befragungen angezeigt, die zum aktuellen Kurs in EvaSys hinterlegt sind:  
![Screenshot: Keine Evaluationen](https://cloud.githubusercontent.com/assets/432117/21270916/ef7220f0-c3b8-11e6-8c8c-9d7211d7385d.png)

In diesem Fall sind keine Befragungen vorbereitet. Falls Befragungen vorhanden sind, wird zusätzlich ein Button angezeigt, der zur Übertragung der Teilnehmerliste dient:  
![Screenshot: Mit Evaluationen](https://cloud.githubusercontent.com/assets/432117/21343860/1d4ce964-c699-11e6-8cd9-2b20f3155153.png)

## Tests

Die Funktionsweise des Blocks wird durch Behattests sichergestellt.

##### Tests durchführen:
Um die Test durchzuführen führen sie:
`php moodle/admin/tool/behat/cli/run.php --tags="@block_evasys_sync"` aus. <br>
Mehr Infos dazu in der [Dokumentation](https://docs.moodle.org/dev/Running_acceptance_test)

##### Neue Tests schreiben:
- Diese Plugin verfügt über einen Behattest Generator, zu finden in `evasys_sync/tests/behat/generator` <br>
- Der Generator ist zweigeteilt. Um bestehende Schritte zu ändern reicht es die entsprechende Schrittdefiniton in `steps.py` zu finden und zu ändern.
- Um neue Schritte zu definieren, muss ein entsprechender Schritt in `steps.py` angelegt werden, und die entsprechenden parameter in `generator.py` als key im `OPTIONS dict` angelegt werden. zudem muss in den verwendeten methoden der Parameter hinzugefügt werden. <br>
Logischerweise muss der neue Schritt auch in `behat_block_evasys_sync` definiert werden sofern es kein Standardschritt ist.

######Generator
Der generelle Aufbau des Generators ist wie folgt:
<br>`steps.py`:
- direkte Texterzeugende Methoden sollten hier definiert werden.
- Für einfache Schritte die nur von einem Parameter abhängen können `dicts` benutzt werden.
- Bennenungskonventionen:
    * `dicts`: <i>type</i>_<i>chechname_with_underscores</i>
    * `functions`: <i>type</i>_<i>chechnamewithoutunderscores</i>
- _type_ richtet sich nach der Funktion des zurückgegebenen Texts im Context des Behat tests:
    * `description`: Der zurückgegebene Text ist für die Scenariobeschreibung.
    * `step`: Der zurückgegebene Text legt die Umgebung des Tests fest.
    * `check`: Der zurückgegebene Text ist eine Überprüfung der erzeugten Seite.
    
<br>`generator.py`:
- 4 hauptmethoden die für die übergebenen Parameter entsprechende Texte zurückgeben:
    * `get_checks` Gibt alle Checks aus
    * `get_scenario` Gibt alle Umgebungsfestlegenden Texte zurück
    * `get_combi_description` Gibt die Beschreibung eines Szenarios auch für eine mehrfachbelegung von parametern zurück
    * `get_postcondensing_checks` Gibt die Checks die beim verkleinern der Szenarien nicht berücksichtigt werden sollen Zurück.
    
Der generator erzeutgt zunächst aus allen in `OPTIONS` festgelegten Parametern das Karthesische Produkt. <br>
Danach werden für jede Option die Checks berechnet. 
Alle Optionen mit gleichem Check werden darauf überprüft ob sie durch das abändern eines
Parameters mit einer der anderen Optionen übereinstimmen. Wenn Ja wird angenommen, dass der Parameter nicht relevant für den Ausgang ist und die 2 Tests zusammengefasst.

######Mockingklassen:
Die Rückgaben des Evasys und LSF system werden für die Tests gemockt.<br>
Dies geschiet in den Dateien `lsf_api_mock_testable.php` sowie in der unterklasse `evasys_api_testable` in `evasys_api.php`
<br>
Die relationen sehen dabei wie folgt aus:<br>
`lsfid` &rarr; `(veranstnr, semestertext)` = `evasyskennung` <br>
Die daten werden als JSON array im `summary` Feld der `course` Tabelle gespeichert. Dabei haben die Daten folgende Form: <br>
`stdClass()->evacourses => array()`
<details name="evavardump">
    <summary>Single Evacourse vardump</summary>
    
    object(stdClass)[281]
      public 'valid' => boolean true
      public 'veranstnr' => int 0
      public 'semestertxt' => string 'WS 2017/18' (length=10)
      public 'studentcount' => int 100
      public 'title' => string 'DynamicSurvey0' (length=14)
      public 'surveys' => 
        array (size=1)
          0 => 
            object(stdClass)[282]
              public 'num' => int 0
              public 'formid' => int 1
              public 'is_open' => string 't' (length=1)
              public 'form_count' => int 20
              public 'pswd_count' => int 200
</details>

Das `stdClass->evacourses` Array sollte dabei für eine lsfid die entsprechende Datenstruktur [siehe vardump](#evavardump) zurückliefern.
<br>
Die gemockte lsfapi gibt lediglich `semestertext` und `veranstnr` zurück da die restlichen Werte nicht behötigt werden <br>
Auch die gemockte evasysapi gibt lediglich benutzte Werte zurück und füllt den Rest mit dummys. Sollte ein neuer Wert benötigt werden, so muss dieser hier eingefügt werden. <br>
Hier sind dafür Vardumps bereitgestellt, (evasys Struktur ändert sich je nachdem ob der Kurs eine oder mehrere Umfragen besitzt!!!):
<details>
<summary>Evasys Kurs Vardump</summary>

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
            
</details>
<details>
<summary>Vardump von einem Kurs mit nur eimer Umfrage</summary>

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
</details>

<details>
<summary>Vardump von einem Kurs mit mehreren Umfragen</summary>

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
</details>

Letztlich ist noch zu bemerken, dass für über die `idnumber` verknüpfte Kurse immer ein Sommersemester als `semestertext` gewählt werden sollte,
entsprechend für über die Mappingfunktion verknüpfte Kurse ein Wintersemester, um gleiche Evasyskennungen zu vermeiden.
