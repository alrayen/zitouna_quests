<?php

include __DIR__ . "/crudSujet.php";
include_once __DIR__ . "/../Model/sujets.php";

$nom = $_POST["nom"];


$date = date("Y-m-d");


$titre = 'fsdgfghj';
$image = null;


if (isset($_FILES["image"]) && $_FILES["image"]["error"] === 0) {

    
    $dossier = __DIR__ . "/images/";


    if (!is_dir($dossier)) {
        mkdir($dossier, 0777, true);
    }

    $image = basename($_FILES["image"]["name"]); 
    $path = $dossier . $image;


      

    
    move_uploaded_file($_FILES["image"]["tmp_name"], $path);
}


$sujet = new Sujet($nom, $date, $titre, $image);


createSujet($sujet);


$position = $_GET["position"] ?? $_POST["position"] ?? '';

if ($position === 'front') {
    header('Location: ../View/FRONT OFFICE/forum.php');
} else {
    header('Location: ../View/BACK OFFICE/build/pages/posts.php');
}

exit;
?>