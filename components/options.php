<?php

$options = json_decode(file_get_contents(__DIR__ . '/../json/countries_cities.json'), true);


return $options;