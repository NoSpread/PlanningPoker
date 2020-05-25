<?php

require_once("Account.php");

if (!isset($_GET["name"]) || !isset($_GET["password1"]) || !isset($_GET["password2"]) || !isset($_GET["email"])) {
    redirect("../../pages/register.php?msg=" . "Empty username or password");
}

if ($_GET["password1"] != $_GET["password2"]) {
    redirect("../../pages/register.php?msg=" . "Passwords do not match");
}

try {
    $acc = new Account($_GET["email"], $_GET["name"], $_GET["password1"]);
    $acc->create();
} catch (Exception $e) {
    redirect("../../pages/register.php?msg=" . $e->getMessage());
} finally {
    redirect("../../pages/login.php");
}

function redirect(String $location) {
    $head = "Location: " . $location;
    header(str_replace(PHP_EOL, '', $head));
    die();
}