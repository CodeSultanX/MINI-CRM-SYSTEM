<?php

session_start();

require_once('config.php');
require_once('functions.php');
require_once('autoloader.php');

$router = new app\Router();
$router->run();