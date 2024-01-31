<?php

namespace EasyCMS\src\Controller;

use EasyCMS\src\Model\UserManager;
use EasyCMS\src\Model\Entity\User;
use EasyCMS\src\Model\Entity\Right;

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

    public function verifPassword($password, $passwordConfirm): ?array
    {
        if (strlen($password) < 8 || 
            !preg_match('/[0-9]/', $password) || 
            !preg_match('/[a-zA-Z]/', $password) || 
            !preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
            $message = [
                'type' => 'warning',
                'message' => 'Le mot de passe ne respecte pas les conditions demandées.'
            ];
            return $message;
        } else if ($password != $passwordConfirm) {
            $message = [
                'type' => 'warning',
                'message' => 'La confirmation ne correspond pas au mot de passe.'
            ];
            return $message;
        } else {
            return null;
        }

    }

    public function hashPassword($password): ?string
    {
        $passHash = sodium_crypto_pwhash_str(
            $password,
            SODIUM_CRYPTO_PWHASH_OPSLIMIT_INTERACTIVE,
            SODIUM_CRYPTO_PWHASH_MEMLIMIT_INTERACTIVE
        );

        return $passHash;
    }

    public function updateUserAction()
    {
        
        if( isset($_REQUEST['userId']) ){
            $id = $_REQUEST['userId'];
            if( $selectedUser = $this->_manager->getUserById($id) ){
                $listUsers = $this->_manager->getAllUsers();
                if($listRights = $this->_manager->getAllRights()){
                    $data = [
                        'listUsers'     => $listUsers,
                        'listRights'    => $listRights,
                        'selectedUser'  => $selectedUser
                    ];

                    $this->render('user', $data);
                }
            }
        
        }
    }

    public function updateUserValidAction()
    {
        $data = [];
        $data['listUsers'] = $this->_manager->getAllUsers();
        $data['listRights'] = $this->_manager->getAllRights(); 

        $login = htmlspecialchars( $_POST['login'] );
        $password = htmlspecialchars( $_POST['password'] );
        $passwordConfirm = htmlspecialchars( $_POST['passwordConfirm'] );
        $idRight = htmlspecialchars($_POST['right']);

        if( $id = $_REQUEST['userId']){
            $user = $this->_manager->getUserById( $id );
            $user->setLogin( $login );
            $user->setIdRight($idRight);
            $data['selectedUser'] = $user;
        
        }    

        if($message = $this->verifPassword( $password, $passwordConfirm )){
            $data['message'] = $message;
            $this->render('user', $data);
            die;
        }

        if( $passHash = $this->hashPassword($password) ){
            $user->setPassword( $passHash );
            if( $this->_manager->updateUser( $user ) ){
                $data['message']['type'] = 'success';
                $data['message']['message'] = 'Modification de l\'utilisateur effectuée !';
            } else {
                $data['message'] = [
                    'type' => 'warning',
                    'message' => 'Echec lors de la modification!'
                ];
            }
        } else {
            $data['message'] = [
                'type' => 'warning',
                'message' => 'Echec lors de la modification!'
            ];
        }
        

        
        $data['listUsers'] = $this->_manager->getAllUsers();      

        $this->render('user', $data);
    }

    public function deleteUserAction()
    {
        $message = [
            'type'      => 'warning',
            'message'   => 'Erreur lors de la suppression' 
        ];

        if ( isset($_REQUEST['id']) ) {
            $id = $_REQUEST['id'];
            if( $this->_manager->deleteUserById( $id ) ){
                $message['type'] = 'success';
                $message['message'] = 'Suppression de l\'utilisateur effectuée !';
                $selectedUser = null;
            } else {
                $selectedUser = $this->_manager->getUserById($id);
            }
        } 
        $listUsers = $this->_manager->getAllUsers();
        $listRights = $this->_manager->getAllRights();  
        $data = [
            'message'           => $message,
            'selectedUser'      => $selectedUser,
            'listUsers'         => $listUsers,
            'listRights'        => $listRights
        ];

        $this->render('user', $data);
    }

    public function createUserAction()
    {
        $data = [
            'listUsers' => $this->_manager->getAllUsers(),
            'listRights' => $this->_manager->getAllRights(),
            'createUser' => true
        ]; 
        $this->render('user', $data);
    }

    public function createUserValidAction()
    {
        $data = [];
        $data['listUsers'] = $this->_manager->getAllUsers();
        $data['listRights'] = $this->_manager->getAllRights(); 
        $data['createUser'] = true;

        $login = htmlspecialchars( $_POST['login'] );
        $password = htmlspecialchars( $_POST['password'] );
        $passwordConfirm = htmlspecialchars( $_POST['passwordConfirm'] );
        $idRight = htmlspecialchars($_POST['right']);

        $user = new User([]);
        $user->setLogin( $login );
        $user->setIdRight($idRight);

        if($message = $this->verifPassword( $password, $passwordConfirm )){
            $data['message'] = $message;
            $this->render('user', $data);
            die;
        }

        if( $passHash = $this->hashPassword($password) ){
            $user->setPassword( $passHash );
            if( $user = $this->_manager->insertUser( $user ) ){
                $data['message']['type'] = 'success';
                $data['message']['message'] = 'Ajout de l\'utilisateur effectué !';
            } else {
                $data['message'] = [
                    'type' => 'warning',
                    'message' => 'Echec lors de l\'ajout !'
                ];
            }
        } else {
            $data['message'] = [
                'type' => 'warning',
                'message' => 'Echec lors de l\'ajout !'
            ];
        }
        
        $data['listUsers'] = $this->_manager->getAllUsers();      

        $this->render('user', $data);
    }
}