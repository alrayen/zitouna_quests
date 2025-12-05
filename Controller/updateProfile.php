<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . '/Projet2/Model/user.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../View/FRONT OFFICE/PRINCIPAL/genifty-html/login_page.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_SESSION['user_id'];
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $email = $_POST['email'] ?? '';
    $birthdate = $_POST['birthdate'] ?? '';
    $bio = $_POST['bio'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $photoName = null;
    
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        $fileName = $_FILES['photo']['name'];
        $fileTmpName = $_FILES['photo']['tmp_name'];
        $fileSize = $_FILES['photo']['size'];
        $fileError = $_FILES['photo']['error'];
        $fileType = $_FILES['photo']['type'];

        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed = array('jpg', 'jpeg', 'png', 'gif');

        if (in_array($fileExt, $allowed)) {
            if ($fileError === 0) {
                if ($fileSize < 5000000) { 
                    $photoName = "profile_" . $id . "_" . uniqid() . "." . $fileExt;
                    
                    $fileDestination = '../uploads/profiles/' . $photoName;
                    
                    if(move_uploaded_file($fileTmpName, $fileDestination)) {
                        $_SESSION['user_image'] = $photoName;
                    } else {
                        $_SESSION['error_message'] = "Erreur lors de l'enregistrement de l'image sur le serveur.";
                    }
                } else {
                    $_SESSION['error_message'] = "Votre fichier est trop volumineux (Max 5MB).";
                }
            } else {
                $_SESSION['error_message'] = "Erreur lors du téléchargement de l'image.";
            }
        } else {
            $_SESSION['error_message'] = "Vous ne pouvez télécharger que des fichiers de type image (jpg, jpeg, png, gif).";
        }
    }

    $audioName = null;

    if (isset($_FILES['bio_audio']) && $_FILES['bio_audio']['error'] === 0) {
        $audioFileName = $_FILES['bio_audio']['name'];
        $audioTmpName = $_FILES['bio_audio']['tmp_name'];
        $audioSize = $_FILES['bio_audio']['size'];
        
        $audioExt = strtolower(pathinfo($audioFileName, PATHINFO_EXTENSION));
        $allowedAudio = ['mp3', 'wav', 'ogg', 'webm', 'm4a'];

        if (in_array($audioExt, $allowedAudio)) {
            if ($audioSize < 10000000) {
                $audioName = "audio_" . $id . "_" . uniqid() . "." . $audioExt;
                
                $uploadDir = '../uploads/audio/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $audioDestination = $uploadDir . $audioName;
                
                if (!move_uploaded_file($audioTmpName, $audioDestination)) {
                    $audioName = null;
                    $_SESSION['error_message'] = "Erreur lors de l'enregistrement du message vocal.";
                }
            } else {
                $_SESSION['error_message'] = "Le fichier audio est trop volumineux.";
            }
        }
    }

    
    $result = User::updateProfile($id, $nom, $prenom, $email, $birthdate, $bio, $photoName, $password, $audioName);

    if ($result['success']) {
        $_SESSION['user_nom'] = $nom;
        $_SESSION['user_prenom'] = $prenom;
        
        header("Location: ../View/FRONT OFFICE/PRINCIPAL/genifty-html/author.php"); // Vérifie ce chemin
        exit();
    } else {
        $_SESSION['error_message'] = $result['message'];
        header("Location: ../View/FRONT OFFICE/PRINCIPAL/genifty-html/modifierprofil.php");
        exit();
    }
     

} else {
    header("Location: ../View/FRONT OFFICE/PRINCIPAL/genifty-html/author.php");
    exit();
}
?>