<?php
require("start.php");
if (!isset($_SESSION['user']) || empty($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

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
    <title>Document</title>
   <!-- CSS-Framework von Bootstrap -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
        }
        .card {
            border: none;
            background-color: white;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #0d6efd;
        }
    </style>
   
</head>
<body>
<div class="container mt-4 d-flex justify-content-center">
    <div class="col-md-4">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <body data-current-user="<?php echo htmlspecialchars($_SESSION['user']); ?>">
    <h3>Friends</h3>
    <div class="btn-group d-flex justify-content-between" role="group">
        <a href="logout.php" Logout class="btn btn-secondary"> &lt; Logout</a>  <a href="einstellungen.php" class="btn btn-secondary">Settings</a>
    </div>
        <hr>
    <ul id = "friends-list" style = none;>
        
    </ul>

    <hr>
    <div class="input-group border rounded input-group-sm mb-3">

        <ul id = "friend-requests"></ul>
    </div>

    <div>
    <div class="input-group border rounded mb-3">
        <input type="text"  id="friend-request-name" list="friend-selector" name = "friendRequestName" class="form-control" placeholder="Add friend to list" aria-label="Add" aria-describedby="button-addon2">
        <button id="send-request-button" class="btn btn-primary" type="button" id="button-addon2">Add</button>
    </div>  
    <datalist id="friend-selector">
        <?php 
        foreach ($potentialFriends as $potentialFriend) {
            //echo "<option value='" . htmlspecialchars($potentialFriend) . "'>";
        }
        ?>
    </datalist>
    
</div>

<div class="modal fade" id="friendRequestModal" tabindex="-1" aria-labelledby="friendRequestModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="friendRequestModalLabel">Friend Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Would you like to accept the friend request from <span id="requestUsername"></span>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" onclick="handleFriendRequest('dismiss')">Deny</button>
                    <button type="button" class="btn btn-success" onclick="handleFriendRequest('accept')">Accept</button>
                </div>
            </div>
        </div>
    </div>
<!-- Notwendige JavaScript-Abhängigkeiten -->
    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js' crossorigin='anonymous'></script>
 <script src="frendesliste.js"></script> 
 
</body>
</html>
