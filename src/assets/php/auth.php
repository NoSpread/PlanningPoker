<?php

require_once "Utils.php";

$file = Utils::getCurrentUrl()['filename'];



if (($file == "login" || $file == "register") && isset($_SESSION['USER'])) Utils::redirect("index.php");
if ($file == "login" || $file == "register") return;


$_SESSION['LASTERROR'][] = "You must be logged in to do that.";
if (!isset($_SESSION['USER'])) Utils::redirect("login.php");
