<?php
session_start();

// Basic redirect logic without external dependencies
if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
    header("Location: freundeliste.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Application</title>
    <link rel="stylesheet" href="app.css">
    <style>
        .loading-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background-color: #f9f9f9;
        }

        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
    <script>
        window.onload = function() {
            setTimeout(function() {
                window.location.href = 'login.php';
            }, 1500);
        };
    </script>
</head>
<body>
    <div class="loading-container">
        <h1>Chat Application</h1>
        <div class="loading-spinner"></div>
        <p>Initializing...</p>
    </div>
</body>
</html>
