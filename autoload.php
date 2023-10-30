<?php

function loadClass( $class ) {

    $tmp = explode('\\', $class);
    $srcPath = $tmp[1];
    $controllerPath = $tmp[2];
    $classFile = $tmp[3];

    require $srcPath . '/' . $controllerPath . '/' . $classFile . '.php';

}

spl_autoload_register('loadClass');