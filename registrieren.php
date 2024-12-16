<?php
var_dump($_POST);
require("start.php");
require_once __DIR__ . '/Utils/BackendService.php';

use Utils\BackendService;

$error = ""; 
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';
$confirm = isset($_POST['confirm']) ? trim($_POST['confirm']) : '';

$baseUrl = "http://localhost/api"; // Beispiel-URL
$collectionId = "1234"; // Beispiel-ID
$backendService = new BackendService($baseUrl, $collectionId);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($username)) {
        $error = "Bitte geben Sie einen Nutzernamen ein.";
    }
    elseif(strlen($username) < 3 ){
        $error = "Der Nutzername muss mindestens 3 Zeichen lang sein.";
    } elseif (empty($password)) {
        $error = "Bitte geben Sie ein Passwort ein.";
    }elseif (strlen($password) < 8){
        $error = "Das Passwort muss mindestens 8 Zeichen lang sein.";
    }elseif ($password !== $confirm) {
        $error = "Die Passwörter stimmen nicht überein.";
    }elseif ($backendService->loadUser($username)) { 
        $error = "Der Nutzername ist bereits vergeben.";
    }else {
        // Registrierung ausführen
        if ($backendService->login($username, $password)) {
            $_SESSION['user'] = $username;
            header("Location: login.php");
            exit();
            } else {
                $error = "Die Registrierung ist fehlgeschlagen.";
            }
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

    <form action="register.php" method="post" id="registerForm">
        <fieldset>
            <div class="field">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" placeholder="Enter your username">
            </div>
            <div class="field">
                <label for="password">Password</label>
                <input type="text" id="password" placeholder="Enter your password">
            </div>
            <div class="field">
                <label for="confirm">Confirm Password</label>
                <input type="text" id="confirm" placeholder="Confirm your password">
            </div>
        </fieldset>
        <div class="button-container">
            <a href="login.php">
                <button type="button" value="Cancel">
            </a>

            <button type="submit" value="Create Account">
        </div>
    </form>
    <script src="aScript.js"></script>
</body>

</html>