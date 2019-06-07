<?php

/**
 * Class CartItem Represents an item that gets put into an online cart
 * @author Max Lee
 * @copyright 5/18/19
 */
class CartItem
{
    private $_image, $_name, $_price;

    /**
     * CartItem constructor.
     * @param String $_image the url to the picture
     * @param String $_name name of the product in the cart
     * @param String $_price price of the product
     * @return void
     */
    public function __construct($_image, $_name, $_price)
    {
        $this->_image = $_image;
        $this->_name = $_name;
        $this->_price = $_price;
    }

    /**
     * Gets the image of the item
     * @return String image url
     */
    public function getImage()
    {
        return $this->_image;
    }

    /**
     * Sets the image of the item
     * @param String $image the image url
     * @return void
     */
    public function setImage($image)
    {
        $this->_image = $image;
    }

    /**
     * Gets the name of the product
     * @return String the name
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Sets the name of the product
     * @param String $name the name
     * @return void
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * Gets the price of the product
     * @return int the price
     */
    public function getPrice()
    {
        return $this->_price;
    }

    /**
     * Sets the price of the product
     * @param int $price
     * @return void
     */
    public function setPrice($price)
    {
        $this->_price = $price;
    }


}