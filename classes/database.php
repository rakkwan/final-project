<?php
/**
 * Created by PhpStorm.
 * User: jrakk
 * Date: 5/25/2019
 * Time: 8:20 PM
 */

require '/home/jgoodric/config-project.php';

class Database
{
    private $_dbh;

    function __construct()
    {
        $this->connect();
    }

    function connect()
    {
        try {
            // Instantiate a db object
            $this->_dbh = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
            //echo "Connected!!!";
            return $this->_dbh;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}