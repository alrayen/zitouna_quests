<?php
session_start();

require_once __DIR__ . '/../../../../Controller/help-room-controller.php';

$userId = $_SESSION['user_id'] ?? 1; 

if (!isset($_POST['challenge_id'])) {
    header("Location: challenges.php");
    exit();
}

$challengeId = $_POST['challenge_id'];

try {
    $controller = new HelpRoomController();
    $roomCode = $controller->createRoom($userId, $challengeId);

    header("Location: help-room.php?room=" . $roomCode);
    exit();

} catch (Exception $e) {
    die("Error creating room: " . $e->getMessage());
}
?>