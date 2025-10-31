<?php
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}
require '../vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

$key = '92aaa2e829d963450b6ae15ecac68165';
$url = 'http://api.openweathermap.org/';

$client = new Client([
    'base_uri' => "$url"
]);

$city = $_GET['cidade'] ?? null;
$country = $_GET['pais'] ?? null;

try {
$geoResponse = $client->get("geo/1.0/direct", [
    'query' => [
        'q' => "$city,$country",
        'appid' => $key
        ]
    ]);

$data = json_decode($geoResponse->getBody(), true);

if(empty($data)) {
    die("Localização inválida");
}

$lat = $data[0]['lat'];
$lon = $data[0]['lon'];


$weatherResponse = $client->get("data/2.5/weather", [
    'query' => [
        'lat' => $lat,
        'lon' => $lon,
        'appid' => $key,
        'units' => "metric",
        'lang' => "pt_br"
    ]
]);
$weather = json_decode($weatherResponse->getBody(), true);
$_SESSION['weather'] = $weather;

// echo $weatherString;
header("Location: /");
exit;

} catch (RequestException $e) {
    $_SESSION['weather_error'] = $e->getMessage();
    header("Location: /");
    exit;
}

