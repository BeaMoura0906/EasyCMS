<?php

namespace EasyCMS\src\Model\Entity;

class Position
{
    private $id = 0;
    private $positionNumber = 0;
    private $page;

    public function __construct(array $positionData)
    {
        $this->hydrate($positionData);
    }

    public function hydrate(array $data)
    {
        foreach ($data as $key => $value) {
            if ($key === 'page') {
                $this->setPage(new Page(['id' => $value]));
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

    public function getPositionNumber()
    {
        return $this->positionNumber;
    }

    public function setPositionNumber($positionNumber)
    {
        $this->positionNumber = $positionNumber;
    }

    public function getPage(): ?Page
    {
        return $this->page;
    }

    public function setPage(Page $page)
    {
        $this->page = $page;
    }

}
