# BlaulichtSMS Alarm API

## Version
- V1.0: Erste Version (2016-08-12)
- V1.1: Alarm Query Endpoint
- V1.2: recipientConfirmation Parameter hinzugefügt
- V1.3: List Alarm Endpunkt hinzugefügt
- V1.4: Änderung des Alarm Data Element: Erweiterung der recipients, Deprecation der participants, Geolocation hinzugefügt (2017-01-19)
- V1.5: Erweiterung der API um die indexNumber

## Allgemein

### Encoding
Encoding ist immer UTF-8.

### Test Basis URL
https://api-staging.blaulichtsms.net/blaulicht

### Live Basis URL
https://api.blaulichtsms.net/blaulicht

##  Alarm API

Für die Verwendung dieser API muss man als "Automatisierter Alarmgeber" im System hinterlegt sein und erhält einen Benutzernamen und Passwort.

### Trigger Alarm
_**/api/alarm/v1/trigger**_

Um einen Alarm zu triggern muss man einen HTTP POST Request mit dem Header: `Content-Type: application/json` auf die oben angebene URL absenden.

- username: string - verpflichtend - Benutzername
- password: string - verpflichtend - Passwort
- customerId: string - verpflichtend - Kundennummer
- type: alarm | info - verpflichtend - Der Alarmtyp
- hideTriggerDetails: boolean - optional - Alarmgeberdetails nicht mitsenden
- alarmText: string - optional - Der Alarmtext
- indexNumber: integer- optional - Die Index Nummer dient zur Identifikation von zwei identen Alarmen. Achtung: Falls zwei oder mehr Alarme mit der selben Index Nummer ausgelöst werden, werden die späteren ignoriert.
- needsAcknowledgement: boolean - verpflichtend - Antwortfunktion
- startDate: string - optional - Das Startdatum für den Alarm, falls der Alarm in der Zukunft starten soll. Der Timestamp muss im UTC Format übertragen werden z.B. :`"2017-01-27T14:49:52.000Z"` 
- duration: integer - conditional - Dauer der Antwortfunktion in Minuten
- recipientConfirmation: boolean - optional - SMS Empfangsbestätigung ein- bzw. ausschalten (kostenpflichtig)
- recipientConfirmationTarget: string - optional - Empfänger für Report zu Empfangsbestätigungen
- template: string - optional - Alarmtextcode z.b. `"A1"`
- groupCodes: list of strings - optional - Alarmgruppen z.b. `["G1"]`
- additionalMsisdns: list of strings - optional - Nummern die zusätzlich alarmiert werden sollen z.B.: `["+4366412345678", "+4367612345678"]`
- coordinates: object of Type Coordinate - optional - Alarmkoordinaten

Ein Beispiel:

    {
        "username" : "myUser",
        "password" : "mySuperSecretPwd",
        "customerId" : "100027",
        "hideTriggerDetails" : false,
        "alarmText" : "Das ist ein Testalarm",
        "type" : "alarm",
        "needsAcknowledgement" : true,
        "duration" : 60,
        "recipientConfirmation" : false,
        "template" : "A1",
        "groupCodes" : ["G1", "G2"],
        "additionalMsisdns" : [],
        "coordinates" : {
          "lat" : 48.205587,
          "lon" : 16.342917
        }
    }

Im Erfolgsfall erhält man z.B. folgendes Resultat. Die möglichen Werte für **result** sind weiter unten angegeben. Im Fehlerfall ist dem Feld **description** eine Beschreibung des Fehlers zu entnehmen.

    {
        "result" : "OK",
        "alarmId" : "dakldjsfal-2343232-afsdaddfa-234",
        "customerId" : 100027,
        "description" : null
        "alarmData" : { siehe Beschreibung AlarmData Object }
    }


### QUERY ALARM
_**/api/alarm/v1/query**_

Um einen Alarm zu suchen muss man einen HTTP POST Request mit dem Header: `Content-Type: application/json` auf die oben angebene URL absenden.

- username: string - verpflichtend - Benutzername
- password: string - verpflichtend - Passwort
- customerId: string - verpflichtend - Kundennummer
- alarmid: string - verpflichtend - Die AlarmId (wird beim Auslösen eines Alarms zurückgegeben)

Ein Beispiel:

    {
        "username" : "myUser",
        "password" : "mySuperSecretPwd",
        "customerId" : "100027",
        "alarmId" : "dakldjsfal-2343232-afsdaddfa-234"
    }

Im Erfolgsfall erhält man z.B. folgendes Resultat. Die möglichen Werte für **result** sind weiter unten angegeben. Im Fehlerfall ist dem Feld **description** eine Beschreibung des Fehlers zu entnehmen.

    {
        "result" : "OK",
        "alarmId" : "dakldjsfal-2343232-afsdaddfa-234",
        "customerId" : 100027,
        "description" : "ok",
        "alarmData" :  { siehe Beschreibung AlarmData Object }
    }


### LIST ALARM
_**/api/alarm/v1/list**_

Um eine Liste von Alarmen zu erhalten muss man einen HTTP POST Request mit dem Header: `Content-Type: application/json` auf die oben angebene URL absenden. Man erhält eine Liste von AlarmData Objekten. Es werden maximal 100 Alarme geliefert - sortiert nach Enddatum des Alarms.

- username: string - verpflichtend - Benutzername
- password: string - verpflichtend - Passwort
- customerIds: list of string - verpflichtend - Liste von Kundennummern
- startDate: date in iso format - optional - Startdatum der Suche (alle Alarme mit End-Datum danach werden geliefert)
- endDate: date in iso format - optional - Enddatum der Suche (alle Alarme mit Start-Datum davor werden geliefert)

Ein Beispiel:

    {
        "username" : "myUser",
        "password" : "mySuperSecretPwd",
        "customerIds" : ["100027", "900027"],
        "startDate" : "2016-01-01T17:00:00.000Z",
        "endDate" : "2016-01-01T17:30:00.000Z"
    }

Im Erfolgsfall erhält man z.B. folgendes Resultat. Die möglichen Werte für **result** sind weiter unten angegeben. Im Fehlerfall ist dem Feld **description** eine Beschreibung des Fehlers zu entnehmen.

    {
        "result" : "OK",
        "description" : "ok",
        "alarms" :  [{ siehe Beschreibung AlarmData Object }]
    }

#### AlarmData
- customerId: Die Kundennummer zu der dieser Alarm gehört
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
- indexNumber: Die Index Nummer des Alarms


Ein Beispiel:

    {
        "customerId" : "100027",
        "alarmId" : "32849abcdef23343",
        "alarmGroups" : [ ], // Liste von AlarmGroup Elementen
        "alarmDate"  : "2016-01-01T17:30:21.345Z", // UTC Datum
        "endDate"  : "2016-01-01T17:30:21.345Z", // UTC Datum
        "authorName" : "Max Mustermann",
        "alarmText" : "Das ist ein Probealarm",
        "needsAcknowledgement" : true,
        "usersAlertedCount" : 10,
        "geolocation" : { }, // see GeoLocation object
        "recipients" : [ ], // Liste von AlarmRecipient Elementen
        "audioUrl" : null,
        "indexNumber": null
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

#### Ergebnis Codes
- OK
- MISSING_INPUT_DATA
- MISSING_CUSTOMER_ID
- MISSING_USERNAME
- INVALID_CUSTOMER_ID
- NOT_CONFIGURED_FOR_CUSTOMER
- UNKNOWN_USER
- NOT_AUTHORIZED
- UNAUTHORIZED_SENDER_ID
- DEACTIVATED
- INVALID_GROUP
- INVALID_TEMPLATE
- NOT FOUND
- UNKNOWN_ERROR
