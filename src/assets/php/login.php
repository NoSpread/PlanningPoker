<?php

session_start();
require_once "Account.php";
$_SESSION['LASTERROR'] = [];

// name or password not entered
if (!isset($_GET["name"]) || !isset($_GET["password"])) {
    $_SESSION['LASTERROR'][] = "Empty username or password";
    Utils::redirect("../../pages/login.php");
}

// try to login with entered credentials
try {
    $acc = new Account();
    $acc->getAccountByName($_GET["name"]);
    $acc->login($_GET["password"]);
} catch (Exception $e) {
    $_SESSION['LASTERROR'][] = $e->getMessage();
    Utils::redirect("../../pages/login.php");
} finally {
    Utils::redirect("../../pages/index.php");
}
