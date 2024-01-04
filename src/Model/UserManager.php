<?php

namespace EasyCMS\src\Model;

use EasyCMS\src\Model\Entity\User;

class UserManager extends Manager
{
    public function getAllUsers(): ?array
    {
        $listUsers = [];
        $sql = "SELECT * FROM users";
        $req = $this->dbManager->db->prepare( $sql );
        if( $req->execute()){
            $listUserData = $req->fetchAll( \PDO::FETCH_ASSOC );

            foreach( $listUserData as $userData){
                $user = new User($userData);
                $listUsers[] = $user;
                
            }
            
            return $listUsers;
        } else {
            return null;
        }
    }

    public function getUserById($id): ?User
    {
        $sql = "SELECT * FROM pages WHERE id=:id";
        $req = $this->dbManager->db->prepare( $sql );
        if( $req->execute( ['id' => $id] )){
            $userData = $req->fetch( \PDO::FETCH_ASSOC );
            $user = new User($userData);           
            return $user;
        } else {
            return null;
        }
    }

    public function updateUser(User $user): ?bool
    {
        if( $user ) {
            $sql = "UPDATE users 
                    SET 
                        login=:login,
                        password=:password,
                        id_right=:id_right
                    WHERE 
                        id=:id";
            $req = $this->dbManager->db->prepare( $sql );
            $state = $req->execute([
                ':id'           => $user->getId(),
                ':login'        => $user->getLogin(),
                ':password'     => $user->getPassword(),
                ':id_right'     => $user->getIdRight()

            ]);
            return $state;
        }

        return false;
    }
}