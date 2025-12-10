<?php

session_start();

require_once(__DIR__ . '/../config.php'); 

header('Content-Type: application/json');



if (!isset($_SESSION['user_id'])) {

    die(json_encode(['success' => false, 'message' => 'Non connecté. Veuillez vous connecter.']));

}

$host_user_id = $_SESSION['user_id'];

$pdo = config::getConnexion();



// Fonction pour générer un code unique (6 caractères)

function generateCode($pdo) {

    do {

        $code = strtoupper(substr(md5(uniqid(rand(), true)), 0, 6));

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM online_sessions WHERE code_invitation = ?");

        $stmt->execute([$code]);

        if ($stmt->fetchColumn() == 0) {

            return $code;

        }

    } while (true);

}

$code_invitation = generateCode($pdo);



try {

    $pdo->beginTransaction();



    // 1. Créer la session

    $sql_session = "INSERT INTO online_sessions (code_invitation, host_user_id) VALUES (?, ?)";

    $stmt_session = $pdo->prepare($sql_session);

    $stmt_session->execute([$code_invitation, $host_user_id]);

    $session_id = $pdo->lastInsertId();



    // 2. Ajouter le Host comme joueur de la session

    $sql_player = "INSERT INTO session_players (session_id, user_id, is_host) VALUES (?, ?, TRUE)";

    $stmt_player = $pdo->prepare($sql_player);

    $stmt_player->execute([$session_id, $host_user_id]);

    

    $pdo->commit();



    echo json_encode([

        'success' => true, 

        'session_id' => $session_id,

        'code' => $code_invitation,

        'message' => 'Session créée. Partagez le code: ' . $code_invitation

    ]);



} catch (Exception $e) {

    if ($pdo->inTransaction()) { $pdo->rollBack(); }

    echo json_encode(['success' => false, 'message' => 'Erreur lors de la création de la session: ' . $e->getMessage()]);

}

?>