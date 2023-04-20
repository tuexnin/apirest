<?php

/**
 * depuracion de errores(para mostrar errores)
 */

ini_set('display_errors',1);
ini_set('log_errors',1);
ini_set('error_log', "C:/wamp64/www/apirest/php_error_log");

/**
 * CORS
 */

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Content-Type: application/json; charset=utf-8');

/**
 * requerimientos
 */

require_once "controllers/routes.controller.php";

$index = new RoutesController();
$index ->index();