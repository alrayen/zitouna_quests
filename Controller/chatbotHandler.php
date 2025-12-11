<?php

header('Content-Type: application/json');

$apiKey = 'AIzaSyBUXtZxNCOM0aIOwaeHdYKtZrkSEuSlnaY'; 


$context = "Tu es l'assistant virtuel officiel de la plateforme 'Zitouna Quest'.
Ton ton est : amical, écologique, encourageant et professionnel.
Tu dois répondre de manière concise (max 3 phrases).

BASE DE CONNAISSANCES :
1. **IDENTITÉ :** Zitouna Quest, plateforme de gamification pour l'impact social/écologique. Siège : Ras Jebel, Bizerte. Créateurs : Team Innovit (Mohamed Ben Hariz, Rayen Gaied, Ahmed Mokhtar, Naffisatou, Houcem).
2. **INSCRIPTION :** Gratuite. Bouton 'Sign Up'. Sécurisée par reCAPTCHA.
3. **CONNEXION :** Email/MDP ou **Face ID** (reconnaissance faciale).
4. **FACE ID :** Activer dans Profil > Modifier > Enregistrer mon visage. Connexion instantanée par caméra.
5. **PROFIL :** Personnalisable (Photo, Bio Texte, **Bio Vocale**).
6. **BUT :** Faire des quêtes, gagner des XP, débloquer des badges.
6. **agent reel ou admin :** faire contacter ce email:mbenhariz77@gmail.com notre admin vous repend instantanement et t envoye un lien d une conversation directe pour t aider ,merci.

CONSIGNES DE COMPORTEMENT :
- **IMPORTANT : Ne commence PAS tes phrases par 'Salut' ou 'Bonjour' sauf si l'utilisateur te salue explicitement.** Sois direct.
- Si on demande 'qui t'a créé', cite la Team Innovit.
- Si hors sujet, refuse poliment.";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userMessage = trim($_POST['message'] ?? '');

    if (empty($userMessage)) {
        echo json_encode(['response' => 'Je n\'ai pas entendu.']);
        exit;
    }


    $finalPrompt = $context . "\n\nQUESTION DE L'UTILISATEUR : " . $userMessage;

    $data = [
        "contents" => [
            [
                "parts" => [
                    ["text" => $finalPrompt]
                ]
            ]
        ]
    ];


    $model = 'gemini-2.0-flash'; 
    $url = "https://generativelanguage.googleapis.com/v1beta/models/" . $model . ":generateContent?key=" . $apiKey;


    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    
    // Désactive SSL pour localhost
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $response = curl_exec($ch);
    
    if (curl_errno($ch)) {
        $botReply = "Erreur technique.";
    } else {
        $json = json_decode($response, true);
        
        if (isset($json['candidates'][0]['content']['parts'][0]['text'])) {
            $botReply = $json['candidates'][0]['content']['parts'][0]['text'];
        } elseif (isset($json['error'])) {
            $botReply = "Erreur Google : " . $json['error']['message'];
        } else {
            $botReply = "Je n'ai pas de réponse.";
        }
    }
    
    curl_close($ch);

    // Mise en forme
    $botReply = nl2br(htmlspecialchars($botReply)); 

    echo json_encode(['response' => $botReply]);
    exit;
}
?>