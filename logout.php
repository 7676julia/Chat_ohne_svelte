<?php
require("start.php");
session_unset();
header("Location: index.php"); // Weiterleitung zur Startseite
exit(); // Wichtiger Exit-Befehl nach Header
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="app.css">
</head>
<body>
    <img src="images/logout.png" style="height: 100px">
    <h1>Logged out...</h1>
    <p>See u!</p>
    <p>
    <a href="login.php">Login again</a>
    </p>
</body>
</html>