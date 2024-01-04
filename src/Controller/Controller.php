<?php

namespace EasyCMS\src\Controller;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\Extension\DebugExtension;

abstract class Controller
{
    protected $twig; 
    protected $pathView = 'src/View';

    public function __construct()
    {

        $loader = new FilesystemLoader( $this->pathView );
        $this->twig = new Environment( $loader, [
            'debug' => true
        ]);
        $this->twig->addGlobal('session', $_SESSION);
        $this->twig->addExtension(new DebugExtension());

        if ( isset($_REQUEST['action']) ) {
            $action = $_REQUEST['action'] . 'Action';
            $this->$action();

        } else {
            $this->defaultAction();
        }

    }

    abstract public function defaultAction();

    protected function render($view, $data=[])
    {   
        
        extract( $data );
        $fileNameView = ucfirst( $view ) . 'View.twig';
        $filePath = $this->pathView . '/' . $fileNameView;
        if( file_exists( $filePath ) ) {
            echo $this->twig->render( $fileNameView, $data );
        } else {
            die('View file not exists');
        }
        
    }

}