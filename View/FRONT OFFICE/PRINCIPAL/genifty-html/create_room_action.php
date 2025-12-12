<?php
session_start();

// 1. Adjust the path to reach your Controller folder
require_once __DIR__ . '/../../../../Controller/help-room-controller.php';

// 2. GET USER ID (With Fallback for Testing)
// If no session exists, we assume User ID 1 for testing purposes
$userId = $_SESSION['user_id'] ?? 1; 

// 3. Validation
if (!isset($_POST['challenge_id'])) {
    header("Location: challenges.php");
    exit();
}

$challengeId = $_POST['challenge_id'];

// 4. Create the Room
try {
    $controller = new HelpRoomController();
    $roomCode = $controller->createRoom($userId, $challengeId);

    // 5. Redirect to the new Help Room
    header("Location: help-room.php?room=" . $roomCode);
    exit();

} catch (Exception $e) {
    die("Error creating room: " . $e->getMessage());
}
?>