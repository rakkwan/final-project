<?php

$user = $_SERVER['USER'];
require "/home/$user/config-project.php";
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
    $_SESSION['cartSize'] = 0;

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
    $image = $_POST['image'];
    $name = $_POST['name'];
    $price = $_POST['price'];

    if(!empty($_POST))
    {
        $cartItem = new CartItem($image, $name, $price);
        $_SESSION['cart'][] = $cartItem;
        $_SESSION['cartSize'] = $_SESSION['cartSize']+1;
    }

    $view = new Template();
    echo $view->render('views/search-page.html');
});

$f3->route('GET|POST /cart', function ($f3) {
    $view = new Template();
    echo $view->render('views/cart.html');
});

//Run fat-free
$f3->run();
?>