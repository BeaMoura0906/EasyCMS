<?php

namespace EasyCMS\src\Model\Entity;

class Navigation
{
    private $id = 0;
    private $navName = 0;
    private $creationDate = 0;
    private $modificationDate = 0;
    private $isPublished = 0;
    private $page;
    private $idUser = 0;
    private $idPosition = 0;

    public function __construct(array $contentData)
    {
        $this->hydrate($contentData);
    }

    public function hydrate(array $data)
    {
        foreach ($data as $key => $value) {
            if ($key === 'page') {
                $this->setPage(new Page($value));
            } else {
                $method = 'set' . $this->convertSnakeCaseToCamelCase($key);

                if (method_exists($this, $method)) {
                    $this->$method($value);
                }
            }
        }
    }

    private function convertSnakeCaseToCamelCase($snakeCase)
    {
        return str_replace('_', '', ucwords($snakeCase, '_'));
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getNavName()
    {
        return $this->navName;
    }

    public function setNavName($navName)
    {
        $this->navName = $navName;
    }

    public function getCreationDate(): ?string
    {
        // Assurez-vous que $this->creationDate n'est pas vide
        if (empty($this->creationDate)) {
            return null;
        }

        try {
            $dateTimeCreationDate = new \DateTime($this->creationDate);
            return $dateTimeCreationDate->format('d/m/Y à H\hi');
        } catch (\Exception $e) {
            // Gérer l'erreur si la date n'est pas valide
            return null;
        }
    }

    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
    }

    public function getModificationDate(): ?string
    {
        // Assurez-vous que $this->modificationDate n'est pas vide
        if (empty($this->modificationDate)) {
            return null;
        }

        try {
            $dateTimeModificationDate = new \DateTime($this->modificationDate);
            return $dateTimeModificationDate->format('d/m/Y à H:i:s');
        } catch (\Exception $e) {
            // Gérer l'erreur si la date n'est pas valide
            return null;
        }
    }

    public function setModificationDate($modificationDate)
    {
        $this->modificationDate = $modificationDate;
    }

    public function getIsPublished()
    {
        return $this->isPublished;
    }

    public function setIsPublished($isPublished)
    {
        $this->isPublished = $isPublished;
    }

    public function getPage(): ?Page
    {
        return $this->page;
    }

    public function setPage(Page $page)
    {
        $this->page = $page;
    }

    public function getIdUser()
    {
        return $this->idUser;
    }

    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;
    }

    public function getIdPosition()
    {
        return $this->idPosition;
    }

    public function setIdPosition($idPosition)
    {
        $this->idPosition = $idPosition;
    }
}
