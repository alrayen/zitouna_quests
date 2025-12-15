<?php
require_once __DIR__ . '/../config.php'; 

function generateQuizImage($quiz_id, $quiz_title) {
    $pdo = config::getConnexion();

    $stmt = $pdo->prepare("SELECT reward_image_url FROM quizzes WHERE id_quiz = ?");
    $stmt->execute([$quiz_id]);
    $current = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($current && !empty($current['reward_image_url'])) {
        return $current['reward_image_url'];
    }


    $apiKey = 'YOUR_OPENAI_API_KEY_HERE'; 
    
    $prompt = "A high quality, futuristic digital art poster representing the topic: " . $quiz_title . ". Vibrant colors, epic style, no text.";

    $data = [
        "model" => "dall-e-2", // or dall-e-3 for better quality
        "prompt" => $prompt,
        "n" => 1,
        "size" => "512x512"
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/images/generations');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey
    ]);

    $result = curl_exec($ch);
    curl_close($ch);
    
    $response = json_decode($result, true);

    // 3. Handle Error or Success
    if (isset($response['data'][0]['url'])) {
        $imageUrl = $response['data'][0]['url'];

        // OPTIONAL: Download the image to your server here so the URL doesn't expire
        // For now, we save the OpenAI URL
        
        // 4. Update Database
        $update = $pdo->prepare("UPDATE quizzes SET reward_image_url = ? WHERE id_quiz = ?");
        $update->execute([$imageUrl, $quiz_id]);

        return $imageUrl;
    } else {
        // Fallback image if AI fails
        return "assets/img/default_reward.jpg"; 
    }
}
?>