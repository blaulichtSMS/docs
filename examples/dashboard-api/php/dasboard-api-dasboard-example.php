<?php

const DASHBOARD_BASE_URL = 'https://api.blaulichtsms.net/blaulicht/api/alarm/v1/dashboard/';

// FILL IN THE SESSION ID BELOW
const SESSION_ID = '';

const DASHBOARD_URL = DASHBOARD_BASE_URL . SESSION_ID;

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, DASHBOARD_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$curlResponse = curl_exec($ch);

curl_close($ch);

$dashboardResult = json_decode($curlResponse);

foreach ($dashboardResult->alarms as $alarm) {
    echo("<b> <u>Alarm:</u> $alarm->alarmText </b><br>");

    foreach ($alarm->recipients as $recipient) {
        echo("Name: $recipient->name <br> Status: $recipient->participation <br> Antwortnachricht: $recipient->participationMessage <br><br>");
    }
    echo("<hr>");
}