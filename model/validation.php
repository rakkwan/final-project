<?php

// valid the personal form
function validForm()
{
    global $f3;
    $isValid = true;

    if (!validName($f3->get('fname')))
    {
        $isValid = false;
        $f3->set("errors['fname']", "Please enter a valid first name");
    }

    if (!validName($f3->get('lname')))
    {
        $isValid = false;
        $f3->set("errors['lname']", "Please enter a valid last name");
    }

    if (!validAddress($f3->get('address')))
    {
        $isValid = false;
        $f3->set("errors['address']", "Please enter your address");
    }

    if (!validEmail($f3->get('email')))
    {
        $isValid = false;
        $f3->set("errors['email']", "Please enter a valid email address");
    }

    if (!validPassword($f3->get('pass')))
    {
        $isValid = false;
        $f3->set("errors['pass']", "Please enter a valid password, at least 7 characters");
    }

    if (!validSamePass($f3->get('pass'), $f3->get('pass1')))
    {
        $isValid = false;
        $f3->set("errors['pass1']", "Please re-enter your password");
    }
    return $isValid;
}


// check to see if name is all alphabetic
function validName($name)
{
    return !empty($name) && ctype_alpha($name);
}

//check address can't be empty
function validAddress($address)
{
    return !empty($address);
}

// check to see that password 7 characters
function validPassword($password)
{
    return !empty($password) && strlen($password) >= 7;
}

// check to see that email address is valid
function validEmail($email)
{
    return !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validSamePass($pass, $pass1)
{
    return !empty($pass) == !empty($pass1);
}

