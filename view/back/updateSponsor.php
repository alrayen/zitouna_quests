<?php
require_once '../../controller/SponsorC.php';
require_once '../../model/Sponsor.php';

$sponsorC = new SponsorC();
if (isset($_GET['id'])) {
    $old = $sponsorC->recupererSponsor($_GET['id']);
} else { header('Location: listSponsor.php'); exit(); }

if (isset($_POST['nom'])) {
    $s = new Sponsor($_GET['id'], $_POST['nom'], $_POST['secteur'], $_POST['contact'], $_POST['contribution']);
    $sponsorC->modifierSponsor($s, $_GET['id']);
    header('Location: listSponsor.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier - Zitouna Quest</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        /* CSS ZITOUNA IDENTIQUE A addSponsor */
        :root { --primary: #27ae60; --sidebar-bg: #2c3e50; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7f6; display: flex; height: 100vh; }
        .sidebar { width: 260px; background-color: var(--sidebar-bg); color: white; display: flex; flex-direction: column; padding: 20px; position: fixed; height: 100%; }
        .sidebar a { text-decoration: none; color: #bdc3c7; padding: 15px; margin-bottom: 10px; border-radius: 8px; transition: 0.3s; display: block; font-weight: 500;}
        .sidebar a:hover { background-color: var(--primary); color: white; padding-left: 20px; }
        .main-content { margin-left: 260px; width: calc(100% - 260px); padding: 40px; display: flex; flex-direction: column; align-items: center; }
        .card { background: white; padding: 40px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); width: 100%; max-width: 800px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 600; }
        input, select { width: 100%; padding: 14px; border: 2px solid #ecf0f1; border-radius: 10px; font-family: inherit; }
        input:focus, select:focus { outline: none; border-color: var(--primary); }
        .btn-submit { background-color: #f39c12; color: white; padding: 14px; border: none; border-radius: 10px; cursor: pointer; width: 100%; font-size: 16px; font-weight: 600; margin-top: 10px; }
        .btn-submit:hover { background-color: #e67e22; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div style="text-align: center; margin-bottom: 40px;">
            <img src="logo.png" alt="Logo" style="width: 90px; margin-bottom: 15px; border-radius: 50%;">
            <h2 style="font-size: 22px; font-weight: 700; color: #ecf0f1;">Zitouna Quest</h2>
        </div>
        <a href="addSponsor.php">🌱 Nouveau Partenaire</a>
        <a href="listSponsor.php">📜 Liste des Partenaires</a>
        <a href="#" style="margin-top: auto; color: #e74c3c;">🚪 Quitter</a>
    </div>

    <div class="main-content">
        <h1 style="margin-bottom: 30px;">Modifier Partenaire #<?php echo $old['id']; ?></h1>

        <div class="card">
            <form action="" method="POST">
                <div class="form-group">
                    <label>Nom de l'entreprise</label>
                    <input type="text" name="nom" value="<?php echo $old['nom']; ?>">
                </div>

                <div class="form-group">
                    <label>Secteur d'activité</label>
                    <select name="secteur">
                        <option value="Agriculture" <?php if($old['secteur']=='Agriculture') echo 'selected'; ?>>🌿 Agriculture</option>
                        <option value="Technologie" <?php if($old['secteur']=='Technologie') echo 'selected'; ?>>💻 Technologie</option>
                        <option value="Environnement" <?php if($old['secteur']=='Environnement') echo 'selected'; ?>>🌍 Environnement</option>
                        <option value="Education" <?php if($old['secteur']=='Education') echo 'selected'; ?>>🎓 Éducation</option>
                        <option value="Autre" <?php if($old['secteur']=='Autre') echo 'selected'; ?>>🔹 Autre</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Contact (Email)</label>
                    <input type="text" name="contact" value="<?php echo $old['contact']; ?>">
                </div>

                <div class="form-group">
                    <label>Contribution (DT)</label>
                    <input type="number" name="contribution" value="<?php echo $old['contribution']; ?>">
                </div>

                <button type="submit" class="btn-submit">Mettre à jour</button>
            </form>
        </div>
    </div>
</body>
</html>