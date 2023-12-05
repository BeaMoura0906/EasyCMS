<!DOCTYPE html>

<?php

ini_set('display_errors', 1);
require_once('autoload.php');
require_once('vendor/autoload.php');
session_start();

$controllerPath = 'EasyCMS\\src\\Controller\\';

if (isset( $_REQUEST['controller'] )) {
    $controllerClassName = $controllerPath . ucfirst( $_REQUEST['controller']) . 'Controller';
    $controller = new  $controllerClassName();
} else {
    $controller = new EasyCMS\src\Controller\IndexController();
}

?>