<?php

//TUrn on error reporting
ini_set('display_errors', true);
error_reporting(E_ALL);

//Require autoload file
require_once('vendor/autoload.php');

//Create an instance of the Base class
$f3 = Base::instance();

//define a default route
$f3->route('GET /', function ()
{
    $view = new Template();
    echo $view->render('views/search-page.html');
});

//Run fat-free
$f3->run();
?>