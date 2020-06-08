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

// close a game (only possible for own games)
try {
    $game = new Game();
    $game->load($_POST["gameid"]);
    if ($game->players[0]->id == $account->id) {
        $game->stop();
        echo true;
    }
} catch (Exception $e) {
    $_SESSION['LASTERROR'][] = $e->getMessage();
    echo false;
}

