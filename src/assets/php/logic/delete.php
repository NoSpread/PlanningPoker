<?php

require_once "../classes/Account.php";
require_once "../classes/Utils.php";

session_start();

if (isset($_SESSION['LOGGEDIN']) && $_SESSION['LOGGEDIN']) {
    $account = unserialize($_SESSION['USER']);
} else {
    Utils::redirect("../../../pages/index");
}

try {
    $acc = new Account();
    echo $acc->delete($_GET["password"], $account->id);
    Utils::redirect("../../../pages/logout");
} catch (Exception $e) {
    $_SESSION['LASTERROR'][] = $e->getMessage();
    Utils::redirect("../../../pages/index");
}
