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
            $userId = $_SESSION['userId'];
            $data = [
                'userId' => $userId
            ]; 
            $this->render('profile', $data);
        }
        
    }
}