<?php

namespace EasyCMS\src\Controller;

use EasyCMS\src\Model\EditManager;

class EditController extends Controller
{
    private $_manager;

    public function __construct()
    {
        $this->_manager = new EditManager();
        parent::__construct();
    }

    public function defaultAction()
    {
        if( isset($_SESSION['userId'] ) ){
                $userId = $_SESSION['userId'];
                $data = [
                    'userId' => $userId
                ]; 
                $this->render('edit', $data);
        } else {
            $this->render('edit');
        }
        
    }
}