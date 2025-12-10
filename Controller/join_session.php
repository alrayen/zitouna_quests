<?php

session_start();

require_once(__DIR__ . '/../config.php'); 

header('Content-Type: application/json');



if (!isset($_SESSION['user_id'])) {

    die(json_encode(['success' => false, 'message' => 'Non connecté. Veuillez vous connecter.']));

}

$user_id = $_SESSION['user_id'];

$pdo = config::getConnexion();



$code_invitation = isset($_POST['code']) ? strtoupper(trim($_POST['code'])) : '';

$MAX_PLAYERS = 6; 



if (empty($code_invitation)) {

    die(json_encode(['success' => false, 'message' => 'Code d\'invitation manquant.']));

}



try {

    // 1. Vérifier la session, l'état (LOBBY), et le nombre de joueurs

    $sql_check = "SELECT 

                    s.session_id, 

                    (SELECT COUNT(*) FROM session_players sp WHERE sp.session_id = s.session_id) AS current_players

                  FROM online_sessions s 

                  WHERE s.code_invitation = :code AND s.game_state = 'LOBBY'";

    $stmt_check = $pdo->prepare($sql_check);

    $stmt_check->execute(['code' => $code_invitation]);

    $session_data = $stmt_check->fetch();



    if (!$session_data) {

        die(json_encode(['success' => false, 'message' => 'Session introuvable, déjà démarrée, ou code incorrect.']));

    }



    $session_id = $session_data['session_id'];

    $current_players = (int)$session_data['current_players'];



    // 2. Vérifier si l'utilisateur est déjà dans la session

    $stmt_already_in = $pdo->prepare("SELECT 1 FROM session_players WHERE session_id = ? AND user_id = ?");

    $stmt_already_in->execute([$session_id, $user_id]);



    if ($stmt_already_in->rowCount() > 0) {

        // Déjà dedans

        echo json_encode(['success' => true, 'session_id' => $session_id, 'message' => 'Redirection vers le lobby.']);

    } elseif ($current_players >= $MAX_PLAYERS) {

        die(json_encode(['success' => false, 'message' => 'La session est complète (max 6 joueurs).']));

    } else {

        // 3. Ajouter le joueur

        $sql_insert = "INSERT INTO session_players (session_id, user_id, is_host) VALUES (?, ?, FALSE)";

        $stmt_insert = $pdo->prepare($sql_insert);

        

        if ($stmt_insert->execute([$session_id, $user_id])) {

            echo json_encode(['success' => true, 'session_id' => $session_id, 'message' => 'Session rejointe!']);

        } else {

            echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'ajout à la session.']);

        }

    }



} catch (Exception $e) {

    echo json_encode(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()]);

}

?>