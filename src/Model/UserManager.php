<?php

namespace EasyCMS\src\Model;

use EasyCMS\src\Model\Entity\User;
use EasyCMS\src\Model\Entity\Right;

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
        $sql = "SELECT * FROM users WHERE id=:id";
        $req = $this->dbManager->db->prepare( $sql );
        if( $req->execute( ['id' => $id] )){
            $userData = $req->fetch( \PDO::FETCH_ASSOC );
            $user = new User($userData);           
            return $user;
        } else {
            return null;
        }
    }

    public function getAllRights(): ?array
    {
        $listRights = [];
        $sql = "SELECT * FROM rights";
        $req = $this->dbManager->db->prepare( $sql );
        if( $req->execute()){
            $listRightsData = $req->fetchAll( \PDO::FETCH_ASSOC );

            foreach( $listRightsData as $rightData){
                $right = new Right($rightData);
                $listRights[] = $right;
                
            }
            
            return $listRights;
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

    public function deleteUserById($id): bool
    {
        $sql = "DELETE FROM users WHERE id =:id";

        try {
            $req = $this->dbManager->db->prepare($sql);
            $req->execute(['id' => $id]);

            // Vérifiez le nombre de lignes affectées pour confirmer la suppression
            $rowCount = $req->rowCount();

            return $rowCount > 0;
        } catch (\PDOException $e) {
            // Gérer l'exception selon les besoins
            echo "Erreur PDO : " . $e->getMessage();
            return false;
        }
    }

    public function insertUser(User $user): ?User
    {
        $sql = "INSERT INTO users (
                    login,
                    password,
                    id_right
                ) VALUES (
                    :login,
                    :password,
                    :id_right
                )";
        $req = $this->dbManager->db->prepare( $sql );
        $state = $req->execute([
            ':login'        => $user->getLogin(),
            ':password'     => $user->getPassword(),
            ':id_right'     => $user->getIdRight()
        ]);
        
        if( !$state ) {
            return null;
        } else {
            $idUser = $this->dbManager->db->lastInsertId();
            $user->setId($idUser);
            
            return $user;
        }  
    }
}