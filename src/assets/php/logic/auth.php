<?php

require_once "../assets/php/classes/Utils.php";
require_once "../assets/php/classes/Account.php";

$_SESSION["LOGGEDIN"] = false;
if (isset($_SESSION['USER'])) {
    $_SESSION["LOGGEDIN"] = true;
}

if (isset($_COOKIE["remember"]) && !$_SESSION["LOGGEDIN"]) {
    $selector = substr($_COOKIE["remember"], 0, 12);
    $validator = substr($_COOKIE["remember"], 12);
    
    $acc = new Account();
    $acc = $acc->getAccountBySelector($selector, $validator);
    if ($acc !== false) {
        $_SESSION["USER"] = serialize($acc);
        $_SESSION["LOGGEDIN"] = true;
    } else {
        unset($_COOKIE['remember']); 
        setcookie('remember', null, -1, '/'); 
    }
}

$file = Utils::getCurrentUrl()['filename'];



if (($file == "login" || $file == "register") && $_SESSION['LOGGEDIN']) Utils::redirect("index.php");
if ($file == "login" || $file == "register" || $file == "index" || $file == "pages" || $file == "information") return;


if (!$_SESSION['LOGGEDIN']) {
    Utils::redirect("login.php");    
    $_SESSION['LASTERROR'][] = "You must be logged in to do that.";
}


