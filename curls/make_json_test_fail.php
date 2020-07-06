<?php

$obj = [];

$filesize = filesize('fail1.jpg');

$fp = fopen('fail1.jpg', 'rb');
$binary = fread($fp, $filesize);
fclose($fp);

$obj['account_id'] = '2';
$obj['media_name'] = 'fail1.jpg';
$obj['media_size'] = $filesize;
$obj['media_type'] = 'image/jpeg';
$obj['media_content'] = base64_encode($binary);

$json = json_encode($obj);

$file = 'test-upload-data.json';
file_put_contents($file, $json);
