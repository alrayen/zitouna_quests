<?php
session_start();


set_error_handler(function ($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) {
        return;
    }
    throw new ErrorException($message, 0, $severity, $file, $line);
});

require_once $_SERVER['DOCUMENT_ROOT'] . '/Projet2/vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Projet2/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Projet2/Model/user.php';

header('Content-Type: application/json');

$clientId = '555243072540-moo7gfq76t7tu7nt9hg1kr76pqjlp9s6.apps.googleusercontent.com'; 

if (!isset($_POST['idtoken'])) {
    echo json_encode(['success' => false, 'message' => 'Token non fourni.']);
    exit;
}

$id_token = $_POST['idtoken'];

try {
    $client = new Google_Client(['client_id' => $clientId]);
    $payload = $client->verifyIdToken($id_token);
    
    if ($payload) {
        $email = $payload['email'];
        $nom = $payload['family_name'] ?? 'Utilisateur';
        $prenom = $payload['given_name'] ?? 'Google';
        $photo = $payload['picture'] ?? null;
    
        
        $user_data = User::getUserByEmail($email);
    
        if ($user_data) {
       

           
            if (isset($user_data['etat']) && $user_data['etat'] == 1) { 
                echo json_encode(['success' => false, 'message' => 'Votre compte est banni.']);
                exit;
            }

            $_SESSION['user_id'] = $user_data['id_user'];
            $_SESSION['user_email'] = $user_data['email'];
            $_SESSION['user_nom'] = $user_data['nom'];
            $_SESSION['user_prenom'] = $user_data['Prenom']; 
            $_SESSION['user_role'] = $user_data['role'];
            // $_SESSION['user_image'] = $user_data['photo']; 

        } else {
           
            
            $random_password = bin2hex(random_bytes(16)); 
            $birthdate = date('Y-m-d'); 

            $newUser = new User($nom, $prenom, $birthdate, $email, $random_password);
            
            
            if ($photo && method_exists($newUser, 'setPhoto')) {
                $newUser->setPhoto($photo);
            }
            
            
            if ($newUser->register()) {
               
                $user_data = User::getUserByEmail($email);

                if ($user_data) {
                    $_SESSION['user_id'] = $user_data['id_user'];
                    $_SESSION['user_email'] = $user_data['email'];
                    $_SESSION['user_nom'] = $user_data['nom'];
                    $_SESSION['user_prenom'] = $user_data['Prenom'];
                    $_SESSION['user_role'] = $user_data['role'];
                } else {
                    throw new Exception("Compte créé mais impossible de récupérer les données.");
                }
            } else {
                throw new Exception("Erreur lors de l'insertion en base de données.");
            }
        }
    
        
        $role = $_SESSION['user_role'] ?? 'client';

        $base_path = '/Projet2';
        $redirect_url = ($role === 'admin') 
            ? $base_path . "/View/BACK%20OFFICE/VIEW/build/pages/dashboard.html"
            : $base_path . "/View/FRONT%20OFFICE/PRINCIPAL/genifty-html/index.php";
    
        echo json_encode(['success' => true, 'redirect_url' => $redirect_url]);
    
    } else {
        echo json_encode(['success' => false, 'message' => 'Token Google invalide ou expiré.']);
    }

} catch (Exception $e) {
    
    http_response_code(500); 
    echo json_encode([
        'success' => false, 
        'message' => 'Erreur serveur: ' . $e->getMessage()
    ]);
}
?>