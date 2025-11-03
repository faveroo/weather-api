<?php
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}
require '../vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$key = $_ENV['API_KEY'];
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

$previsionsResponse = $client->get("data/2.5/forecast", [
    'query' => [
        'lat'=> $lat,
        'lon' => $lon,
        'appid' => $key,
        'units' => 'metric',
        'cnt' => 6
    ]
]);

$previsions = json_decode($previsionsResponse->getBody(), true);
$_SESSION['previsions'] = $previsions['list'];
// echo "<pre>";
// print_r($_SESSION['previsions']);
// echo "<pre>";

// echo $weatherString;
header("Location: /");
exit;

} catch (RequestException $e) {
    $_SESSION['weather_error'] = $e->getMessage();
    header("Location: /");
    exit;
}

