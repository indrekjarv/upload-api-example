<?php

require 'bootstrap.php';

use Src\Controllers\MediaController;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

if ($uri[1] !== 'media' and $uri[1] !== 'upload') {
    header("HTTP/1.1 404 Not Found");
    exit();
}

$dataId = null;
if (isset($uri[2])) {
    $dataId = (int)$uri[2];
}

$requestMethod = $_SERVER["REQUEST_METHOD"];

$controller = new MediaController($dbConnection, $requestMethod, $dataId);
$controller->processRequest();
