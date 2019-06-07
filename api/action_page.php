<?php

/**
 * Handles accessing walmart's api and converting the returning data into JSON
 * @author Max Lee and Jittima Goodrich
 * @copyright 6/6/2019
 */

$product = $_POST['product'];

$key = "72y26bybhgagkpnwxkjq5c6x";
$url = "http://api.walmartlabs.com/v1/search?query=" . $product . "&format=json&apiKey=" . $key;

//send the api request
$return = file_get_contents($url);


//give the result to website
echo json_encode($return);