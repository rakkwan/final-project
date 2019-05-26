<?php
/**
 * Created by PhpStorm.
 * User: jrakk
 * Date: 5/25/2019
 * Time: 8:20 PM
 */
/*
 * CREATE TABLE users
(
	user_id INTEGER NOT NULL AUTO_INCREMENT,
	fname VARCHAR(20) NOT NULL,
	lname VARCHAR(40) NOT NULL,
	address TEXT NOT NULL,
	email VARCHAR(254) NOT NULL,
	password VARCHAR(128) NOT NULL,
	UNIQUE (email),
	PRIMARY KEY(user_id)
);

CREATE TABLE orders
(
	order_id INTEGER NOT NULL AUTO_INCREMENT,
	product TEXT NOT NULL,
	shipping TEXT NOT NULL,
	address TEXT NOT NULL,
    user_id INTEGER,
	PRIMARY KEY(order_id),
	FOREIGN KEY(user_id) REFERENCES users(user_id)
);
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

    function getUsers()
    {
        // 1. Define the query
        $sql = "SELECT * FROM users
                ORDER BY last, first";

        // 2 Prepare the statement
        $statement = $this->_dbh->prepare($sql);

        // 3. Bind the parameters
        //$statement->bindParam(':address', $address, PDO::PARAM_STR);

        // 4. Execute the statement
        $statement->execute();

        // 5. Return the results
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        //print_r($result);
        return $result;
    }

    function register($user)
    {
        if (isset($this->_dbh)) {
            // prepare sql statement
            $sql = "INSERT INTO users (fname, lname, address, email, password)
                    VALUES (:fname, :lname, :address, :email, :password)";

            // save prepared statement
            $statement = $this->_dbh->prepare($sql);

            // assign values
            $fname = $user->getFname();
            $lname = $user->getLname();
            $address = $user->getAddress();
            $email = $user->getEmail();
            $password = $user->getPassword();

            // bind params
            $statement->bindParam(':fname', $fname, PDO::PARAM_STR);
            $statement->bindParam(':lname', $lname, PDO::PARAM_STR);
            $statement->bindParam(':address', $address, PDO::PARAM_STR);
            $statement->bindParam(':email', $email, PDO::PARAM_STR);
            $statement->bindParam(':password', $password, PDO::PARAM_STR);

            // execute insert into users
            $statement->execute();

        }
    }
}