<?php
$container = require_once __DIR__ . '/../src/bootstrap.php';

$shortener = $container->get('shortener');


$data = $shortener->createUrlData('https://www.php.net/manual/ru/function.curl-getinfo.php');

exit;



