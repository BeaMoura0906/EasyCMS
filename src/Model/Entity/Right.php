<?php

namespace EasyCMS\src\Model\Entity;

class Right
{
    private $id = 0;
    private $rightName = 0;

    public function __construct(array $rightData)
    {
        $this->hydrate($rightData);
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

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getRightName()
    {
        return $this->rightName;
    }

    public function setRightName($rightName)
    {
        $this->rightName = $rightName;
    }

}