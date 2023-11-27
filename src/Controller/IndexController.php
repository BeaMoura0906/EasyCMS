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
        $this->render('index');
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
        $loginSpace = true;
        $data = [
            'loginSpace' => $loginSpace,
            'message' => [
                'type' => 'warning',
                'message' => 'Erreur lors de la connexion !' 
            ]
        ];

        $login = $_REQUEST['login'];
        $password = $_REQUEST['password'];

        if( isset($_REQUEST['login']) && isset($_REQUEST['password']) ){
            $userId = $this->_manager->loginVerify($login, $password);
            if( $userId ){
                $_SESSION['userId'] = $userId;
                $this->render('dashboard', []);
            }
        }

        $this->render('index', $data);
    }
}