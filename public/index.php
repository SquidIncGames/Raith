<?php
ini_set('display_errors', 'on');
error_reporting(E_ALL);
require __DIR__.'/../vendor/autoload.php';

$app = new Raith\MyApp();
$app->run();
?>