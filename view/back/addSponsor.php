<?php
require_once '../../controller/SponsorC.php';
require_once '../../model/Sponsor.php';

$error = "";
$success = "";

if (isset($_POST["nom"]) && isset($_POST["secteur"]) && isset($_POST["contact"]) && isset($_POST["contribution"])) {
    if (!empty($_POST["nom"]) && !empty($_POST["contact"])) {
        $sponsor = new Sponsor(null, $_POST['nom'], $_POST['secteur'], $_POST['contact'], $_POST['contribution']);
        $sponsorC = new SponsorC();
        $sponsorC->ajouterSponsor($sponsor);
        $success = "Partenaire ajouté à la quête avec succès !";
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter Partenaire - Zitouna Quest</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        /* THEME ZITOUNA QUEST */
        :root { --primary: #27ae60; --dark: #2c3e50; --bg: #f4f7f6; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Poppins', sans-serif; background-color: var(--bg); display: flex; height: 100vh; }
        
        .sidebar { width: 260px; background-color: var(--dark); color: white; display: flex; flex-direction: column; padding: 20px; position: fixed; height: 100%; }
        
        /* Titres de section dans le menu */
        .menu-title { font-size: 0.85em; text-transform: uppercase; color: #7f8c8d; margin-top: 20px; margin-bottom: 10px; font-weight: 600; padding-left: 10px; letter-spacing: 1px;}

        .sidebar a { text-decoration: none; color: #bdc3c7; padding: 15px; margin-bottom: 5px; border-radius: 8px; transition: 0.3s; display: block; font-weight: 500;}
        .sidebar a:hover, .sidebar a.active { background-color: var(--primary); color: white; padding-left: 20px; }
        
        .main-content { margin-left: 260px; width: calc(100% - 260px); padding: 40px; display: flex; flex-direction: column; align-items: center; }
        .card { background: white; padding: 40px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); width: 100%; max-width: 800px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 600; }
        input, select { width: 100%; padding: 14px; border: 2px solid #ecf0f1; border-radius: 10px; font-family: inherit; }
        input:focus { outline: none; border-color: var(--primary); }
        .btn-submit { background-color: var(--primary); color: white; padding: 14px; border: none; border-radius: 10px; cursor: pointer; width: 100%; font-size: 16px; font-weight: 600; margin-top: 10px; }
        .alert-success { background: #d5f5e3; color: #1e8449; padding: 15px; border-radius: 10px; margin-bottom: 20px; border: 1px solid #abebc6;}
        .error-msg { color: #e74c3c; font-size: 0.85em; margin-top: 5px; display: block; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div style="text-align: center; margin-bottom: 20px;">
            <img src="logo.png" alt="Logo" style="width: 80px; margin-bottom: 10px; border-radius: 50%;">
            <h2 style="font-size: 20px; font-weight: 700; color: #ecf0f1;">Zitouna Quest</h2>
        </div>

<a href="dashboard.php">📊 Dashboard</a>
<hr style="border-color: #444; margin: 15px 0;">

<div class="menu-title">Sponsor</div>
<a href="addSponsor.php" class="active">🌱 Nouveau Sponsor</a>
<a href="listSponsor.php">📜 Liste Sponsor</a>

        <div class="menu-title">Dons & Points</div>
        <a href="addDonation.php">🎁 Faire un Don</a>
        <a href="listDonation.php">📜 Historique Dons</a>

        <a href="#" style="margin-top: auto; color: #e74c3c;">🚪 Quitter</a>
    </div>

    <div class="main-content">
        <h1 style="margin-bottom: 30px; color: #2c3e50;">Ajouter un Partenaire</h1>

        <?php if(!empty($error)) { echo "<div style='color:red'>$error</div>"; } ?>
        <?php if(!empty($success)) { echo "<div class='alert-success'>$success</div>"; } ?>

        <div class="card">
            <form action="" method="POST" novalidate>
                <div class="form-group">
                    <label>Nom de l'entreprise</label>
                    <input type="text" id="nom" name="nom" placeholder="Ex: BioTunisie">
                    <span id="errorNom" class="error-msg"></span>
                </div>

                <div class="form-group">
                    <label>Secteur d'activité</label>
                    <select id="secteur" name="secteur">
                        <option value="">-- Choisir --</option>
                        <option value="Agriculture">🌿 Agriculture</option>
                        <option value="Technologie">💻 Technologie</option>
                        <option value="Environnement">🌍 Environnement</option>
                        <option value="Education">🎓 Éducation</option>
                    </select>
                    <span id="errorSecteur" class="error-msg"></span>
                </div>

                <div class="form-group">
                    <label>Contact (Email)</label>
                    <input type="text" id="contact" name="contact" placeholder="contact@entreprise.com">
                    <span id="errorContact" class="error-msg"></span>
                </div>

                <div class="form-group">
                    <label>Contribution (DT)</label>
                    <input type="number" id="contribution" name="contribution" placeholder="Ex: 1000">
                    <span id="errorContribution" class="error-msg"></span>
                </div>

                <button type="submit" class="btn-submit" onclick="valider(event)">Valider l'ajout</button>
            </form>
        </div>
    </div>

    <script>
        function valider(e) {
            let nom = document.getElementById("nom").value;
            let secteur = document.getElementById("secteur").value;
            let contact = document.getElementById("contact").value;
            let contribution = document.getElementById("contribution").value;
            let isValid = true;
            document.querySelectorAll('.error-msg').forEach(el => el.innerHTML = "");

            if (nom.trim().length < 2) { document.getElementById("errorNom").innerHTML = "Nom requis."; isValid = false; }
            if (secteur === "") { document.getElementById("errorSecteur").innerHTML = "Secteur requis."; isValid = false; }
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(contact)) { document.getElementById("errorContact").innerHTML = "Email invalide."; isValid = false; }
            if (contribution === "" || contribution <= 0) { document.getElementById("errorContribution").innerHTML = "Montant invalide."; isValid = false; }

            if (!isValid)e.preventDefault();
           /* else{
            e.preventDefault();
            window.location.href="listSponsor.php";}*/

        }
    </script>
</body>
</html>