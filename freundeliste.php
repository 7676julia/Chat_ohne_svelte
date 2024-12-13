<?php
require("start.php");
if (!isset($_SESSION['user']) || empty($_SESSION['user'])){
    header("Location: login.php");
    exit();
}
//verzweiflung ist verzweifelt
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $aktion = $_POST['aktion']; // Wert des Buttons
    $username = $_POST['username']; // Get the username of the friend

    if ($aktion == "akzeptieren") {
        // Logik für die Akzeptieren-Aktion
        echo "Akzeptieren-Button wurde gedrückt für: " . htmlspecialchars($username);
        // Here you should add your logic to accept the friend request
    }
    // Weitere Bedingungen für andere Aktionen
}
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="app.css">
    <script src="frendesliste.js"></script>
</head>
<body>
    <h1>Friends</h1>
    <a href="logout.html" Logout> &lt; Logout</a> | <a href="einstellungen.html">Settings</a>
    <hr>
    <ul id = "friends-list">
        
    </ul>

    <hr>
    <h2>New Requests</h2>
    <ol id = "friend-requests">
</ol>
    
    <hr>

    <form action="freundeliste.html" method="get">
        <label for="eintragFeld"></label>
        <input type="text" id="eintragFeld" name="eintrag" placeholder="Add friends to List" list="friend-selector">
        <datalist id="friend-selector">
        </datalist>
        <input type="submit" value="Add">
    </form>
  
</body>
</html>
