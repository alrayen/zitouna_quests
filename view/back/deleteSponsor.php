<?php
require_once '../../controller/SponsorC.php';

if (isset($_GET["id"])) {
    try {
        $sponsorC = new SponsorC();
        $sponsorC->supprimerSponsor($_GET["id"]);
        header('Location: listSponsor.php?notif_type=success&notif_msg=' . urlencode('Partenaire supprimé avec succès'));
    } catch (Exception $e) {
        header('Location: listSponsor.php?notif_type=error&notif_msg=' . urlencode('Erreur lors de la suppression'));
    }
    exit();
}
?>