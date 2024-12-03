// Hilsdatei um Login zu umgehen, bis es funktionert
<?php 
require ("start.php");
$service->login("Tom", "12345678");
$_SESSION['username'] = "Tom";