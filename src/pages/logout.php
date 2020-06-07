<?php
session_start();
require_once "../assets/php/classes/Account.php";
require_once "../assets/php/classes/Utils.php";

Account::logout();
if (isset($_COOKIE['remember'])) {
    unset($_COOKIE['remember']); 
    setcookie('remember', null, -1, '/'); 
}
Utils::redirect("login.php");
