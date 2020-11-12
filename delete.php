<?php
require 'database.php';

$database->delete("users",[
    "id" => $data['id'],
]);
header ('location: home.php');

?>