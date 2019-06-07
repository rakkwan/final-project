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

/**
 * Class Database handles all database interaction
 * @author Max Lee and Jittima Goodrich
 * @copyright 6/6/2019
 */
class Database
{
    private $_dbh;

    /**
     * Database constructor. connects to the database when made
     * @return void
     */
    function __construct()
    {
        $this->connect();
    }

    /**
     * Connects to the database
     * @return void
     */
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

    /**
     * Gets the user's info
     * @param int $user the user's ID
     * @return mixed user's info in an array
     */
    function getUsers($user)
    {
        // 1. Define the query
        $sql = 'SELECT * FROM users WHERE user_id=:user_id';

        // 2 Prepare the statement
        $statement = $this->_dbh->prepare($sql);

        // 3. Bind the parameters
        //$statement->bindParam(':address', $address, PDO::PARAM_STR);
        $statement->bindParam(':user_id', $user, PDO::PARAM_STR);

        // 4. Execute the statement
        $statement->execute();

        // 5. Return the results
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        //print_r($result);
        return $result;
    }

    /**
     * Attempts to log the user in
     * @param String $email the email given
     * @param String $password the password given
     * @return mixed the ID of the user just made
     */
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

    /**
     * Register's the user into the database
     * @param User $user An User object
     * @return void
     */
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

    /**
     * Inserts the order attached with the user's id
     * @param int $userID the ID of the user placing the order
     * @param String $costs the cost of all the items
     * @param String $shipping the shipping type
     * @param String $address the address
     * @param String $pictures all the item's image's urls
     * @param String $itemNames all the item's names
     * @return void
     */
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

    /**
     * Gets all orders place by the user
     * @param int $userID the ID of the user
     * @return array the orders made by the user
     */
    function getOrders($userID)
    {
        $sql = "SELECT * FROM orders WHERE user_id = :user_id ORDER BY order_id ASC";

        $statement = $this->_dbh->prepare($sql);

        $statement->bindParam(':user_id', $userID, PDO::PARAM_STR);
        $statement->execute();
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $rows;
    }

    /**
     * Gets the address of the user
     * @param int $userID user id
     * @return array an array that contains the address
     */
    function getAddress($userID)
    {
        $sql = "SELECT address FROM users WHERE user_id = :user_id";
        $statement = $this->_dbh->prepare($sql);
        $statement->bindParam(':user_id', $userID, PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Changes the user's address
     * @param int $user user's id
     * @param String $address new address
     * @return void
     */
    function changeAddress($user, $address)
    {
        $sql = "UPDATE users SET address = :address WHERE user_id = :user_id";
        $statement = $this->_dbh->prepare($sql);

        // bind param
        $statement->bindParam(':user_id', $user, PDO::PARAM_STR);
        $statement->bindParam(':address', $address, PDO::PARAM_STR);
        $statement->execute();
    }

    /**
     * Checks if the email is already in the database
     * @param String $email the given email
     * @return array that contains the email if it was already in the database
     */
    function checkEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        $statement = $this->_dbh->prepare($sql);

        // bind param
        $statement->bindParam(':email', $email, PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Changes the user's email
     * @param int $user the user's id
     * @param String $email the new email
     * @return void
     */
    function changeUsername($user, $email)
    {
        $sql = "UPDATE users SET email = :email WHERE user_id = :user_id";
        $statement = $this->_dbh->prepare($sql);

        //bind param
        $statement->bindParam(':user_id', $user, PDO::PARAM_STR);
        $statement->bindParam(':email', $email, PDO::PARAM_STR);
        $statement->execute();
    }

    /**
     * Changes the user's current password
     * @param int $user the user's id
     * @param String $password the new password
     * @return void
     */
    function changePassword($user, $password)
    {
        $sql = "UPDATE users SET password = :password WHERE user_id = :user_id";
        $statement = $this->_dbh->prepare($sql);

        //bind param
        $statement->bindParam(':user_id', $user, PDO::PARAM_STR);
        $statement->bindParam(':password', $password, PDO::PARAM_STR);
        $statement->execute();
    }

    /**
     * Deletes the given order
     * @param int $orderID the order's id
     * @return void
     */
    function deleteOrder($orderID)
    {
        $sql = "DELETE FROM orders WHERE order_id = :order_id";
        $statement = $this->_dbh->prepare($sql);

        $statement->bindParam(':order_id', $orderID, PDO::PARAM_STR);
        $statement->execute();
    }
}