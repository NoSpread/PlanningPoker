<?php

/**
 * INPUT: Card to be drawn
 * OUTPUT: TRUE/FALSE
*/

require_once "../classes/Game.php";
require_once "../classes/Account.php";
//require_once "auth.php";

session_start();

if (isset($_SESSION['LOGGEDIN']) && $_SESSION['LOGGEDIN'] && isset($_POST["cardid"]) ) {
    $account = unserialize($_SESSION['USER']);
} else {
    echo 0;
    die;
} 

try {
    $game = new Game();
    echo $game->pickCard($account, $_POST["cardid"], $_POST["gameid"], $_POST["cardid"]);
    
} catch (Exception $e) {
    $_SESSION["LASTERROR"][] = $e->getMessage();
    die;
}

 