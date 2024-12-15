<?php
require("start.php");
if (!isset($_SESSION['user']) || empty($_SESSION['user'])){
    header("Location: login.php");
    exit();
}
if (!isset($_GET['friend']) || empty($_GET['friend'])){
    header("Location: freundeliste.php");
    exit();
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="app.css">
    <script src="chat.js"></script>
    <?php
require("start.php");
$user = new Model\User("Test"); 
$json = json_encode($user); echo $json;
?>
</head>
<body>
    <h1 class="left">Chat with</h1>
    <a href="freundeliste.php" class="leftL"> < Back </a> |
    <a href="profil.php" class="leftL"> Profile </a> |
    <!-- freunde entfernen -->
    <div class="friend-item">
    <span class="friend-username"></span>
    <a href="#" class="remove-friend" data-username="FreundUsername">Freund entfernen</a>
    </div>
    <hr>
    <div>
        <div id="message-container">
            <!-- Messages will be inserted here -->
        </div>
        <div class="chat">
            <form>
                <input type="text" placeholder="Type your message...">
                <input type="button" value="Send" onclick="sendMessage(event)">
            </form>
        </div>
    </div>
</body>
</html>