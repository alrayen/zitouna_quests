<?php
// Using dirname(__DIR__, 5) to safely reach the project root
$root = dirname(__DIR__, 5);
require_once $root . '/Controller/DonationC.php';

if (isset($_GET['id']) && isset($_GET['montant'])) {
    $donationC = new DonationC();
    // Appel de la fonction qui valide ET attribue les points
    $donationC->validateDonation($_GET['id'], $_GET['montant']);
    
    // Retour à la liste
    header('Location: listDonation.php');
}
?>