<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . '/Projet2/Controller/userController.php');

if (isset($_POST['verifier']) && isset($_POST['code']) && isset($_POST['email'])) {
    $email = $_POST['email'];
    $code = $_POST['code'];

    $userController = new UserController();
    
    
    $result = $userController->verifierCode($email, $code);

    
    echo "<script>alert('$result'); window.location.href='../View/FRONT OFFICE/PRINCIPAL/verification_page.php?email=$email';</script>";
} else {

    header("Location: ../View/FRONT OFFICE/PRINCIPAL/login.php");
}
?>