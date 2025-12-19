<?php
require_once '../../controller/DonationC.php';
require_once '../../controller/SponsorC.php';

$donationC = new DonationC();
$sponsorC = new SponsorC();

// Get all data
$donations = $donationC->listDonations();
$sponsors = $sponsorC->afficherSponsors();

// Initialize statistics
$totalDonations = 0;
$totalValidated = 0;
$totalPending = 0;
$totalPoints = 0;
$totalAmount = 0;
$donationsByType = ['Argent' => 0, 'Materiel' => 0, 'Arbres' => 0];
$monthlyDonations = [];

// Calculate donation statistics
foreach ($donations as $don) {
    $totalDonations++;
    $totalAmount += $don['montant'];
    
    if ($don['etat'] == 'Validé') {
        $totalValidated++;
        $totalPoints += $don['points_gagnes'];
    } else {
        $totalPending++;
    }
    
    // Count by type
    if (isset($donationsByType[$don['type_don']])) {
        $donationsByType[$don['type_don']]++;
    }
    
    // Monthly grouping
    $month = date('M', strtotime($don['date_don']));
    if (!isset($monthlyDonations[$month])) {
        $monthlyDonations[$month] = 0;
    }
    $monthlyDonations[$month] += $don['montant'];
}

// Calculate sponsor statistics
$totalSponsors = 0;
$totalContributions = 0;
$sponsorsBySector = [];

foreach ($sponsors as $sponsor) {
    $totalSponsors++;
    $totalContributions += $sponsor['contribution'];
    
    $sector = $sponsor['secteur'];
    if (!isset($sponsorsBySector[$sector])) {
        $sponsorsBySector[$sector] = 0;
    }
    $sponsorsBySector[$sector]++;
}

// Prepare data for charts
$monthlyLabels = json_encode(array_keys($monthlyDonations));
$monthlyValues = json_encode(array_values($monthlyDonations));
$typeLabels = json_encode(array_keys($donationsByType));
$typeValues = json_encode(array_values($donationsByType));
$sectorLabels = json_encode(array_keys($sponsorsBySector));
$sectorValues = json_encode(array_values($sponsorsBySector));
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Zitouna Quest</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary: #27ae60;
            --dark: #2c3e50;
            --bg: #f4f7f6;
            --orange: #f39c12;
            --blue: #3498db;
            --red: #e74c3c;
            --purple: #9b59b6;
        }
        
        * { box-sizing: border-box; margin: 0; padding: 0; }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg);
            display: flex;
            height: 100vh;
            overflow: hidden;
        }
        
        /* SIDEBAR */
        .sidebar {
            width: 260px;
            background-color: var(--dark);
            color: white;
            padding: 20px;
            display: flex;
            flex-direction: column;
            height: 100vh;
            flex-shrink: 0;
        }
        
        .logo-section {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #34495e;
        }
        
        .logo-section img {
            width: 80px;
            margin-bottom: 10px;
            border-radius: 50%;
        }
        
        .logo-section h2 {
            font-size: 20px;
            font-weight: 700;
            color: #ecf0f1;
        }
        
        .menu-title {
            font-size: 0.75em;
            text-transform: uppercase;
            color: #7f8c8d;
            margin-top: 20px;
            margin-bottom: 10px;
            font-weight: 600;
            padding-left: 10px;
            letter-spacing: 1px;
        }
        
        .sidebar a {
            text-decoration: none;
            color: #bdc3c7;
            padding: 15px;
            margin-bottom: 5px;
            border-radius: 8px;
            transition: all 0.3s;
            display: block;
            font-weight: 500;
        }
        
        .sidebar a:hover,
        .sidebar a.active {
            background-color: var(--primary);
            color: white;
            padding-left: 20px;
            transform: translateX(5px);
        }
        
        /* MAIN CONTENT */
        .main-content {
            flex-grow: 1;
            padding: 30px;
            overflow-y: auto;
            background: linear-gradient(135deg, #f4f7f6 0%, #e8f5e9 100%);
        }
        
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .dashboard-header h1 {
            color: var(--dark);
            font-size: 32px;
            font-weight: 700;
        }
        
        .date-info {
            background: white;
            padding: 12px 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            color: #555;
            font-weight: 500;
        }
        
        /* KPI CARDS */
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .kpi-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: transform 0.3s, box-shadow 0.3s;
            position: relative;
            overflow: hidden;
        }
        
        .kpi-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }
        
        .kpi-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: var(--primary);
        }
        
        .kpi-card.orange::before { background: var(--orange); }
        .kpi-card.blue::before { background: var(--blue); }
        .kpi-card.red::before { background: var(--red); }
        .kpi-card.purple::before { background: var(--purple); }
        
        .kpi-content h3 {
            font-size: 14px;
            color: #7f8c8d;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
        }
        
        .kpi-content .value {
            font-size: 32px;
            font-weight: 700;
            color: var(--dark);
        }
        
        .kpi-content .subtext {
            font-size: 12px;
            color: #95a5a6;
            margin-top: 5px;
        }
        
        .kpi-icon {
            font-size: 48px;
            opacity: 0.15;
        }
        
        /* CHARTS SECTION */
        .charts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }
        
        .chart-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }
        
        .chart-card h3 {
            color: var(--dark);
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        
        .chart-card h3::before {
            content: '';
            width: 4px;
            height: 20px;
            background: var(--primary);
            margin-right: 12px;
            border-radius: 2px;
        }
        
        .chart-container {
            position: relative;
            height: 300px;
        }
        
        /* RECENT ACTIVITY */
        .activity-section {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }
        
        .activity-section h3 {
            color: var(--dark);
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        
        .activity-item {
            padding: 15px;
            border-left: 3px solid var(--primary);
            margin-bottom: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .activity-item:hover {
            background: #e8f5e9;
            transform: translateX(5px);
        }
        
        .activity-item strong {
            color: var(--dark);
            font-weight: 600;
        }
        
        .activity-item .date {
            color: #7f8c8d;
            font-size: 12px;
            margin-top: 5px;
            display: block;
        }
        
        /* STATS BADGES */
        .stat-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-left: 10px;
        }
        
        .stat-badge.success {
            background: #d5f5e3;
            color: #1e8449;
        }
        
        .stat-badge.warning {
            background: #fef5e7;
            color: #d68910;
        }
        
        /* RESPONSIVE */
        @media (max-width: 768px) {
            body { flex-direction: column; }
            .sidebar { width: 100%; height: auto; }
            .main-content { padding: 20px; }
            .kpi-grid { grid-template-columns: 1fr; }
            .charts-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="logo-section">
            <img src="logo.png" alt="Logo Zitouna Quest">
            <h2>Zitouna Quest</h2>
        </div>

        <a href="dashboard.php" class="active">📊 Dashboard</a>
        <hr style="border-color: #444; margin: 15px 0;">

        <div class="menu-title">Sponsors</div>
        <a href="addSponsor.php">🌱 Nouveau Sponsor</a>
        <a href="listSponsor.php">📜 Liste Sponsors</a>

        <div class="menu-title">Dons & Points</div>
        <a href="addDonation.php">🎁 Faire un Don</a>
        <a href="listDonation.php">📜 Historique Dons</a>

        <a href="../front/index.php" style="margin-top: auto; color: #e74c3c;">🚪 Quitter</a>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <div class="dashboard-header">
            <h1>📊 Tableau de Bord</h1>
            <div class="date-info">
                📅 <?php echo date('d/m/Y'); ?>
            </div>
        </div>

        <!-- KPI CARDS -->
        <div class="kpi-grid">
            <div class="kpi-card">
                <div class="kpi-content">
                    <h3>Total Dons</h3>
                    <div class="value"><?php echo $totalDonations; ?></div>
                    <div class="subtext">
                        <span class="stat-badge success"><?php echo $totalValidated; ?> validés</span>
                        <span class="stat-badge warning"><?php echo $totalPending; ?> en attente</span>
                    </div>
                </div>
                <div class="kpi-icon">🎁</div>
            </div>

            <div class="kpi-card orange">
                <div class="kpi-content">
                    <h3>Montant Total</h3>
                    <div class="value"><?php echo number_format($totalAmount, 0, ',', ' '); ?></div>
                    <div class="subtext">Dinars Tunisiens</div>
                </div>
                <div class="kpi-icon">💰</div>
            </div>

            <div class="kpi-card blue">
                <div class="kpi-content">
                    <h3>Points Attribués</h3>
                    <div class="value"><?php echo $totalPoints; ?></div>
                    <div class="subtext">Points de fidélité</div>
                </div>
                <div class="kpi-icon">⭐</div>
            </div>

            <div class="kpi-card purple">
                <div class="kpi-content">
                    <h3>Sponsors</h3>
                    <div class="value"><?php echo $totalSponsors; ?></div>
                    <div class="subtext"><?php echo number_format($totalContributions, 0, ',', ' '); ?> DT contribués</div>
                </div>
                <div class="kpi-icon">🤝</div>
            </div>
        </div>

        <!-- CHARTS -->
        <div class="charts-grid">
            <div class="chart-card">
                <h3>📈 Évolution Mensuelle des Dons</h3>
                <div class="chart-container">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <h3>🎯 Dons par Type</h3>
                <div class="chart-container">
                    <canvas id="typeChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <h3>🏢 Sponsors par Secteur</h3>
                <div class="chart-container">
                    <canvas id="sectorChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <h3>✅ Statut des Dons</h3>
                <div class="chart-container">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>

        <!-- RECENT ACTIVITY -->
        <div class="activity-section">
            <h3>🕒 Activité Récente</h3>
            <?php
            $count = 0;
            foreach ($donations as $don) {
                if ($count >= 5) break;
                echo "<div class='activity-item'>";
                echo "<strong>{$don['nom_donateur']}</strong> a fait un don de <strong>{$don['montant']} DT</strong> ({$don['type_don']})";
                echo "<span class='date'>📅 {$don['date_don']}</span>";
                echo "</div>";
                $count++;
            }
            ?>
        </div>
    </div>

    <script>
        // Monthly Donations Chart
        const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
        new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: <?php echo $monthlyLabels; ?>,
                datasets: [{
                    label: 'Montant (DT)',
                    data: <?php echo $monthlyValues; ?>,
                    borderColor: '#27ae60',
                    backgroundColor: 'rgba(39, 174, 96, 0.1)',
                    tension: 0.4,
                    fill: true,
                    borderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                }
            }
        });

        // Donation Type Chart
        const typeCtx = document.getElementById('typeChart').getContext('2d');
        new Chart(typeCtx, {
            type: 'doughnut',
            data: {
                labels: <?php echo $typeLabels; ?>,
                datasets: [{
                    data: <?php echo $typeValues; ?>,
                    backgroundColor: ['#3498db', '#f39c12', '#27ae60'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });

        // Sector Chart
        const sectorCtx = document.getElementById('sectorChart').getContext('2d');
        new Chart(sectorCtx, {
            type: 'bar',
            data: {
                labels: <?php echo $sectorLabels; ?>,
                datasets: [{
                    label: 'Nombre',
                    data: <?php echo $sectorValues; ?>,
                    backgroundColor: '#9b59b6',
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                }
            }
        });

        // Status Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'pie',
            data: {
                labels: ['Validés', 'En attente'],
                datasets: [{
                    data: [<?php echo $totalValidated; ?>, <?php echo $totalPending; ?>],
                    backgroundColor: ['#27ae60', '#e67e22'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    </script>

</body>
</html>