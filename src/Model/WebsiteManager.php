<?php

namespace EasyCMS\src\Model;

use EasyCMS\src\Model\Entity\Page;
use EasyCMS\src\Model\Entity\Content;
use EasyCMS\src\Model\Entity\ContentType;
use EasyCMS\src\Model\Entity\Navigation;
use EasyCMS\src\Model\Entity\Position;

class WebsiteManager extends Manager 
{
    public function getPublishedHomePage()
    {        
        $sql = "SELECT * FROM pages WHERE is_home_page = 1 AND is_published = 1";
        $req = $this->dbManager->db->prepare( $sql );
        if( $req->execute()){
            $pageData = $req->fetch( \PDO::FETCH_ASSOC );
            $page = new Page($pageData);           
            return $page;
        } else {
            return null;
        }
    }


    public function getPublishedContentsByIdPage($idPage): ?array
    {
        $sql = "SELECT
                    c.id,
                    c.content_name,
                    c.content_description,
                    c.creation_date,
                    c.modification_date,
                    c.is_published,
                    c.id_user,
                    p.id AS position_id,
                    p.position_number,
                    pg.id AS page_id,
                    pg.page_name,
                    pg.is_home_page,
                    pg.is_published AS pg_is_published,
                    ct.id AS content_type_id,
                    ct.content_type_name,
                    ct.content_type_description
                FROM
                    contents c                    
                INNER JOIN
                    content_types ct ON c.id_content_type = ct.id
                INNER JOIN
                    positions p ON c.id_position = p.id
                INNER JOIN
                    pages pg ON p.id_page = pg.id
                WHERE
                    p.id_page = :idPage AND c.is_published = 1";

        try {
            $req = $this->dbManager->db->prepare($sql);

            if ($req->execute(['idPage' => $idPage])) {
                $results = $req->fetchAll(\PDO::FETCH_ASSOC);
                $listContents = [];

                foreach ($results as $result) {
                    $content = new Content($result);
                    $contentType = new ContentType([
                        'id' => $result['content_type_id'],
                        'content_type_name' => $result['content_type_name'],
                        'content_type_description' => $result['content_type_description']
                    ]);
                    $content->setContentType($contentType);
                    $page = new Page([
                        'id' => $result['page_id'],
                        'page_name' => $result['page_name'],
                        'is_home_page' => $result['is_home_page'],
                        'is_published' => $result['pg_is_published']
                    ]);
                    $position = new Position([
                        'id' => $result['position_id'],
                        'position_number' => $result['position_number'],
                        'page' => $page
                    ]);
                    $content->setPosition($position);

                    $listContents[] = $content;
                }

                return $listContents;
            } else {
                // Afficher ou enregistrer l'erreur selon les besoins
                var_dump($req->errorInfo());
                return null;
            }
        } catch (\PDOException $e) {
            // Gérer l'exception selon les besoins
            echo "Erreur PDO : " . $e->getMessage();
            return null;
        }
    }

    public function getAllPublishedNav()
    {
        $sql = "SELECT 
                    n.*,
                    p.id AS page_id,
                    p.page_name,
                    p.is_home_page,
                    p.is_published AS p_is_published
                FROM navigations n
                INNER JOIN pages p ON n.id_page = p.id
                WHERE n.is_published = 1";

        try {
            $req = $this->dbManager->db->prepare($sql);

            if ($req->execute()) {
                $listNavData = $req->fetchAll( \PDO::FETCH_ASSOC );

                foreach( $listNavData as $navData){
                    $nav = new Navigation($navData);

                    $page = new Page([
                        'id' => $navData['page_id'],
                        'page_name' => $navData['page_name'],
                        'is_home_page' => $navData['is_home_page'],
                        'is_published' => $navData['p_is_published']
                    ]);

                    $nav->setPage($page);
                    $listNavigations[] = $nav;
                    
                }
                
                return $listNavigations;
                
            } else {
                // Afficher ou enregistrer l'erreur selon les besoins
                var_dump($req->errorInfo());
                return null;
            }
        } catch (\PDOException $e) {
            // Gérer l'exception selon les besoins
            echo "Erreur PDO : " . $e->getMessage();
            return null;
        }
    }
    
    public function getPageById($id): ?Page
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
}