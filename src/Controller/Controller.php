<?php

namespace EasyCMS\src\Controller;

abstract class Controller
{
    public function __construct()
    {
        if ( isset($_REQUEST['action']) ) {
            $action = $_REQUEST['action'] . 'Action';
            $this->$action();
        } else {
            $this->defaultAction();
        }
    }

    abstract public function defaultAction();

    protected function render($view)
    {   
        $fileNameView = 'src/View/' . ucfirst( $view ) . 'View.php';
        if (file_exists($fileNameView)) {
            require_once $fileNameView;
        } else {
            die('View file not exists');
        }
        
    }

}