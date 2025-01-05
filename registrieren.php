<?php
//var_dump($_POST);
require("start.php");

$error = ""; 
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';
$confirm = isset($_POST['confirm']) ? trim($_POST['confirm']) : '';

$baseUrl = "http://localhost/api"; // Beispiel-URL

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirmPassword = trim($_POST['confirm'] ?? '');

    if (empty($username) || strlen($username) < 3) {
        $usernameError = "Der Nutzername muss mindestens 3 Zeichen lang sein.";
    } elseif (empty($password)) {
        $passwordError = "Bitte geben Sie ein Passwort ein.";
    } elseif (strlen($password) < 8) {
        $passwordError = "Das Passwort muss mindestens 8 Zeichen lang sein.";
    } elseif ($password !== $confirmPassword) {
        $confirmPasswordError = "Die Passwörter stimmen nicht überein.";
    } elseif ($service->loadUser($username)) {
        $usernameError = "Der Nutzername ist bereits vergeben.";
    } else {
        if ($service->register($username, $password)) {
            $user = $service->loadUser($username);
            if ($user) {
                $_SESSION['user'] = $username;
                header("Location: freundeliste.php");
                exit();
            }
        }
        $usernameError = "Die Registrierung ist fehlgeschlagen.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- CSS-Framework von Bootstrap -->
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' rel='stylesheet' crossorigin='anonymous'>
    <script src="main.js"></script>
</head>

<body>
    <div class="container mt-4 d-flex justify-content-center">
    <div class="col-md-4"> <!-- Set the width of the form to about one-third of the screen -->
            <img src="images/user.png" style="height: 100px" class="img-fluid rounded-circle mx-auto d-block"> <!-- Centered image -->
            <h1 class="text-center">Register yourself</h1> <!-- Centered heading -->

            <?php if ($error): ?>
                <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <div class="border rounded p-4 shadow-sm" style="max-width: 400px; margin: auto;">    
            <form action="registrieren.php" method="post" id="registerForm" class="needs-validation" novalidate>
    <fieldset>
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" 
                   value="<?php echo htmlspecialchars($username); ?>" 
                   placeholder="Enter your username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" 
                   placeholder="Enter your password" required>
        </div>
        <div class="mb-3">
            <label for="confirm" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="confirm" name="confirm" 
                   placeholder="Confirm your password" required>
        </div>
    </fieldset>
    <div class="btn-group d-flex justify-content-between" role="group">
        <button type="button" class="btn btn-secondary" onclick="window.location.href='login.php'">Cancel</button>
        <button type="submit" class="btn btn-primary">Create Account</button>
    </div>
</form>

        </div>

    </div>
    <!-- Notwendige JavaScript-Abhängigkeiten -->
    <script src='https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js' crossorigin='anonymous'></script>
    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js' crossorigin='anonymous'></script>
    <script src="aScript.js"></script>
</body>

</html>