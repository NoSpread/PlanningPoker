<?php

require_once("../../../submodules/PHP-MySQLi-Database-Class/MysqliDb.php");

$db = new MysqliDb([
    'host' => 'localhost',
    'username' => 'root', 
    'password' => '',
    'db'=> 'planningpoker',
    'port' => 3306,
    'charset' => 'utf8'
]);