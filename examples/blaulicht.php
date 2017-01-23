<?php

const TRIGGER_URL = 'https://api.blaulichtsms.net/blaulicht/api/alarm/v1/trigger';

const CUSTOMER_ID = '';
const USERNAME = '';
const PASSWORD = '';

const ADDITIONAL_MSISDNS = ['+43660123456789'];
const GROUPS = ['G1'];
const TEXT = 'Das ist ein test der API';

$request_body = [
    'alarmCode' => '1',
    'username' => USERNAME,
    'password' => PASSWORD,
    'hideTriggerDetails' => false,
    'customerId' => CUSTOMER_ID,
    'alarmText' => TEXT,
    'type' => 'alarm',
    'needsAcknowledgement' => true,
    'duration' => 60,
    'template' => '',
    'groupCodes' => GROUPS,
    'additionalMsisdns' => ADDITIONAL_MSISDNS
];

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, TRIGGER_URL);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json"
]);

curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request_body));
$curlResponse = curl_exec($ch);

curl_close($ch);

print_r($curlResponse);
