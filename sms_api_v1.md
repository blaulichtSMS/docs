# blaulichtSMS SMS API

## Version
- V1.0: first version (2017-07-24)

## General

### Target number:

**Austria:** +43 (0) 82822100

**Germany:** +49 (0) 1771783998

##  SMS API

If you want to be able to alert multiple customer IDs, your sender number must be assigned to an "automatic alarm trigger". Please contact us so we can provide you with an alarm trigger.
If you only want to alert your own customer ID, it is sufficient to create a manual alarm trigger on the web paltform (start.blaulichtsms.net) in section "Config" > "Alarm Trigger"

### Trigger alarm

An SMS triggering an alert consists of two parts, separated by a colon `:`.
To the left of the colon there is the **Alarm code**, to the right there is the payload (the **Alarm text**)

#### Alarm code

The alarm code consists of the following parts. You must at least provide a customer ID and a groups, the other parts are optional. Every code must begin with a "K".

- **K**{{customerId}} - mandatory -  customer ID e.g. `K100027`
- **G**{{groupId}} - mandatory - alarm group e.g. `G1`
- **A**{{templateId}} - optional - alarm test template (edited on web platform) e.g. `A1`
- **Q**0 - optional - participants can reply with YES/NO
- **I** - optional - type is "info", not "alarm"
- **Z** - optional - recipient confirmation is active
- **M**{{indexNumber}} - optional - Index nuber of an alert. An identical index number identifies two identical alarms. Caution: Do not use the same index number if you want to trigger individual alarms. Use it only to merge alarms.
- **T**{{additionalMsisdn}} - optional - additional msisdns to be alerted (not members of a group) e.g. `T+4366412345678`


#### Alarm text

You can provide the `alarmText` and `coordinates` (location of incident).


#### Koordinaten

Falls Koordinaten übermittelt werden sollen, können diese in folgenden Formaten an einer beliebigen Stelle im Alarmtext stehen. Diese Koordianten werden aus der SMS extrahiert und zum Alarm gespeichert.

**Via Längen- & Breitengrad (WGS84)**

```
[xx.xxxxxx,yy.yyyyyy]
```
oder
```
(xx.xxxxxx,yy.yyyyyy)
```
Zum Beispiel `(48.220778,16.3100209)` für Wien.

**Via Rechts- & Hochwert**
```
XY: xxxxxxx.xx / yyyyyyy.yy
```
Zum Beispiel `XY:4468503.333/5333317.780` für München.

Es gilt zu Beachten, dass sowohl der Rechtswert, als auch der Hochwert genau 7 Ziffern lang sein müssen. Optional kann mit einem Punkt getrennte Kommastellen einfügen. 

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
        "template" : "A1",
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
