<?php

session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/Projet2/config.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Projet2/Model/user.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);

    if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
        $secretKey = '6LezTR8sAAAAAMjKjaZndDaG39AeKjtf8BiyDoSy'; 
        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secretKey . '&response=' . $_POST['g-recaptcha-response']);
        $responseData = json_decode($verifyResponse);

        if (!$responseData->success) {
            $_SESSION['error_recaptcha'] = "La vérification reCAPTCHA a échoué. Veuillez réessayer.";
            header("Location: ../View/FRONT%20OFFICE/PRINCIPAL/genifty-html/login.php");
            exit;
        }
    } else {
        $_SESSION['error_recaptcha'] = "Veuillez cocher la case 'Je ne suis pas un robot'.";
        header("Location: ../View/FRONT%20OFFICE/PRINCIPAL/genifty-html/login.php");
        exit;
    }


    if (empty($email) || empty($password)) {
        if (empty($email)) {
            $_SESSION['error_email'] = "L'adresse email est requise.";
        }
        if (empty($password)) {
            $_SESSION['error_password'] = "Le mot de passe est requis.";
        }
        header("Location: ../View/FRONT%20OFFICE/PRINCIPAL/genifty-html/login.php");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_email'] = "Le format de l'email est invalide.";
        header("Location: ../View/FRONT%20OFFICE/PRINCIPAL/genifty-html/login.php");
        exit;
    }

    try {
        $loginResult = User::login($email, $password);
        
        if ($loginResult === 'banned') {
            $_SESSION['error_login'] = "Votre compte est banni. Veuillez consulter l'administrateur.";
            header("Location: ../View/FRONT%20OFFICE/PRINCIPAL/genifty-html/login.php");
            exit;
        } elseif ($loginResult) { 
            $user = $loginResult;
            $_SESSION['user_id'] = $user['id_user'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_nom'] = $user['nom'];
            $_SESSION['user_prenom'] = $user['Prenom'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_image'] = $user['photo'];
            
            $base_path = '/Projet2';
            if ($user['role'] === 'admin') {
                header("Location: " . $base_path . "/View/BACK%20OFFICE/VIEW/build/pages/dashboard.html");
            } else {
                header("Location: " . $base_path . "/View/FRONT%20OFFICE/PRINCIPAL/genifty-html/index.php");
            }
            exit;
        } else {
            $_SESSION['error_login'] = "Email ou mot de passe incorrect.";
            header("Location: ../View/FRONT%20OFFICE/PRINCIPAL/genifty-html/login.php");
            exit;
        }
    } catch (Exception $e) {
        $_SESSION['error_login'] = "Erreur: " . $e->getMessage();
        header("Location: ../View/FRONT%20OFFICE/PRINCIPAL/genifty-html/login.php");
        exit;
    }
} else {
    header("Location: ../View/login.html");
    exit;
}
?>