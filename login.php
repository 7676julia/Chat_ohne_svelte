<?php
    require("start.php");

    $error = ""; // Variable für Fehlermeldungen

    if (!empty($_POST['username']) && !empty($_POST['password'])) {
        // Beide Felder wurden ausgefüllt
        $username = $_POST['username'];
        $password = $_POST['password'];

        if (BackendService::login($username, $password)) {
            $_SESSION['user'] = $username; // Nutzername in der Session speichern
            header("Location: friends.php"); // Weiterleitung
            exit(); // Beendet den weiteren Code
        }

            // Logik zur Überprüfung der Daten
            $error = "Formulardaten verarbeitet. Username: $username";
        } else {
            $error = "Bitte alle Felder ausfüllen!";
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
    <?php if (!empty($error)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
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