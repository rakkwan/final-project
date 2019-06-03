<?php
/**
 * Created by PhpStorm.
 * User: jrakk
 * Date: 6/1/2019
 * Time: 7:13 PM
 */


// Turn on error reporting
error_reporting(E_ALL);
ini_set("display_errors", 1);

$email = $_POST['value'];
//Connect to DB
require("/home/jgoodric/config-student.php");

try {
    // Instantiate a database object
    $dbh = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
}
catch (PDOException $e) {
    echo $e->getMessage();
    return;
}

//Define and execute the query
$select = 'SELECT fname, lname, address FROM users WHERE email=:email';
$statement = $dbh->prepare($select);
$statement->bindParam(':email', $email, PDO::PARAM_STR);
$statement->execute();
$row = $statement->fetch(PDO::FETCH_ASSOC);

// Display the results
if (!empty($row)) {
    echo "<h3>Your Profile</h3>";
    echo "Name: {$row['fname']} {$row['lname']}<br>";
    echo "Address: {$row['address']}<br>";
} else {
    echo "Username: $email not found";
}
