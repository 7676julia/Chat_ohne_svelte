<?php
require("start.php");
if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    header("Location: login.php");
    exit();
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

    <div class="modal fade" id="friendRequestModal" tabindex="-1" aria-labelledby="friendRequestModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white border-0">
                    <h5 class="modal-title">
                        <i class="bi bi-person-plus-fill me-2"></i>Friend Request
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-light p-3 me-3">
                            <i class="bi bi-person-circle fs-3 text-primary"></i>
                        </div>
                        <p class="h5 mb-0" id="modalFriendName"></p>
                    </div>
                    <div class="alert alert-info mt-3 mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        This person wants to be your friend
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn btn-lg btn-success px-4" id="acceptFriendButton">
                        <i class="bi bi-check-lg me-2"></i>Accept
                    </button>
                    <button type="button" class="btn btn-lg btn-outline-danger px-4" id="declineFriendButton">
                        <i class="bi bi-x-lg me-2"></i>Decline
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="freundeliste.js"></script>
</body>

</html>
<!-- Logout Confirmation Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Logout</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to logout?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelector('.btn-outline-danger').addEventListener('click', function(event) {
        event.preventDefault();
        var logoutModal = new bootstrap.Modal(document.getElementById('logoutModal'));
        logoutModal.show();
    });
</script>