# blaulichtSMS Dashboard API

## Version
- V1.0: First version (2016-08-12)
- V1.1: added integrations, adapted AlarmData element (2017-01-19)

## General

### Encoding
Encoding shall be UTF-8.

### Base URL
https://api.blaulichtsms.net/blaulicht

## Dashboard API

The dashboard needed to use this API can be created on the web platform (start.blaulichtsms.net) under "Dashboard".

### Login
_**/api/alarm/v1/dashboard/login**_

To log in you need to send an HTTP POST request with header `Content-Type: application/json` to the above URL.
The login data are the same as for [https://dashboard.blaulichtsms.net](https://dashboard.blaulichtsms.net/#/).

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
       "fields" : { // JSON Object with the following information
           "apiKey" : "23423ldjsakfjdsflj34343"
       }
    }

# Dashboard automatic login

Die `sessionId` kann auch dazu verwendet werden, um einen automatischen Login beim Einsatzmonitor zu erzeugen. Hierzu muss nur das Dashboard mit folgender URL gestartet werden:

_**https://dashboard.blaulichtsms.net/#/login?token={{sessionId}}**_



