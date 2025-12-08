<?php

include __DIR__ . "/crudSujet.php";
include_once __DIR__ . "/../model/sujets.php";

$nom = $_POST["nom"];

// Date du jour
$date = date("Y-m-d");

// Valeur par défaut
$titre = 'fsdgfghj';
$image = null;

// Upload de l’image
if (isset($_FILES["image"]) && $_FILES["image"]["error"] === 0) {

    // Dossier d’upload — CORRECTION : ajout du slash !
    $dossier = __DIR__ . "/images/";

    // On s'assure que le dossier existe
    if (!is_dir($dossier)) {
        mkdir($dossier, 0777, true);
    }

    $image = basename($_FILES["image"]["name"]);
    $path = $dossier . $image;

    // CORRECTION : mettre le chemin complet vers le fichier
    move_uploaded_file($_FILES["image"]["tmp_name"], $path);
}

// Création de l’objet
$sujet = new Sujet($nom, $date, $titre, $image);

// Enregistrement en BD
createSujet($sujet);

// Récupération de la position (GET ou POST selon ton formulaire)
$position = $_GET["position"] ?? $_POST["position"] ?? '';

if ($position === 'front') {
    header('Location: ../view/frontoffice/forum.php');
} else {
    header('Location: ../view/backoffice/build/pages/posts.php');
}

exit;
?>