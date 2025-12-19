<?php
// Using dirname(__DIR__, 5) to safely reach the project root
$root = dirname(__DIR__, 5);
require_once $root . '/Controller/SponsorC.php';

if (isset($_GET["id"])) {
    $sponsorC = new SponsorC();
    $sponsorC->supprimerSponsor($_GET["id"]);
    header('Location: listSponsor.php');
}
?>