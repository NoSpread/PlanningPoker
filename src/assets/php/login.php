<?php

require_once("Account.php");

if (!isset($_GET["name"]) || !isset($_GET["password"])) {
    redirect("../../pages/login.php?msg=" . "Empty username or password");
}


try {
    $acc = new Account();
    $acc->getAccountByName($_GET["name"]);
    $acc->login($_GET["password"]);
} catch (Exception $e) {
    redirect("../../pages/login.php?msg=" . $e->getMessage());
} finally {
    redirect("../../pages/index.php");
}


function redirect(String $location) {
    $head = "Location: " . $location;
    header(str_replace(PHP_EOL, '', $head));
    die();
}