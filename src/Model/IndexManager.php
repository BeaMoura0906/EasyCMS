<?php

namespace EasyCMS\src\Model;

use EasyCMS\src\Model\Entity\User;

class IndexManager extends Manager 
{

    public function getUserByLogin(string $login): ?User
    {
        $sql = 'SELECT * FROM users WHERE login=:login';
        $req = $this->dbManager->db->prepare( $sql );
        if( $req->execute( ['login' => $login] ) ){
           $userData = $req->fetch( \PDO::FETCH_ASSOC );
           if( $userData ){
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