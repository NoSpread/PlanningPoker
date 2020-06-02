<?php

include_once("Account.php");
include_once("Game.php");

session_start();


if (!isset($_SESSION['USER'])){
    $acc = new Account();
    $acc->getAccountByName("admin@mail.de");
    $acc->login("TollesTESTpw1448434545");
}

$account = unserialize($_SESSION['USER']);
print "HELLO $account->username \n";

$acc = new Account();
$acc->getAccountByID(2);
$acc->fetchOpenInvites();


