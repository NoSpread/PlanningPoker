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
    $acc = new Account();
    //$acc->getAccountByID($account->id);
    $acc->fetchOwnGames($account->id);
    $acc->fetchOpenInvites($account->id);

    //topic
    //inviter
    //id
    
    $data = [
        "owngames" => [],
        "invitedgames" => []
    ];

    $game = new Game();
    foreach($acc->games as $gameid) {
        $game->load($gameid);
        array_push($data["owngames"], [
            "topic" => $game->topic,
            "id" => $gameid
        ]);
    }

    foreach($acc->gameInvites as $gameid) {
        $game->load($gameid);
        array_push($data["invitedgames"], [
            "topic" => $game->topic,
            "inviter" => $game->players[0]->username,
            "id" => $gameid
        ]);
    }

    $output = json_encode($data);
    echo $output;
} catch (Exception $e) {
    $_SESSION["LASTERROR"][] = $e->getMessage();
    echo 0;
}


