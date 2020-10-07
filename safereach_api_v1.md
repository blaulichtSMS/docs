# safeREACH API

## Version
- V1.0: Erste Version (2017-02-19)
- V1.1: Zusätzlicher Text hinzugefügt beim Auslösen hinzugefügt (2018-12-14)

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
- type: ScenarioType - Pflichtfeld (Entweder `PRACTICE` oder `LIVE`)
- additionalText: string - Optional - Optionaler Freitext

Ein Beispiel:

    {
        "username" : "myUser",
        "password" : "mySuperSecretPwd",
        "customerId" : "100027",
        "scenarioConfigId" : "32849abcdef23343",
        "type": "PRACTICE",
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

### Suche eines ausgelösten Szenarios
_**/api/alarm/v1/scenario/query**_

Mittels HTTP POST Request mit dem Header: `Content-Type: application/json` auf die oben angebene URL kann man 
nach einem ausgelösten Szenario suchen.


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


#### ScenarioData
- scenarioId: Die ID des Szenarios
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
        "authorId": "author1",
        "authorName": "Max Mustermann",
        "usersAlertedCount": 5,
        "creationDate": "2018-12-13T14:56:53.016Z",
        "startDate": "2018-12-13T14:56:53.016Z",
        "endDate": null,
        "alarms": [ AlarmData Objekte ]
    }
