<?php

require_once('vendor/autoload.php');

session_start();

//TUrn on error reporting
ini_set('display_errors', true);
error_reporting(E_ALL);

//Require autoload file


//Create an instance of the Base class
$f3 = Base::instance();


//define a default route
$f3->route('GET /', function()
{
    $view = new Template();
    echo $view->render('views/home.html');
});

//define a register route
$f3->route('GET|POST /register', function($f3)
{
    $view = new Template();
    echo $view->render('views/register.html');
});

//define a loggin route
$f3->route('GET|POST /loggin', function ($f3)
{
    if(!empty($_POST))
    {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $f3->set('username', $username);
        $f3->set('password', $password);


        if(valid())
        {

        }

        $f3->reroute('views/profile.html');
    }
    $view = new Template();
    echo $view->render('views/loggin.html');
});


//define a search page route
$f3->route('GET|POST /search', function ($f3)
{
    if(!empty($_POST))
    {
        $cartItem = new CartItem();
        $f3->reroute('/summary');
    }

    $view = new Template();
    echo $view->render('views/search-page.html');
});

//Run fat-free
$f3->run();
?>