<?php

require 'database.php';

$db = new Database();

$db->insertOrder($_POST['userID'], $_POST['costString'], $_POST['shipping'],
    $_POST['address'], $_POST['pictureString'], $_POST['itemString']);

echo '<p>Thank you for ordering with us!</p>';
echo '<p>Order being shipped to: '.$_POST['address'].'</p>';
echo '<p>The total cost is: '.$_POST['totalCost'].'</p>';
echo '<p>You bought: '.$_POST['itemString'].'</p>';