<?php
include __DIR__ . "/crudSujet.php";
include __DIR__ . "/modérateur.php";

$id = $_GET['id'];
$contenu = $_POST['nom'] ?? "";
$titre = $_POST['titre'] ?? null;

// Modérer le titre et le contenu lors de la modification
if ($titre) {
    $etatTitre = modererCommentaire($titre);
    if ($etatTitre == -1) {
        $position = $_GET["position"] ?? "";
        if ($position == 'front') {
            header('Location: ../View/FRONT OFFICE/PRINCIPAL/genifty-html/forum.php?error=inappropriate_content&post=' . $id);
        } else {
            header('Location: ../View/BACK OFFICE/VIEW/build/pages/posts.php?error=inappropriate_content');
        }
        exit;
    }
}

$etatContenu = modererCommentaire($contenu);
if ($etatContenu == -1) {
    $position = $_GET["position"] ?? "";
    if ($position == 'front') {
        header('Location: ../View/FRONT OFFICE/PRINCIPAL/genifty-html/forum.php?error=inappropriate_content&post=' . $id);
    } else {
        header('Location: ../View/BACK OFFICE/VIEW/build/pages/posts.php?error=inappropriate_content');
    }
    exit;
}

modifierposts($id, $contenu, $titre);

$position = $_GET["position"] ?? "";
if ($position == 'front') {
    header('Location: ../View/FRONT OFFICE/PRINCIPAL/genifty-html/forum.php?success=post_updated&post=' . $id);
} else {
    header('Location: ../View/BACK OFFICE/VIEW/build/pages/posts.php');
}
exit;
?>