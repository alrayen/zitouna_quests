<?php
require_once "../Model/user.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $prenom = $_POST['prenom'];
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password2 = $_POST['password2'];
    $birthdate = $_POST['birthdate'];

    if ($password != $password2){
        $_SESSION['error_message'] = "Les mots de passe ne correspondent pas.";
        header("Location: ../View/FRONT OFFICE/PRINCIPAL/genifty-html/registration.html");
        exit;
    }

    if (User::emailExists($email)) {
        $_SESSION['error_message'] = 'Cet email est déjà utilisé. Veuillez utiliser un autre email.';
        header("Location: ../View/FRONT OFFICE/PRINCIPAL/genifty-html/registration.html");
        exit;
    }

    $user = new User(
        $nom,
        $prenom, 
        $birthdate,  
        $email, 
        $password,
        'user',      
        1,           
        0,          
        "null",        
        "null",        
        "null "   ,
        "null"     
    );

    if ($user->register()){
        $_SESSION['success_message'] = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
        header("Location: ../View/FRONT%20OFFICE/PRINCIPAL/genifty-html/login.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Erreur lors de l'inscription.";
        header("Location: ../View/FRONT OFFICE/PRINCIPAL/genifty-html/registration.html");
        exit();
    }
}
?>