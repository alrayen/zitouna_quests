<?php
session_start();
require_once __DIR__ . '/../../../../config.php';
$pdo = config::getConnexion();

$session_id = isset($_GET['session']) ? (int)$_GET['session'] : 0;

if ($session_id === 0) {
    header("Location: quizzes.php");
    exit;
}

// 1. Fetch Players Ordered by Score (Highest First)
try {
    $sql = "SELECT u.nom as username, u.photo, sp.score_total 
            FROM session_players sp 
            JOIN user u ON sp.user_id = u.id_user 
            WHERE sp.session_id = ? 
            ORDER BY sp.score_total DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$session_id]);
    $players = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    die("Erreur: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Résultats - Zitouna Quest</title>
    <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/plugins/fontawesome-pro-icons.css">

    <style>
        /* Shared Background Animation */
        @keyframes moveGradient { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
        body.rt_bg-secondary {
            background: linear-gradient(135deg, #005248, #00c49f, #00796b);
            background-size: 400% 400%;
            animation: moveGradient 20s ease infinite;
            min-height: 100vh;
            color: #fff;
        }
        .result-container {
            max-width: 600px;
            margin: 80px auto;
            padding: 40px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            text-align: center;
        }
        .rank-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(255, 255, 255, 0.15);
            margin-bottom: 15px;
            padding: 15px 20px;
            border-radius: 12px;
            font-size: 1.2rem;
            font-weight: 600;
        }
        
        /* Trophy Colors */
        .rank-1 { background: linear-gradient(90deg, rgba(255,215,0,0.2), rgba(255,215,0,0.5)); border: 2px solid #FFD700; transform: scale(1.05); }
        .rank-2 { background: linear-gradient(90deg, rgba(192,192,192,0.2), rgba(192,192,192,0.5)); border: 2px solid #C0C0C0; }
        .rank-3 { background: linear-gradient(90deg, rgba(205,127,50,0.2), rgba(205,127,50,0.5)); border: 2px solid #CD7F32; }

        .medal-icon { margin-right: 15px; font-size: 1.5rem; }
        .score-badge { background: #fff; color: #333; padding: 2px 10px; border-radius: 20px; font-size: 0.9rem; }
    </style>
</head>

<body class="rt_bg-secondary">

    <div class="result-container">
        <h1 class="mb-2">🎉 Fin de la Partie ! 🎉</h1>
        <p class="mb-5">Voici le classement final</p>

        <div id="leaderboard">
            <?php 
            $rank = 1;
            foreach($players as $player): 
                $rankClass = '';
                $icon = '';
                
                if($rank == 1) { $rankClass = 'rank-1'; $icon = '🥇'; }
                elseif($rank == 2) { $rankClass = 'rank-2'; $icon = '🥈'; }
                elseif($rank == 3) { $rankClass = 'rank-3'; $icon = '🥉'; }
                else { $icon = '<span style="font-size:1rem; opacity:0.7;">#' . $rank . '</span>'; }
            ?>
            
            <div class="rank-item <?php echo $rankClass; ?>">
                <div style="display:flex; align-items:center;">
                    <span class="medal-icon"><?php echo $icon; ?></span>
                    <span><?php echo htmlspecialchars($player['username']); ?></span>
                </div>
                <span class="score-badge"><?php echo $player['score_total']; ?> pts</span>
            </div>

            <?php 
            $rank++;
            endforeach; 
            ?>
        </div>

        <a href="quizzes.php" class="rts-btn btn-primary mt-4">Retour au Menu</a>
    </div>

</body>
</html>