<?php
// Using dirname(__DIR__, 5) to safely reach the project root
$root = dirname(__DIR__, 5);
require_once $root . '/Controller/DonationC.php';

// On vérifie si l'ID est bien présent dans l'URL (ex: ?id=3)
if (isset($_GET["id"]) && !empty($_GET["id"])) {
    
    $donationC = new DonationC();
    
    // On appelle la fonction de suppression
    $donationC->deleteDonation($_GET["id"]);
    
    // On redirige vers la liste après suppression
    header('Location: listDonation.php');
    exit();

} else {
    // Si pas d'ID, on renvoie à la liste
    header('Location: listDonation.php?error=no_id');
    exit();
}
?>