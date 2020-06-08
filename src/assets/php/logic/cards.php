<?php

require_once "../classes/Game.php";
require_once "../classes/Utils.php";

session_start();

if (isset($_SESSION['LOGGEDIN']) && $_SESSION['LOGGEDIN'] && isset($_POST["gameid"]) ) {
    $account = unserialize($_SESSION['USER']);
} else {
    echo 0;
    die;
} 

$game = new Game();
$game->load($_POST["gameid"]);

try {
    $cardData = $game->playedCards($_POST["gameid"]);

    if ($cardData == []) {
        $cardData["stats"] = [];
        $cardData["user"] = [];
    }

    $gamestatus = $game->end_date === null ? true : false;
    $output = [
        "game" => $gamestatus,
        "players" => [],
        "userdata" => [],
        "stats" => $cardData["stats"]
    ];

    foreach($game->players as $player) {
        array_push($output["players"], $player->username); // fill players-array with players
    }
    
    foreach($cardData["user"] as $user) {
        array_push($output["userdata"], [
            "userid" => $user["account"],
            "card" => $user["card"],
            "index" => getUserIndex($game, $user["account"]) // fill userdata-array with userid, card and userindex
        ]);
    }

    if ($_SESSION["LASTERROR"] == []) {
        if (!$gamestatus && $account->id != $game->players[0]->id) $_SESSION["LASTERROR"][] = '<span style="padding: 0.5rem; border-bottom: 2px solid #4285f4;">' . $game->players[0]->username . '</span> has ended the game.';
    }

    echo json_encode($output);
} catch (Exception $e) {
    echo json_encode([
        "error" => $e->getMessage()
    ]);
}

die;


/**
 * get user-index
 * @param  mixed $game
 * @param  mixed $user
 * @return $user_client_id
 */
function getUserIndex($game, $user)
{
    $user_client_id = 0;
    foreach ($game->players as $player) {
        $user_client_id++;
        if ($player->id == $user) {
            return $user_client_id++;
            break;
        }
    }
}