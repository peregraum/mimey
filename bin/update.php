#!/usr/bin/env php
<?php
declare(strict_types=1);

$updateUrl = "https://svn.apache.org/repos/asf/httpd/httpd/trunk/docs/conf/mime.types";
$destinationFile = dirname(__DIR__) . "/data/mime.types";

file_put_contents($destinationFile, file_get_contents($updateUrl));

include __DIR__ . '/generate.php';
