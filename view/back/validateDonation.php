<?php
require_once '../../controller/DonationC.php';

if (isset($_GET['id']) && isset($_GET['montant'])) {
    $donationC = new DonationC();
    // Appel de la fonction qui valide ET attribue les points
    $donationC->validateDonation($_GET['id'], $_GET['montant']);
    
    // Retour à la liste
    header('Location: listDonation.php');
}
?>