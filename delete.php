<?php
require 'database.php';

$database->delete("users",[
    "id" => $_GET['id'],
]);
header ('location: home.php');

?>