# blaulichtSMS Alarm API

## Version
- V1.0: First Version (2016-08-12)
- V1.1: Alarm Query Endpoint
- V1.2: recipientConfirmation parameter added
- V1.3: List Alarm end point added
- V1.4: Change of Alarm Data Element: extension of recipients, deprecation of participants, Geolocation added (2017-01-19)
- V1.5: extended the API by indexNumber

## General

### Encoding
Encoding shall be UTF-8.

### Test Base URL
https://api-staging.blaulichtsms.net/blaulicht

### Live Base URL
https://api.blaulichtsms.net/blaulicht

##  Alarm API

In order to be able to use this API, an "automatic alarm trigger" with username and password must be configured for the relevant customerId(s).

### Trigger Alarm
_**/api/alarm/v1/trigger**_

To trigger an alarm, send an HTTP POST REQUEST with header `Content-Type: application/json` to the above URL.

- username: string - mandatory - username
- password: string - mandatory - password
- customerId: string - mandatory - customer ID
- type: alarm | info - mandatory - event type
- hideTriggerDetails: boolean - optional - do not send details of alarm trigger
- alarmText: string - optional - content of alarm
- indexNumber: integer- optional - The index number serves to distinguish different alarms. A second alarm with the same index number will be ignored.
- needsAcknowledgement: boolean - mandatory - reply function
- startDate: string - optional - The date of an alarm in case it is to be triggered in the future. The format shall be UTC e.g. :`"2017-01-27T14:49:52.000Z"` 
- duration: integer - conditional - duration for which the reply function is enabled
- recipientConfirmation: boolean - optional - turn on/off confirmation that SMS was received (charges apply)
- recipientConfirmationTarget: string - optional - msisdn of recipient of SMS confirmation
- template: string - optional - Alarm text code e.g. `"A1"`
- groupCodes: list of strings - optional - Alarm group(s) e.g. `["G1"]`
- additionalMsisdns: list of strings - optional - additional msidns to be alerted e.g.: `["+4366412345678", "+4367612345678"]`
- coordinates: object of Type Coordinate - optional - coordinated of location of alarm
- geolocation: object of Type Geolocation - optional - instead of coordinates, a Geolocation object containing an address may also be provided. This address will then  be converted to coordinates e.g.: `{"address": "Getreidemartk 11, 1060 Wien"}` 

An example:

    {
        "username" : "myUser",
        "password" : "mySuperSecretPwd",
        "customerId" : "100027",
        "hideTriggerDetails" : false,
        "alarmText" : "This is a test",
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

The following is an exapmple of a successful API call.
A list of all possible values of **result** is provided further down. In case of an error the field **description** contains a description of the error.

    {
        "result" : "OK",
        "alarmId" : "dakldjsfal-2343232-afsdaddfa-234",
        "customerId" : 100027,
        "description" : null
        "alarmData" : { see description AlarmData Object }
    }


### QUERY ALARM
_**/api/alarm/v1/query**_

To search for an alarm, send an HTTP POST Request with header `Content-Type: application/json` to the URL mentioned above.

- username: string - mandatory - username
- password: string - mandatory - password
- customerId: string - mandatory - customer ID
- alarmid: string - mandatory - AlarmId (is returned upon triggering an alarm)

An example:

    {
        "username" : "myUser",
        "password" : "mySuperSecretPwd",
        "customerId" : "100027",
        "alarmId" : "dakldjsfal-2343232-afsdaddfa-234"
    }

The following is an exapmple of a successful API call.
A list of all possible values of **result** is provided further down. In case of an error the field **description** contains a description of the error.

    {
        "result" : "OK",
        "alarmId" : "dakldjsfal-2343232-afsdaddfa-234",
        "customerId" : 100027,
        "description" : "ok",
        "alarmData" :  { see description AlarmData object }
    }


### LIST ALARM
_**/api/alarm/v1/list**_

To get a list of alarms, send an HTTP POST Request with header `Content-Type: application/json` to the URL mentioned above. You will receive a list of AlarmData objects. Only 100 alarms will be returned, sorted by the end date of the alarm.

- username: string - mandatory - username
- password: string - mandatory - password
- customerIds: list of string - mandatory - list of customer IDs
- startDate: date in iso format - optional - start date for search (all alarms with later end date will be returned)
- endDate: date in iso format - optional - end date for search (all alarms with prior start date will be returned)

Ein Beispiel:

    {
        "username" : "myUser",
        "password" : "mySuperSecretPwd",
        "customerIds" : ["100027", "900027"],
        "startDate" : "2016-01-01T17:00:00.000Z",
        "endDate" : "2016-01-01T17:30:00.000Z"
    }

The following is an exapmple of a successful API call.
A list of all possible values of **result** is provided further down. In case of an error the field **description** contains a description of the error.

    {
        "result" : "OK",
        "description" : "ok",
        "alarms" :  [{ see description AlarmData object }]
    }

#### AlarmData
- customerId: Customer ID belonging to this alarm
- alarmId: unique identifier of the alarm
- alarmGroups: list of alarm group elements (see AlarmGroup object)
- alarmDate : time of alert
- endDate: end of reply function window (if activated)
- authorName: name of the alarm trigger that has triggered the alarm
- alarmText: the alarm text
- needsAcknowledgement: reply function active/inactive
- usersAlertedCount: nubmer of participants alerted
- geolocation: see GeoLocation object
- recipients: list of participants - see AlarmRecipient object
- audioUrl: Url of audio alarm, if applicable
- indexNumber: index number of the alarm


An example:

    {
        "customerId" : "100027",
        "alarmId" : "32849abcdef23343",
        "alarmGroups" : [ ], // list of AlarmGroup elements
        "alarmDate"  : "2016-01-01T17:30:21.345Z", // UTC date
        "endDate"  : "2016-01-01T17:30:21.345Z", // UTC date
        "authorName" : "John Doe",
        "alarmText" : "This is a test",
        "needsAcknowledgement" : true,
        "usersAlertedCount" : 10,
        "geolocation" : { }, // see GeoLocation object
        "recipients" : [ ], // list of AlarmRecipient elements
        "audioUrl" : null,
        "indexNumber": null
    }

#### AlarmGroup

    {
        "groupId" : "G1",
        "groupName" : "whole team"
    }

#### AlarmRecipient

    {
        "id" : "2342343242342abcde32423423",
        "name" : "Jeanny Doe",
        "msisdn" : "+4366412345678",
        "comment" : "Fire Brigade ABC" // optional
        "participation" : "yes", // yes | no | uknown | pending
        "participationMessage" : "Coming in 5 minutes",
        "functions": [ ], // list of AlarmFunction Elementen (functions / qualifications)
    }
    
#### AlarmFunction

    {
        "functionId": "123123789"
        "name": "respiratory equipment carriers"
        "order": 2
        "shortForm": "REC"
        "backgroundHexColorCode": "#3164c2"
        "foregroundHexColorCode" "#ffffff"
    }

#### GeoLocation

    {
        "coordinates" : {
            "lat" : 17.34334,
            "lon" : 23.32343
        },
        "positionSetByAuthor" : true, // if coordinates set by author
        "radius" : 10, // radius in m (may be null)
        "distance" : 10, // distance in m (may be null)
        "address" : "High Street 1, 1234 Metropolis" // textual address (may be null)
    }

#### Return Codes
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
