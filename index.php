<?php

//Require autoload file
require_once('vendor/autoload.php');

session_start();

/*
 * Name: Maxwell Lee and Jittima Goodrich
 * Date: 6/4/2019
 * File: index.php use for routing and store session data
 */

//Turn on error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

//Get validation functions
require_once ('model/validation.php');

//Create an instance of the Base class
$f3 = Base::instance();

//Establish connection to database
$db = new Database();

//If the cart is not created yet, then initialize it
if(!isset($_SESSION['user']))
{
    $_SESSION['user'] = new Guest();
}

//define a default route
$f3->route('GET /', function()
{
    echo $_SESSION['user']->getSizeOfCart();
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

        // if data is valid...
        if (validForm())
        {
            //create a new user and register them into the database
            $user = new User($_POST['fname'], $_POST['lname'], $_POST['address'], $_POST['email'], $_POST['pass']);
            $user->setCart($_SESSION['user']->getCart());
            $_SESSION['user'] = $user;
            global $db;
            $db->register($user);
            $_SESSION['userID'] = $f3->get('userID');

            $f3->reroute('/thankyou');
        }
    }

    $view = new Template();
    echo $view->render('views/register.html');
});

//define a login route
$f3->route('GET|POST /login', function ($f3)
{
    if(!empty($_SESSION['userID']))
    {
        $f3->reroute('/profile');
    }

    if(!empty($_POST))
    {
        //if user submitted data to the login section of the login page...
        if($_POST['redirect'] == "login")
        {
            global $db;

            //try to login(checks if username and password are in the database
            $user = $db->login($_POST['username'], $_POST['password']);

            //if a result is retrieved from the database...
            if(!empty($user['user_id']))
            {
                //add the user's id to session and then go to the profile page
                $_SESSION['userID'] = $f3->get('userID');
                $f3->reroute('/profile');
            }
            $f3->set('errors', 'No matching user');
        }

        //if the user is trying to register, then go to the register page
        if($_POST['redirect'] == "register")
        {
            $f3->reroute('/register');

        }
    }

    $view = new Template();
    echo $view->render('views/loggin.html');
});

// define a logout route
$f3->route('GET|POST /logout', function ($f3)
{
    //if the user is not logged in, then go to login page
    if (empty($_SESSION['userID']))
    {
        $f3->reroute('/login');
    }

    $_SESSION = []; // Clear the variables.
    session_destroy(); // Destroy the session itself.

    //restart the session and the cart variables
    session_start();
    $_SESSION['user'] = new Guest();

    $view = new Template();
    echo $view->render('views/logout.html');

});


//define a search page route
$f3->route('GET|POST /search', function ($f3)
{
    //get the product's info
    $image = $_POST['image'];
    $name = $_POST['name'];
    $price = $_POST['price'];

    if(!empty($_POST))
    {
        //if the user tried to delete their cart, reset the variables
        if($_POST['cartDelete'] == 'delete')
        {
            $_SESSION['user']->deleteCart();
        }
        else
        {
            if(!empty($_POST['name']))
            {
                //Add the product to the cart as a CartItem.
                $cartItem = new CartItem($image, $name, $price);
                $_SESSION['user']->addItemToCart($cartItem);
            }
        }
    }

    $view = new Template();
    echo $view->render('views/search-page.html');
});


// define a profile route
$f3->route('GET|POST /profile', function($f3)
{
    global $db;

    //if the user is trying to change something...
    if(!empty($_POST))
    {
        //if the user has come from the cart page and bought their cart, then reset the cart variables
        if(isset($_POST['cartBought']))
        {
            $_SESSION['user']->deleteCart();
        }

        //change the user's address
        if(isset($_POST['newaddress']))
        {
            if(validAddress($_POST['newaddress']))
            {
                $db->changeAddress($_SESSION['userID'], $_POST['newaddress']);
            }
            else
            {
                $f3->set('error', 'Invalid new address');
            }
        }

        //change the user's email
        if(isset($_POST['newusername']))
        {
            if (validEmail($_POST['newusername']))
            {
                $db->changeUsername($_SESSION['userID'], $_POST['newusername']);
            }
            else
            {
                $f3->set('error', 'Invalid new username/email');
            }
        }

        //change the user's password
        if(isset($_POST['newpassword']))
        {
            if(validPassword($_POST['newpassword']))
            {
                $db->changePassword($_SESSION['userID'], $_POST['newpassword']);
            }
            else
            {
                $f3->set('error', 'Invalid new password');
            }
        }

        //delete an order
        if(isset($_POST['orderID']))
        {
            $db->deleteOrder($_POST['orderID']);
        }
    }

    //retrieve the user's info and orders
    $users = $db->getUsers($_SESSION['userID']);
    $orders = $db->getOrders($_SESSION['userID']);

    //set the user's info and orders into the hive
    $f3->set('users', $users);
    $f3->set('orders', $orders);

    $template = new Template();
    echo $template->render('views/profile.html');
});

$f3->route('GET|POST /thankyou', function($f3)
{
    global $db;

    //retrieve the new user's info
    $user = $db->getUsers($_SESSION['userID']);
    $f3->set('user', $user);

    $template = new Template();
    echo $template->render('views/thankyou.html');
});

$f3->route('GET|POST /cart', function ($f3)
{
    global $db;
    $address = $db->getAddress($_SESSION['userID']);
    $f3->set('userAddress', $address['address']);

    $f3->set('cart', $_SESSION['user']->getCart());
    $cartTotal = 0;
    $priceString = '';
    $pictureString = '';
    $itemString = '';

    foreach ($_SESSION['user']->getCart() as $item)
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

    $view = new Template();
    echo $view->render('views/cart.html');
});

//Run fat-free
$f3->run();
