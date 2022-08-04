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
The login data is the same as for [https://dashboard.blaulichtsms.net](https://dashboard.blaulichtsms.net/#/).

    {
        "username" : "myUser",
        "password" : "mySuperSecretPwd",
        "customerId" : "123456"
    }

After a successful login you receive a session ID:

    {
        "success" : true,
        "sessionId" : "lafjdfajdslfja89324u983u2894u89jlassdfj",
        "error" : null
    }

This session ID must be saved in a cookie / LocalStorage / SessionStorage gespeichert and will be used for all further requests.

In case of an error you receive the following reply

    {
        "success" : false,
        "sessionId" : null,
        "error" : "MISSING_INPUT_DATA" // error codes see below
    }

#### Error codes
- MISSING_INPUT_DATA
- MISSING_PASSWORD
- MISSING_CUSTOMERID
- MISSING_USERNAME
- INVALID_CREDENTIALS

Only one session at a time is possible.

### Dasboard information
_**/api/alarm/v1/dashboard/{{sessionId}}**_

In order to receive information from a dashboard you must send an HTTP GET request to the above URL.

If the session has expired, you will receive the reply **HTTP 401 Unauthorized**.

A valid session leads to the reply **HTTP 200 OK** as a **json** object with the following contents :

    {
        "customerId" : "123456",
        "customerName" : "FF Test",
        "username" : "dashboard",
        "integrations" : [ ], // list of integrations
        "alarms" : [ ], // list of AlarmData elements
        "infos" : [ ] // list of AlarmData elements
    }

All active alarms within the last 24 hours will be returned, as well as the last one, regardless of its age.

#### AlarmData
- alarmId: the unique identifier of the alarm
- alarmGroups: list of AlarmGroup elements (see AlarmGroup object)
- alarmDate : time of alert
- endDate: end of reply function time window (if activated)
- authorName: name of the trigger
- alarmText: the alarm text
- needsAcknowledgement: true = the reply function is active
- usersAlertedCount: number of alerted recipients
- geolocation: see GeoLocation object
- recipients: list of recipients - see AlarmRecipient object
- audioUrl: Url of audio alarm, if used


An example:

    {
        "alarmId" : "32849abcdef23343",
        "alarmGroups" : [ ], // list of AlarmGroup elements
        "alarmDate"  : "2016-01-01T17:30:21.345Z", // UTC date
        "endDate"  : "2016-01-01T17:30:21.345Z", // UTC date
        "authorName" : "John Doe",
        "alarmText" : "This is a test",
        "needsAcknowledgement" : true,
        "usersAlertedCount" : 10,
        "geolocation" : { }, // GeoLocation element
        "recipients" : [ ], // list of AlarmRecipient elementes
        "audioUrl" : null
    }

#### AlarmGroup

    {
        "groupId" : "G1",
        "groupName" : "whole fire department"
    }

#### AlarmRecipient

    {
        "id" : "2342343242342abcde32423423",
        "name" : "Jenny Jobber",
        "msisdn" : "+4366412345678"
        "participation" : "yes", // yes | no | uknown | pending
        "participationMessage" : "arriving in 5 minutes",
        "functions": [ ], // list of AlarmFunction elements (functions / qualifications)
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
        "positionSetByAuthor" : true, // true if set by trigger
        "radius" : 10, // radius in m (may be null)
        "distance" : 10, // distance in m (may be null)
        "address" : "High Street 99, 1234 Back-of-beyond" // address as test (may be null)
    }


#### AlarmIntegration

    {
       "type" : "wasserkarte.info",
       "fields" : { // JSON Object with the following information
           "apiKey" : "23423ldjsakfjdsflj34343"
       }
    }

# Dashboard automatic login

The `sessionId` may also be used to create an automatic login to the dashboard. Therefore, the dashboard must be accessed using the following URL:

_**https://dashboard.blaulichtsms.net/#/login?token={{sessionId}}**_



