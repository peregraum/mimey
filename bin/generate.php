#!/usr/bin/env php
<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/vendor/autoload.php';

//////////////////////////////////////////////////////////////

use Mimey\MimeMappingGenerator;

$mimeTypes = dirname(__DIR__) . "/data/mime.types";
$mimeTypesCustom = dirname(__DIR__) . "/data/mime.types.custom";
$destination = dirname(__DIR__) . "/dist/mime.types.json";
$destinationMin = dirname(__DIR__) . "/dist/mime.types.min.json";

$mimeTypesContent = file_get_contents($mimeTypesCustom);
$mimeTypesCustomContent = file_get_contents($mimeTypes);

$generator = new MimeMappingGenerator($mimeTypesCustomContent . PHP_EOL . $mimeTypesContent);
$mapping = $generator->generateMapping();

$json = json_encode($mapping, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
file_put_contents($destination, $json);

$json_min = json_encode($mapping, JSON_THROW_ON_ERROR);
file_put_contents($destinationMin, $json_min);
