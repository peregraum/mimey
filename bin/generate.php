#!/usr/bin/env php
<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

$mime_types_custom_text = file_get_contents(dirname(__DIR__) . '/mime.types.custom');
$mime_types_text = file_get_contents(dirname(__DIR__) . '/mime.types');

$generator = new \Mimey\MimeMappingGenerator($mime_types_custom_text . PHP_EOL . $mime_types_text);
$mapping = $generator->generateMapping();

$json = json_encode($mapping, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
file_put_contents(dirname(__DIR__) . '/dist/mime.types.json', $json);

$json_min = json_encode($mapping, JSON_THROW_ON_ERROR);
file_put_contents(dirname(__DIR__) . '/dist/mime.types.min.json', $json_min);
