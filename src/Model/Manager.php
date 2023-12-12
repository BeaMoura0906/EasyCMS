<?php

namespace EasyCMS\src\Model;

use EasyCMS\src\Classes\dbConnect;

class Manager 
{
    private $_dsn = 'mysql:host=localhost;dbname=';
    private $_dbname;
    private $_dblogin;
    private $_dbpassword; 

    protected $dbManager;
    
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
        
        $this->dbManager = dbConnect::getDb(
            $this->_dsn, 
            $this->_dblogin, 
            $this->_dbpassword
        );
    }

}