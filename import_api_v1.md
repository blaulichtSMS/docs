#BlaulichtSMS Import API

## Version
- V1.0: Erste Version (2016-08-12)
- V1.1: Alarmteilnehmer Import (2017-02-15)

## Allgemein

### Encoding
Encoding ist immer UTF-8.

### Test Basis URL
https://blaulicht-dev.alpspay.com/blaulicht

### Live Basis URL
https://api.blaulichtsms.net/blaulicht

##  Import API

Diese API ist ein zusätzliches Angebot zum bestehenden Import im [BlaulichtSMS Kundenbereich](https://start2.blaulichts.sms.net).


**Vorsicht**:
>Bei einem Import werden vorhandene Daten (z.B. Alarmteilnehmer) und alle Änderungen, die an diesen vorgenommen wurden gelöscht und mit den neuen Daten überschrieben. Diese Schnittstelle ist also vor allem in Fällen sinnvoll zu verwenden, wo die Verwaltung der Alarmteilnehmer in einem Drittsystem durchgeführt wird.
>
>Zusätzlich wird empfohlen die Importfunktion mit einem Testaccount / auf einem Testsystem zu testen, um Datenverlust zu vermeiden.

Die Authentifizierung findet über Kundennummer, Benutzernamen und Passwort statt.

### Import Alarmteilnehmer - JSON
_**/api/portal/v1/import/participants/json**_

Mittels HTTP POST Request mit dem Header: `Content-Type: application/json` auf die oben angebene URL können die Alarmteilnehmer eines Kunden importiert werden.

- customerId: string - Pflichtfeld - Kundennummer
- username: string - Pflichtfeld - Benutzername
- password: string - Pflichtfeld - Passwort
- participants: Liste von Objekten vom Typ PartcipipantData - Alarmteilnehmer

#### ParticipantData

- msisdn: string - Pflichtfeld - Telefonnummer im Format +4366412345678
- givenname: string - Pflichtfeld - Vorname
- surname: string - Pflichtfeld - Nachname
- email: string - Optional - E-Mail Adresse
- groups: Liste von strings - Pflichtfeld - Alarmgruppen

Sollten keine Alarmgruppen im Quellsystem verwaltet werden, empfehlen wir für alle Teilnehmer immer die Gruppe G1 anzugeben, da es sich dabei in der Regel um die allgemeine Alarmgruppe handelt.

#### Ein Beispiel

    {
        "customerId" : "100027",
        "username" : "myUser",
        "password" : "mySuperSecretPwd",
        "participants" : [
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


### Import Alarmteilnehmer - CSV
_**/api/portal/v1/import/participants/csv/{{customerId}}**_

Die CSV Schnittstelle funktioniert wie die JSON Schnittstelle oben - es gelten die gleichen Richtlinien hinsichtlich Pflichtfelder, Format und optionale Felder und es werden die gleichen Fehlercodes zurückgegeben.

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

Entscheidend ist die Reihenfolge der Daten, nicht die Beschriftung im Header. Die erste Zeile ist für den Header reserviert und wird beim Import übersprungen. Leere Zeilen am Ende des Files können zu Problemen führen und sollten nicht mitgeschickt werden. Gruppen sind per Beistrich (ohne Leerzeichen) zu trennen.

#### Ein Beispiel

    givenname;surname;msisdn;email;groups  
    Max;Mustermann;+4366412345678;;G1  
    Martina;Musterfrau;+4367612345678;martina.musterfrau@example.com;G1,G2