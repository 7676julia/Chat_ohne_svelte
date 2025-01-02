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
    <div class="container fluid mt-4 ml-8 mr-8">
        <h1 class="mb-3">Profile of <?= htmlspecialchars($profileUsername) ?></h1>
        
        <div class="btn-group mb-4" role="group" aria-label="Friend Actions">
            <a href="freundeliste.php" class="btn btn-secondary">
                &lt; Back to Chat
            </a>
            <?php if ($isFriend): ?>
                <a href="freundeliste.php?action=remove&friend=<?= urlencode($profileUsername) ?>" 
                   class="btn btn-danger" onclick="return confirm('Are you sure you want to remove this friend?');">
                    Remove Friend
                </a>
            <?php endif; ?>
        </div>

        <div class="row align-items-start">
            <!-- Profile Picture Section -->
            <div class="col-md-3 text-center">
                <img src="images/user.png" id="profilPicture" alt="Profile Picture" class="img-fluid rounded">
            </div>

            <!-- Profile Information Section -->
            <div class="col-md-9">
                <div class="p-3 border rounded shadow-sm">
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
            </div>
        </div>
    </div>

    <!-- Notwendige JavaScript-Abhängigkeiten -->
    <script src='https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js' crossorigin='anonymous'></script>
    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js' crossorigin='anonymous'></script>
</body>

</html>
