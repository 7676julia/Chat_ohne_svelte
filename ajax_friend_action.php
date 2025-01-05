<?php
require "start.php";

if (!isset($_SESSION['user'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(["message" => "No user in session stored"]);
    return;
}

$action = $_REQUEST["action"] ?? "";
$friend = $_REQUEST["friend"] ?? "";

// Validate required parameters
if(empty($friend)) {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(["message" => "Missing friend parameter"]);
    return;
}

if(empty($action)) {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(["message" => "Missing action parameter"]);
    return;
}

// Validate action type
$validActions = ['add', 'remove', 'accept', 'dismiss'];
if (!in_array($action, $validActions)) {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(["message" => "Invalid action type"]);
    return;
}

try {
    $result = false;
    switch($action) {
        case 'add':
            $result = $service->friendRequest(new Model\Friend($friend));
            break;
        case 'remove':
            $result = $service->removeFriend($friend);
            break;
        case 'accept':
            $result = $service->friendAccept($friend);
            break;
        case 'dismiss':
            $result = $service->friendDismiss($friend);
            break;
    }

    if ($result) {
        http_response_code(204);
    } else {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode([
            "message" => "Action failed to complete",
            "action" => $action,
            "friend" => $friend
        ]);
    }
} catch (Exception $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
        "message" => "Server error occurred",
        "error" => $e->getMessage()
    ]);
}
?>