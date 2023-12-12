<?php

namespace EasyCMS\src\Model;

use EasyCMS\src\Model\Entity\Page;

class EditManager extends Manager 
{

    public function getAllPages(): ?array
    {
        $listPages = [];
        $sql = "SELECT * FROM pages";
        $req = $this->dbManager->db->prepare( $sql );
        if( $req->execute()){
            $listPageData = $req->fetchAll( \PDO::FETCH_ASSOC );

            foreach( $listPageData as $pageData){
                $page = new Page($pageData);
                $listPages[] = $page;
                
            }
            
            return $listPages;
        } else {
            return null;
        }
    }

    public function getPageById($id): Page
    {
        $sql = "SELECT * FROM pages WHERE id=:id";
        $req = $this->dbManager->db->prepare( $sql );
        if( $req->execute( ['id' => $id] )){
            $pageData = $req->fetch( \PDO::FETCH_ASSOC );
            $page = new Page($pageData);           
            return $page;
        } else {
            return null;
        }
    }

    public function updatePage(Page $page): ?bool
    {
        if( $page ) {
            $sql = "UPDATE pages 
                    SET 
                        page_name=:pageName, 
                        is_home_page=:isHomePage, 
                        modification_date=CURRENT_TIME, 
                        is_published=:isPublished, 
                        id_user=:userId 
                    WHERE 
                        id=:id";
            $req = $this->dbManager->db->prepare( $sql );
            $state = $req->execute([
                ':id'           => $page->getId(),
                ':pageName'     => $page->getPageName(),
                ':isHomePage'   => intval($page->getIsHomePage()),
                ':isPublished'  => intval($page->getIsPublished()),
                ':userId'       => $page->getIdUser()
            ]);
            return $state;
        }

        return false;
    }

    public function createPage(Page $page): ?bool
    {
        $sql = "INSERT INTO pages (
                    page_name,
                    is_home_page,
                    creation_date,
                    modification_date,
                    is_published,
                    id_user
                ) VALUES (
                    :pageName,
                    :isHomePage,
                    CURRENT_TIME,
                    CURRENT_TIME,
                    :isPublished,
                    :userId
                )";
        $req = $this->dbManager->db->prepare( $sql );
        $state = $req->execute([
            ':pageName'     => $page->getPageName(),
            ':isHomePage'   => intval($page->getIsHomePage()),
            ':isPublished'  => intval($page->getIsPublished()),
            ':userId'       => $page->getIdUser()
        ]);
        return $state;
        
    }

}
