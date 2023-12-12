<?php

namespace EasyCMS\src\Model;

class IndexManager extends Manager 
{

    public function loginVerify(string $login, string $password)
    {
        $sql = 'SELECT * FROM users WHERE login=:login';
        $req = $this->dbManager->db->prepare( $sql );
        if( $req->execute( ['login' => $login] ) ){
           $loginData = $req->fetch( \PDO::FETCH_ASSOC );
           if( $loginData && $password === $loginData['password'] ){
                $userId = $loginData['id'];
                return $userId;
           } else {
            return null;
           }
        } else {
            return null;
        }


    }

}