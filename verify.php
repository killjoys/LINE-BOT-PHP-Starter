<?php
$access_token = 'AYiADvPjYOy2x6IIf8u0uwvlQiG3lsURLeO6mAMXB9mmwjVeZgyVPfD0j/Dt3onHYCXs9dzCflr6yOhCxTy3J6aPuNi6b+cXBK0Y2y5YTJM1H6pFdpUNM1Ut+JpmfpHLImA4hTGj6gsYThKa4JbYtQdB04t89/1O/w1cDnyilFU=';

$url = 'https://api.line.me/v1/oauth/verify';

$headers = array('Authorization: Bearer ' . $access_token);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
$result = curl_exec($ch);
curl_close($ch);

echo $result;