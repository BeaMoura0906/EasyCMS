<?php

namespace EasyCMS\src\Controller;

class ProfileController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function defaultAction()
    {
        if( isset($_SESSION['userId'] ) ){
            
            $this->render('profile');
        } 
        
    }
}