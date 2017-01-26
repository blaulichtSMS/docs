<?php

const LOGIN_URL = 'https://api.blaulichtsms.net/blaulicht/api/alarm/v1/dashboard/login';

// FILL IN YOUR USER CREDENTIALS BELOW
const CUSTOMER_ID = '';
const USERNAME = '';
const PASSWORD = '';

$login_request_body = [
    'username' => USERNAME,
    'password' => PASSWORD,
    'customerId' => CUSTOMER_ID
];

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, DASHBOARD_URL);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json"
]);

curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($login_request_body));
$curlResponse = curl_exec($ch);

curl_close($ch);

print_r($curlResponse);
