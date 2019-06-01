<?php

require 'database.php';

$db = new Database();

$db->insertOrder($_POST['userID'], $_POST['cost'], $_POST['shipping'], $_POST['address']);

echo '<p>user email: '.$_POST['userID'].'</p>';
echo '<p>address: '.$_POST['address'].'</p>';
echo '<p>cost: '.$_POST['cost'].'</p>';
echo '<p>shipping: '.$_POST['shipping'].'</p>';