<?php
require("start.php");
if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
?>

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['aktion'], $_POST['username'])) {
        $aktion = $_POST['aktion'];
        $username = $_POST['username'];
        if ($aktion === "akzeptieren") {
            $success = $service->friendAccept($username);    
        } elseif ($aktion === "ablehnen") {
            $dismiss = $service->friendDismiss($username);
        }
    } 
}


// Handle friend request submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['friendRequestName'])) {
    $friendRequestName = $_POST['friendRequestName'];
    $success = $service->friendRequest(new Model\Friend($friendRequestName)); 
    if ($success) {
        
    } else {
        // Handle error (could show an error message)
        $errorMessage = "Could not send friend request to " . htmlspecialchars($friendRequestName);
    }
}


//potential friends
// Assuming session is started and current user is already set
$currentUser = $_SESSION['user']; // Aktueller Benutzer

// Load all users and the current user's friends
$allUsers = $service->loadUsers(); // Methode, um alle Nutzer zu laden
$friends = $service->loadFriends($currentUser); // Methode, um aktuelle Freunde zu laden

// Get the usernames of current user's friends
$friendUsernames = array_map(function($friend) {
    return $friend->getUsername(); // Convert Friend objects to their usernames
}, $friends);

// Initialize an array to hold potential friends
$potentialFriends = [];

// Iterate through all users to find potential friends
foreach ($allUsers as $user) {
    // Check if the user is not the current user and not already a friend
    if ($user !== $currentUser && !in_array($user, $friendUsernames)) {
        $potentialFriends[] = $user; // Add to potential friends
    }
}

//handle friend request
if ($action === "add") {
    $result = $service->friendRequest(new Model\Friend($friend));
    if ($result) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(["message" => "Friend request sent successfully"]);
        return;
    }
}



?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Friends List - Chat Application</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .friend-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem;
            border-bottom: 1px solid #dee2e6;
        }

        .friend-item:last-child {
            border-bottom: none;
        }

        .friend-name {
            font-size: 1.1rem;
            color: #0d6efd;
            text-decoration: none;
        }

        .friend-actions {
            display: flex;
            gap: 0.5rem;
        }

        .unread-badge {
            background-color: #0d6efd;
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 0.5rem;
            margin-left: 0.5rem;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Friends</h1>
            <div>
                <a href="logout.php" class="btn btn-outline-danger">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
                <a href="einstellungen.php" class="btn btn-outline-secondary">
                    <i class="bi bi-gear"></i> Settings
                </a>
            </div>
        </div>

        <!-- Friends List -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">My Friends</h5>
            </div>
            <div class="list-group list-group-flush" id="friends-list">
                <!-- Friends will be populated via JavaScript, but here's the template: -->
            </div>
        </div>

        <!-- Friend Requests -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Friend Requests</h5>
            </div>
            <div class="list-group list-group-flush" id="friend-requests">
                <!-- Requests will be populated via JavaScript -->
            </div>
        </div>

        <!-- Add Friend Form -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Add New Friend</h5>
            </div>
            <div class="card-body">
                <form class="d-flex gap-2" id="add-friend-form">
                    <input type="text" class="form-control" id="friend-request-name" list="friend-selector"
                        placeholder="Enter username">
                    <button id="send-request-button" class="btn btn-primary" type="submit">
                        <i class="bi bi-person-plus"></i> Add Friend
                    </button>
                </form>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">All Users</h5>
                <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse"
                    data-bs-target="#allUsersCollapse">
                    <i class="bi bi-chevron-down"></i>
                </button>
            </div>
            <div class="collapse" id="allUsersCollapse">
                <div class="list-group list-group-flush" id="all-users-list">
                    <!-- Users will be populated via JavaScript -->
                    <div class="p-3 text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add this right after your container div in freundeliste.php -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="requestSentToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <i class="bi bi-check-circle-fill text-success me-2"></i>
                <strong class="me-auto">Notification</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body"></div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="freundeliste.js"></script>
</body>

</html>