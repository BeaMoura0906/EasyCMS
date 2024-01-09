<?php

namespace EasyCMS\src\Controller;

use EasyCMS\src\Model\IndexManager;

class IndexController extends Controller
{
    private $_manager;
    private $userManager;

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
        $data=[];
        if( isset( $_POST['login'] ) && isset( $_POST['password'] ) ) {
 
            if( $user = $this->_manager->getUserByLogin( $_POST['login'] ) ) {
                if( sodium_crypto_pwhash_str_verify( $user->getPassword('password'), $_POST['password']) ) {
                    $_SESSION['userId'] = $user->getId();
                    $_SESSION['userLogin'] = $user->getLogin();
                    $_SESSION['userIdRight'] = $user->getIdRight();
                    $data = [
                        'user' => $user
                    ]; 
                    header('Location:index.php?controller=profile');
                    exit;
                } else {
                    $_SESSION['login'] = $user->getLogin();
                    $data['message'] = [
                        'type'  => 'warning',
                        'message'  => 'Le mot de passe est incorrect'
                    ];
                    $data['loginSpace'] = true;
                }
            } else {
                $data['message'] = [
                    'type'  => 'warning',
                    'message'  => 'Le login est incorrect'
                ];
                $data['loginSpace'] = true;
            }
        } 

        $this->render('index', $data);

    }

    public function logoutAction()
    {
        session_destroy();
        header('Location: .');
        exit;
    }
}