#BlaulichtSms API description for Alarm Dashboard

## Version Information
- V1.0: Initial Version (2016-08-12)
- V1.1: Add integrations, adapt alarm data (2017-01-19)

## Endpoints
There are two endpoints for the alarm dashboard. Encoding is always UTF-8.

### Login
/api/alarm/v1/dashboard/login

You need to perform a POST with the following **application/json** data to login and receive a valid session identifier.

    {
        "username" : "myUser",
        "password" : "mySuperSecretPwd",
        "customerId" : "K123456"
    }

Upon success you will receive a response in the following format:

    {
        "success" : true,
        "sessionId" : "lafjdfajdslfja89324u983u2894u89jlassdfj",
        "error" : null
    }

You should store the sessionId in a cookie/session storage to have it available for further requests.

Upon error you will receive e.g.:

    {
        "success" : false,
        "sessionId" : null,
        "error" : "MISSING_INPUT_DATA" //for error codes see below
    }

#### Error Codes
- MISSING_INPUT_DATA
- MISSING_PASSWORD
- MISSING_CUSTOMERID
- MISSING_USERNAME
- INVALID_CREDENTIALS

Please note that only one session is supported for a user at a certain time. All older sessions might be invalidated upon a successful login.

### Alarm Data Query
/api/alarm/v1/dashboard/<sessionId>

You need to perform a GET request using your sessionId in the path.


If the session has expired you will get a **HTTP 401 Unauthorized** response.

If the session is valid you will receive a **HTTP 200 OK** response in **application/json** format with the following content:

    {
        "customerId" : "K123456",
        "customerName" : "FF Test",
        "integrations" : [ ], //list of AlarmIntegration elements
        "alarms" : [ ], //list of AlarmData elements
        "infos" : [ ] //list of AlarmData elements
    }

Note that the latest alarm/info is always returned. In addition all alarms/infos active in the last 24 hours will be returned.

#### AlarmData
For each alarm a AlarmData element will be returned in the following format:

    {
        "alarmId" : "32849abcdef23343",
        "alarmGroups" : [ ], // list of AlarmGroup elements
        "alarmDate"  : "2016-01-01T17:30:21.345Z", // UTC date
        "endDate"  : "2016-01-01T17:30:21.345Z", // UTC date
        "authorName" : "Max Mustermann",
        "alarmText" : "Das ist ein Probealarm",
        "needsAcknowledgement" : true,
        "usersAlertedCount" : 10,
        "geolocation" : {
          "coordinates" : {
              "lat" : 17.34334,
              "lon" : 23.32343
          },
          "positionSetByAuthor" : true, //if coordinates are from alarm author,
          "radius" : 10, //radius in m - might be null
          "distance" : 10, // distance in m - might be null
          "address" : null // textual address string
        }
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
        "participation" : "yes", //one of yes | no | uknown | pending
        "participationMessage" : "Komme 5 Minuten später",
    }

#### AlarmIntegration

    {
       "type" : "wasserkarte.info",
       "fields" : { //json object containing config information
           "apiKey" : "23423ldjsakfjdsflj34343"
       }
    }

