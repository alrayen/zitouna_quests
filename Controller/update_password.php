<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/Projet2/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pass = $_POST['password'];
    $confirm = $_POST['confirm_password'];
    
    // Vérification de sécurité (Email présent en session)
    if (!isset($_SESSION['reset_email'])) {
        header("Location: ../View/FRONT OFFICE/PRINCIPAL/genifty-html/login.php");
        exit();
    }
    
    $email = $_SESSION['reset_email'];

 
    if ($pass === $confirm) {
        $pdo = config::getConnexion();
        
  
        $hashedPwd = password_hash($pass, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE user SET password = :pwd, verification_code = NULL WHERE email = :email");
        $stmt->execute(['pwd' => $hashedPwd, 'email' => $email]);

       
        session_destroy();
        header("Location: ../View/FRONT OFFICE/PRINCIPAL/genifty-html/login.php");
        exit();
        
    } else {
      
        $_SESSION['error_message'] = "Les mots de passe ne correspondent pas.";
        header("Location: ../View/FRONT OFFICE/PRINCIPAL/genifty-html/reset_password.php");
        exit();
    }
} else {
    header("Location: ../View/FRONT OFFICE/PRINCIPAL/genifty-html/login.php");
    exit();
}
?>