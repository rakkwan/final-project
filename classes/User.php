<?php
/**
 * Created by PhpStorm.
 * User: jrakk
 * Date: 5/25/2019
 * Time: 11:30 PM
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
     * @param $_fname
     * @param $_lname
     * @param $_address
     * @param $_email
     * @param $_password
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
     * @return mixed
     */
    public function getFname()
    {
        return $this->_fname;
    }

    /**
     * @param mixed $fname
     */
    public function setFname($fname)
    {
        $this->_fname = $fname;
    }

    /**
     * @return mixed
     */
    public function getLname()
    {
        return $this->_lname;
    }

    /**
     * @param mixed $lname
     */
    public function setLname($lname)
    {
        $this->_lname = $lname;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->_address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->_address = $address;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->_email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->_email = $email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->_password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->_password = $password;
    }




}