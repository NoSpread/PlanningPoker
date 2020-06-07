<?php

require_once(dirname(__DIR__, 4). "\submodules\PHP-MySQLi-Database-Class\MysqliDb.php");
// database access parameters
$db = new MysqliDb([
    'host' => 'localhost',
    'username' => 'root', 
    'password' => '',
    'db'=> 'planningpoker',
    'port' => 3306,
    'charset' => 'utf8'
]);