<?php
require("start.php");

// Check if user is logged in
if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Get profile username from URL parameter
$profileUsername = $_GET['friend'] ?? $_SESSION['user']; // Get username from URL or use current user

try {
    // Load user profile with error checking and debugging
    $profileUser = $service->loadUser($profileUsername);
    if (!$profileUser) {
        error_log("Failed to load user profile for: " . $profileUsername);
        throw new Exception("User not found");
    }

    // Debug output to check what we're getting
    error_log("Loaded profile data for " . $profileUsername . ": " . print_r($profileUser, true));

    // Check friendship status
    $isFriend = false;
    if ($profileUsername !== $_SESSION['user']) {
        $friends = $service->loadFriends();
        if ($friends) {
            foreach ($friends as $friend) {
                if ($friend->getUsername() === $profileUsername && $friend->getStatus() === "accepted") {
                    $isFriend = true;
                    break;
                }
            }
        }
    }
} catch (Exception $e) {
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
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' rel='stylesheet'
        crossorigin='anonymous'>
</head>

<body>
    <div class="container fluid mt-4 ml-8 mr-8">
        <h1 class="mb-3">Profile of <?= htmlspecialchars($profileUsername) ?></h1>

        <div class="btn-group mb-4" role="group" aria-label="Friend Actions">
            <a href="freundeliste.php" class="btn btn-secondary">
                &lt; Back to Chat
            </a>
            <?php if ($isFriend): ?>
                <a href="freundeliste.php?action=remove&friend=<?= urlencode($profileUsername) ?>" class="btn btn-danger"
                    onclick="return confirm('Are you sure you want to remove this friend?');">
                    Remove Friend
                </a>
            <?php endif; ?>
        </div>

        <div class="row align-items-start">
            <!-- Profile Picture Section -->
            <div class="col-md-3 text-center">
                <img src="images/user.png" id="profilPicture" alt="Profile Picture" class="img-fluid rounded">
            </div>

            <!-- Profile Information Column -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">About Me</h5>
                        <p class="card-text">
                            <?php
                            $description = $profileUser->getDescription();
                            echo !empty($description) ? nl2br(htmlspecialchars($description)) :
                                '<span class="text-muted">No description provided</span>';
                            ?>
                        </p>

                        <hr>

                        <div class="mb-3">
                            <span class="info-label">Name:</span>
                            <span class="ms-2">
                                <?php
                                $firstName = $profileUser->getFirstName();
                                $lastName = $profileUser->getLastName();
                                $fullName = trim($firstName . ' ' . $lastName);
                                echo !empty($fullName) ? htmlspecialchars($fullName) :
                                    '<span class="text-muted">Not specified</span>';
                                ?>
                            </span>
                        </div>

                        <div class="mb-3">
                            <span class="info-label">Coffee or Tea?</span>
                            <span class="ms-2">
                                <?php
                                $preference = $profileUser->getCoffeeOrTea();
                                echo !empty($preference) ? htmlspecialchars($preference) :
                                    '<span class="text-muted">Not specified</span>';
                                ?>
                            </span>
                        </div>

                        <?php if ($profileUsername === $_SESSION['user']): ?>
                            <div class="mt-4">
                                <h5>Profile Change History</h5>
                                <ul class="list-group">
                                    <?php
                                    $history = $profileUser->getChangeHistory();
                                    if (!empty($history)):
                                        foreach ($history as $change):
                                            ?>
                                            <li class="list-group-item"><?= htmlspecialchars($change) ?></li>
                                        <?php
                                        endforeach;
                                    else:
                                        ?>
                                        <li class="list-group-item text-muted">No changes recorded</li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <?php if ($profileUsername === $_SESSION['user']): ?>
                            <div class="mt-4">
                                <a href="einstellungen.php" class="btn btn-primary">
                                    <i class="bi bi-pencil"></i> Edit Profile
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notwendige JavaScript-Abhängigkeiten -->
    <script src='https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js'
        crossorigin='anonymous'></script>
    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js'
        crossorigin='anonymous'></script>
</body>

</html>