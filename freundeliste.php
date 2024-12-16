<?php
require("start.php");
if (!isset($_SESSION['user']) || empty($_SESSION['user'])){
    header("Location: login.php");
    exit();
}
//verzweiflung ist verzweifelt
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

/*send friend request 
// Handle friend request submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['friendRequestName'])) {
    $friendRequestName = $_POST['friendRequestName'];
    $success = $service->friendRequest($friendRequestName);
    if ($success) {
        // Redirect to prevent form resubmission
        header("Location: freundeliste.php");
        exit();
    } else {
        // Handle error (could show an error message)
        $errorMessage = "Could not send friend request to " . htmlspecialchars($friendRequestName);
    }
}
*/

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


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="app.css">
   
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
    <label for="friend-request-name">Add Friend</label>
    <input 
        type="text" 
        placeholder="Add Friend to List" 
        id="friend-request-name" 
        list="friend-selector" 
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
</div>
 <script src="frendesliste.js"></script>
</body>
</html>
