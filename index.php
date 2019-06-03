<?php

//Require autoload file
require_once('vendor/autoload.php');

session_start();

//TUrn on error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

//Create an instance of the Base class
$f3 = Base::instance();

$db = new Database();

//define a default route
$f3->route('GET /', function()
{
    $_SESSION['cart'] = [];
    $_SESSION['cartSize'] = 0;

    $view = new Template();
    echo $view->render('views/home.html');
});

//define a register route
$f3->route('GET|POST /register', function($f3)
{
    if(!empty($_POST))
    {
        $user = new User($_POST['fname'], $_POST['lname'], $_POST['address'], $_POST['email'], $_POST['pass']);
        $_SESSION['email'] = $_POST['email'];
        global $db;
        $db->register($user);
        $_SESSION['userID'] = $f3->get('userID');

        $f3->reroute('/thankyou');
    }

    $view = new Template();
    echo $view->render('views/register.html');
});

//define a loggin route
$f3->route('GET|POST /loggin', function ($f3)
{
    if(!empty($_POST))
    {
        if($_POST['redirect'] == "login")
        {
            global $db;
            $user = $db->login($_POST['username'], $_POST['password']);
            if(!empty($user['user_id']))
            {
                $_SESSION['userID'] = $f3->get('userID');
                $_SESSION['email'] = $_POST['username'];
                $f3->reroute('/profile');
            }
            $f3->set('errors', 'No matching user');
        }
        if($_POST['redirect'] == "register")
        {
            $f3->reroute('/register');

        }
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
        array_push($_SESSION['cart'], $cartItem);
        $_SESSION['cartSize'] = $_SESSION['cartSize']+1;
    }

    $view = new Template();
    echo $view->render('views/search-page.html');
});


// define a default route
$f3->route('GET|POST /profile', function($f3)
{
    echo 'USERID: '.$_SESSION['userID'];
    global $db;

    $users = $db->getUsers($_SESSION['email']);

    $f3->set('users', $users);

    $template = new Template();
    echo $template->render('views/profile.html');
});

// define a default route
$f3->route('GET|POST /thankyou', function($f3)
{
    global $db;
    $user = $db->getUsers($_SESSION['email']);
    $f3->set('user', $user);
    $template = new Template();
    echo $template->render('views/thankyou.html');
});


$f3->route('GET|POST /cart', function ($f3)
{
    $f3->set('cart', $_SESSION['cart']);
    $cartTotal = 0;
    foreach ($_SESSION['cart'] as $item)
    {
        $cartTotal += $item->getPrice();
    }
    $f3->set('cartTotal', $cartTotal);
    $tax = number_format($cartTotal*0.1, 2, '.', '');
    $f3->set('tax', $tax);

    $view = new Template();
    echo $view->render('views/cart.html');
});

//Run fat-free
$f3->run();
