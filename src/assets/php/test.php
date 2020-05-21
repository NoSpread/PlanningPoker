<?php

include_once("Account.php");
include_once("Game.php");

session_start();


if (!isset($_SESSION['USER'])){
    $acc = new Account("admin@mail.de", "admin", "TollesTESTpw1448434545");

    $acc1 = new Account();
    $acc1->getAccountByID(2);
    $acc2 = new Account();
    $acc2->getAccountByID(3);
    $acc3 = new Account();
    $acc3->getAccountByID(4);

    $acc->login("TollesTESTpw1448434545");
}

$acc = $_SESSION['USER'];
print "HELLO $acc->username \n";
print "CREATE GAME";

$game = new Game();
$game->start("TEST TOPIC", $acc, [$acc1, $acc2, $acc3]);

$game->stop();
