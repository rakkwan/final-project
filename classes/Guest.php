<?php


class Guest
{
    private $_cart;

    function __construct()
    {
        $this->_cart = new CartItem();
    }
}