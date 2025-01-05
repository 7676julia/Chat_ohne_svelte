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
    } else {
        echo "Missing action or username!";
    }
} else {
    echo "No action received!";
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
   <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' rel='stylesheet' crossorigin='anonymous'>
   
</head>
<body>
<body data-current-user="<?php echo htmlspecialchars($_SESSION['user']); ?>">
    <h1>Friends</h1>
    <a href="logout.php" Logout> &lt; Logout</a> | <a href="einstellungen.php">Settings</a>
    <hr>
    <ul id = "friends-list">
        
    </ul>

    <hr>
    <h2>New Requests</h2>
    <ul id = "friend-requests">
        

    </ul>
    
    <hr>
    <div>
    <form>
    <label for="friend-request-name">Add Friend</label>
    
    <input 
        type="text" 
        placeholder="Add Friend to List" 
        id="friend-request-name" 
        list="friend-selector" 
        name = "friendRequestName"
        required
    >
    <datalist id="friend-selector">
        <?php 
        foreach ($potentialFriends as $potentialFriend) {
            echo "<option value='" . htmlspecialchars($potentialFriend) . "'>";
        }
        ?>
    </datalist>
    <button id="send-request-button">Add Friend</button>
    </form>
    
</div>
<!-- Notwendige JavaScript-Abhängigkeiten -->
     <script src='https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js' crossorigin='anonymous'></script>
    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js' crossorigin='anonymous'></script>
 <script src="frendesliste.js"></script> 
 
</body>
</html>
