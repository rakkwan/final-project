<?php

/**
 * Class Guest An unregistered user can have a cart
 * @author Max Lee
 * @copyright 6/6/2019
 */
class Guest
{
    private $_cart;

    /**
     * Guest constructor.
     * Stores and array that will store CartItems
     * @return void
     */
    function __construct()
    {
        $this->_cart = array();
    }

    /**
     * Adds an item to the guest's cart
     * @param CartItem $item and item from Walmart
     * @return void
     */
    function addItemToCart($item)
    {
        array_push($this->_cart, $item);
    }

    /**
     * Gets the cart
     * @return array The array of CartItems
     */
    function getCart()
    {
        return $this->_cart;
    }

    /**
     * Sets the cart for the guest
     * @param array $cart An array of CartItems
     */
    function setCart($cart)
    {
        $this->_cart = $cart;
    }

    /**
     * Deletes the cart
     * @return void
     */
    function deleteCart()
    {
        $this->_cart = array();
    }

    /**
     * Gets the size of the cart stored
     * @return int size of the cart
     */
    function getSizeOfCart()
    {
        return sizeof($this->_cart);
    }
}