# BlaulichtSMS Dashboard API

## Version
- V1.0: Erste Version (2016-08-12)
- V1.1: Integrationen hinzugefügt, AlarmData Element angepasst (2017-01-19)

## Allgemein

### Encoding
Encoding ist immer UTF-8.

### Basis URL
https://api.blaulichtsms.net/blaulicht

## Dashboard API

Für die Verwendung der Dashboard API wird ein Dashboard User benötigt. 

### Login
_**/api/alarm/v1/dashboard/login**_

Um einen Login durchzuführen muss man einen HTTP POST Request mit dem Header: `Content-Type: application/json` auf die oben angebene URL absenden.
Die Benutzerdaten hierfür sind die selben, die für den Login auf [https://dashboard.blaulichtsms.net](https://dashboard.blaulichtsms.net/#/) verwendet werden.

    {
        "username" : "myUser",
        "password" : "mySuperSecretPwd",
        "customerId" : "123456"
    }

Nach einem erfolgreichen Login erhält man die Session ID:

    {
        "success" : true,
        "sessionId" : "lafjdfajdslfja89324u983u2894u89jlassdfj",
        "error" : null
    }

Die Session ID muss in einem Cookie / LocalStorage / SessionStorage gespeichert werden und für die nächsten Requests verwendet werden.

Im Fehlerfall erhält man folgende Antwort:

    {
        "success" : false,
        "sessionId" : null,
        "error" : "MISSING_INPUT_DATA" //für Error codes, siehe unten
    }

#### Error Codes
- MISSING_INPUT_DATA
- MISSING_PASSWORD
- MISSING_CUSTOMERID
- MISSING_USERNAME
- INVALID_CREDENTIALS

Es wird immer nur eine paralelle Session unterstützt. 

### Dasboard Informationen
_**/api/alarm/v1/dashboard/{{sessionId}}**_

Um Dashboard Informationen zu erhalten muss man einen HTTP GET Request auf die oben angebene URL absenden.


Wenn die Session abgelaufen ist, erhält man eine **HTTP 401 Unauthorized** Antwort.

Bei einer validen Session wird eine **HTTP 200 OK** Antwort im **json** Format mit folgendem Inhalt versendet:

    {
        "customerId" : "123456",
        "customerName" : "FF Test",
        "username" : "einsatzmonitor",
        "integrations" : [ ], // Liste an Integrationen
        "alarms" : [ ], // Liste von AlarmData Elementen
        "infos" : [ ] // List von AlarmData Elementen
    }

Es wird immer der letzte Alarm versendet und alle in den letzten 24 Stunden aktive Alarme

#### AlarmData
- alarmId: Der eindeutige Identifier des Alarms
- alarmGroups: Liste der Alarmgruppen Elemente (siehe AlarmGroup Object)
- alarmDate : Zeitpunkt der Alarmierung
- endDate: Ende der Antwortfunktion (falls aktiviert)
- authorName: Name des Alarmgebers der den Alarm ausgelöst hat
- alarmText: Der Alarmierungstext
- needsAcknowledgement: Ob die Antwortfunktion aktiviert ist
- usersAlertedCount: Anzahl der alarmierten Personen
- geolocation: Siehe GeoLocation Object
- recipients: Liste der Alarmteilnehmer - siehe AlarmRecipient Object
- audioUrl: Url zum Abspielen des Audio-Alarms, falls ein solcher ausgelöst wurde


Ein Beispiel:

    {
        "alarmId" : "32849abcdef23343",
        "alarmGroups" : [ ], // Liste von AlarmGroup Elementen
        "alarmDate"  : "2016-01-01T17:30:21.345Z", // UTC Datum
        "endDate"  : "2016-01-01T17:30:21.345Z", // UTC Datum
        "authorName" : "Max Mustermann",
        "alarmText" : "Das ist ein Probealarm",
        "needsAcknowledgement" : true,
        "usersAlertedCount" : 10,
        "geolocation" : { }, // GeoLocation Element
        "recipients" : [ ], // Liste von AlarmRecipient Elementen
        "audioUrl" : null
    }

#### AlarmGroup

    {
        "groupId" : "G1",
        "groupName" : "Alle Einsatzkräfte"
    }

#### AlarmRecipient

    {
        "id" : "2342343242342abcde32423423",
        "name" : "Martina Musterfrau",
        "msisdn" : "+4366412345678"
        "participation" : "yes", // eines von yes | no | uknown | pending
        "participationMessage" : "Komme 5 Minuten später",
        "functions": [ ], // Liste von AlarmFunction Elementen (Funktionen / Qualifikationen)
    }
    
#### AlarmFunction

    {
        "functionId": "123123789"
        "name": "Atemschutzgeräteträger"
        "order": 2
        "shortForm": "AGT"
        "backgroundHexColorCode": "#3164c2"
        "foregroundHexColorCode" "#ffffff"
    }

#### GeoLocation

    {
        "coordinates" : {
            "lat" : 17.34334,
            "lon" : 23.32343
        },
        "positionSetByAuthor" : true, // Wenn die Koordinaten durch den Autor gesetzt wurden
        "radius" : 10, // Radius in m (Kann auch null sein)
        "distance" : 10, // Distanz in m (Kann auch null sein)
        "address" : "Musterstraße 1, 1010 Wien" // Adresse im Textformat (Kann auch null sein)
    }


#### AlarmIntegration

    {
       "type" : "wasserkarte.info",
       "fields" : { // JSON Object mit folgender Information
           "apiKey" : "23423ldjsakfjdsflj34343"
       }
    }

# Einsatzmonitor Autologin

Die `sessionId` kann auch dazu verwendet werden, um einen automatischen Login beim Einsatzmonitor zu erzeugen. Hierzu muss nur das Dashboard mit folgender URL gestartet werden:

_**https://dashboard.blaulichtsms.net/#/login?token={{sessionId}}**_



