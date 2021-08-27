<?php
echo "hi";
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://40.123.209.82:202/api/Common/GetUserProfile',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{"HospitalLocationId":"1","FacilityId":"2","RegistrationNo":"100000074"}',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Basic Tm92aXRhc19QYXRpZW50QXBwX0FTUEw6QVNQTE5vdml0YXNAUGF0aWVudGFwcCM0MzIxISQ=',
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo '<pre>';
print_r(json_decode($response));
echo '</pre>';
die;
