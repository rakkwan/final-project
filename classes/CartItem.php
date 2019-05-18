<?php

/**
 * Class CartItem
 * @author Max Lee
 * @copyright 5/18/19
 */
class CartItem
{
    private $_image, $_name, $_price;

    /**
     * CartItem constructor.
     * @param $_image
     * @param $_name
     * @param $_price
     */
    public function __construct($_image, $_name, $_price)
    {
        $this->_image = $_image;
        $this->_name = $_name;
        $this->_price = $_price;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->_image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->_image = $image;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->_price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->_price = $price;
    }


}