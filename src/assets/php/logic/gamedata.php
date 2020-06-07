<?php

require_once "../classes/Game.php";
require_once "../classes/Account.php";
//require_once "auth.php";

session_start();

if (isset($_SESSION['LOGGEDIN']) && $_SESSION['LOGGEDIN'] && isset($_POST["gameid"]) ) {
    $account = unserialize($_SESSION['USER']);
} else {
    echo 0;
    die;
} 

try {
    $game = new Game();
    $game->load($_POST["gameid"]);
    
    $data = [
        "id" => $game->id,
        "topic" => $game->topic,
        "host" => $game->players[0],
        "players" => []
    ];
    
    foreach ($game->players as $player) {
        array_push($data["players"], [
            "id" => $player->id,
            "username" => $player->username
        ]);
    }
    
    echo json_encode($data);
} catch (Exception $e) {
    $_SESSION["LASTERROR"][] = $e->getMessage();
    die;
}
