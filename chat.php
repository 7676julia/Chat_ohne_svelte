<?php
// /home/nikita/Projects/UNI/WTP/chat.php

require("start.php");
if (!isset($_SESSION['user']) || empty($_SESSION['user'])){
    header("Location: login.php");
    exit();
}
if (!isset($_GET['friend']) || empty($_GET['friend'])){
    header("Location: freundeliste.php");
    exit();
}

// Get the friend parameter and sanitize it
$friend = htmlspecialchars($_GET['friend']); // Define the friend variable properly

// Load current user data
$currentUser = $service->loadUser($_SESSION['user']);
$chatLayout = $currentUser->getChatLayout();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat with <?= $friend ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .message-container {
            height: calc(100vh - 250px);
            overflow-y: auto;
        }
        .chat-message {
            margin-bottom: 1rem;
        }
        .nav-link {
            color: inherit;
        }
        .chat-input {
            resize: none;
        }
        .one-line .username {
            display: inline;
            font-weight: bold;
        }
        .one-line .message {
            display: inline;
        }
        .two-lines .username {
            display: block;
            font-weight: bold;
        }
        .two-lines .message {
            display: block;
        }
    </style>
</head>
<body class="bg-light <?= $chatLayout ?>">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
        <div class="container">
            <div class="navbar-nav me-auto">
                <a href="freundeliste.php" class="nav-link">
                    <i class="bi bi-arrow-left"></i> Back to Friends
                </a>
                <a href="profil.php?friend=<?= urlencode($friend) ?>" class="nav-link">View Profile</a>
            </div>
            <div class="navbar-nav ms-auto">
                <a href="#" class="nav-link text-danger remove-friend" data-username="<?= $friend ?>">
                    <i class="bi bi-person-x"></i> Remove Friend
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Chat Container -->
    <div class="container py-4">
        <!-- Chat Header -->
        <div class="d-flex align-items-center mb-4">
            <h2 class="mb-0">Chat with <?= $friend ?></h2>
        </div>

        <!-- Messages Container -->
        <div class="card mb-4">
            <div class="card-body message-container" id="message-container" data-chat-layout="<?= $chatLayout ?>">
                <!-- Messages will be dynamically inserted here -->
            </div>
        </div>

        <!-- Chat Input -->
        <div class="card">
            <div class="card-body">
                <form id="chat-form" class="d-flex gap-3">
                    <input type="text" 
                           class="form-control" 
                           placeholder="Type your message..." 
                           id="message-input">
                    <button type="button" 
                            class="btn btn-primary px-4" 
                            onclick="sendMessage(event)">
                        Send
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Add currentUser to window scope for chat.js
        window.currentUser = <?= json_encode($_SESSION['user']) ?>;
        window.chatLayout = <?= json_encode($chatLayout) ?>;
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="chat.js"></script>
</body>
</html>
