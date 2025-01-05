<?php
require("start.php");
session_unset();
header("Location: index.php"); // Weiterleitung zur Startseite
exit(); // Wichtiger Exit-Befehl nach Header
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
</head>
<body>
    <div class="container mt-4 d-flex justify-content-center">
        <div class="col-md-4">
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
            <img src="images/logout.png" style="height: 100px" class="img-fluid rounded-circle mx-auto d-block">
            <div class="container-fluid">
            <div class="card p-4" style="max-width: 400px; margin: auto; border: none; background-color: #f8f9fa;">

            <h3>Logged out... </h3>
            <p>See u!</p>
            <div class="btn-group d-flex justify-content-between" role="group">
                    <button type="button" class="btn btn-secondary" onclick="window.location.href='login.php'">Login again</button>
            </div>
</body>
</html>