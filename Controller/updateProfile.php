<?php
session_start();
require_once "../Model/user.php";

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = 'Vous devez être connecté pour modifier votre profil.';
    header("Location: ../View/FRONT OFFICE/PRINCIPAL/genifty-html/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $nom = htmlspecialchars(trim($_POST['nom']));
    $prenom = htmlspecialchars(trim($_POST['prenom']));
    $email = htmlspecialchars(trim($_POST['email']));
    $birthdate = htmlspecialchars(trim($_POST['birthdate']));
    $bio = htmlspecialchars(trim($_POST['bio'] ?? ''));
    $password = !empty($_POST['password']) ? $_POST['password'] : null;
    $password2 = !empty($_POST['password2']) ? $_POST['password2'] : null;
    
    // Handle file upload
    $photo = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../uploads/profiles/';
        
        // Create directory if it doesn't exist
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $file = $_FILES['photo'];
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 5 * 1024 * 1024; // 5MB
        
        // Validate file type
        if (!in_array($file['type'], $allowedTypes)) {
            $_SESSION['error_message'] = 'Type de fichier non autorisé. Veuillez uploader une image (JPG, PNG, GIF ou WEBP).';
            header("Location: ../View/FRONT OFFICE/PRINCIPAL/genifty-html/modifierprofil.php");
            exit;
        }
        
        // Validate file size
        if ($file['size'] > $maxSize) {
            $_SESSION['error_message'] = 'Le fichier est trop volumineux. Taille maximale: 5MB.';
            header("Location: ../View/FRONT OFFICE/PRINCIPAL/genifty-html/modifierprofil.php");
            exit;
        }
        
        // Get current user photo to delete old one later
        $currentUser = User::getUserById($user_id);
        $oldPhoto = $currentUser['photo'] ?? null;
        
        // Generate unique filename
        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $photo = 'profile_' . $user_id . '_' . time() . '.' . $fileExtension;
        $uploadPath = $uploadDir . $photo;
        
        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            // Delete old photo if it exists and is in our uploads directory
            if ($oldPhoto && file_exists($uploadDir . $oldPhoto)) {
                @unlink($uploadDir . $oldPhoto);
            }
        } else {
            $_SESSION['error_message'] = 'Erreur lors de l\'upload du fichier.';
            header("Location: ../View/FRONT OFFICE/PRINCIPAL/genifty-html/modifierprofil.php");
            exit;
        }
    } else {
        // No new photo uploaded, keep existing one
        $currentUser = User::getUserById($user_id);
        $photo = $currentUser['photo'] ?? null;
    }

    // Validation
    if (empty($nom) || empty($prenom) || empty($email) || empty($birthdate)) {
        $_SESSION['error_message'] = 'Veuillez remplir tous les champs obligatoires.';
        header("Location: ../View/FRONT OFFICE/PRINCIPAL/genifty-html/modifierprofil.php");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_message'] = 'Email invalide.';
        header("Location: ../View/FRONT OFFICE/PRINCIPAL/genifty-html/modifierprofil.php");
        exit;
    }

    // Password validation if provided
    if (!empty($password)) {
        if ($password !== $password2) {
            $_SESSION['error_message'] = 'Les mots de passe ne correspondent pas.';
            header("Location: ../View/FRONT OFFICE/PRINCIPAL/genifty-html/modifierprofil.php");
            exit;
        }
        if (strlen($password) < 6) {
            $_SESSION['error_message'] = 'Le mot de passe doit contenir au moins 6 caractères.';
            header("Location: ../View/FRONT OFFICE/PRINCIPAL/genifty-html/modifierprofil.php");
            exit;
        }
    }

    // Update profile
    $result = User::updateProfile($user_id, $nom, $prenom, $email, $birthdate, $bio, $photo, $password);

    if ($result['success']) {
        // Update session variables
        $_SESSION['user_nom'] = $nom;
        $_SESSION['user_prenom'] = $prenom;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_image'] = $photo;
        $_SESSION['success_message'] = $result['message'];
        header("Location: ../View/FRONT OFFICE/PRINCIPAL/genifty-html/author.php");
    } else {
        $_SESSION['error_message'] = $result['message'];
        header("Location: ../View/FRONT OFFICE/PRINCIPAL/genifty-html/modifierprofil.php");
    }
    exit;
} else {
    header("Location: ../View/FRONT OFFICE/PRINCIPAL/genifty-html/modifierprofil.php");
    exit;
}
?>
