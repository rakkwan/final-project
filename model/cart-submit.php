<?php

require 'database.php';

$db = new Database();

$db->connect();

echo 'address: ';
echo $_POST['address'];
echo ' cost: '.$_POST['cost'];
echo ' shipping: '.$_POST['shipping'];