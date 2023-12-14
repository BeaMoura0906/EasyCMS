<?php

namespace EasyCMS\src\Model;

use EasyCMS\src\Model\Entity\Page;
use EasyCMS\src\Model\Entity\Content;
use EasyCMS\src\Model\Entity\ContentType;
use EasyCMS\src\Model\Entity\Navigation;
use EasyCMS\src\Model\Entity\Position;

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

    public function deletePageById($pageId): bool
    {
        $sql = "DELETE FROM pages WHERE id = :pageId";

        try {
            $req = $this->dbManager->db->prepare($sql);
            $req->execute(['pageId' => $pageId]);

            // Vérifiez le nombre de lignes affectées pour confirmer la suppression
            $rowCount = $req->rowCount();

            return $rowCount > 0;
        } catch (\PDOException $e) {
            // Gérer l'exception selon les besoins
            echo "Erreur PDO : " . $e->getMessage();
            return false;
        }
    }

    public function getAllContents(): ?array
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
                LEFT JOIN
                    positions p ON c.id_position = p.id
                INNER JOIN
                    content_types ct ON c.id_content_type = ct.id
                LEFT JOIN
                    pages pg ON p.id_page = pg.id";

        try {
            $req = $this->dbManager->db->prepare($sql);

            if ($req->execute()) {
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

                    // Vérifier si id_position est NULL
                    if ($result['position_id'] !== null) {
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
                    } else {
                        // Si id_position est NULL, position est NULL
                        $content->setPosition(null);
                    }

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
                LEFT JOIN
                    positions p ON c.id_position = p.id
                INNER JOIN
                    content_types ct ON c.id_content_type = ct.id
                LEFT JOIN
                    pages pg ON p.id_page = pg.id
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

                    if ($result['position_id'] !== null) {
                        $page = new Page([
                            'id' => $result['page_id'],
                            'page_name' => $result['page_name'],
                            'is_home_page' => $result['is_home_page'],
                            'is_published' => $result['pg_is_published']
                        ]);

                        $position = new Position([
                            'id' => $result['position_id'],
                            'position_number' => $result['position_number']
                        ]);
                        $position->setPage($page);

                        $content->setPosition($position);
                    } else {
                        $content->setPosition(null);
                    }

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
            $positionId = ($content->getPosition()->getId() === 0) ? null : $content->getPosition()->getId();

            // Mettre à jour la position de l'ancien contenu à NULL avant d'affecter la nouvelle position
            $this->updateOldContentPosition($positionId);

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

    private function updateOldContentPosition($newPositionId): void
    {
        $sql = "UPDATE contents SET id_position = NULL WHERE id_position = :newPositionId";

        $req = $this->dbManager->db->prepare($sql);
        $req->execute(['newPositionId' => $newPositionId]);
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
                    CURRENT_TIMESTAMP,
                    CURRENT_TIMESTAMP,
                    :isPublished,
                    :userId,
                    :positionId,
                    :contentTypeId
                )";

        try {
            $req = $this->dbManager->db->prepare($sql);

            // Si getPositionId est égal à 0, affecte NULL à la place
            $positionId = ($content->getPosition()->getId() === 0) ? null : $content->getPosition()->getId();

            // Mettre à jour la position de l'ancien contenu à NULL avant d'insérer le nouveau contenu
            $this->updateOldContentPosition($positionId);

            return $req->execute([
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


    public function deleteContentById($contentId): bool
    {
        $sql = "DELETE FROM contents WHERE id = :contentId";

        try {
            $req = $this->dbManager->db->prepare($sql);
            $req->execute(['contentId' => $contentId]);

            // Vérifiez le nombre de lignes affectées pour confirmer la suppression
            $rowCount = $req->rowCount();

            return $rowCount > 0;
        } catch (\PDOException $e) {
            // Gérer l'exception selon les besoins
            echo "Erreur PDO : " . $e->getMessage();
            return false;
        }
    }

    public function getAllNavigations(): ?array
    {
        $listNavigations = [];
        $sql = "SELECT * FROM navigations";
        $req = $this->dbManager->db->prepare( $sql );
        if( $req->execute()){
            $listNavData = $req->fetchAll( \PDO::FETCH_ASSOC );

            foreach( $listNavData as $navData){
                $nav = new Navigation($navData);
                $listNavigations[] = $nav;
                
            }
            
            return $listNavigations;
        } else {
            return null;
        }
    }

    public function getNavigationById($id): ?Navigation
    {
        $sql = "SELECT n.*, p.id AS page_id, p.page_name, p.is_home_page, p.is_published AS p_is_published FROM navigations n
                INNER JOIN pages p ON n.id_page = p.id
                WHERE n.id=:id";

        try {
            $req = $this->dbManager->db->prepare($sql);

            if ($req->execute(['id' => $id])) {
                $navData = $req->fetch(\PDO::FETCH_ASSOC);

                if ($navData) {
                    $nav = new Navigation($navData);

                    $page = new Page([
                        'id' => $navData['page_id'],
                        'page_name' => $navData['page_name'],
                        'is_home_page' => $navData['is_home_page'],
                        'is_published' => $navData['p_is_published']
                    ]);

                    $nav->setPage($page);

                    return $nav;
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

    public function updateNavigation(Navigation $nav): bool
    {
        $sql = "UPDATE navigations
                SET
                    nav_name = :navName,
                    modification_date = CURRENT_TIME,
                    is_published = :isPublished,
                    id_page = :pageId,
                    id_user = :userId,
                    id_position = :positionId
                    
                WHERE
                    id = :id";

        try {
            $req = $this->dbManager->db->prepare($sql);

            // Si getPositionId est égal à 0, affecte NULL à la place
            $positionId = ($nav->getIdPosition() === 0) ? null : $nav->getIdPosition();

            return $req->execute([
                'id' => $nav->getId(),
                'navName' => $nav->getNavName(),
                'isPublished' => intval($nav->getIsPublished()),
                'userId' => $nav->getIdUser(),
                'positionId' => $positionId,
                'pageId' => $nav->getPage()->getId()
            ]);
            

            
        } catch (\PDOException $e) {
            // Gérer l'exception selon les besoins
            echo "Erreur PDO : " . $e->getMessage();
            return false;
        }
    }

    public function insertNavigation(Navigation $nav): bool
    {
        $sql = "INSERT INTO navigations (
                    nav_name,
                    creation_date,
                    modification_date,
                    is_published,
                    id_page,
                    id_user,
                    id_position
                ) VALUES (
                    :navName,
                    CURRENT_TIME,
                    CURRENT_TIME,
                    :isPublished,
                    :pageId,
                    :userId,
                    :positionId
                )";
        
        try {
            $req = $this->dbManager->db->prepare($sql);

            // Si getIdPosition est égal à 0, affecte NULL à la place
            $positionId = ($nav->getIdPosition() === 0) ? null : $nav->getIdPosition();

            return $req->execute([
                'navName' => $nav->getNavName(),
                'isPublished' => intval($nav->getIsPublished()),
                'pageId' => $nav->getPage()->getId(),
                'userId' => $nav->getIdUser(),
                'positionId' => $positionId
            ]);
        } catch (\PDOException $e) {
            // Gérer l'exception selon les besoins
            echo "Erreur PDO : " . $e->getMessage();
            return false;
        }
    }

    public function deleteNavById($navId): bool
    {
        $sql = "DELETE FROM navigations WHERE id = :navId";

        try {
            $req = $this->dbManager->db->prepare($sql);
            $req->execute(['navId' => $navId]);

            // Vérifiez le nombre de lignes affectées pour confirmer la suppression
            $rowCount = $req->rowCount();

            return $rowCount > 0;
        } catch (\PDOException $e) {
            // Gérer l'exception selon les besoins
            echo "Erreur PDO : " . $e->getMessage();
            return false;
        }
    }

    public function getAllPositions(): ?array
    {
        $sql = "SELECT 
                    p.id,
                    p.position_number,
                    pg.id AS page_id,
                    pg.page_name,
                    pg.is_home_page,
                    pg.is_published
                FROM positions p
                INNER JOIN
                    pages pg ON p.id_page = pg.id";
        try {
            $req = $this->dbManager->db->prepare($sql);

            if ($req->execute()) {
                $results = $req->fetchAll(\PDO::FETCH_ASSOC);
                $listPositions = [];

                foreach ($results as $result) {
                    $position = new Position($result);
                    $page = new Page([
                        'id' => $result['page_id'],
                        'page_name' => $result['page_name'],
                        'is_home_page' => $result['is_home_page'],
                        'is_published' => $result['is_published']
                    ]);
                    $position->setPage($page);

                    $listPositions[] = $position;
                }

                return $listPositions;
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
        return null;
    }
    
    public function getPositionById($positionId): ?Position
{
    $sql = "SELECT 
                p.id,
                p.position_number,
                pg.id AS page_id,
                pg.page_name,
                pg.is_home_page,
                pg.is_published
            FROM positions p
            INNER JOIN
                pages pg ON p.id_page = pg.id
            WHERE p.id = :positionId";

    try {
        $req = $this->dbManager->db->prepare($sql);

        if ($req->execute(['positionId' => $positionId])) {
            $result = $req->fetch(\PDO::FETCH_ASSOC);

            if ($result !== false) {
                $position = new Position($result);
                $page = new Page([
                    'id' => $result['page_id'],
                    'page_name' => $result['page_name'],
                    'is_home_page' => $result['is_home_page'],
                    'is_published' => $result['is_published']
                ]);
                $position->setPage($page);

                return $position;
            } else {
                // La position avec l'ID donné n'existe pas
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

}
