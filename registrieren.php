<?php
require("start.php");

$error = ""; 
$username = $_POST['username'];
$password = $_POST['password'];
$confirm = $_POST['confirm'];

if(strlen($username) < 3 ){
    $error = "Der Nutzername muss mindestens 3 Zeichen lang sein.";
}elseif (strlen($password) < 8){
    $error = "Das Passwort muss mindestens 8 Zeichen lang sein.";
}elseif ($password !== $confirm) {
    $error = "Die Passwörter stimmen nicht überein.";
}elseif (BackendService::loadUser($username)) { 
    $error = "Der Nutzername ist bereits vergeben.";
}else {
    // Registrierung ausführen
    if (BackendService::register($username, $password)) {
        $_SESSION['user'] = $username;
        header("Location: login.php");
        exit();
    } else {
        $error = "Die Registrierung ist fehlgeschlagen.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="app.css">
    <script src="main.js"></script>
</head>

<body>
    <img src="images/user.png" style="height: 100px">
    <h1>Register yourself</h1>

    <?php if ($error): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    
    <form action="freundeliste.php" method="get" id="registerForm">
        <fieldset>
            <div class="field">
                <label for="username">Username</label>
                <input type="text" id="username" placeholder="Enter your username">
                <span id="usernameError" class="error"></span>
            </div>
            <div class="field">
                <label for="password">Password</label>
                <input type="text" id="password" placeholder="Enter your password">
                <span id="passwordError" class="error"></span>
            </div>
            <div class="field">
                <label for="confirm">Confirm Password</label>
                <input type="text" id="confirm" placeholder="Confirm your password">
                <span id="confirmError" class="error"></span>
            </div>
        </fieldset>
        <div class="button-container">
            <a href="login.php">
                <input type="button" value="Cancel">
            </a>

            <input type="submit" value="Create Account">
        </div>
    </form>
    <script src="aScript.js"></script>
</body>

</html>