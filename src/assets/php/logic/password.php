<?php

require_once "../classes/Account.php";
require_once "../classes/Utils.php";

session_start();

if (isset($_SESSION['LOGGEDIN']) && $_SESSION['LOGGEDIN'] && isset($_GET["currPassword"]) && isset($_GET["password1"]) && isset($_GET["password2"]) && isset($_GET["ref"])) {
    $account = unserialize($_SESSION['USER']);
} else {
    Utils::redirect("../../../pages/" . htmlspecialchars($_GET["ref"]));
} 


$acc = new Account();

if ($account->checkPassword($_GET["currPassword"])) {
    if ($_GET["password1"] == $_GET["password2"]) {
        $acc->checkPasswordRequirements($_GET["password1"], $_SESSION["LASTERROR"]);
        if ($_SESSION["LASTERROR"] == []) {
            $newData = [
                "password" => $_GET["password1"]
            ];
            $acc->update($newData, $_GET["currPassword"], $account->id);
            $acc->clearSession($account->id);
        } else {
            // Missing passowrd req.
            $_SESSION["LASTERROR"][] = "Not all password requirements are satisfied";
        }
    } else {
        $_SESSION["LASTERROR"][] = "The passwords you entered are not the same";
        // passwords not the same
    }
} else {
    $_SESSION["LASTERROR"][] = "The password you entered is wrong";
    // worng opassword
}

if ($_SESSION["LASTERROR"] != []) {
    Utils::redirect("../../../pages/" . htmlspecialchars($_GET["ref"]));
}

Utils::redirect("../../../pages/logout");
