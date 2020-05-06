<?php

include_once("Account.php");
include_once("Game.php");

session_start();


if (!isset($_SESSION['USER'])){
    $acc = new Account("admin@mail.de", "admin", "TollesTESTpw1448434545");
    $acc->login("TollesTESTpw1448434545");
}

$acc = $_SESSION['USER'];
print "HELLO $acc->username";
print "\CREATE GAME";

$game = new Game();
$game->start("TEST TOPIC", [11, 2, 4]);
$game->invite($acc);
sleep(5);
$game->stop();
sleep(5);
$game->delete();