<?php

session_start();
require_once "../config.php";
require_once "../Model/user.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);


    if (empty($email) || empty($password)) {
        echo "<script>alert('Veuillez remplir tous les champs'); window.location.href='../View/login.html';</script>";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Email invalide'); window.location.href='../View/FRONT%20OFFICE/PRINCIPAL/genifty-html/login.html';</script>";
        exit;
    }

    try {
        $pdo = config::getConnexion();
        

        $stmt = $pdo->prepare("SELECT * FROM user WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id_user'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_nom'] = $user['nom'];
            $_SESSION['user_prenom'] = $user['Prenom'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_image'] = $user['photo'];
            /*
            if ($remember) {
                setcookie('user_email', $email, time() + (86400 * 30), "/"); // 30 jours
            }
            */

            if ($user['role'] == 1 || $user['role'] == 'admin') {
                header("Location: ../View/BACK%20OFFICE/VIEW/build/pages/dashboard.html");
            } else {
                header("Location: ../View/FRONT%20OFFICE/PRINCIPAL/genifty-html/index.html");
            }
            exit;
        } else {

            echo "<script>alert('Email ou mot de passe incorrect'); window.location.href='../View/login.html';</script>";
            exit;
        }
    } catch (Exception $e) {
        echo "<script>alert('Erreur: " . $e->getMessage() . "'); window.location.href='../View/login.html';</script>";
        exit;
    }
} else {
    header("Location: ../View/login.html");
    exit;
}
?>