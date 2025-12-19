<?php
require_once '../../controller/SponsorC.php';
$sponsorC = new SponsorC();
$liste = $sponsorC->afficherSponsors();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    
    <meta charset="UTF-8">
    <title>Partenaires - Zitouna Quest</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #27ae60; --dark: #2c3e50; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7f6; display: flex; height: 100vh; overflow: hidden; }
        
        .sidebar { width: 260px; background-color: var(--dark); color: white; display: flex; flex-direction: column; padding: 20px; height: 100vh; flex-shrink: 0; }
        .menu-title { font-size: 0.85em; text-transform: uppercase; color: #7f8c8d; margin-top: 20px; margin-bottom: 10px; font-weight: 600; padding-left: 10px; letter-spacing: 1px;}
        .sidebar a { text-decoration: none; color: #bdc3c7; padding: 15px; margin-bottom: 5px; border-radius: 8px; transition: 0.3s; display: block; font-weight: 500;}
        .sidebar a:hover, .sidebar a.active { background-color: var(--primary); color: white; padding-left: 20px; }

        .main-content { flex-grow: 1; padding: 40px; overflow-y: auto; }
        .header-list { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .table-container { background: white; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); overflow: hidden; }
        table { width: 100%; border-collapse: collapse; }
        th { background-color: var(--primary); color: white; padding: 18px; text-align: left; font-weight: 600; text-transform: uppercase; font-size: 0.85em; }
        td { padding: 18px; border-bottom: 1px solid #eee; color: #555; }
        
        .badge { padding: 6px 12px; border-radius: 20px; font-size: 0.8em; font-weight: 600; background-color: #eee; color: #555;}
        .action-btn { padding: 8px 12px; border-radius: 8px; text-decoration: none; color: white; font-size: 0.9em; margin-right: 5px; }
        .btn-edit { background-color: #f39c12; }
        .btn-delete { background-color: #e74c3c; }
        .btn-add { background-color: var(--primary); color: white; text-decoration: none; padding: 12px 25px; border-radius: 10px; font-weight: 600; }
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

<div class="menu-title">Sponsors</div>
<a href="addSponsor.php">🌱 Nouveau Sponsor</a>
<a href="listSponsor.php" class="active">📜 Liste Sponsor</a>

        <div class="menu-title">Dons & Points</div>
        <a href="addDonation.php">🎁 Faire un Don</a>
        <a href="listDonation.php">📜 Historique Dons</a>

        <a href="#" style="margin-top: auto; color: #e74c3c;">🚪 Quitter</a>
    </div>

    <div class="main-content">
        <div class="header-list">
            <h1>Nos Partenaires</h1>
            <a href="addSponsor.php" class="btn-add">+ Ajouter</a>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Secteur</th>
                        <th>Contact</th>
                        <th>Contribution</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($liste as $row) { ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($row['nom']); ?></strong></td>
                        <td><span class='badge'><?php echo htmlspecialchars($row['secteur']); ?></span></td>
                        <td><?php echo htmlspecialchars($row['contact']); ?></td>
                        <td style="color:#27ae60; font-weight:bold;"><?php echo htmlspecialchars($row['contribution']); ?> DT</td>
                        <td>
                            <a href="updateSponsor.php?id=<?php echo $row['id']; ?>" class="action-btn btn-edit">Éditer</a>
                            <a href="deleteSponsor.php?id=<?php echo $row['id']; ?>" class="action-btn btn-delete" onclick="return confirm('Supprimer ?');">X</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>