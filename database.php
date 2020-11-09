<?php
session_start();
require_once ('Medoo.php');

use Medoo\Medoo;

$database = new Medoo ([
    'database_type' => 'mysql',
	'database_name' => 'formulaire_medoo',
	'server' => 'localhost',
	'username' => 'root',
    'password' => 'root',

    'port' => 8889,

    'charset' => 'utf8mb4',
]);
?>