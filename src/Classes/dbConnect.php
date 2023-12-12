<?php

namespace EasyCMS\src\Classes;

class dbConnect
{
    public $db;
    private static $instance = null;


    public function __construct($dsn, $dblogin, $dbpassword){

        // Connexion à la base de données
        try
        {
            $this->db = new \PDO($dsn, $dblogin, $dbpassword);
            $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING);
        }
        catch(\Exception $e)
        {
            die('Erreur : '.$e->getMessage());
        }

        
    }

    public static function getDb($dsn, $dblogin, $dbpassword): self
    {
        if( is_null(self::$instance)){
            self::$instance = new dbConnect($dsn, $dblogin, $dbpassword);
        }
        return self::$instance;
    }

}