# blaulichtSMS Docs

## Alarm API

> Erstellen und Suchen von Alarmen für Automatisierte Alarmgeber und Leitstellen

* API Beschreibung: [Alarm API](./alarm_api_v1.md)
* PHP Beispielimplementation: [Alarm API PHP Beispiel](./examples/alarm-api/php/alarm-api-example.php)
* Häufig gestellte Fragen: [FAQ Alarm API](https://github.com/blaulichtSMS/docs/issues?q=label:alarm-api%20label:question)

Um API Zugangsdaten für einen Automatisierten Alarmgeber zu erhalten, nehmen Sie bitte mit uns [Kontakt](https://start2.blaulichtsms.net/de/contact) auf.

## SMS API

> Erstellen von Alarmen via SMS

* API Beschreibung: [SMS API](./sms_api_v1.md)
* Häufig gestellte Fragen: [FAQ SMS API](https://github.com/blaulichtSMS/docs/issues?q=label:sms-api%20label:question)

Um eine Freischaltung der Absendernummern zu erhalten, nehmen Sie bitte mit uns [Kontakt](https://start2.blaulichtsms.net/de/contact) auf.

## Dashboard API

> Informationen zum aktuellen Alarm und Teilnehmer für Feuerwehren mit einem Einsatzmonitor.

* API Beschreibung: [Dashboard API](./dashboard_api_v1.md)
* Javascript Beispielimplementation: [Dashboard API Javascript Beispiel](./examples/dashboard-api/javascript/)
* PHP Beispielimplementation: [Dashboard API PHP Beispiel](./examples/dashboard-api/php/)
* Häufig gestellte Fragen: [FAQ Dashboard API](https://github.com/blaulichtSMS/docs/issues?q=label:dashboard-api%20label:question)

Um die Dashboard API zu verwenden, benötigen Sie die Zugangsdaten zu Ihrem Einsatzmonitor, falls Sie diese noch nicht haben, nehmen Sie bitte mit uns [Kontakt](https://start2.blaulichtsms.net/de/contact) auf.

## Import API

> Import von Alarmteilnehmern, Alarmgebern und Gruppen via JSON oder csv.
>
> ACHTUNG: Diese API überschreibt alle vorhandenen Daten.

* API Beschreibung: [Import API](./import_api_v1.md)

## safeREACH API

**All safeREACH APIs moved to a separate repository: [safeREACH API](https://github.com/safeREACH/docs)**

## Fragen & Probleme

* Bei technischen Fragen erstellen Sie bitte ein Issue [hier](https://github.com/blaulichtSMS/docs/issues/new)
* Bei administrativen Fragen nehmen Sie bitte [hier mit uns Kontakt auf](https://blaulichtsms.net/support/#kontaktformular)

### Testzugang

Für alle Entwickler bieten wir einen kostenfreien (limitierten) Testzugang an. Falls Sie Interesse haben, nehmen sie bitte [hier mit uns Kontakt auf](https://blaulichtsms.net/support/#kontaktformular).

## Community Projekte

* HDMI-CEC Steuerung für den Einsatzmonitor https://github.com/stg93/blaulichtsms_einsatzmonitor_tv_controller
* [Node-RED](https://nodered.org/) Node für das Einspielen von blaulichtSMS Alarmen & Infos https://github.com/riederch/node-red-contrib-blaulicht-sms

## Deprecation

Wir unterstützen nur die API Felder, die in den API Beschreibungen definiert werden.

* **2021-09-27:** safeREACH API moved to own repository and changed TLD (old TLD EOL: 2021-11-01)
* **2017-01-23:** AlarmData > Participation wird durch die Erweiterung von AlarmData > Recipients deprecated. (EOL: 2017-07-23) 



