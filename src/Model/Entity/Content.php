<?php

namespace EasyCMS\src\Model\Entity;

class Content
{
    private $id = 0;
    private $contentName = 0;
    private $contentDescription = 0;
    private $creationDate = 0;
    private $modificationDate = 0;
    private $isPublished = 0;
    private $idUser = 0;
    private $position;
    private $contentType;

    public function __construct(array $contentData)
    {
        $this->hydrate($contentData);
    }

    public function hydrate(array $data)
    {
        foreach ($data as $key => $value) {
            if ($key === 'contentType') {
                $this->setContentType(new ContentType($value));
            } else if ($key === 'position') {
                $this->setPosition(new Position($value));
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

    public function getContentName()
    {
        return $this->contentName;
    }

    public function setContentName($contentName)
    {
        $this->contentName = $contentName;
    }

    public function getContentDescription()
    {
        return $this->contentDescription;
    }

    public function setContentDescription($contentDescription)
    {
        $this->contentDescription = $contentDescription;
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

    public function getIdUser()
    {
        return $this->idUser;
    }

    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;
    }

    public function getPosition(): ?Position
    {
        return $this->position;
    }

    public function setPosition(Position $position)
    {
        $this->position = $position;
    }

    public function getContentType(): ?ContentType
    {
        return $this->contentType;
    }

    public function setContentType(ContentType $contentType)
    {
        $this->contentType = $contentType;
    }

}
