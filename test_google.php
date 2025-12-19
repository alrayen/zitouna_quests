<?php
// test_google.php

// ⚠️ COLLE TA CLÉ API ICI (Celle qui commence par AIza...)
$apiKey = ''; 

// On demande à Google : "Quels modèles ai-je le droit d'utiliser ?"
$url = "https://generativelanguage.googleapis.com/v1beta/models?key=" . $apiKey;

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Important pour localhost
$response = curl_exec($ch);
$json = json_decode($response, true);

echo "<h1>🔍 Diagnostic Google Gemini</h1>";

if (isset($json['error'])) {
    echo "<h3 style='color:red'>❌ Erreur :</h3>";
    echo "Message : " . $json['error']['message'];
} elseif (isset($json['models'])) {
    echo "<h3 style='color:green'>✅ Connexion Réussie ! Voici les modèles disponibles pour toi :</h3>";
    echo "<ul>";
    foreach ($json['models'] as $model) {
        // On affiche seulement les modèles qui savent "générer du contenu"
        if (in_array("generateContent", $model['supportedGenerationMethods'])) {
            // On nettoie le nom pour l'affichage
            $cleanName = str_replace('models/', '', $model['name']);
            echo "<li><strong>" . $cleanName . "</strong></li>";
        }
    }
    echo "</ul>";
    echo "<p>👉 <strong>SOLUTION :</strong> Copie un des noms en gras ci-dessus et mets-le dans ton fichier <code>chatbotHandler.php</code> !</p>";
} else {
    echo "<h3 style='color:orange'>⚠️ Réponse bizarre de Google :</h3>";
    echo "<pre>";
    print_r($json);
    echo "</pre>";
}
?>