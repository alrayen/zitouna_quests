<?php
session_start();

require_once ($_SERVER['DOCUMENT_ROOT'] . '/Projet2/Controller/userController.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $recaptcha_secret = '6LezTR8sAAAAAMjKjaZndDaG39AeKjtf8BiyDoSy'; 
    $recaptcha_response = $_POST['g-recaptcha-response'] ?? '';

   
    if (empty($recaptcha_response)) {
        $_SESSION['error_message'] = "Veuillez cocher la case 'Je ne suis pas un robot'.";
        header("Location: ../View/FRONT OFFICE/PRINCIPAL/genifty-html/registration.php");
        exit();
    }

    $verify_url = "https://www.google.com/recaptcha/api/siteverify?secret={$recaptcha_secret}&response={$recaptcha_response}";
    $response_json = file_get_contents($verify_url);
    $response_data = json_decode($response_json);

    if (!$response_data->success) {

        $_SESSION['error_message'] = "Échec de la vérification anti-robot. Veuillez réessayer.";
        header("Location: ../View/FRONT OFFICE/PRINCIPAL/genifty-html/registration.php");
        exit();
    }


    $prenom = $_POST['prenom'] ?? '';
    $nom = $_POST['nom'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $passwordConfirm = $_POST['password2'] ?? ''; 
    $birthdate = $_POST['birthdate'] ?? '';
    $face_descriptor = $_POST['face_descriptor'] ?? null;



    if (empty($email) || empty($password) || empty($nom) || empty($prenom)) {
        $_SESSION['error_message'] = "Veuillez remplir tous les champs obligatoires.";
        header("Location: ../View/FRONT OFFICE/PRINCIPAL/registration.php");
        exit();
    }

    if ($password !== $passwordConfirm) {
        $_SESSION['error_message'] = "Les mots de passe ne correspondent pas.";
        header("Location: ../View/FRONT OFFICE/PRINCIPAL/registration.php");
        exit();
    }


    $userController = new UserController();
    $userController->inscription($nom, $prenom, $email, $password, $birthdate, $face_descriptor);

} else {
    header("Location: ../View/FRONT OFFICE/PRINCIPAL/registration.php");
    exit();
}
?>