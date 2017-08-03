# BlaulichtSMS SMS API

## Version
- V1.0: Erste Version (2017-07-24)

## Allgemein

### Zielrufnummern:

**Österreich:** +43 (0) 82822100

**Deutschland:** +49 (0) 1771783998

##  SMS API

Für die Verwendung dieser API für mehrere Kunden, muss die SMS Absendernummer in unserem System bei dem dazugehörigen "Automatisierten Alarmgeber" hinterlegt sein, nehmen Sie dazu bitte mit uns [Kontakt](https://start2.blaulichtsms.net/de/contact) auf.
Falls Sie nur Ihre Kundennummer Alarmierenwollen, reicht es, wenn Sie die Absendernummer als Alarmgeber unter "Konfiguration > Alarmgeber" selbst eintragen.

### Trigger Alarm

Eine Alarmierung über SMS besteht aus zwei Teilen, die mittels `:` getrennt sind. Der Teil vor dem ersten `:` ist der **Alarmcode** und der folgende Teil (hinter dem erstem `:`) ist der **Alarmtext**.
 
Der Alarmcode setzt sich aus verschiedenen Konfigurationen zusammen. Eine Konfiguration besteht immer aus einem Buchstaben und einer Zahl.

#### Alarmcode

- **K**{{customerId}} - verpflichtend -  Kundennummer z.B. `K100027`
- **G**{{groupId}} - verpflichtend - Alarmgruppe z.B. `G1`
- **A**{{templateId}} - optional - Alarmvorlage z.B. `A1`
- **Q**0 - optional - Falls vorhanden: Antwortfunktion ist aktiv
- **I**0 - optional - Falls vorhanden: Alarm ist eine Info
- **Z**0 - optional - Falls vorhanden: Zustellbestätigung ist aktiv.
- **M**{{indexNumber}} - optional - Index Nummer einer Alarmierung. Die Index Nummer dient zur Identifikation von zwei identen Alarmen. Achtung: Falls zwei oder mehr Alarme mit der selben Index Nummer ausgelöst werden, werden die späteren ignoriert.
- **T**{{additionalMsisdn}} - optional - Nummern die zusätzlich alarmiert werden sollen z.B. `T+4366412345678`


#### Alarmtext

Im Alarmtext werden der `alarmText` und die `coordinates` übergeben.


#### Koordinaten

Falls Koordinaten übermittelt werden sollen, können diese in folgendem Format an einer beliebigen Stelle im Alarmtext stehen. 

```
[xx.xxxx,yy.yyyy]
```
oder
```
(xx.xxxx,yy.yyyy)
```

Die Koordniaten müssen in Längen- und Breitengraden koodiert sein. z.B. `(48.220778,16.3100209)` für Wien.

#### Beispiele

##### Alamierung der Gruppe 1 und 2 des Kunden 100027 mit Koordinaten

via REST:
```
    {
        "customerId" : "100027",
        "alarmText" : "Das ist ein Testalarm",
        "type" : "alarm",
        "needsAcknowledgement" : true,
        "recipientConfirmation" : false,
        "template" : A1,
        "groupCodes" : ["G1", "G2"],
        "additionalMsisdns" : [],
        "coordinates" : {
          "lat" : 48.205587,
          "lon" : 16.342917
        }
    }
```

via SMS: 
```
K100027G1G2A1Q0:Das ist ein Testalarm(48.205587,16.342917)
```

##### Alamierung von mehreren zusätzlichen Alarmteilnehmern


via REST:
```
    {
        "customerId" : "100027",
        "alarmText" : "Das ist ein Testalarm",
        "type" : "alarm",
        "needsAcknowledgement" : true,
        "recipientConfirmation" : false,
        "groupCodes" : ["G1"],
        "additionalMsisdns" : ["+4366412345678", "+4367612345678"]
    }
```

via SMS: 
```
K100027G1Q0T+4366412345678T+4367612345678:Das ist ein Testalarm
```
