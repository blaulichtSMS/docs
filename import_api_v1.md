# blaulichtSMS participant import API

## Version
- V1.0: first version (2016-08-12)
- V1.1: participant import (2017-02-15)
- V1.2: group import (2017-08-31)
- V1.3: alarm trigger import (2019-11-11)

## General

### Encoding
The encoding shall be UTF-8.

### Test base URL
https://api-staging.blaulichtsms.net/blaulicht

### Live base URL
https://api.blaulichtsms.net/blaulicht

##  Import API

This API provides and attitional facility to the import on the [web platform](https://start.blaulichtsms.net).


**Caution**:
>Upon import all existing data (recipients, groups, assigments of triggers to groups and of recipients to groups) will be overwritten with new data.
>This interface makes sense if the administration of participants is done by an external system. The external system periodically updates the data in blaulichtSMS.
>
>It is recommendend to first try the import with a dummy account in order to prevent loss of data. Please contact us for a dummy account.

Authentication is done by customer ID, user name, and password.

### Import of recipients - JSON
_**/api/portal/v1/import/participants/json**_

To import recipients for a customer ID, send an HTTP POST request with header: `Content-Type: application/json` to the above URL.
- customerId: string - mandatory - customer ID
- username: string - mandatory - user name
- password: string - mandatory - password
- participants: list of PartcipipantData objects

#### ParticipantData

- msisdn: string - mandatory - telephone number in format +(countrycode)(number), e.g. +445553939
- givenname: string - mandatory - given name
- surname: string - mandatory - surname
- email: string - optional - e-mail address
- groups: list of strings (alarm groups) - mandatory

If the external system does not distinguish between groups, it is recommended to import all praticipants to groups G1.

#### An example

    {
        "customerId" : "100027",
        "username" : "import",
        "password" : "mySuperSecretPwd",
        "participants" : [
            {
              "msisdn" : "+4366412345678",
              "givenname" : "John",
              "surname" : "Doe",
              "email" : null,
              "groups" : ["G1"]
            },
            {
              "msisdn" : "+4367612345678",
              "givenname" : "Joanne",
              "surname" : "Doe",
              "email" : "joanne@doe.com",
              "groups" : ["G1", "G2"]
            }
        ]
    }

In case of success the reply is HTTP 200 OK without further content.

In case of an error one of the following HTTP error codes will be returned to allow easier debgging:

- HTTP 400 BAD Request: data validation failed
- HTTP 401 Unauthorized: error in authentication
- HTTP 403 Forbidden: error in authentication


### Import participants - CSV
_**/api/portal/v1/import/participants/csv/{{customerId}}**_

The CSV interface works in the same way as the JSON interface above. The same rules regarding mandatory fields, optional fields, and error codes apply. Note the different URL.

The following header needs to be included in the HTTP POST request:

- `Content-Type: text/csv`
- `X-Username: myUser`
- `X-Password: mySuperSecretPwd`

The following columns (separated by **;**) will be read:

- givenname
- surname
- msisdn
- email
- groups

It is important to keep this sequence of columns, as the first row i.e. the heading/label of the columns will be disregarded.
Trailing empty lines may lead to problems, so remove them. Groups shall be separated by comma, not space.

#### An example

    givenname;surname;msisdn;email;groups  
    John;Doe;+4366412345678;;G1  
    Joanne;Doe;+4367612345678;joane@doe.com;G1,G2


### Import Alarmgeber - JSON
> Das Importieren von Alarmgebern funktioniert analog zum Importieren von Alarmteilnehmern, im JSON Request muss man die Alarmeber als `trigger` übergeben. 
_**/api/portal/v1/import/trigger/json**_

Mittels HTTP POST Request mit dem Header: `Content-Type: application/json` auf die oben angebene URL können die Alarmgeber eines Kunden importiert werden.

- customerId: string - mandatory - Kundennummer
- username: string - mandatory - Benutzername
- password: string - mandatory - Passwort
- trigger: Liste von Objekten vom Typ PartcipipantData - Alarmgeber

#### ParticipantData

- msisdn: string - mandatory - Telefonnummer im Format +4366412345678
- givenname: string - mandatory - Vorname
- surname: string - mandatory - Nachname
- email: string - Optional - E-Mail Adresse
- groups: Liste von strings - Pflichtfeld - Alarmgruppen

Sollten keine Alarmgruppen im Quellsystem verwaltet werden, empfehlen wir für alle Alarmeber immer die Gruppe G1 anzugeben, da es sich dabei in der Regel um die allgemeine Alarmgruppe handelt.

#### Ein Beispiel

    {
        "customerId" : "100027",
        "username" : "import",
        "password" : "mySuperSecretPwd",
        "trigger" : [
            {
              "msisdn" : "+4366412345678",
              "givenname" : "Max",
              "surname" : "Mustermann",
              "email" : null,
              "groups" : ["G1"]
            },
            {
              "msisdn" : "+4367612345678",
              "givenname" : "Martina",
              "surname" : "Musterfrau",
              "email" : "martina.musterfrau@example.com",
              "groups" : ["G1", "G2"]
            }
        ]
    }

Im Erfolgsfall erhält man HTTP 200 OK ohne Inhalt.

Im Fehlerfall wird ein HTTP Fehlercode geliefert, sowie in der Regel auch eine textuelle Beschreibung des Problems, um das debuggen zu erleichtern.

Folgende Fehler können typischerweise auftreten:

- HTTP 400 BAD Request: Datenvalidierung fehlgeschlagen
- HTTP 401 Unauthorized: Problem bei der Authentifizierung
- HTTP 403 Forbidden: Problem bei der Authentifizierung


### Import Alarmgeber - CSV
_**/api/portal/v1/import/trigger/csv/{{customerId}}**_

The CSV interface works in the same way as the JSON interface above. The same rules regarding mandatory fields, optional fields, and error codes apply. Note the different URL.

The following header needs to be included in the HTTP POST request:

Folgende Header müssen bei dem HTTP POST Request auf die CSV Schnittstelle inkludiert werden:

- `Content-Type: text/csv`
- `X-Username: myUser`
- `X-Password: mySuperSecretPwd`

Folgende Spalten (getrennt durch **;**) werden eingelesen:

- givenname
- surname
- msisdn
- email
- groups

It is important to keep this sequence of columns, as the first row i.e. the heading/label of the columns will be disregarded.
Trailing empty lines may lead to problems, so remove them. Groups shall be separated by comma, not space.ohne Leerzeichen) zu trennen.

#### Ein Beispiel

    givenname;surname;msisdn;email;groups  
    Max;Mustermann;+4366412345678;;G1  
    Martina;Musterfrau;+4367612345678;martina.musterfrau@example.com;G1,G2


### Import Gruppen - JSON
_**/api/portal/v1/import/groups/json**_

Mittels HTTP POST Request mit dem Header: `Content-Type: application/json` auf die oben angebene URL können die Gruppen eines Kunden importiert werden.

- customerId: string - mandatory - Kundennummer
- username: string - mandatory - Benutzername
- password: string - mandatory - Passwort
- groups: Liste von Objekten vom Typ GroupData - Gruppen

#### GroupData

- name: string - mandatory - Name der Gruppe
- groupId: string - mandatory - GruppenId - Die Gruppen ID muss mit einem G beginnen und zwischen G0 und G999999999 liegen
- redo: optional int - Optional - Ob eine Alarmwiederholung für diese Gruppe stattfinden soll (default: 0)
- redoInterval: long - Optional - Wie oft eine Alarmwiederholung für diese Gruppe stattfinden soll (default: 0)


#### Ein Beispiel

    {
        "customerId" : "100027",
        "username" : "import",
        "password" : "mySuperSecretPwd",
        "groups": [
            {
              "name": "Sirenenalarm",
              "groupId": "G1"
            },
            {
              "name": "Stiller Alarm",
              "groupId": "G2"
            },
            {
              "name": "Alle Kameraden",
              "groupId": "G3"
            }
          ]
    }

Im Erfolgsfall erhält man HTTP 200 OK ohne Inhalt.

Im Fehlerfall wird ein HTTP Fehlercode geliefert, sowie in der Regel auch eine textuelle Beschreibung des Problems, um das debuggen zu erleichtern.

Folgende Fehler können typischerweise auftreten:

- HTTP 400 BAD Request: Datenvalidierung fehlgeschlagen
- HTTP 401 Unauthorized: Problem bei der Authentifizierung
- HTTP 403 Forbidden: Problem bei der Authentifizierung


### Import groups - CSV
> Upon imorting new groups, all previous groups will be deleted as well as the assignment of participants to groups. 
_**/api/portal/v1/import/groups/csv/{{customerId}}**_

The CSV interface works in the same way as the JSON interface above. The same rules regarding mandatory fields, optional fields, and error codes apply. Note the different URL.

The following header needs to be included in the HTTP POST request:

- `Content-Type: text/csv`
- `X-Username: myUser`
- `X-Password: mySuperSecretPwd`

The following columns (separated by **;**) will be read:

- groupId
- name
- redo
- redoInterval

It is important to keep this sequence of columns, as the first row i.e. the heading/label of the columns will be disregarded.
Trailing empty lines may lead to problems, so remove them. Groups shall be separated by comma, not space.

#### An example

    groupId;name;redo;redoInterval
    G1;siren;;
    G2;silent alert;;
    G3;whole brigade;;
