#BlaulichtSms API Beschreibung für die automatische Alarmierung

## Version
- V1.0: Erste Version (2016-08-12)
- V1.1: Alarm Query Endpoint
- V1.2: Add recipientConfirmation parameters
- V1.3: Add List Endpoint
- V1.4: Adapt Alarm Data element - remove participation, geolocation (2017-01-19)

## Encoding
Encoding ist immer UTF-8.

## Test Basis URL
https://blaulicht-dev.alpspay.com/blaulicht

## Live Basis URL
https://api.blaulichtsms.net/blaulicht

### Trigger Alarm
/api/alarm/v1/trigger

Um einen Alarm zu triggern muss man einen POST mit Typ **application/json** auf die oben angebene URL absenden.

- username: string - mandatory - Benutzername
- password: string - mandatory - Passwort
- customerId: string - mandatory - Kundennummer
- hideTriggerDetails: boolean - optional - Alarmgeberdetails nicht mitsenden
- alarmText: string - optional - Der Alarmtext
- type: alarm | info - mandatory - Der Alarmtyp
- needsAcknowledgement: boolean - mandatory - Antwortfunktion
- duration: integer - conditional - Dauer der Antwortfunktion in Minuten
- recipientConfirmation: boolean - optional - SMS Empfangsbestätigung ein- bzw. ausschalten (kostenpflichtig)
- recipientConfirmationTarget: string - optional - Empfänger für Report zu Empfangsbestätigungen
- template: string - optional - Alarmtextcode z.b. A1
- groupCodes: list of strings - optional - Alarmgruppen z.b. G1
- additionalMsisdns: list of strings - optional - Nummern die zusätzlich alarmiert werden sollen z.B.: ["+4366412345678", "+4367612345678"]
- coordinates: object of Type Coordinate - optional - Alarmkoordinaten

Ein Beispiel:

    {
        "username" : "myUser",
        "password" : "mySuperSecretPwd",
        "customerId" : "100027",
        "hideTriggerDetails" : false,
        "indexNumber" : 1234,
        "alarmText" : "Das ist ein Testalarm",
        "type" : "alarm",
        "needsAcknowledgement" : true,
        "duration" : 60,
        "recipientConfirmation" : false,
        "template" : A1,
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
/api/alarm/v1/query

Um einen Alarm zu triggern muss man einen POST mit Typ **application/json** auf die oben angebene URL absenden.

- username: string - mandatory - Benutzername
- password: string - mandatory - Passwort
- customerId: string - mandatory - Kundennummer
- alarmid: string - mandatory - Die AlarmId (wird beim Auslösen eines Alarms zurückgegeben)

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
/api/alarm/v1/list

Um eine Liste von Alarmen zu erhalten muss man einen POST mit Typ **application/json** auf die oben angebene URL absenden. Man erhält eine Liste von AlarmData Objekten. Es werden maximal 100 Alarme geliefert - sortiert nach Enddatum des Alarms.

- username: string - mandatory - Benutzername
- password: string - mandatory - Passwort
- customerIds: list of string - mandatory - Liste von Kundennummern
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


#### Coordinate
- lat: double - mandatory- Breitengrad
- lon: double - mandatory - Längengrad

Ein Beispiel:

    {
        "lat" : 48.205587,
        "lon" : 16.342917
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


Ein Beispiel:

    {
        "customerId" : "100027",
        "alarmId" : "32849abcdef23343",
        "alarmGroups" : [ ], // list of AlarmGroup elements
        "alarmDate"  : "2016-01-01T17:30:21.345Z", // UTC date
        "endDate"  : "2016-01-01T17:30:21.345Z", // UTC date
        "authorName" : "Max Mustermann",
        "alarmText" : "Das ist ein Probealarm",
        "needsAcknowledgement" : true,
        "usersAlertedCount" : 10,
        "geolocation" : { }, // see GeoLocation object
        "recipients" : [ ], // list of AlarmRecipient elements
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
        "participation" : "yes", // one of yes, no, unknown, pending
        "participationMessage" : "Komme 5 Minuten später"
    }

#### GeoLocation

    {
        "coordinates" : {
            "lat" : 17.34334,
            "lon" : 23.32343
        },
        "positionSetByAuthor" : true, // whether coordinates are set by author or calculated,
        "radius" : 0, // radius in m - can be null
        "distance" : 0, // distance in m - can be null
        "address" : "Musterstraße 1, 1010 Wien" // address in text format
    }

#### Result Codes
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


