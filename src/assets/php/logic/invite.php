<?php

require_once "../classes/Game.php";
require_once "../classes/Account.php";

session_start();

if (isset($_SESSION['LOGGEDIN']) && $_SESSION['LOGGEDIN'] && isset($_POST["players"]) && !empty($_POST["players"]) && isset($_POST["gameid"]) && !empty($_POST["gameid"])) {
    $account = unserialize($_SESSION['USER']);
} else {
    echo 0;
} 

$players = explode(",", $_POST["players"]);
$players = preg_replace('/[^a-zA-Z0-9\u00c4-\u00df\u00e4-\u00fc]/', "", $players);

$i = 0;
foreach($players as $player) {
    if ($i >= 3) break;
    try {
        $acc = new Account();
        $players[$i] = $acc->getAccountByName($player);
    } catch(Exception $e) {
        echo 0;
    }
    $i++;
}

try {
    $game = new Game();
    foreach($players as $player) {
        $game->invite($account, $player, $_POST["gameid"]);
    }    
    echo 1;
} catch (Exception $e) {
    echo 0;
}