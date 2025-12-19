<?php
session_start();
include_once __DIR__ . "/crudSujet.php";

if (!isset($_SESSION['user_id'])) {
    header('Location: ../View/FRONT OFFICE/PRINCIPAL/genifty-html/login.php');
    exit;
}

if (isset($_GET['id']) && isset($_GET['choix'])) {
    $id = intval($_GET['id']);
    $choix = $_GET['choix'];

    if ($choix == 'increment') {
        incrementLike($id);
    } else {
        decrementLike($id);
    }
}

header('Location: ../View/FRONT OFFICE/PRINCIPAL/genifty-html/forum.php');
exit;
?>