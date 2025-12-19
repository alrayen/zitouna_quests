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
        echo "Les mots de passe ne correspondent pas";
        exit;
    }

    if (User::emailExists($email)) {
        echo "<script>alert('Cet email est déjà utilisé. Veuillez utiliser un autre email.'); window.location.href='../View/FRONT OFFICE/PRINCIPAL/genifty-html/registration.html';</script>";
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
        echo "Inscription réussie ! <a href='../View/FRONT%20OFFICE/PRINCIPAL/genifty-html/login.html'>Se connecter</a>";

    } else {
        echo "Erreur lors de l'inscription.";
    }
}
?>