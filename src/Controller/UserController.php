<?php

namespace EasyCMS\src\Controller;

use EasyCMS\src\Model\UserManager;


class UserController extends Controller
{
    private $_manager;

    public function __construct()
    {
        $this->_manager = new UserManager();
               
        parent::__construct();
    }

    public function defaultAction()
    {
        if( isset( $_SESSION['userId'] ) && $_SESSION['userIdRight'] == 1){
            if($listUsers = $this->_manager->getAllUsers()){
                $data = [
                    'listUsers' => $listUsers
                ];
                $this->render('user', $data);
            }
        } else {
            $this->render('index');
        }
        
    }

    public function updateUserAction(){
        
        
        if( isset($_REQUEST['userId']) ){
            $id = $_REQUEST['userId'];
            if( $selectedUser = $this->_manager->getUserById($id) ){
                $listUsers = $this->_manager->getAllUsers();
                $data = [
                    'listUsers' => $listUsers,
                    'selectedUser' => $selectedUser
                ]; 
                $this->render('user', $data);
            }
        
        }
    }

}