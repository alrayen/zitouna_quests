<?php
require_once '../../controller/DonationC.php';
$donationC = new DonationC();
$liste = $donationC->listDonations();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique Dons - Zitouna Quest</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        /* CSS IDENTIQUE AUX AUTRES PAGES */
        :root { --primary: #27ae60; --dark: #2c3e50; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7f6; display: flex; height: 100vh; overflow: hidden; }
        .sidebar { width: 260px; background-color: var(--dark); color: white; padding: 20px; display: flex; flex-direction: column; height: 100vh; flex-shrink: 0; }
        .sidebar a { text-decoration: none; color: #bdc3c7; padding: 15px; margin-bottom: 10px; border-radius: 8px; transition: 0.3s; display: block; font-weight: 500;}
        .sidebar a:hover, .sidebar a.active { background-color: var(--primary); color: white; padding-left: 20px; }
        .menu-title {
    font-size: 0.85em;
    text-transform: uppercase;
    color: #7f8c8d;
    margin-top: 20px;
    margin-bottom: 10px;
    font-weight: 600;
    padding-left: 10px;
    letter-spacing: 1px;
}
        .main-content { flex-grow: 1; padding: 40px; overflow-y: auto; }
        
        /* TABLEAU */
        .table-container { background: white; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); overflow: hidden; }
        table { width: 100%; border-collapse: collapse; }
        th { background-color: var(--primary); color: white; padding: 18px; text-align: left; font-weight: 600; text-transform: uppercase; font-size: 0.85em; }
        td { padding: 18px; border-bottom: 1px solid #eee; color: #555; }
        
        /* ETATS & POINTS */
        .status-wait { color: #e67e22; font-weight: bold; background: #fdf2e9; padding: 5px 10px; border-radius: 20px; font-size: 0.8em; }
        .status-ok { color: #27ae60; font-weight: bold; background: #eafaf1; padding: 5px 10px; border-radius: 20px; font-size: 0.8em; }
        .points-badge { background-color: #f1c40f; color: #fff; padding: 5px 10px; border-radius: 50%; font-weight: bold; font-size: 0.9em; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }

        /* BOUTONS */
        .btn-validate { background-color: #2980b9; color: white; padding: 8px 12px; border-radius: 8px; text-decoration: none; font-size: 0.9em; font-weight: 600; transition: 0.3s;}
        .btn-validate:hover { background-color: #1a5276; }
        .btn-delete { background-color: #e74c3c; color: white; padding: 8px 12px; border-radius: 8px; text-decoration: none; font-size: 0.9em; }
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
<a href="listSponsor.php">📜 Liste Sponsors </a>

<div class="menu-title">Dons & Points</div>
<a href="addDonation.php">🎁 Faire un Don</a>
<a href="listDonation.php" class="active">📜 Historique Dons</a>
    </div>

    <div class="main-content">
        <h1 style="margin-bottom: 30px; color: #2c3e50;">Gestion des Dons</h1>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Donateur</th>
                        <th>Type & Montant</th>
                        <th>Date</th>
                        <th>État</th>
                        <th>Points Gagnés</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($liste as $row) { ?>
                    <tr>
                        <td><strong><?php echo $row['nom_donateur']; ?></strong></td>
                        <td><?php echo $row['type_don']; ?> <br> <small style="color:#777;"><?php echo $row['montant']; ?> DT</small></td>
                        <td><?php echo $row['date_don']; ?></td>
                        
                        <td>
                            <?php if($row['etat'] == 'En attente'): ?>
                                <span class="status-wait">⏳ En attente</span>
                            <?php else: ?>
                                <span class="status-ok">✅ Validé</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php if($row['etat'] == 'Validé'): ?>
                                <span class="points-badge">+<?php echo $row['points_gagnes']; ?></span>
                            <?php else: ?>
                                <small>---</small>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php if($row['etat'] == 'En attente'): ?>
                                <a href="validateDonation.php?id=<?php echo $row['id']; ?>&montant=<?php echo $row['montant']; ?>" class="btn-validate">✔ Valider</a>
                            <?php endif; ?>
                            
                            <a href="deleteDonation.php?id=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Supprimer ?');">X</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>