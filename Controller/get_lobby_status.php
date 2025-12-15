<?php

session_start();

require_once "../config.php";

header('Content-Type: application/json');



if (!isset($_POST['session_id'])) {

    die(json_encode(['success' => false, 'message' => 'ID de session manquant.']));

}

$session_id = (int)$_POST['session_id'];

$pdo = config::getConnexion();



try {

    // Joindre vos tables pour obtenir l'état et les joueurs

    // Dans get_lobby_status.php

$sql = "SELECT 

            os.game_state,

            os.host_user_id,

            sp.user_id, 

            u.nom,    /* Utilisation de la colonne 'nom' de la table user */

            sp.is_host

        FROM online_sessions os

        JOIN session_players sp ON os.session_id = sp.session_id

        JOIN user u ON sp.user_id = u.id_user /* Utilisation de la clé correcte: id_user */

        WHERE os.session_id = ?";



    $stmt = $pdo->prepare($sql);

    $stmt->execute([$session_id]);

    $results = $stmt->fetchAll();



    $players = [];

    $game_state = 'ENDED'; 

    $host_id = 0;



    if (count($results) > 0) {

        // Dans get_lobby_status.php

    foreach($results as $row) {

        $game_state = $row['game_state'];

        $host_id = $row['host_user_id'];

        $players[] = [

            'id' => $row['user_id'],

            'username' => $row['nom'], // Utilise le champ 'nom' de votre table

            'is_host' => (bool)$row['is_host']

        ];

    }

// ... le reste du fichier est inchangé

    } else {

        die(json_encode(['success' => false, 'message' => 'Session introuvable ou terminée.']));

    }



    echo json_encode([

        'success' => true,

        'state' => $game_state,

        'players' => $players,

        'host_id' => $host_id,

        'current_user_id' => $_SESSION['user_id'] ?? 0

    ]);



} catch (Exception $e) {

    echo json_encode(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()]);

}

?>

