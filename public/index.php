<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config.php';

use Controller\CFrontController;
require_once( '/../config.php');

$fc = new CFrontController();
$fc->run($_SERVER['REQUEST_URI']);