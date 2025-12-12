<?php
require_once __DIR__ . '/../config.php';

class HelpRoomController {
    private $pdo;

    public function __construct() {
        $this->pdo = config::getConnexion();
    }

    // 1. Create a Room (When user clicks "Stuck?")
    public function createRoom($userId, $challengeId) {
        // Check if this user already has an active room for this challenge
        // We don't want them opening 10 different help requests for the same thing
        $check = $this->pdo->prepare("SELECT room_code FROM help_rooms WHERE host_user_id = ? AND challenge_id = ? AND status = 'active'");
        $check->execute([$userId, $challengeId]);
        
        if ($row = $check->fetch(PDO::FETCH_ASSOC)) {
            return $row['room_code']; // Return their existing room
        }

        // Generate a unique room code (e.g., sos_65a4b3)
        $roomCode = 'sos_' . uniqid();
        
        // Insert the new room
        $sql = "INSERT INTO help_rooms (room_code, host_user_id, challenge_id, status) VALUES (?, ?, ?, 'active')";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$roomCode, $userId, $challengeId]);
        
        return $roomCode;
    }

    // 2. List Active Rooms (To show on the Challenge Resources page)
    public function getActiveRoomsForChallenge($challengeId) {
        // We JOIN with the 'user' table so we can show WHO is asking for help
        // Note: Using 'id_user', 'nom', 'photo' to match your schema
        $sql = "SELECT hr.*, u.nom, u.prenom, u.photo 
                FROM help_rooms hr 
                JOIN user u ON hr.host_user_id = u.id_user 
                WHERE hr.challenge_id = ? AND hr.status = 'active'
                ORDER BY hr.created_at DESC";
                
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$challengeId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 3. Get Room Details (For the actual room page)
    public function getRoomDetails($roomCode) {
        $sql = "SELECT * FROM help_rooms WHERE room_code = ? AND status = 'active'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$roomCode]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 4. Close Room (When they solve it)
    public function closeRoom($roomCode) {
        $sql = "UPDATE help_rooms SET status = 'solved' WHERE room_code = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$roomCode]);
    }
}
?>