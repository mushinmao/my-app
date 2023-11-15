<?php
$container = require_once __DIR__ . '/../src/bootstrap.php';

$db = $container->get('db_manager');

//$shortener->setShortLink('shortlink');
$shortLinks = new \App\DB\Eloquent\ShortLinks();

$shortLinks->hash = 'sdasdasdasd';
$shortLinks->url = 'https://www.php.net/manual/ru/function.curl-getinfo.php';
$shortLinks->code = 'code';

$shortLinks->save();
//$shortener->convert('https://www.php.net/manual/ru/function.curl-getinfo.php');
