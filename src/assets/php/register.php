<?php

session_start();
require_once "Account.php";
$_SESSION['LASTERROR'] = [];
// name, password or email not entered
if (!isset($_GET["name"]) || !isset($_GET["password1"]) || !isset($_GET["password2"]) || !isset($_GET["email"])) {
    $_SESSION['LASTERROR'][] = "Empty username or password";
}

if ($_GET["name"] < 3) {
    $_SESSION['LASTERROR'][] = "Username must be at least 3 characters";
}

// passwords do not match
if ($_GET["password1"] !== $_GET["password2"]) {
    $_SESSION['LASTERROR'][] = "Passwords must match";
}

Account::checkPasswordRequirements($_GET["password1"], $_SESSION['LASTERROR']);

if (!filter_var($_GET["email"], FILTER_VALIDATE_EMAIL)) {
    $_SESSION['LASTERROR'][] = "E-Mail must be valid";
}

if ($_SESSION['LASTERROR'] != []) {
    Utils::redirect("../../pages/register.php");
}

// try to register with entered credentials
try {
    $acc = new Account($_GET["email"], $_GET["name"], $_GET["password1"]);
    $acc->create();
} catch (Exception $e) {
    $_SESSION['LASTERROR'][] = $e->getMessage();
    Utils::redirect("../../pages/register.php");
} finally {
    Utils::redirect("../../pages/login.php");
}
