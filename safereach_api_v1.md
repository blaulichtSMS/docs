# safeREACH API

## Version
- V1.0: Erste Version (2017-02-19)
- V1.1: Zusätzlicher Text hinzugefügt beim Auslösen hinzugefügt (2018-12-14)
- V1.2: Die Auslösetypen `LIVE` und `PRACTICE` wurden aus der API entfernt (2020-10-12)
- V1.3: Hinzufügen der Szenario List API mit Filtern für die Config Id und einem Date CuttOff (2020-10-12)

## Allgemein

### Encoding
Encoding ist immer UTF-8.

### Test Basis URL
https://api-staging.blaulichtsms.net/blaulicht

### Live Basis URL
https://api.blaulichtsms.net/blaulicht

##  safeREACH API

Diese API kann nur von Kunden des Produktes safeREACH (https://www.safereach.net/) genutzt werden.

In der safeREACH Web-Plattform kann man Szenarien hinterlegen. Diese Konfigurationen kann man über die API auslesen 
und auslösen.

Für die Verwendung dieser API muss ein "Automatisierter Alarmgeber" im System hinterlegt sein und erhält einen 
Benutzernamen und Passwort.

### Anzeigen von Szenario Konfigurationen
_**/api/alarm/v1/scenario/config/list**_

Mittels HTTP POST Request mit dem Header: `Content-Type: application/json` auf die oben angebene URL kann man 
Szenario Konfiguration abrufen.


- username: string - Pflichtfeld - Benutzername
- password: string - Pflichtfeld - Passwort
- customerIds: Liste von strings - Pflichtfeld - Kundennummer

Ein Beispiel:

    {
        "username" : "myUser",
        "password" : "mySuperSecretPwd",
        "customerIds" : ["100027"],
    }

Im Erfolgsfall erhält man z.B. folgendes Resultat. Die möglichen Werte für **result** sind hier zu finden: https://github.com/blaulichtSMS/docs/blob/master/alarm_api_v1.md#ergebnis-codes

    {
        "result" : "OK",
        "descirption": "Beschreibung",
        "configs" :  [ ScenarioConfigData Objekte ]
    }


Im Fehlerfall wird ein HTTP Fehlercode geliefert, sowie in der Regel auch eine textuelle Beschreibung des Problems, um das debuggen zu erleichtern.

Folgende Fehler können typischerweise auftreten:

- HTTP 400 BAD Request: Datenvalidierung fehlgeschlagen
- HTTP 401 Unauthorized: Problem bei der Authentifizierung
- HTTP 403 Forbidden: Problem bei der Authentifizierung

### Auslösen eines Szenarios
_**/api/alarm/v1/scenario/trigger**_

Mittels HTTP POST Request mit dem Header: `Content-Type: application/json` auf die oben angebene URL kann man 
Szenarios auslösen.


- username: string - Pflichtfeld - Benutzername
- password: string - Pflichtfeld - Passwort
- customerId: string - Pflichtfeld - Kundennummer
- scenarioConfigId: string - Pflichtfeld - Die ID der Konfiguration
- additionalText: string - Optional - Optionaler Freitext

Ein Beispiel:

    {
        "username" : "myUser",
        "password" : "mySuperSecretPwd",
        "customerId" : "100027",
        "scenarioConfigId" : "32849abcdef23343",
        "additionalText": "Zusatzinfo vom User"
        
    }

Im Erfolgsfall erhält man z.B. folgendes Resultat. Die möglichen Werte für **result** sind hier zu finden: https://github.com/blaulichtSMS/docs/blob/master/alarm_api_v1.md#ergebnis-codes

    {
        "result" : "OK",
        "descirption": "Beschreibung",
        "scenarioId" :  "123-ABC-asdfqwerasdf"
    }


Im Fehlerfall wird ein HTTP Fehlercode geliefert, sowie in der Regel auch eine textuelle Beschreibung des Problems, um das debuggen zu erleichtern.

Folgende Fehler können typischerweise auftreten:

- HTTP 400 BAD Request: Datenvalidierung fehlgeschlagen
- HTTP 401 Unauthorized: Problem bei der Authentifizierung
- HTTP 403 Forbidden: Problem bei der Authentifizierung

### Detail Suche eines ausgelösten Szenarios
_**/api/alarm/v1/scenario/query**_

Mittels HTTP POST Request mit dem Header: `Content-Type: application/json` auf die oben angebene URL kann man nach einem ausgelösten Szenario suchen.


- username: string - Pflichtfeld - Benutzername
- password: string - Pflichtfeld - Passwort
- customerId: string - Pflichtfeld - Kundennummer
- scenarioId: string - Pflichtfeld - Szenario Id, welches gesucht wird 

Ein Beispiel:

    {
        "username" : "myUser",
        "password" : "mySuperSecretPwd",
        "customerId" : "100027",
        "scenarioId" :  "123-ABC-asdfqwerasdf"
        
    }

Im Erfolgsfall erhält man z.B. folgendes Resultat. Die möglichen Werte für **result** sind hier zu finden: https://github.com/blaulichtSMS/docs/blob/master/alarm_api_v1.md#ergebnis-codes

    {
        "result" : "OK",
        "descirption": "Beschreibung",
        "scenarioData": { ScenarioData Objekt }
    }


Im Fehlerfall wird ein HTTP Fehlercode geliefert, sowie in der Regel auch eine textuelle Beschreibung des Problems, um das debuggen zu erleichtern.

Folgende Fehler können typischerweise auftreten:

- HTTP 400 BAD Request: Datenvalidierung fehlgeschlagen
- HTTP 401 Unauthorized: Problem bei der Authentifizierung
- HTTP 403 Forbidden: Problem bei der Authentifizierung


### Liste der ausgelösten Szenarios
_**/api/alarm/v1/scenario/list**_

Mittels HTTP POST Request mit dem Header: `Content-Type: application/json` auf die oben angebene URL kann man nach einem ausgelösten Szenario suchen.


- username: string - Pflichtfeld - Benutzername
- password: string - Pflichtfeld - Passwort
- customerId: string - Pflichtfeld - Kundennummer
- startedOrEndedAfter: date - Optional - Ein Datumsfilter, um nur Szenarien anzuzugeigen, die nach diesen Datum entweder erstellt oder beendet wurden
- scenarioConfigIds: string[] - Optional -  Listet nur Szenarien, die von einer der angegebenen Konfigurationen ausgelöst wurden

Ein Beispiel:

    {
        "username" : "myUser",
        "password" : "mySuperSecretPwd",
        "customerId" : "100027",
        "startedOrEndedAfter": "2020-10-12T09:18:53.740Z",
        "scenarioConfigIds": [
            "0123-ABC-asdfqwerasdf-CONFIG1",
            "0123-ABC-asdfqwerasdf-CONFIG2"
        ]
    }

Im Erfolgsfall erhält man z.B. folgendes Resultat. Die möglichen Werte für **result** sind hier zu finden: https://github.com/blaulichtSMS/docs/blob/master/alarm_api_v1.md#ergebnis-codes

Den Wert des Feldes `startedOrEndedBefore` kann man für die nächste Abfrage als `startedOrEndedAfter` Filter nutzen.

    {
        "result" : "OK",
        "descirption": "Beschreibung",
        "startedOrEndedAfter": "2020-10-12T09:18:53.740Z",
        "startedOrEndedBefore": "2020-10-12T09:48:54.068Z",
        "scenarioConfigIds": [
            "0123-ABC-asdfqwerasdf
        ]
        "scenarioPreviews": { ScenarioPreviewData Objekt }
    }


Im Fehlerfall wird ein HTTP Fehlercode geliefert, sowie in der Regel auch eine textuelle Beschreibung des Problems, um das debuggen zu erleichtern.

Folgende Fehler können typischerweise auftreten:

- HTTP 400 BAD Request: Datenvalidierung fehlgeschlagen
- HTTP 401 Unauthorized: Problem bei der Authentifizierung
- HTTP 403 Forbidden: Problem bei der Authentifizierung


### Models

#### ScenarioConfigData
- scenarioConfigId: Die ID einer Konfiguration
- customerId: Die Kundennummer zu der dieser Alarm gehört
- name: Der Name einer Konfiguration


Ein Beispiel:

    {
        "scenarioConfigId" : "32849abcdef23343",
        "customerId" : "100027",
        "name": "Szenario 1"
    }


#### ScenarioPreviewData
- scenarioId: Die ID des Szenarios
- scenarioConfigId: Die ID der Szenario Konfiguration, die ausgelöst wurde
- authorId: Die ID des Autors
- authorName: Der Name des Autors
- creationDate: Erstellungsdatum
- startDate: Startdatum
- endDate: Enddatum

Ein Beispiel:

    {
        "scenarioId": "123-ABC-asdfqwerasdf",
        "scenarioConfigId": "123-ABC-asdfqwerasdf-CONFIG",
        "authorId": "author1",
        "authorName": "Max Mustermann",
        "creationDate": "2018-12-13T14:56:53.016Z",
        "startDate": "2018-12-13T14:56:53.016Z",
        "endDate": null,
    }


#### ScenarioData
- scenarioId: Die ID des Szenarios
- scenarioConfigId: Die ID der Szenario Konfiguration, die ausgelöst wurde
- authorId: Die ID des Autors
- authorName: Der Name des Autors
- usersAlertedCount: Die Summe der insgesamt alarmierten Personen
- creationDate: Erstellungsdatum
- startDate: Startdatum
- endDate: Enddatum
- alarms: Liste an [AlarmData Objekten](alarm_api_v1.md#alarmdata)

Ein Beispiel:

    {
        "scenarioId": "123-ABC-asdfqwerasdf",
        "scenarioConfigId": "123-ABC-asdfqwerasdf-CONFIG",
        "authorId": "author1",
        "authorName": "Max Mustermann",
        "usersAlertedCount": 5,
        "creationDate": "2018-12-13T14:56:53.016Z",
        "startDate": "2018-12-13T14:56:53.016Z",
        "endDate": null,
        "alarms": [ AlarmData Objekte ]
    }
