<?php
require("start.php");
session_unset();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logged Out</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .logout-card {
            max-width: 400px;
            width: 100%;
            padding: 2rem;
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            background-color: #ffffff;
            text-align: center;
        }
        .logout-image {
            height: 100px;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="logout-card">
        <img src="images/logout.png" class="img-fluid rounded-circle logout-image" alt="Logout">
        <h3 class="mb-3">Logged out</h3>
        <p class="mb-4">See you soon!</p>
        <a href="login.php" class="btn btn-primary">Login Again</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>