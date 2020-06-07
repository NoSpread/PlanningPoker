<?php

require_once "../classes/Game.php";
require_once "../classes/Utils.php";
require_once "../classes/Account.php";

session_start();

if (isset($_SESSION['LOGGEDIN']) && $_SESSION['LOGGEDIN'] && isset($_GET["topic"]) && isset($_GET["players"]) && !empty($_GET["topic"]) && !empty($_GET["players"])) {
    $account = unserialize($_SESSION['USER']);
} else {
    $_SESSION["LASTERROR"][] = "Failed to create game.";
    Utils::redirect("../../../pages/index");
} 

$players = explode(",", $_GET["players"]);
$players = preg_replace('/[^a-zA-Z0-9\u00c4-\u00df\u00e4-\u00fc]/', "", $players);

$i = 0;
foreach($players as $player) {
    if ($i >= 3) break;
    try {
        $acc = new Account();
        $players[$i] = $acc->getAccountByName($player);
        $i++;
    } catch (Exception $e) {
        $_SESSION["LASTERROR"][] = $e->getMessage();
        Utils::redirect("../../../pages/index");
    }
}

try {
    $game = new Game();
    $game->start($_GET["topic"], $account, $players);
    Utils::redirect("../../../pages/game?id={$game->id}");
} catch (Exception $e) {
    $_SESSION["LASTERROR"][] = $e->getMessage();
    Utils::redirect("../../../pages/index");
}