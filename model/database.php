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
ALTER TABLE orders
ADD description TEXT;

ALTER TABLE orders ADD images TEXT;
 */

$user = $_SERVER['USER'];
require "/home/$user/config-student.php";

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
            $this->_dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            //echo $e->getMessage();
        }
    }

    function getUsers($email)
    {
        // 1. Define the query
        $sql = 'SELECT fname, lname, address FROM users WHERE email=:email';

        // 2 Prepare the statement
        $statement = $this->_dbh->prepare($sql);

        // 3. Bind the parameters
        //$statement->bindParam(':address', $address, PDO::PARAM_STR);
        $statement->bindParam(':email', $email, PDO::PARAM_STR);

        // 4. Execute the statement
        $statement->execute();

        // 5. Return the results
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        //print_r($result);
        return $result;
    }

    function login($email, $password)
    {
        $sql = "SELECT user_id FROM users WHERE email = :email AND password = :password";

        $statement = $this->_dbh->prepare($sql);

        $statement->bindParam(':email', $email, PDO::PARAM_STR);
        $statement->bindParam(':password', $password, PDO::PARAM_STR);

        $statement->execute();

        $row = $statement->fetch(PDO::FETCH_ASSOC);

        global $f3;
        $f3->set('userID', $row['user_id']);
        return $row;
    }

    function register($user)
    {
        // prepare sql statement
        $sql = "INSERT INTO users(fname, lname, address, email, password)
        VALUES (:fname, :lname, :address, :email, :password)";

        // save prepared statement
        $statement = $this->_dbh->prepare($sql);

        // assign values
        $fname = $user->getFname();
        $lname = $user->getLname();
        $address = $user->getAddress();
        $email = $user->getEmail();
        $password = $user->getPassword();
        //$password = password_hash($password, 1);

        // bind params
        $statement->bindParam(':fname', $fname, PDO::PARAM_STR);
        $statement->bindParam(':lname', $lname, PDO::PARAM_STR);
        $statement->bindParam(':address', $address, PDO::PARAM_STR);
        $statement->bindParam(':email', $email, PDO::PARAM_STR);
        $statement->bindParam(':password', $password, PDO::PARAM_STR);

        // execute insert into users
        $statement->execute();

        global $f3;
        $lastID = $this->_dbh->lastInsertId();
        $f3->set('userID', $lastID);
    }

    function insertOrder($userID, $costs, $shipping, $address, $pictures, $itemNames)
    {
        $sql = "INSERT INTO orders(product, shipping, address, user_id, description, images) 
        VALUES (:product, :shipping, :address, :user_id, :description, :images)";
        $statement = $this->_dbh->prepare($sql);

        $statement->bindParam(':product', $costs, PDO::PARAM_STR);
        $statement->bindParam(':shipping', $shipping, PDO::PARAM_STR);
        $statement->bindParam(':address', $address, PDO::PARAM_STR);
        $statement->bindParam(':user_id', $userID, PDO::PARAM_STR);
        $statement->bindParam(':description', $itemNames, PDO::PARAM_STR);
        $statement->bindParam(':images', $pictures, PDO::PARAM_STR);

        $statement->execute();
    }

    function getOrders($userID)
    {
        $sql = "SELECT * FROM orders WHERE user_id = :user_id ORDER BY order_id ASC";

        $statement = $this->_dbh->prepare($sql);

        $statement->bindParam(':user_id', $userID, PDO::PARAM_STR);
        $statement->execute();
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $rows;
    }

    function getAddress($userID)
    {
        $sql = "SELECT address FROM users WHERE user_id = :user_id";
        $statement = $this->_dbh->prepare($sql);
        $statement->bindParam(':user_id', $userID, PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }
}