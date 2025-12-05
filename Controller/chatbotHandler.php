<?php
// On indique que la réponse sera du JSON (pour que le JS comprenne)
header('Content-Type: application/json');

// On vérifie que c'est bien une requête POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // On récupère le message et on le met en minuscules pour faciliter la comparaison
    $msg = strtolower(trim($_POST['message'] ?? ''));
    
    // Réponse par défaut si on ne comprend pas
    $response = "Désolé, je n'ai pas compris. Essayez 'Connexion', 'Inscription', 'Profil' ou 'Face ID'.";

    // --- LOGIQUE (Analyse des mots-clés) ---

    // 1. Bonjour / Salut
    if (strpos($msg, 'bonjour') !== false || strpos($msg, 'salut') !== false || strpos($msg, 'hello') !== false) {
        $response = "Bonjour ! 👋 Comment puis-je vous aider sur Zitouna Quest ?";
    }
    
    // 2. Connexion / Login
    elseif (strpos($msg, 'connexion') !== false || strpos($msg, 'login') !== false || strpos($msg, 'connecter') !== false) {
        $response = "Pour vous connecter, cliquez sur le bouton 'Login' en haut à droite. Vous pouvez utiliser votre email ou la reconnaissance faciale.";
    }
    
    // 3. Inscription
    elseif (strpos($msg, 'inscription') !== false || strpos($msg, 'créer') !== false || strpos($msg, 'sign up') !== false) {
        $response = "L'inscription est gratuite ! Cliquez sur 'Sign Up'. Vous pourrez ensuite ajouter votre photo pour le Face ID.";
    }
    
    // 4. Face ID / Reconnaissance Faciale
    elseif (strpos($msg, 'face') !== false || strpos($msg, 'visage') !== false || strpos($msg, 'caméra') !== false) {
        $response = "📸 **Face ID :**<br>1. Allez dans votre Profil.<br>2. Cliquez sur 'Modifier'.<br>3. Enregistrez votre visage.<br>4. Connectez-vous ensuite sans mot de passe !";
    }
    
    // 5. Mot de passe oublié
    elseif (strpos($msg, 'mot de passe') !== false || strpos($msg, 'oubli') !== false || strpos($msg, 'password') !== false) {
        $response = "Si vous avez oublié votre mot de passe, cliquez sur 'Mot de passe oublié' sur la page de login pour le réinitialiser par email.";
    }

    // 6. NOUVEAU CAS : PROFIL (Bio, Photo, Audio)
    elseif (strpos($msg, 'profil') !== false || strpos($msg, 'bio') !== false || strpos($msg, 'photo') !== false || strpos($msg, 'modifier') !== false) {
        $response = "👤 **Gestion du Profil :**<br>Vous pouvez tout personnaliser !<br>- Changez votre **Photo**.<br>- Ajoutez une **Bio Texte**.<br>- Enregistrez une **Bio Vocale** 🎤.<br><br>Il suffit d'aller sur votre page Profil et de cliquer sur le bouton d'édition (le crayon).";
    }

    // 7. Quêtes / Zitouna
    elseif (strpos($msg, 'quête') !== false || strpos($msg, 'zitouna') !== false || strpos($msg, 'c\'est quoi') !== false) {
        $response = "Zitouna Quest est une plateforme gamifiée pour l'impact social et écologique. Réalisez des défis, gagnez des points et obtenez des badges ! 🌱";
    }

    // On renvoie la réponse au format JSON
    echo json_encode(['response' => $response]);
    exit;
}
?>