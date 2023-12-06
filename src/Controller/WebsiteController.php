<?php

namespace EasyCMS\src\Controller;

use EasyCMS\src\Model\IndexManager;

class WebsiteController extends Controller
{
    private $_manager;

    public function __construct()
    {
        $this->_manager = new IndexManager();
        parent::__construct();
    }

    public function defaultAction()
    {
        if( isset($_SESSION['userId'] ) ){
                $userId = $_SESSION['userId'];
                $data = [
                    'userId' => $userId
                ]; 
                $this->render('website', $data);
        } else {
            $this->render('website');
        }
        
    }
}