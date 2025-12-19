<?php
session_start();
include __DIR__ . "/crudSujet.php";
include __DIR__ . "/modérateur.php";
include_once __DIR__ . "/../Model/sujets.php";

if (!isset($_SESSION['user_id'])) {
    header('Location: ../View/FRONT OFFICE/PRINCIPAL/genifty-html/login.php');
    exit;
}

$nom = $_POST["nom"] ?? "";
$date = date("Y-m-d H:i:s");
$id_user = $_SESSION['user_id'];
$titre = $_POST["titre"] ?? substr($nom, 0, 50);

// Modérer le titre et le contenu
$etatTitre = modererCommentaire($titre);
$etatContenu = modererCommentaire($nom);

if ($etatTitre == -1 || $etatContenu == -1) {
    $position = $_GET["position"] ?? $_POST["position"] ?? '';
    if ($position === 'front') {
        header('Location: ../View/FRONT OFFICE/PRINCIPAL/genifty-html/forum.php?error=inappropriate_content');
    } else {
        header('Location: ../View/BACK OFFICE/VIEW/build/pages/posts.php?error=inappropriate_content');
    }
    exit;
}

$image = null;

if (isset($_FILES["image"]) && $_FILES["image"]["error"] === 0) {
    $dossier = __DIR__ . "/images/";
    if (!is_dir($dossier)) {
        mkdir($dossier, 0777, true);
    }

    $extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
    $image = uniqid() . "." . $extension; 
    $path = $dossier . $image;
    
    move_uploaded_file($_FILES["image"]["tmp_name"], $path);
}

$sujet = new Sujet($nom, $date, $titre, $image, $id_user);
$success = createSujet($sujet);

$position = $_GET["position"] ?? $_POST["position"] ?? '';

if ($position === 'front') {
    if ($success) {
        header('Location: ../View/FRONT OFFICE/PRINCIPAL/genifty-html/forum.php?success=post_added');
    } else {
        header('Location: ../View/FRONT OFFICE/PRINCIPAL/genifty-html/forum.php?error=db_error');
    }
} else {
    if ($success) {
        header('Location: ../View/BACK OFFICE/VIEW/build/pages/posts.php?success=post_added');
    } else {
        header('Location: ../View/BACK OFFICE/VIEW/build/pages/posts.php?error=db_error');
    }
}

exit;
?>