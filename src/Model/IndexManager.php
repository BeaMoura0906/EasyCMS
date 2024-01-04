<?php

namespace EasyCMS\src\Model;

use EasyCMS\src\Model\Entity\User;

class IndexManager extends Manager 
{

    public function loginVerify(string $login, string $password): ?User
    {
        $sql = 'SELECT * FROM users WHERE login=:login';
        $req = $this->dbManager->db->prepare( $sql );
        if( $req->execute( ['login' => $login] ) ){
           $userData = $req->fetch( \PDO::FETCH_ASSOC );
           if( $userData && $password === $userData['password'] ){
                $user = new User($userData); 
                return $user;
           } else {
            return null;
           }
        } else {
            return null;
        }


    }

}