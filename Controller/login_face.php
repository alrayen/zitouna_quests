<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . '/Projet2/Controller/userController.php');

header('Content-Type: application/json');


$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['descriptor']) || !isset($data['email'])) {
    echo json_encode(['success' => false, 'message' => 'Données manquantes (email ou visage).']);
    exit;
}
$loginDescriptor = $data['descriptor'];
$email = $data['email'];

$userController = new UserController();
$userFaceData = $userController->getFaceDescriptorByEmail($email);

if (empty($userFaceData) || !is_array($userFaceData['descriptor'])) {
    echo json_encode(['success' => false, 'message' => 'Aucun visage n\'est enregistré pour cet e-mail.']);
    exit;
}

$postData = json_encode([
    'login_descriptor' => $loginDescriptor,
    'user_descriptors' => [$userFaceData] 
]);

$ch = curl_init('http://127.0.0.1:5000/find_match');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Content-Length: ' . strlen($postData)
]);

$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpcode != 200) {
    echo json_encode(['success' => false, 'message' => 'Erreur de communication avec le service de reconnaissance faciale.']);
    exit;
}

$result = json_decode($response, true);

if ($result && isset($result['match_found']) && $result['match_found']) {
    $user = User::getUserById($result['user_id']); 

    $_SESSION['user_id'] = $user['id_user'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_nom'] = $user['nom'];
    $_SESSION['user_prenom'] = $user['Prenom'];
    $_SESSION['user_role'] = $user['role'];
    $_SESSION['user_image'] = $user['photo'];

    $base_path = '/Projet2';
    $admin_url = $base_path . '/View/BACK%20OFFICE/VIEW/build/pages/users_table.php';
    $user_url = $base_path . '/View/FRONT%20OFFICE/PRINCIPAL/genifty-html/index.php';
    $redirect_url = ($user['role'] == 1) ? $admin_url : $user_url;
    
    echo json_encode(['success' => true, 'redirect_url' => $redirect_url]);
} else {
    echo json_encode(['success' => false, 'message' => 'Visage non reconnu.']);
}
?>
