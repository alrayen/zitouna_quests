<?php
require_once __DIR__ . '/../config.php';

class HelpRoomController {
    private $pdo;

    public function __construct() {
        $this->pdo = config::getConnexion();
    }

    public function createRoom($userId, $challengeId) {
    
        $check = $this->pdo->prepare("SELECT room_code FROM help_rooms WHERE host_user_id = ? AND challenge_id = ? AND status = 'active'");
        $check->execute([$userId, $challengeId]);
        
        if ($row = $check->fetch(PDO::FETCH_ASSOC)) {
            return $row['room_code']; 
        }

        $roomCode = 'sos_' . uniqid();
        
        $sql = "INSERT INTO help_rooms (room_code, host_user_id, challenge_id, status) VALUES (?, ?, ?, 'active')";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$roomCode, $userId, $challengeId]);
        
        return $roomCode;
    }

    public function getActiveRoomsForChallenge($challengeId) {

        $sql = "SELECT hr.*, u.nom, u.prenom, u.photo 
                FROM help_rooms hr 
                JOIN user u ON hr.host_user_id = u.id_user 
                WHERE hr.challenge_id = ? AND hr.status = 'active'
                ORDER BY hr.created_at DESC";
                
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$challengeId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRoomDetails($roomCode) {
        $sql = "SELECT * FROM help_rooms WHERE room_code = ? AND status = 'active'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$roomCode]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function closeRoom($roomCode) {
        $sql = "UPDATE help_rooms SET status = 'solved' WHERE room_code = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$roomCode]);
    }
}
?>