<?php

include_once("Account.php");
include_once("Game.php");

session_start();


if (!isset($_SESSION['USER'])){
    $acc = new Account("admin@mail.de", "admin", "TollesTESTpw1448434545");

/* 
    $acc->create();

    $acc1 = new Account("support@mail.de", "supporter", "TollesTESTpw1448434545");
    $acc1->create();

    $acc2 = new Account("tadi@mail.de", "Tatjana", "TollesTESTpw1448434545");
    $acc2->create();

    $acc3 = new Account("lucas@mail.de", "Lucas", "TollesTESTpw1448434545");
    $acc3->create(); */

    $acc->login("TollesTESTpw1448434545");
}

$acc = $_SESSION['USER'];
print "HELLO $acc->username \n";
print "CREATE GAME";

$game = new Game();
$game->start("TEST TOPIC", [1, 2, 3, 4]);

$game->stop();

$game->delete();