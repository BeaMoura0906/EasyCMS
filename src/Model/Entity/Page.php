<?php

namespace EasyCMS\src\Model\Entity;

class Page
{
    private $id = 0;
    private $pageName = 0;
    private $websiteName = 0;
    private $isHomePage = 0;
    private $creationDate = 0;
    private $modificationDate = 0;
    private $isPublished = 0;
    private $idUser = 0;

    public function __construct(array $pageData)
    {    
        $this->hydrate( $pageData );
    }

    public function hydrate(array $data)
    {
        foreach ($data as $key => $value) {
            $method = 'set' . $this->convertSnakeCaseToCamelCase($key);

            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    private function convertSnakeCaseToCamelCase($snakeCase)
    {
        return str_replace('_', '', ucwords($snakeCase, '_'));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getPageName(): ?string
    {
        return $this->pageName;
    }

    public function setPageName($pageName)
    {
        $this->pageName = $pageName;
    }

    public function getWebsiteName(): ?string
    {
        return $this->websiteName;
    }

    public function setWebsiteName($websiteName)
    {
        $this->websiteName = $websiteName;
    }

    public function getIsHomePage(): ?bool
    {
        return $this->isHomePage;
    }

    public function setIsHomePage($isHomePage)
    {
        $this->isHomePage = $isHomePage;
    }

    public function getCreationDate(): ?string
    {
        $dateTimeCreationDate = new \DateTime($this->creationDate);
        $creationDate = $dateTimeCreationDate->format('Y-m-d \à H:i:s');
        return $creationDate;
    }

    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
    }

    public function getModificationDate(): ?string
    {
        $dateTimeModificationDate = new \DateTime($this->modificationDate);
        $modificationDate = $dateTimeModificationDate->format('Y-m-d \à H:i:s');
        return $modificationDate;
    }

    public function setModificationDate($modificationDate)
    {
        $this->modificationDate = $modificationDate;
    }

    public function getIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished($isPublished)
    {
        $this->isPublished = $isPublished;
    }

    public function getIdUser(): ?int
    {
        return $this->idUser;
    }

    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;
    }

    

}


