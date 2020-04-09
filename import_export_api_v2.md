# safeREACH Import Export API

## Version
- v2.0: Initial Draft (2018-09-30)
- v2.1: Small API adjustments and (2019-03-01)
- v2.2: Add merge flag for import requests (2019-08-20)
- v2.3: Rename `customerOrGroupId` to `customerId` in data objects (2020-03-05)
- v2.4: Add optional deleteOnlyExternal flag for import requests (2020-04-09)

## General

### Encoding
UTF-8 encoding shall be used at all times.

### Test base URL
https://api-staging.blaulichtsms.net/blaulicht

### Live base URL
https://api.blaulichtsms.net/blaulicht

###  Usage

The API extends the possibility for managing users and groups on the [safeREACH web platform](https://start.safereach.net).
API requests are limited by the Fair Use Policy. 

### Authentication

For API usage your `customerOrGroupId`, an API user `username` and `password` are needed.

### Use cases / flags

The purpose of the API is to export recipients and their assigned groups to external data sources for comparison.
The identification of a recipient / group is done via an UUID version 4, referenced as `<UUIDv4>`.

The import API provides the following improvements compared to the [Import API Version 1](import_api_v1.md).
- recipient and group identification via an `externalId` (_VARCHAR(255)_)
- a `dryRun` flag, which allows data-set comparison and pre-import validation
- a `partial` flag, which allows partial imports (no deletion of unreferenced elements)
- a `merge` flag, which allows an initial merge with existing data (makes migration a lot easier)
- a `deleteOnlyExternal` flag, which will only delete recipients with an externalId if `partial` is `false` and externalIds are used

### Objects

#### RecipientData

- id: string - mandatory - `<UUIDv4>` - for new records and for external id usage leave empty
- externalId: string - optional - for id usage leave empty; mandatory for external id usage
- customerId: string - mandatory
- msisdn: string - mandatory - phone number with country code prefix in the following format: +4366412345678
- givenname: string - mandatory - first name
- surname: string - mandatory - last name
- email: string - optional - e-mail address
- comment: string- optional - e.g. division in organisation or other additional information
- groups: list of objects of the type `RecipientGroupParticipationData` - mandatory can be an empty list

#### RecipientGroupParticipationData

- groupId: string - mandatory e.g. "G1"

#### GroupData

- id: string - mandatory - `<UUIDv4>` - for new records or external id usage leave empty
- externalId: string - optional - for new records or id usage leave empty
- customerId: string - mandatory
- groupId: string - mandatory - group Id - the groupId has to start with a `G` followed by an int between G0 and G999999999 - The groupId can't be changed once it was created - only the name can be updated
- name: string - mandatory - name of the the group


### Import recipients - JSON

_**/api/public/v1/recipient/import**_

With a HTTP POST request with the header: `Content-Type: application/json` recipients can be imported.

- dryRun: boolean - optional - default `false`; defines if only a data-set comparison should be made (true) or if data should also be imported (false)
- useExternalId: boolean - optional - default `false`; enables the use of externalIds or safeREACH UUIDs
- partial: boolean - optional - default `false`; defines if records missing in the import data should be deleted from the existing data (false)
- merge: boolean - optional - default `false`; defines if existing entries should be merged based on the recipients msisdn. The `externalId` is mandatory and will be added to the recipient. Current group assignment will not be overwritten. Also the comment will not be overwritten.
- deleteOnlyExternal: boolean - optional - default `false`; defines if only recipients with an `externalId` should be considered for deletion
- recipients: list of objects of the type `RecipientData` - recipients


#### example

```json
{
    "customerOrGroupId" : "100027",
    "username" : "import",
    "password" : "mySuperSecretPwd",
    "dryRun": true,
    "externalId": false,
    "partial": false,
    "merge": false,
    "recipients" : [
        {
          "id": "<UUIDv4>",
          "externalId": "", 
          "customerId": "100027",
          "msisdn" : "+4366412345678",
          "givenname" : "Max",
          "surname" : "Mustermann",
          "email": null,
          "comment": "Division 1",
          "groups" : [
            {
                "groupId": "G1"
            },
            {
               "groupId": "G2"
            }            
          ]
        },
        {
          "id": "<UUIDv4>",
          "externalId": "",
          "customerId": "100027",
          "msisdn" : "+4367612345678",
          "givenname" : "Martina",
          "surname" : "Musterfrau",
          "email" : "martina.musterfrau@example.com",
          "comment": "Division 2",
          "groups" : []
        }
    ]
}
```

#### Response

HTTP 200 OK

```json
{
    "result" : "OK",
    "description" : null,
    "created": 1,
    "updated": 2,
    "deleted": 3,
    "merged": 0,
    "request": {
        "dryRun": true,
        "externalId": false,
        "partial": false,
        "merge": false
    }
}
```

The following errors can occur:

- HTTP 400 BAD Request: malformed JSON request received
- HTTP 401 Unauthorized: invalid credentials
- HTTP 403 Forbidden: missing permissions
- HTTP 409 Conflict: input data conflicting with current data set; see the description of the response for  more information


### Import recipients - CSV

_**/api/public/v1/recipient/import**_

The following query parameters can be set:

- dryRun: boolean - optional - default `false`
- useExternalId: boolean - optional - default `false`
- partial: boolean - optional - default `false`
- merge: boolean - optional - default `false`
- deleteOnlyExternal: boolean - optional - default `false`

The following headers have to be set:

- `Content-Type: text/csv`
- `X-CustomerId: 100027`
- `X-Username: myUser`
- `X-Password: mySuperSecretPwd`


The following columns have to be separated by `;`

- id: string - mandatory - `<UUIDv4>` - for new records or external id usage leave empty
- externalId: string - optional - for new records or id usage leave empty
- customerId: string - mandatory
- givenname: string - mandatory
- surname: string - mandatory
- msisdn: string - mandatory
- email: string - optional
- comment: string - optional - e.g. division ()

After "comment" group participation is listed by `groupId`.
`1` indicates group participation. `0` indicates no participation.

> As lines are separated by a newline, which will not be escaped, it is not possible to import values that contain a `;` or `\n`.


#### example

```csv
id;externalId;customerId;givenname;surname;msisdn;email;comment;G1;G2;G3 
<UUIDv4>;;100027;Max;Mustermann;+4366412345678;;;1;1;0 
<UUIDv4>;;100027;Martina;Musterfrau;+4367612345678;martina.musterfrau@example.com;;0;0;0
```

### Import groups - JSON
_**/api/public/v1/group/import**_

With a HTTP POST request with the header: `Content-Type: application/json` groups can be imported.

- customerOrGroupId: string - mandatory - customerOrGroupId
- username: string - mandatory - username
- password: string - mandatory - password
- dryRun: boolean - optional - default `false`
- useExternalId: boolean - optional - default `false`
- partial: boolean - optional - default `false`
- groups: List of objects of type GroupData - groups


#### example

```json
{
    "customerOrGroupId" : "100027",
    "username" : "import",
    "password" : "mySuperSecretPwd",
    "dryRun": true,
    "externalId": false,
    "partial": false,
    "merge": false,       
    "groups": [
        {
            "id": "<UUIDv4>",
            "externalId": "", 
            "customerId": "100027",
            "groupId": "G1",
            "name": "All employees"
        },
        {
          "id": "<UUIDv4>",
          "externalId": "",
          "customerId": "100027",
          "groupId": "G2",
          "name": "Crises managment"
        },
        {
          "id": "<UUIDv4>",
          "externalId": "", 
          "customerId": "100027",
          "groupId": "G3",
          "name": "IT"
        }
      ]
}
```

#### Response

HTTP 200 OK

```json
{
    "result" : "OK",
    "description": null,
    "created": 1,
    "updated": 2,
    "deleted": 3,
    "merged": 0,
    "request": {
        "dryRun": true,
        "externalId": false,
        "partial": false,
        "merge": false
    }
}
```

Following errors can occur:

- HTTP 400 BAD Request: malformed JSON request received
- HTTP 401 Unauthorized: invalid credentials
- HTTP 403 Forbidden: missing permissions
- HTTP 409 Conflict: input data conflicting with current data set; see the description of the response for  more information


### Import groups - CSV
_**/api/public/v1/group/import**_

The following query parameters can be set:

- dryRun: boolean - optional - default `false`
- useExternalId: boolean - optional - default `false`
- partial: boolean - optional - default `false`
- merge: boolean - optional - default `false`
- deleteOnlyExternal: boolean - optional - default `false`


The following headers have to be set:

- `Content-Type: text/csv`
- `X-Username: myUser`
- `X-Password: mySuperSecretPwd`

The following columns need to be separated by `;`

- id: string - mandatory - `<UUIDv4>` - for new records or external id usage leave empty
- externalId: string - optional - for new records or id usage leave empty
- customerId
- groupId
- name

#### example

    id;exteranlId;customerId;groupId;name
    <UUIDv4>;externalId;100027;G1;Sirenenalarm
    <UUIDv4>;externalId;100027;G2;Stiller Alar
    <UUIDv4>;externalId;100027;G3;Alle Kameraden



### Export recipients - CSV / JSON

_**/api/public/v1/group/{{customerOrGroupId}}/export**_

 or
 
_**/api/public/v1/recipient/{{customerOrGroupId}}/export**_


The following headers have to be set:

- `X-Username: myUser`
- `X-Password: mySuperSecretPwd`
- `Accept: text/csv` | `Accept: application/json`


Groups and recipients can be exported via JSON or CSV.

### response

HTTP 200 OK

```json
{
    "result": "OK",
    "description": null,
    "recipients": {
        <RecipientData>
    }
}
```

or

```json
{
    "result": "OK",
    "description" : null,
    "groups": {
        <GroupData>
    }
}
```

The CSV response will look like the import CSV examples.

The following errors can occur:

- HTTP 401 Unauthorized: invalid credentials
- HTTP 403 Forbidden: missing permissions

