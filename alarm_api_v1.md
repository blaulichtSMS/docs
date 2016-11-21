#BlaulichtSms API Beschreibung für die automatische Alarmierung

## Version
- V1_0: Erste Version (2016-08-12)
- V1_1: Alarm Query Endpoint

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

#### Coordinate
- lat: double - mandatory- Breitengrad
- lon: double - mandatory - Längengrad

Ein Beispiel:

    {
        "lat" : 48.205587,
        "lon" : 16.342917
    }

#### AlarmData
- alarmid: Der eindeutige Identifier des Alarms
- alarmGroups: Liste der Alarmgruppen Elemente (siehe AlarmGroup Object)
- alarmDate : Zeitpunkt der Alarmierung
- endDate: Ende der Antwortfunktion (falls aktiviert)
- authorName: Name des Alarmgebers der den Alarm ausgelöst hat
- alarmText: Der Alarmierungstext
- needsAcknowledgement: Ob die Antwortfunktion aktiviert ist
- usersAlertedCount: Anzahl der alarmierten Personen
- coordinates: Siehe Coordinate Object
- positionSetByAuthor: Ob die Position vom Alarmgeber gesetzt wurde oder später hinzugefügt/berechnet
- recipients: Liste der Alarmteilnehme - siehe AlarmRecipient Object
- participation: Teilnahmeinformationen
- audioUrl: Url zum Abspielen des Audio-Alarms, falls ein solcher ausgelöst wurde


Ein Beispiel:

    {
        "alarmId" : "32849abcdef23343",
        "alarmGroups" : [ ], // list of AlarmGroup elements
        "alarmDate"  : "2016-01-01T17:30:21.345Z", // UTC date
        "endDate"  : "2016-01-01T17:30:21.345Z", // UTC date
        "authorName" : "Max Mustermann",
        "alarmText" : "Das ist ein Probealarm",
        "needsAcknowledgement" : true,
        "usersAlertedCount" : 10,
        "coordinates" : {
            "lat" : 17.34334,
            "lon" : 23.32343
        },
        "positionSetByAuthor" : true, //if coordinates are from alarm author
        "recipients" : [ ], // list of AlarmRecipient elements
        "participation" : {
            "yes" : [ ], // list of AlarmParticipation elements
            "no" : [ ], // list of AlarmParticipation elements
            "unknown" : [ ] // list of AlarmParticipation elements
        },
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
    }

#### AlarmParticipation

    {
        "recipient" : { }, // element of type AlarmRecipient
        "message" : "Ich komme später" // the response of the participant
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


