<?php
session_start();

define('IN_SCRIPT', true);

function __autoload($class) {
    require_once($class . '.class.php');
}

$Core = new Core();
$Entity = new Entity();
$Entity->prepare();

// var_dump($Entity->validEntity);

if (!$Core->SQL) {
    die('Could not connect to MySQL: ' . mysql_error());
}

echo 'Connection OK';