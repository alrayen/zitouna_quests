<?php
require_once '../../controller/DonationC.php';
require_once '../../model/Donation.php';

$error = "";
$success = "";

if (isset($_POST["nom"]) && isset($_POST["montant"])) {
    if (!empty($_POST["nom"]) && !empty($_POST["montant"])) {
        // Date du jour par défaut
        $date = date('Y-m-d');
        $don = new Donation(null, $_POST['nom'], $_POST['type'], $_POST['montant'], $date);
        
        $donationC = new DonationC();
        $donationC->createDonation($don);
        $success = "Don enregistré ! Il est maintenant en attente de validation.";
    } else {
        $error = "Champs manquants.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Faire un Don - Zitouna Quest</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #27ae60; --dark: #2c3e50; --bg: #f4f7f6; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Poppins', sans-serif; background-color: var(--bg); display: flex; height: 100vh; }
        .sidebar { width: 260px; background-color: var(--dark); color: white; display: flex; flex-direction: column; padding: 20px; position: fixed; height: 100%; }
        .sidebar a { text-decoration: none; color: #bdc3c7; padding: 15px; margin-bottom: 10px; border-radius: 8px; transition: 0.3s; display: block; font-weight: 500;}
        .sidebar a:hover, .sidebar a.active { background-color: var(--primary); color: white; padding-left: 20px; }
        .main-content { margin-left: 260px; width: calc(100% - 260px); padding: 40px; display: flex; flex-direction: column; align-items: center; }
        .card { background: white; padding: 40px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); width: 100%; max-width: 800px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 600; }
        input, select { width: 100%; padding: 14px; border: 2px solid #ecf0f1; border-radius: 10px; font-family: inherit; }
        input:focus { outline: none; border-color: var(--primary); }
        .btn-submit { background-color: var(--primary); color: white; padding: 14px; border: none; border-radius: 10px; cursor: pointer; width: 100%; font-size: 16px; font-weight: 600; margin-top: 10px; }
        .alert-success { background: #d5f5e3; color: #1e8449; padding: 15px; border-radius: 10px; width: 100%; margin-bottom: 20px; border: 1px solid #abebc6;}
    </style>
</head>
<body>
    <div class="sidebar">
        <div style="text-align: center; margin-bottom: 40px;">
            <img src="logo.png" alt="Logo" style="width: 90px; margin-bottom: 15px; border-radius: 50%;">
            <h2 style="font-size: 22px; font-weight: 700; color: #ecf0f1;">Zitouna Quest</h2>
        </div>
<a href="dashboard.php">📊 Dashboard</a>
<hr style="border-color: #444; margin: 15px 0;">

<div class="menu-title">Sponsors</div>
<a href="addSponsor.php">🌱 Nouveau Sponsor</a>
<a href="listSponsor.php">📜 Liste Sponsors</a>

<div class="menu-title">Dons & Points</div>
<a href="addDonation.php" class="active">🎁 Faire un Don</a>
<a href="listDonation.php">📜 Historique Dons</a>
    </div>

    <div class="main-content">
        <h1 style="margin-bottom: 30px; color: #2c3e50;">Enregistrer un Don</h1>
        <?php if(!empty($success)) echo "<div class='alert-success'>$success</div>"; ?>

        <div class="card">
            <form action="" method="POST">
                <div class="form-group">
                    <label>Nom du Donateur</label>
                    <input type="text" name="nom" required placeholder="Ex: Ahmed Ben Ali">
                </div>
                <div class="form-group">
                    <label>Type de Don</label>
                    <select name="type">
                        <option value="Argent">💵 Argent</option>
                        <option value="Materiel">🛠️ Matériel</option>
                        <option value="Arbres">🌳 Arbres</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Valeur estimée (DT)</label>
                    <input type="number" name="montant" required placeholder="Ex: 50">
                    <small style="color: #777;">Info: 10 DT = 1 Point de fidélité après validation.</small>
                </div>
                <button type="submit" class="btn-submit">Enregistrer</button>
            </form>
        </div>
    </div>
</body>
</html>