<?php
require("start.php");

// Check if user is logged in
if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Get profile username from URL parameter - now using 'friend' to match chat.php
$profileUsername = $_GET['friend'] ?? $_SESSION['user']; // Default to own profile if no friend specified

try {
    // Load user profile
    $profileUser = $service->loadUser($profileUsername);
    if (!$profileUser) {
        throw new Exception("User not found");
    }

    // Check if viewing user is a friend (only if viewing someone else's profile)
    $isFriend = false;
    if ($profileUsername !== $_SESSION['user']) {
        $friends = $service->loadFriends();
        foreach ($friends as $friend) {
            if ($friend->username === $profileUsername && $friend->status === "accepted") {
                $isFriend = true;
                break;
            }
        }
    }
} catch (Exception $e) {
    // Log error but don't expose details to user
    error_log($e->getMessage());
    header("Location: freundeliste.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <!-- CSS-Framework von Bootstrap -->
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' rel='stylesheet' crossorigin='anonymous'>
</head>
<body>
    <h1 class="left">Profile of <?= htmlspecialchars($profileUsername) ?></h1>
    <a href="freundeliste.php" class="leftL"> &lt; Back to Chat</a>
    <?php if ($isFriend): ?>
        |
        <a href="freundeliste.php?action=remove&friend=<?= urlencode($profileUsername) ?>" 
           class="leftL critical">Remove Friend</a>
    <?php endif; ?>
    <hr>
    
    <div class="profile-container">
        <img src="images/user.png" id="profilPicture" alt="Profile Picture">
        <fieldset>
            <p><?= nl2br(htmlspecialchars($profileUser->description ?? '')) ?></p>
            <p><strong>Coffee or Tea?</strong></p>
            <p class="question"><?= htmlspecialchars($profileUser->coffeeOrTea ?? 'Not specified') ?></p>
            <p><strong>Name</strong></p>
            <p class="question">
                <?= htmlspecialchars(trim(($profileUser->firstName ?? '') . ' ' . 
                    ($profileUser->lastName ?? ''))) ?>
            </p>
            
            <?php if ($profileUsername === $_SESSION['user']): ?>
                <h3>Profile Change History</h3>
                <ul>
                    <?php 
                    $history = $profileUser->changeHistory ?? [];
                    foreach ($history as $change): 
                    ?>
                        <li><?= htmlspecialchars($change) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </fieldset>
    </div>
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
    
     <!-- Notwendige JavaScript-Abhängigkeiten -->
     <script src='https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js' crossorigin='anonymous'></script>
    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js' crossorigin='anonymous'></script>
</body>

</html>
</body>
</html>
