<?php

namespace EasyCMS\src\Model;

class Manager 
{
    private $_dsn = 'mysql:host=localhost;dbname=';
    private $_dbname;
    private $_dblogin;
    private $_dbpassword; 

    protected $_db;

    public function __construct()
    {

        if( strstr($_SERVER['HTTP_HOST'], '51.178.86.117') ){
            $this->_dbname = 'beatriz_2';
            $this->_dblogin = 'beatriz';
            $this->_dbpassword = 'Eg;ah9do';
        } else {
            $this->_dbname = 'easycms';
            $this->_dblogin = 'root';
            $this->_dbpassword = '';
        }

        $this->_dsn .= $this->_dbname . ';charset=utf8';
        
        // Connexion à la base de données
        try
        {
            $this->_db = new \PDO($this->_dsn, $this->_dblogin, $this->_dbpassword);
            $this->_db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING);
        }
        catch(\Exception $e)
        {
            die('Erreur : '.$e->getMessage());
        }
    }

}