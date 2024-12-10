<?php
require("start.php");
$service1 = new Utils\BackendService("https://online-lectures-cs.thi.de/chat/", "302241ec-cc05-4668-a0ae-68ecbd6d1ba9"); 
var_dump($service->test());
var_dump($service->login("Test123", "12345678")); 
var_dump($service->loadUser("Test123"));

