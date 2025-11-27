<?php

session_start();
require_once "../config.php";
require_once "../Model/user.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);


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
        // Utiliser la méthode statique User::login qui contient la logique de vérification de l'état
        $loginResult = User::login($email, $password);
        
        if ($loginResult === 'banned') {
            // L'utilisateur est banni, on utilise le même mécanisme d'erreur que pour un mot de passe incorrect
            $_SESSION['error_login'] = "Votre compte est banni. Veuillez consulter l'administrateur.";
            header("Location: ../View/FRONT%20OFFICE/PRINCIPAL/genifty-html/login.php");
            exit;
        } elseif ($loginResult) { // Si $loginResult contient les données de l'utilisateur (connexion réussie)
            $user = $loginResult;
            $_SESSION['user_id'] = $user['id_user'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_nom'] = $user['nom'];
            $_SESSION['user_prenom'] = $user['Prenom'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_image'] = $user['photo'];
            
            if ($user['role'] == 1 || $user['role'] == 'admin') {
                header("Location: ../View/BACK%20OFFICE/VIEW/build/pages/dashboard.html");
            } else {
                header("Location: ../View/FRONT%20OFFICE/PRINCIPAL/genifty-html/index.php");
            }
            exit;
        } else {
            // Identifiants incorrects
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