<?php

namespace EasyCMS\src\Model\Entity;

class ContentType
{
    private $id;
    private $contentTypeName;
    private $contentTypeDescription;

    public function __construct(array $contentTypeData)
    {    
        $this->hydrate( $contentTypeData );
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

    public function getContentTypeName()
    {
        return $this->contentTypeName;
    }

    public function setContentTypeName($contentTypeName)
    {
        $this->contentTypeName = $contentTypeName;
    }

    public function getContentTypeDescription()
    {
        return $this->contentTypeDescription;
    }

    public function setContentTypeDescription($contentTypeDescription)
    {
        $this->contentTypeDescription = $contentTypeDescription;
    }

}
