<?php

require 'database.php';

$db = new Database();

$db->insertOrder($_POST['userID'], $_POST['cost'], $_POST['shipping'], $_POST['address']);

echo 'user email: '.$_POST['userID'];
echo ' address: '.$_POST['address'];
echo ' cost: '.$_POST['cost'];
echo ' shipping: '.$_POST['shipping'];