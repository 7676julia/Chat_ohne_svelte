<?php
require("start.php");
$user = new Model\User("Test"); 
$json = json_encode($user); echo $json;
?>