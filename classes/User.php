<?php
/**
 * Created by PhpStorm.
 * User: jrakk
 * Date: 5/25/2019
 * Time: 11:30 PM
 */

/**
 * Class User is a registered user in out system
 * @author Jittima Goodrich
 * @copyright 6/1/2019
 */
class User extends Guest
{
    private $_fname;
    private $_lname;
    private $_address;
    private $_email;
    private $_password;

    /**
     * User constructor.
     * @param String $_fname the first name of the user
     * @param String $_lname the last name of the user
     * @param String $_address the address of the user
     * @param String $_email the email of the user
     * @param String $_password the password of the user
     * @return void
     */
    public function __construct($_fname, $_lname, $_address, $_email, $_password)
    {
        $this->_fname = $_fname;
        $this->_lname = $_lname;
        $this->_address = $_address;
        $this->_email = $_email;
        $this->_password = $_password;
    }

    /**
     * The getter for the first name
     * @return String the first name of the user
     */
    public function getFname()
    {
        return $this->_fname;
    }

    /**
     * The setter for the first name
     * @param String $fname first name
     * @return void
     */
    public function setFname($fname)
    {
        $this->_fname = $fname;
    }

    /**
     * Gets the last name of the user
     * @return String last name
     */
    public function getLname()
    {
        return $this->_lname;
    }

    /**
     * Sets the last name for the user
     * @param String $lname last name
     * @return void
     */
    public function setLname($lname)
    {
        $this->_lname = $lname;
    }

    /**
     * Gets the address of the user
     * @return String address
     */
    public function getAddress()
    {
        return $this->_address;
    }

    /**
     * Sets the address of the user
     * @param String $address the address
     * @return void
     */
    public function setAddress($address)
    {
        $this->_address = $address;
    }

    /**
     * Gets the email of the user
     * @return String email
     */
    public function getEmail()
    {
        return $this->_email;
    }

    /**
     * Sets the email for the user
     * @param String $email the email
     * @return void
     */
    public function setEmail($email)
    {
        $this->_email = $email;
    }

    /**
     * Gets the password of the user
     * @return String the password
     */
    public function getPassword()
    {
        return $this->_password;
    }

    /**
     * Sets the password for the user
     * @param String $password the password
     * @return void
     */
    public function setPassword($password)
    {
        $this->_password = $password;
    }




}