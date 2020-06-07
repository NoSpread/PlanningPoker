<?php

require_once "../classes/Game.php";
require_once "../classes/Account.php";

session_start();

if (isset($_SESSION['LOGGEDIN']) && $_SESSION['LOGGEDIN']) {
    $account = unserialize($_SESSION['USER']);
} else {
    echo 0;
    die;
} 

try {
    $game = new Game();
    $acc = new Account();
    $acc->getAccountByID($account->id);
    $game->join($acc, $_POST["gameid"]);
    echo 1;
} catch (Exception $e) {
    $_SESSION["LASTERROR"][] = $e->getMessage();
    echo 0;
    die;
}
