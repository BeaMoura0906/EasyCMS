<?php

namespace EasyCMS\src\Model;

use EasyCMS\src\Classes\dbConnect;

/**
 * Class Manager
 *
 * The base manager class responsible for handling database connections.
 */
class Manager 
{
    /** @var string The Data Source Name (DSN) for connecting to the database. */
    private $_dsn = 'mysql:host=localhost;dbname=';

    /** @var string The name of the database. */
    private $_dbname;

    /** @var string The login username for the database connection. */
    private $_dblogin;

    /** @var string The password for the database connection. */
    private $_dbpassword; 

    /** @var dbConnect The database manager instance. */
    protected $dbManager;
    
    /**
     * Manager constructor.
     *
     * Initializes the Manager object and establishes a database connection.
     */
    public function __construct()
    {
        // Set database credentials based on the server's hostname
        if( strstr($_SERVER['HTTP_HOST'], '51.178.86.117') ){
            $this->_dbname = 'beatriz_2';
            $this->_dblogin = 'beatriz';
            $this->_dbpassword = 'Eg;ah9do';
        } else {
            $this->_dbname = 'easycms';
            $this->_dblogin = 'root';
            $this->_dbpassword = '';
        }

        // Build the DSN string for the database connection
        $this->_dsn .= $this->_dbname . ';charset=utf8';
        
        // Establish a database connection using the dbConnect class
        $this->dbManager = dbConnect::getDb(
            $this->_dsn, 
            $this->_dblogin, 
            $this->_dbpassword
        );
    }

}