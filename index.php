<?php

//Require autoload file
require_once('vendor/autoload.php');

session_start();

/*
 * Name: Maxwell Lee and Jittima Goodrich
 * Date: 6/4/2019
 * File: index.php use for routing and store session data
 */

//TUrn on error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once ('model/validation.php');

//Create an instance of the Base class
$f3 = Base::instance();

$db = new Database();

//define a default route
$f3->route('GET /', function()
{
    if(!isset($_SESSION['cart']))
    {
        $_SESSION['cart'] = [];
        $_SESSION['cartSize'] = 0;
    }

    $view = new Template();
    echo $view->render('views/home.html');
});

//define a register route
$f3->route('GET|POST /register', function($f3)
{
    if(!empty($_POST))
    {
        // get data from form
        $first = $_POST['fname'];
        $last = $_POST['lname'];
        $address = $_POST['address'];
        $email = $_POST['email'];
        $password = $_POST['pass'];
        $password1 = $_POST['pass1'];

        // add data to hive
        $f3->set('fname', $first);
        $f3->set('lname', $last);
        $f3->set('address', $address);
        $f3->set('email', $email);
        $f3->set('pass', $password);
        $f3->set('pass1', $password1);

        // if data is valid
        if (validForm())
        {
            $user = new User($_POST['fname'], $_POST['lname'], $_POST['address'], $_POST['email'], $_POST['pass']);
            $_SESSION['email'] = $_POST['email'];
            global $db;
            $db->register($user);
            $_SESSION['userID'] = $f3->get('userID');

            $f3->reroute('/thankyou');
        }
    }

    $view = new Template();
    echo $view->render('views/register.html');
});

//define a loggin route
$f3->route('GET|POST /login', function ($f3)
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
    echo $view->render('views/login.html');
});

// define a logout route
$f3->route('GET|POST /logout', function ($f3)
{
    /*
    if (empty($_SESSION['userID']))
    {
        $f3->reroute('/login');
    }
    */
    $_SESSION = []; // Clear the variables.
    session_destroy(); // Destroy the session itself.

    session_start();
    $_SESSION['cart'] = [];
    $_SESSION['cartSize'] = 0;

    $view = new Template();
    echo $view->render('views/logout.html');

});


//define a search page route
$f3->route('GET|POST /search', function ($f3)
{
    $image = $_POST['image'];
    $name = $_POST['name'];
    $price = $_POST['price'];

    if(!empty($_POST))
    {
        if($_POST['cartDelete'] == 'delete')
        {
            $_SESSION['cartSize'] = 0;
            $_SESSION['cart'] = [];
        }
        else
        {
            $cartItem = new CartItem($image, $name, $price);
            array_push($_SESSION['cart'], $cartItem);
            $_SESSION['cartSize'] = $_SESSION['cartSize']+1;
        }
    }

    $view = new Template();
    echo $view->render('views/search-page.html');
});


// define a profile route
$f3->route('GET|POST /profile', function($f3)
{
    //echo 'USERID: '.$_SESSION['userID'];
    global $db;

    if(!empty($_POST))
    {
        if(isset($_POST['cartBought']))
        {
            $_SESSION['cartSize'] = 0;
            $_SESSION['cart'] = [];
        }
        if(isset($_POST['newaddress']))
        {
            $db->changeAddress($_SESSION['userID'], $_POST['newaddress']);
        }

        if(isset($_POST['newusername']))
        {
            if (validEmail($_POST['newusername']))
            {
                $db->changeUsername($_SESSION['userID'], $_POST['newusername']);
            }
        }

        if(isset($_POST['newpassword']))
        {
            if(validPassword($_POST['newpassword']))
            {
                $db->changePassword($_SESSION['userID'], $_POST['newpassword']);
            }
        }
    }

    $users = $db->getUsers($_SESSION['email']);
    $orders = $db->getOrders($_SESSION['userID']);

    $f3->set('users', $users);
    $f3->set('orders', $orders);
    //$f3->set('address', $address);

    $template = new Template();
    echo $template->render('views/profile.html');
});

// define a default route
$f3->route('GET|POST /thankyou', function($f3)
{
    //echo 'USERID: '.$_SESSION['userID'];
    global $db;
    $user = $db->getUsers($_SESSION['email']);
    $f3->set('user', $user);
    $template = new Template();
    echo $template->render('views/thankyou.html');
});

$f3->route('GET|POST /cart', function ($f3)
{
    global $db;
    $address = $db->getAddress($_SESSION['userID']);
    $f3->set('userAddress', $address['address']);

    $f3->set('cart', $_SESSION['cart']);
    $cartTotal = 0;
    $priceString = '';
    $pictureString = '';
    $itemString = '';

    foreach ($_SESSION['cart'] as $item)
    {
        $cartTotal += $item->getPrice();
        $priceString = $priceString.$item->getPrice().', ';
        $pictureString = $pictureString.$item->getImage().', ';
        $itemString = $itemString.$item->getName().', ';
    }

    $f3->set('cartTotal', $cartTotal);
    $tax = number_format($cartTotal*0.1, 2, '.', '');
    $f3->set('tax', $tax);

    $f3->set('priceString', trim($priceString, ', '));
    $f3->set('pictureString', trim($pictureString, ', '));
    $f3->set('itemString', trim($itemString, ', '));
    //echo 'Pictures: '.$pictureString;
    //echo 'Prices: '.$priceString;
    //echo 'Items: '.$itemString;

    $view = new Template();
    echo $view->render('views/cart.html');
});

//Run fat-free
$f3->run();
