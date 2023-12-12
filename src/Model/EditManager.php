<?php

namespace EasyCMS\src\Model;

use EasyCMS\src\Model\Entity\Page;
use EasyCMS\src\Model\Entity\Content;
use EasyCMS\src\Model\Entity\ContentType;

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

    public function insertPage(Page $page): ?bool
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

    public function getAllContents(): ?array
    {
        $listContents = [];
    
        $sql = "SELECT
                    c.id,
                    c.content_name,
                    c.content_description,
                    c.creation_date,
                    c.modification_date,
                    c.is_published,
                    c.id_user,
                    c.id_position,
                    ct.id AS content_type_id,
                    ct.content_type_name,
                    ct.content_type_description
                FROM
                    contents c
                INNER JOIN
                    content_types ct ON c.id_content_type = ct.id";
    
        try {
            $req = $this->dbManager->db->prepare($sql);
    
            if ($req->execute()) {
                $result = $req->fetchAll(\PDO::FETCH_ASSOC);
    
                foreach ($result as $row) {
                    $content = new Content($row);
                    $contentType = new ContentType([
                        'id' => $row['content_type_id'],
                        'content_type_name' => $row['content_type_name'],
                        'content_type_description' => $row['content_type_description']
                    ]);
                    $content->setContentType($contentType);
    
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

    public function getContentById($id): ?Content
    {
        $sql = "SELECT
                    c.id,
                    c.content_name,
                    c.content_description,
                    c.creation_date,
                    c.modification_date,
                    c.is_published,
                    c.id_user,
                    c.id_position,
                    ct.id AS content_type_id,
                    ct.content_type_name,
                    ct.content_type_description
                FROM
                    contents c
                INNER JOIN
                    content_types ct ON c.id_content_type = ct.id
                WHERE
                    c.id = :id";

        try {
            $req = $this->dbManager->db->prepare($sql);

            if ($req->execute(['id' => $id])) {
                $result = $req->fetch(\PDO::FETCH_ASSOC);

                if ($result) {
                    $content = new Content($result);
                    $contentType = new ContentType([
                        'id' => $result['content_type_id'],
                        'content_type_name' => $result['content_type_name'],
                        'content_type_description' => $result['content_type_description']
                    ]);
                    $content->setContentType($contentType);

                    return $content;
                } else {
                    // Aucun résultat trouvé pour l'ID spécifié
                    return null;
                }
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

    public function getAllContentTypes(): ?array
    {
        $listContentTypes = [];
        $sql = "SELECT * FROM content_types";
        $req = $this->dbManager->db->prepare( $sql );
        if( $req->execute()){
            $listContentTypesData = $req->fetchAll( \PDO::FETCH_ASSOC );

            foreach( $listContentTypesData as $contentTypeData){
                $content = new ContentType($contentTypeData);
                $listContentTypes[] = $content;
                
            }
            
            return $listContentTypes;
        } else {
            return null;
        }
    }

    public function getContentTypeById($id): ?ContentType
    {
        $sql = "SELECT * FROM content_types WHERE id=:id";
        $req = $this->dbManager->db->prepare( $sql );
        if( $req->execute( ['id' => $id] )){
            $contentTypeData = $req->fetch( \PDO::FETCH_ASSOC );
            $contentType = new ContentType($contentTypeData);           
            return $contentType;
        } else {
            return null;
        }
    }


    public function updateContent(Content $content): bool
    {
        $sql = "UPDATE contents
                SET
                    content_name = :contentName,
                    content_description = :contentDescription,
                    modification_date = CURRENT_TIMESTAMP,
                    is_published = :isPublished,
                    id_user = :userId,
                    id_position = :positionId,
                    id_content_type = :contentTypeId
                WHERE
                    id = :id";

        try {
            $req = $this->dbManager->db->prepare($sql);

            // Si getPositionId est égal à 0, affecte NULL à la place
            $positionId = ($content->getIdPosition() === 0) ? null : $content->getIdPosition();

            return $req->execute([
                'id' => $content->getId(),
                'contentName' => $content->getContentName(),
                'contentDescription' => $content->getContentDescription(),
                'isPublished' => intval($content->getIsPublished()),
                'userId' => $content->getIdUser(),
                'positionId' => $positionId,
                'contentTypeId' => $content->getContentType()->getId()
            ]);
        } catch (\PDOException $e) {
            // Gérer l'exception selon les besoins
            echo "Erreur PDO : " . $e->getMessage();
            return false;
        }
    }

    public function insertContent(Content $content): bool
    {
        $sql = "INSERT INTO contents (
                    content_name,
                    content_description,
                    creation_date,
                    modification_date,
                    is_published,
                    id_user,
                    id_position,
                    id_content_type
                ) VALUES (
                    :contentName,
                    :contentDescription,
                    CURRENT_TIME,
                    CURRENT_TIME,
                    :isPublished,
                    :userId,
                    :positionId,
                    :contentTypeId
                )";
        try {
            $req = $this->dbManager->db->prepare($sql);

            // Si getPositionId est égal à 0, affecte NULL à la place
            $positionId = ($content->getIdPosition() === 0) ? null : $content->getIdPosition();

            return $req->execute([
                'contentName' => $content->getContentName(),
                'contentDescription' => $content->getContentDescription(),
                'isPublished' => intval($content->getIsPublished()),
                'userId' => $content->getIdUser(),
                'positionId' => $positionId,
                'contentTypeId' => $content->getContentType()->getId()
            ]);
        } catch (\PDOException $e) {
            // Handle the exception as needed
            echo "Erreur PDO : " . $e->getMessage();
            return false;
        }
    }


}
