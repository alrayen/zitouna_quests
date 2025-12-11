<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/Projet2/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userCode = $_POST['code'];
    $email = $_SESSION['reset_email'];
    
    $pdo = config::getConnexion();
    
    // Vérifier si le code correspond à l'email en session
    $stmt = $pdo->prepare("SELECT * FROM user WHERE email = :email AND verification_code = :code");
    $stmt->execute(['email' => $email, 'code' => $userCode]);
    $user = $stmt->fetch();

    if ($user) {
        // Code bon ! On autorise l'accès à la page de changement de MDP
        $_SESSION['code_verified'] = true;
        header("Location: ../View/FRONT OFFICE/PRINCIPAL/genifty-html/reset_password.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Code incorrect.";
        header("Location: ../View/FRONT OFFICE/PRINCIPAL/genifty-html/verify_code.php");
        exit();
    }
}
?>