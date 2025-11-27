<?php
require_once(__DIR__ . '/../../../../../config.php');


header('Content-Type: application/json');


if (!isset($_POST['email']) || !isset($_POST['userId'])) {
    echo json_encode(['exists' => false, 'error' => 'Paramètres manquants.']);
    exit;
}

$email = $_POST['email'];
$userId = $_POST['userId'];

try {
    $pdo = config::getConnexion();
    
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM user WHERE email = :email AND id_user != :id");
    $stmt->execute([':email' => $email, ':id' => $userId]);
    $count = $stmt->fetchColumn();

    echo json_encode(['exists' => $count > 0]);

} catch (Exception $e) {
    
    echo json_encode(['exists' => false, 'error' => 'Erreur de base de données: ' . $e->getMessage()]);
}
?>