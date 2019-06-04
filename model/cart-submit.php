<?php

require 'database.php';

$db = new Database();

$db->insertOrder($_POST['userID'], $_POST['costString'], $_POST['shipping'],
    $_POST['address'], $_POST['pictureString'], $_POST['itemString']);

echo '<p>user email: '.$_POST['userID'].'</p>';
echo '<p>address: '.$_POST['address'].'</p>';
echo '<p>shipping: '.$_POST['shipping'].'</p>';
echo '<p>cost: '.$_POST['costString'].'</p>';
echo '<p>pictures: '.$_POST['pictureString'].'</p>';
echo '<p>Item names: '.$_POST['itemString'].'</p>';