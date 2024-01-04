<?php

namespace EasyCMS\src\Controller;

use EasyCMS\src\Model\IndexManager;

class IndexController extends Controller
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
                $this->render('index');
        } else {
            $this->render('index');
        }
        
    }

    public function loginAction()
    {
        $loginSpace = true;
        $data = [
            'loginSpace' => $loginSpace
        ];
        $this->render('index', $data);
    }

    public function verifyLoginAction()
    {
        if( isset($_REQUEST['login']) && isset($_REQUEST['password']) ){
            $login = $_REQUEST['login'];
            $password = $_REQUEST['password'];
            $user = $this->_manager->loginVerify($login, $password);
            if( $user ){
                $_SESSION['userId'] = $user->getId();
                $_SESSION['userLogin'] = $user->getLogin();
                $_SESSION['userIdRight'] = $user->getIdRight();
                $data = [
                    'user' => $user
                ]; 
                header('Location:index.php?controller=profile');
                exit;
            } else {
                $loginSpace = true;
                $data = [
                    'loginSpace' => $loginSpace,
                    'message' => [
                        'type' => 'warning',
                        'message' => 'Erreur lors de la connexion !' 
                    ]
                ];
                $this->render('index', $data);
            }
        } 

    }

    public function logoutAction()
    {
        session_destroy();
        header('Location: .');
        exit;
    }
}