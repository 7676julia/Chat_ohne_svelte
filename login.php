<?php
require("start.php");


// If already logged in, redirect to friends list
if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
    header("Location: freundeliste.php");
    exit();
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $error = "Please fill in all fields";
    } else {
        try {
            if ($service->login($username, $password)) {
                $_SESSION['user'] = $username;
                header("Location: freundeliste.php");
                exit();
            } else {
                $error = "Invalid username or password";
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            $error = "An error occurred during login. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Chat Application</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="app.css">
    <style>
        .bg {
            background-c /* Helles Grau */
        }
        </style>
</head>
<body>
    <div class="container mt-4 d-flex justify-content-center">
    <div class="col-md-4">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <img src="images/chat.png" alt="Chat Logo" style="height: 100px" class="img-fluid rounded-circle mx-auto d-block">
        <div class="container-fluid">
        <div class="card p-4" style="max-width: 400px; margin: auto;">
            <h2 class="text-center mb-4" >Please sign in</h1>
            
            <?php if (!empty($error)): ?>
                <p class="text-center" style="color: red;"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <div class="border rounded p-4 shadow-sm" style="max-width: 400px; margin: auto;">
            <form action="login.php" method="post">
                <fieldset>
                    <div class="mb-3">
                        <label for="username">Username</label>
                        <input type="text" 
                            id="username" 
                            name="username" 
                            placeholder="Username"
                            value="<?= htmlspecialchars($username ?? '') ?>">
                    </div>
                    <div class="field">
                        <label for="password">Password</label>
                        <input type="password" 
                            id="password" 
                            name="password" 
                            placeholder="Password">
                    </div>
                    </fieldset>
                <div class="button-container">
                    <button type="button" onclick="window.location.href='registrieren.php'">Register</button>
                    <input type="submit" value="Login">
                </div>
            </div>
            </div>
        </form>
</body>
</html>
