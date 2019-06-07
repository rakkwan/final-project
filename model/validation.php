<?php

/**
 * used to validate the register form
 * @return bool if everything was valid or not
 */
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
        $f3->set("errors['pass1']", "Password must be matched, Please re-enter your password");
    }
    return $isValid;
}


/**
 * Checks if the name given was valid
 * @param String $name the name given
 * @return bool if the name was valid
 */
function validName($name)
{
    return !empty($name) && ctype_alpha($name);
}

/**
 * Checks if the address is empty
 * @param String $address the address given
 * @return bool if the address was valid
 */
function validAddress($address)
{
    return !empty($address);
}

/**
 * Checks if password was valid
 * @param String $password the password given
 * @return bool if the password was 7 characters or longer
 */
function validPassword($password)
{
    return !empty($password) && strlen($password) >= 7;
}

/**
 * Checks if the email is valid
 * @param String $email the email given
 * @return bool if the email was valid ot not
 */
function validEmail($email)
{
    global $db;
    $emailValid = $db->checkEmail($email);
    if (empty($emailValid))
    {
        return !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    return false;
}

/**
 * Checks if the passwords given match
 * @param String $pass the first password
 * @param String $pass1 the second password
 * @return bool if the passwords match
 */
function validSamePass($pass, $pass1)
{
    if(!empty($pass) == !empty($pass1))
    {
        return $pass == $pass1;
    }
    return false;
}

