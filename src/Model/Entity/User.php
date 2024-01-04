<?php

namespace EasyCMS\src\Model\Entity;

class User
{
    private $id = 0;
    private $login = 0;
    private $password = 0;
    private $idRight = 0;

    public function __construct(array $userData)
    {
        $this->hydrate($userData);
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

    public function getLogin()
    {
        return $this->login;
    }

    public function setLogin($login)
    {
        $this->login = $login;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getIdRight()
    {
        return $this->idRight;
    }

    public function setIdRight($idRight)
    {
        $this->idRight = $idRight;
    }

}