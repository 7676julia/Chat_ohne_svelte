<?php
    require("start.php");


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Formulardaten abholen
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (!empty($username) && !empty($password)) {
            // Logik zur Überprüfung der Daten
            echo "Formulardaten verarbeitet. Username: $username";
        } else {
            echo "Bitte alle Felder ausfüllen!";
        }
    } else {
        echo "Das Formular wurde noch nicht abgeschickt.";
    }

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
    <img src="images/chat.png" style="height: 100px">
    <h1>Please sign in</h1>
    <form action="freundeliste.php" method="post">
        <fieldset>
            <legend>Login</legend>
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Username"><br>
            <label for="password">Password</label>
            <input type="text" id="password" name="password" placeholder="Password"><br>
        </fieldset>
        <br>
        <div class="button-container">
        <button type="button" onclick="window.location.href='registrieren.php'">Register</button>
        <input type="submit" value="Login">
        </div>
    </form>
    <?php
        var_dump($_POST); //zur Überpfüfung was versendet wurde
    ?>
</body>

</html>