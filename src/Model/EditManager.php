<?php

namespace EasyCMS\src\Model;

use EasyCMS\src\Model\Entity\Page;

class EditManager extends Manager 
{

    public function getPagesByUserId(int $userId): Page
    {
        $sql = "SELECT * FROM personnages WHERE user_id=:id";
        $req = $this->_db->prepare( $sql );
        if( $req->execute(['id' => $userId])){
            $pageData = $req->fetch( \PDO::FETCH_ASSOC );
            $page = new Page($pageData);
            return $page;
        } else {
            return null;
        }
    }

}