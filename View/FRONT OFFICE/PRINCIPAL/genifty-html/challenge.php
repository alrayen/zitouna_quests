<?php
require_once __DIR__ . '/../../../../config.php';
require_once __DIR__ . '/../../../../Model/challenge.php';
require_once __DIR__ . '/../../../../Controller/challenge-controller.php';

$challengeController = new ChallengeController();
$allChallenges = $challengeController->listChallenges();

$userId = $_SESSION['user_id'] ?? null; 
$pdo = config::getConnexion();

if ($userId) {
    $userStmt = $pdo->prepare("SELECT xp, level FROM user WHERE id_user = ?");
    $userStmt->execute([$userId]);
    $userData = $userStmt->fetch(PDO::FETCH_ASSOC) ?: ['xp' => 0, 'level' => 1];

    $progStmt = $pdo->prepare("SELECT challenge_id FROM user_challenge_progress WHERE user_id = ? AND status = 'completed'");
    $progStmt->execute([$userId]);
    $completedIds = $progStmt->fetchAll(PDO::FETCH_COLUMN);

    $startStmt = $pdo->prepare("SELECT challenge_id FROM user_challenge_progress WHERE user_id = ? AND status = 'started'");
    $startStmt->execute([$userId]);
    $startedIds = $startStmt->fetchAll(PDO::FETCH_COLUMN);

    $badgeStmt = $pdo->prepare("
        SELECT b.* FROM badges b 
        JOIN user_badges ub ON b.id = ub.badge_id 
        WHERE ub.user_id = ?
    ");
    $badgeStmt->execute([$userId]);
    $myBadges = $badgeStmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $userData = ['xp' => 0, 'level' => 1];
    $completedIds = [];
    $startedIds = [];
    $myBadges = [];
}

$xpForNextLevel = $userData['level'] * 100;
$currentLevelBaseXp = ($userData['level'] - 1) * 100;
$xpInCurrentLevel = $userData['xp'] - $currentLevelBaseXp;
$progressPercent = min(100, max(0, ($xpInCurrentLevel / 100) * 100)); // Clamp between 0-100

$uniqueCategories = [];
$uniqueDifficulties = [];
foreach ($allChallenges as $challenge) {
    $uniqueCategories[$challenge->getCategorie()] = true; 
    $uniqueDifficulties[$challenge->getDifficulty()] = true; 
}
$uniqueCategories = array_keys($uniqueCategories);
$uniqueDifficulties = array_keys($uniqueDifficulties);

$difficultyOrder = ['Easy', 'Medium', 'Hard', 'Expert'];
usort($uniqueDifficulties, function($a, $b) use ($difficultyOrder) {
    $valA = ucfirst(strtolower(trim($a)));
    $valB = ucfirst(strtolower(trim($b)));
    $posA = array_search($valA, $difficultyOrder);
    $posB = array_search($valB, $difficultyOrder);
    if ($posA === false) return 1;
    if ($posB === false) return -1;
    return $posA - $posB;
});

$shouldShowLevelUp = false;
if (isset($_SESSION['show_level_up']) && $_SESSION['show_level_up'] === true) {
    $shouldShowLevelUp = true;
    unset($_SESSION['show_level_up']); 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Zitouna Quests - Explore Challenges</title>
    <meta name="robots" content="noindex">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="theme-style-mode" content="1"> 
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/fab-icon.png">
    <link rel="stylesheet" href="assets/css/plugins/gordita.css">
    <link rel="stylesheet" href="assets/css/plugins/fontawesome-pro-icons.css">
    <link rel="stylesheet" href="assets/css/vendor/swiper.css">
    <link rel="stylesheet" href="assets/css/plugins/unicons.css">
    <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        html { overflow-y: scroll; }
        body.rt_bg-secondary {
            background: linear-gradient(135deg, #14b8a6, #14b8a6, #3ddf43ff, #81c784);
            background-size: 400% 400%;
            animation: moveGradient 25s ease infinite;
            overflow-x: hidden;
            min-height: 100vh;
        }
        .bg-animation { position: fixed; top: 0; left: 0; width: 100%; height: 100vh; z-index: -1; overflow: hidden; }
        .bg-animation .blob { position: absolute; border-radius: 50%; filter: blur(80px); opacity: 0.4; animation: float 25s ease-in-out infinite alternate; }
        .bg-animation .blob1 { width: 400px; height: 400px; background: rgba(144, 238, 144, 0.5); top: -50px; left: -100px; animation-duration: 22s; }
        .bg-animation .blob2 { width: 300px; height: 300px; background: rgba(0, 150, 136, 0.4); bottom: -80px; right: -80px; animation-duration: 28s; animation-delay: -5s; }
        @keyframes float { 0% { transform: translateY(0) translateX(0); } 50% { transform: translateY(-20px) translateX(20px); } 100% { transform: translateY(0) translateX(0); } }
        @keyframes moveGradient { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px) scale(0.95); } to { opacity: 1; transform: translateY(0) scale(1); } }

        .gamification-bar {
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            padding: 20px 0;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            position: sticky; 
            top: 180px; 
            z-index: 900;
            margin-bottom: 40px;
        }
        .level-circle {
            width: 50px; height: 50px;
            background: linear-gradient(135deg, #ffd700, #ff8c00);
            border-radius: 50%;
            display: flex; justify-content: center; align-items: center;
            font-weight: 800; font-size: 1.2rem; color: #fff;
            box-shadow: 0 0 15px rgba(255, 215, 0, 0.4);
            border: 2px solid #fff;
        }
        .xp-bar-bg { width: 100%; height: 8px; background: rgba(255,255,255,0.2); border-radius: 4px; overflow: hidden; margin-top: 5px; }
        .xp-bar-fill { height: 100%; background: #00e676; width: <?php echo $progressPercent; ?>%; transition: width 1s ease; }

        .badge-card {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 15px;
            padding: 15px;
            text-align: center;
            width: 100px;
            transition: transform 0.3s;
            display: inline-block;
            margin-right: 10px;
            margin-bottom: 10px;
        }
        .badge-card:hover { transform: scale(1.1); background: rgba(255,255,255,0.2); }
        .badge-icon { font-size: 1.8rem; color: #ffd700; margin-bottom: 5px; }
        .badge-name { font-size: 0.7rem; font-weight: 700; color: #fff; line-height: 1.2; }

        .quiz-card {
            background: rgba(20, 60, 20, 0.35); backdrop-filter: blur(15px);
            border: 1px solid rgba(100, 255, 100, 0.2); border-radius: 24px; 
            padding: 25px; cursor: pointer; transition: all 0.3s; position: relative;
            height: 100%; display: flex; flex-direction: column;
            animation: fadeIn 0.6s ease-out forwards;
            text-decoration: none; color: #fff;
        }
        .quiz-card:hover { 
            transform: translateY(-10px) scale(1.02); 
            background: rgba(20, 60, 20, 0.5); 
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3), 0 0 30px rgba(100, 255, 100, 0.5);
            border-color: rgba(100, 255, 100, 0.6);
        }
        .quiz-card.completed { border-color: #00e676; opacity: 0.8; }
        .quiz-card.started { border-color: #29b6f6; }
        .quiz-card.recommended {
            border: 2px solid #ffd700;
            box-shadow: 0 0 25px rgba(255, 215, 0, 0.3);
            transform: scale(1.02);
        }
        .rec-badge {
            position: absolute; top: -12px; left: 50%; transform: translateX(-50%);
            background: #ffd700; color: #000;
            padding: 5px 15px; border-radius: 20px;
            font-weight: 800; font-size: 0.75rem;
            box-shadow: 0 5px 10px rgba(0,0,0,0.2);
            z-index: 10;
        }

        .quiz-filter-controls { margin-bottom: 40px; text-align: center; }
        .quiz-filter-group { margin: 0; padding: 0; list-style: none; display: inline-block; margin-bottom: 15px; }
        .quiz-filter-group li { display: inline-block; margin: 0 5px; }
        .filter-btn { background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.25); color: #fff; padding: 10px 20px; border-radius: 20px; cursor: pointer; transition: all 0.3s ease; font-weight: 600; backdrop-filter: blur(5px); }
        .filter-btn:hover { background: rgba(255, 255, 255, 0.25); border-color: rgba(255, 255, 255, 0.5); transform: translateY(-2px); }
        .filter-btn.is-active { background: #fff; color: #1b5e20; box-shadow: 0 5px 15px rgba(76, 175, 80, 0.5); border-color: #fff; }
        
        .quiz-card-header { display: flex; justify-content: space-between; align-items: center; font-size: 0.9rem; font-weight: 600; margin-bottom: 20px; }
        .quiz-card-header .categorie { background: linear-gradient(45deg, #43a047, #66bb6a); color: white; padding: 6px 15px; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.5px; font-size: 0.75rem; font-weight: 700; box-shadow: 0 2px 10px rgba(67, 160, 71, 0.3); }
        .quiz-card-bottom-tags { display: flex; flex-direction: column; align-items: flex-end; gap: 8px; }
        .niveau { font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; font-size: 0.75rem; }
        .niveau.easy { color: #69f0ae; }
        .niveau.medium { color: #ffd54f; }
        .niveau.hard { color: #ff8a80; }
        .niveau.expert { color: #ea80fc; }
        .quiz-card-body { flex-grow: 1; }
        .quiz-card-body .titre { font-size: 1.5rem; font-weight: 700; margin: 0 0 10px 0; line-height: 1.3; color: #fff; }
        .quiz-card-body .description { font-size: 0.95rem; color: #e0e0e0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; margin-bottom: 20px; opacity: 0.9; }
        .challenge-meta { display: flex; flex-wrap: wrap; gap: 15px; margin-bottom: 20px; font-size: 0.85rem; color: #c8e6c9; }
        .challenge-meta div { display: flex; align-items: center; gap: 6px; background: rgba(0,0,0,0.2); padding: 4px 10px; border-radius: 10px;}
        .challenge-meta i { color: #66bb6a; }
        .quiz-card-footer { display: flex; justify-content: space-between; align-items: flex-end; margin-top: auto; border-top: 1px solid rgba(255, 255, 255, 0.1); padding-top: 20px; }
        .xp-reward { color: #ffd700; font-weight: 800; font-size: 0.9rem; display: flex; align-items: center; gap: 5px; margin-bottom: 5px;}
        .start-btn { background: linear-gradient(45deg, #43a047, #81c784); color: white; padding: 8px 20px; border-radius: 25px; font-weight: 700; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3); border: none; font-size: 0.85rem; }
        .quiz-card:hover .start-btn { background: linear-gradient(45deg, #66bb6a, #a5d6a7); color: #0a3d0c; box-shadow: 0 5px 20px rgba(102, 187, 106, 0.5); transform: translateY(-2px); }

        .page-title-area { text-align: center; margin-bottom: 50px; }
        .page-title-area .title { font-size: 3.5rem; color: #fff; font-weight: 800; text-shadow: 0 4px 15px rgba(0, 0, 0, 0.3); }
        .page-title-area .sub { display: block; font-size: 1.1rem; color: #69f0ae; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 10px;}
        .page-title-area .disc { font-size: 1.3rem; color: #e8f5e9; opacity: 0.95; }
        
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); backdrop-filter: blur(8px); z-index: 9999; display: none; justify-content: center; align-items: center; padding: 20px; opacity: 0; transition: opacity 0.3s ease-in-out; }
        .modal-overlay.active { opacity: 1; }
        .detail-modal-card { width: 100%; max-width: 800px; background: rgba(20, 60, 20, 0.85); backdrop-filter: blur(20px); border: 1px solid rgba(100, 255, 100, 0.3); border-radius: 30px; padding: 40px; box-shadow: 0 25px 50px rgba(0, 0, 0, 0.4); color: #fff; position: relative; transform: scale(0.9); opacity: 0; transition: transform 0.4s, opacity 0.4s; max-height: 90vh; overflow-y: auto; z-index: 1; }
        .modal-overlay.active .detail-modal-card { transform: scale(1); opacity: 1; }
        .close-modal-btn { position: absolute; top: 20px; right: 20px; background: rgba(255,255,255,0.1); border: none; color: #fff; width: 40px; height: 40px; border-radius: 50%; cursor: pointer; font-size: 1.2rem; display: flex; justify-content: center; align-items: center; transition: all 0.2s; z-index: 10; }
        .close-modal-btn:hover { background: rgba(255,0,0,0.3); transform: rotate(90deg); }
        .modal-header-content { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 30px; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 20px; }
        .modal-title { font-size: 2.5rem; font-weight: 800; margin: 0; background: linear-gradient(to right, #fff, #b9f6ca); -webkit-background-clip: text; -webkit-text-fill-color: transparent; line-height: 1.2;}
        .info-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-bottom: 25px; }
        .info-box { background: rgba(0,0,0,0.3); padding: 15px; border-radius: 15px; text-align: center; border: 1px solid rgba(255,255,255,0.05); }
        .info-label { display: block; font-size: 0.7rem; color: #a5d6a7; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px; }
        .info-value { font-size: 1.1rem; font-weight: 700; color: #fff; }
        .description-box { background: rgba(0, 0, 0, 0.3); padding: 25px; border-radius: 20px; line-height: 1.8; color: #ffffff; font-size: 1rem; border-left: 4px solid #69f0ae; margin-bottom: 30px; }
        .btn-accept-lg { background: linear-gradient(45deg, #43a047, #00e676); border: none; color: #003d1a; width: 100%; padding: 15px; border-radius: 50px; font-weight: 800; font-size: 1.2rem; box-shadow: 0 0 20px rgba(0, 230, 118, 0.4); cursor: pointer; transition: all 0.3s; text-transform: uppercase; }
        .btn-accept-lg:hover { transform: scale(1.02); box-shadow: 0 0 40px rgba(0, 230, 118, 0.6); }
        .confirm-box { background: #1a1a1a; border: 2px solid #69f0ae; padding: 30px; border-radius: 20px; text-align: center; max-width: 400px; }
        .confirm-actions { display: flex; gap: 10px; justify-content: center; margin-top: 20px; }
        .btn-yes { background: #69f0ae; color: #000; padding: 10px 30px; border-radius: 10px; font-weight: 700; border: none; cursor: pointer; text-decoration: none; }
        .btn-no { background: transparent; color: #ff8a80; padding: 10px 30px; border-radius: 10px; font-weight: 700; border: 1px solid #ff8a80; cursor: pointer; }
        
        .rts-header-area { position: fixed !important; top: 0; left: 0; width: 100%; z-index: 999; background: rgba(20, 60, 20, 0.65) !important; backdrop-filter: blur(12px); box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1); border-bottom: 1px solid rgba(255, 255, 255, 0.1); transition: transform 0.4s ease-in-out; padding-top: 10px; padding-bottom: 10px; }
        .header-hidden { transform: translateY(-100%); }

        
        .levelup-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.9); 
            z-index: 999999; 
            display: none; justify-content: center; align-items: center;
            opacity: 0; transition: opacity 0.5s ease;
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
        }
        .levelup-overlay.active { display: flex; opacity: 1; }
        
        .levelup-card {
            position: relative;
            width: 90%; max-width: 550px;
            
            background: rgba(10, 10, 10, 0.65); 
            backdrop-filter: blur(25px);        
            -webkit-backdrop-filter: blur(25px); 
            
            border: 2px solid rgba(255, 215, 0, 0.5); 
            border-radius: 40px;
            padding: 60px 40px;
            text-align: center;
            
            box-shadow: 0 0 80px rgba(255, 215, 0, 0.1), inset 0 0 20px rgba(255, 215, 0, 0.05);
            transform: scale(0.5);
            transition: transform 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            overflow: hidden;
            color: #fff;
        }
        .levelup-overlay.active .levelup-card { transform: scale(1); }

        .levelup-shine {
            position: absolute; top: 0; left: -100%;
            width: 50%; height: 100%;
            background: linear-gradient(to right, transparent, rgba(255,255,255,0.1), transparent);
            transform: skewX(-25deg);
            animation: shine 6s infinite;
        }
        @keyframes shine { 0% { left: -100%; } 20% { left: 200%; } 100% { left: 200%; } }

        .levelup-header {
            font-size: 3.8rem; font-weight: 900;
            
            background: linear-gradient(to bottom, #fff, #ffd700, #ff8c00);
            -webkit-background-clip: text; 
            -webkit-text-fill-color: transparent;
            
            margin-bottom: 10px;
            text-transform: uppercase;
            filter: drop-shadow(0 4px 0px rgba(0,0,0,0.5));
            animation: bounceIn 1s ease;
            line-height: 1.1;
        }
        
        .levelup-badge-container {
            margin: 20px auto;
            position: relative;
            animation: float 4s ease-in-out infinite;
        }
        .levelup-icon {
            font-size: 7rem; 
            color: #ffd700;
            filter: drop-shadow(0 0 25px rgba(255, 215, 0, 0.6));
        }
        
        .levelup-text {
            font-size: 1.6rem; color: #fff; margin-bottom: 10px; font-weight: 700;
            text-shadow: 0 2px 10px rgba(0,0,0,0.5);
        }
        .levelup-sub {
            font-size: 1.1rem; color: #e0e0e0; margin-bottom: 35px; opacity: 0.9;
        }
        
        .levelup-btn {
            background: linear-gradient(90deg, #ff8c00, #ffd700);
            border: none; color: #000;
            padding: 15px 50px; font-size: 1.2rem; font-weight: 800;
            border-radius: 50px; cursor: pointer;
            box-shadow: 0 0 20px rgba(255, 215, 0, 0.4);
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .levelup-btn:hover { transform: scale(1.05); box-shadow: 0 0 40px rgba(255, 215, 0, 0.7); color: #000; }

        @keyframes bounceIn { 0% { opacity: 0; transform: translateY(-50px); } 60% { opacity: 1; transform: translateY(10px); } 100% { transform: translateY(0); } }
    </style>
</head>

<body class="rt_bg-secondary">

    <div class="bg-animation">
        <div class="blob blob1"></div>
        <div class="blob blob2"></div>
    </div>

    <div id="mainHeader" class="rts-header-area header-inner-one">
        <div class="container-header">
            <div class="row align-items-center ptb_sm--20 padding-controler-header">
                <div class="col-xl-2 col-lg-4 col-md-4 col-sm-12 ">
                    <div class="header-left">
                        <a href="index.html" class="logo">
                            <img src="assets/images/logo/logo3.png" alt="Logo">
                        </a>
                    </div>
                </div>
                <div class="col-xl-5 d-xl-block d-none">
                    <div class="main-menu-wrapepr">
                        <nav class="mainmenu-nav d-none d-xl-block">
                            <ul class="main-menu">
                                <li class="single-items off-arrow"><a class="navmain" href="index.html">Home</a></li>
                                <li class="single-items off-arrow"><a class="navmain" href="challenges.php">Challenges</a></li>
                                <li class="single-items off-arrow"><a class="navmain" href="quizzes.php">Quiz</a></li>
                                <li class="single-items off-arrow"><a class="navmain" href="#">Forum</a></li>
                                <li class="single-items off-arrow"><a class="navmain" href="contact.html">Contact</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
                 <div class="col-xl-5 col-lg-8 col-md-8 col-sm-12 justify-content-sm-center d-xsm-flex">
                    <div class="header-right">
                         <a id="connect-wallet" href="login.html" class="rts-btn btn-primary">Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="gamification-bar">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="level-circle"><?php echo $userData['level']; ?></div>
                </div>
                <div class="col">
                    <div style="display:flex; justify-content:space-between; font-size:0.85rem; font-weight:700;">
                        <span>Level <?php echo $userData['level']; ?> Adventurer</span>
                        <span><?php echo floor($xpInCurrentLevel); ?> / 100 XP</span>
                    </div>
                    <div class="xp-bar-bg">
                        <div class="xp-bar-fill"></div>
                    </div>
                </div>
                <div class="col-auto text-end">
                    <div style="font-size:0.8rem; opacity:0.8;">Total Score</div>
                    <div style="font-weight:800; font-size:1.1rem; color:#00e676;"><?php echo $userData['xp']; ?> XP</div>
                </div>
            </div>
        </div>
    </div>

    <div class="rts-explore-area rts-section-gap" style="padding-top: 280px; position: relative; z-index: 2;">
        <div class="container">

            <div class="row mb-5">
                <div class="col-12">
                    <h4 style="color:#fff; font-weight:700; margin-bottom:20px;">Your Badges</h4>
                    <div style="display:flex; gap:15px; flex-wrap:wrap;">
                        <?php if (empty($myBadges)): ?>
                            <p class="disc">Complete challenges to earn badges!</p>
                        <?php else: ?>
                            <?php foreach ($myBadges as $badge): ?>
                                <div class="badge-card" title="<?php echo htmlspecialchars($badge['description']); ?>">
                                    <div class="badge-icon"><i class="<?php echo htmlspecialchars($badge['icon']); ?>"></i></div>
                                    <div class="badge-name"><?php echo htmlspecialchars($badge['name']); ?></div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="page-title-area">
                        <span class="sub">Zitouna Quests</span>
                        <h3 class="title">Real-World Challenges</h3>
                        <p class="disc">Apply your skills, solve problems, and level up!</p>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center mb-5">
                <div class="col-lg-10">
                    <div class="ai-banner" style="background: linear-gradient(135deg, #667eea, #764ba2); padding: 40px; border-radius: 24px; color: white; text-align: center; position: relative; overflow: hidden; box-shadow: 0 20px 40px rgba(118, 75, 162, 0.4);">
                        
                        <div style="position: absolute; top: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
                        <div style="position: absolute; bottom: -50px; right: -20px; width: 200px; height: 200px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
                        <div style="position: absolute; top: 20px; right: 40px; font-size: 2rem; opacity: 0.2; animation: float 6s infinite;"><i class="fas fa-code"></i></div>

                        <span style="background: rgba(255,255,255,0.2); padding: 5px 15px; border-radius: 20px; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 15px; display: inline-block;">
                            <i class="fas fa-sparkles"></i> New Feature
                        </span>
                        
                        <h2 style="font-weight: 800; margin-bottom: 15px; color: #fff; text-shadow: 0 4px 10px rgba(0,0,0,0.2);">
                            Need a Custom Challenge?
                        </h2>
                        <p style="opacity: 0.95; margin-bottom: 30px; color: #fff; font-size: 1.1rem; max-width: 600px; margin-left: auto; margin-right: auto;">
                            Our AI analyzes your skills and generates a unique mission tailored just for you.
                        </p>
                        
                        <button id="btnGenerateAI" onclick="generateAiChallenge()" style="background: white; color: #764ba2; border: none; padding: 15px 40px; border-radius: 50px; font-weight: 800; cursor: pointer; transition: all 0.3s; font-size: 1rem; box-shadow: 0 10px 20px rgba(0,0,0,0.2); display: inline-flex; align-items: center; gap: 10px;">
                            <i class="fas fa-magic"></i> Generate My Challenge
                        </button>
                        
                        <div id="aiLoading" style="display:none; margin-top: 20px;">
                            <div class="spinner-border text-light" role="status" style="width: 2rem; height: 2rem;">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <div style="margin-top: 10px; font-size: 0.95rem; font-weight: 600;">Creating your mission...</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="quiz-filter-controls">
                        <ul id="category-filters" class="quiz-filter-group">
                            <li><button class="filter-btn is-active" data-filter="*">All Categories</button></li>
                            <?php foreach ($uniqueCategories as $category): ?>
                                <?php $categorySelector = strtolower(str_replace(' ', '-', preg_replace("/[^A-Za-z0-9 ]/", '', $category))); ?>
                                <li><button class="filter-btn" data-filter=".<?php echo $categorySelector; ?>">
                                    <?php echo htmlspecialchars($category); ?>
                                </button></li>
                            <?php endforeach; ?>
                        </ul>
                        <br>
                        <ul id="difficulty-filters" class="quiz-filter-group">
                            <li><button class="filter-btn is-active" data-filter="*">All Difficulties</button></li>
                            <?php foreach ($uniqueDifficulties as $difficulty): ?>
                                <?php $difficultySelector = strtolower(htmlspecialchars($difficulty)); ?>
                                <li><button class="filter-btn" data-filter=".<?php echo $difficultySelector; ?>">
                                    <?php echo htmlspecialchars($difficulty); ?>
                                </button></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row g-5 mt--20" id="quiz-grid">
                <?php if (empty($allChallenges)): ?>
                    <div class="col-12">
                        <p class="text-center" style="font-size: 1.2rem; color: #fff;">No challenges are active at the moment. Check back later!</p>
                    </div>
                <?php else: ?>
                    <?php 
                    $recFound = false;
                    foreach ($allChallenges as $challenge): 
                        if (strtolower($challenge->getStatus()) === 'inactive') continue;

                        $difficultyRaw = $challenge->getDifficulty();
                        $difficultyClass = strtolower($difficultyRaw);
                        $categoryFromDB = htmlspecialchars($challenge->getCategorie());
                        $categoryClass = strtolower(str_replace(' ', '-', preg_replace("/[^A-Za-z0-9 ]/", '', $categoryFromDB)));

                        $isCompleted = in_array($challenge->getIdDefi(), $completedIds);
                        $isStarted = in_array($challenge->getIdDefi(), $startedIds);
                        $statusClass = '';
                        if ($isCompleted) $statusClass = 'completed';
                        elseif ($isStarted) $statusClass = 'started';

                        $isRecommended = false;
                        if (!$recFound && !$isCompleted && ($challenge->getDifficulty() !== 'Expert' || $userData['level'] > 5)) {
                            $isRecommended = true;
                            $recFound = true;
                        }

                        $jsonChallenge = json_encode([
                            'id' => $challenge->getIdDefi(),
                            'titre' => $challenge->getTitre(),
                            'description' => $challenge->getDescription(),
                            'categorie' => $categoryFromDB,
                            'points' => $challenge->getPoints(),
                            'time' => $challenge->getTime(),
                            'difficulty' => $difficultyRaw,
                            'place' => $challenge->getPlace()
                        ], JSON_HEX_APOS | JSON_HEX_QUOT);
                    ?>
                        <div class="col-lg-4 col-md-6 col-sm-12 quiz-card-wrapper <?php echo $categoryClass; ?> <?php echo $difficultyClass; ?>">
                            <div class="quiz-card <?php echo $statusClass; ?> <?php echo $isRecommended ? 'recommended' : ''; ?>" 
                                 onclick='openDetailModal(<?php echo $jsonChallenge; ?>)'>
                                <?php if($isRecommended): ?>
                                    <div class="rec-badge"><i class="fas fa-star"></i> RECOMMENDED</div>
                                <?php endif; ?>
                                <div class="quiz-card-header">
                                    <span class="categorie"><?php echo $categoryFromDB; ?></span>
                                </div>
                                <div class="quiz-card-body">
                                    <h5 class="titre"><?php echo htmlspecialchars($challenge->getTitre()); ?></h5>
                                    <p class="description"><?php echo htmlspecialchars($challenge->getDescription()); ?></p>
                                    <div class="challenge-meta">
                                        <div><i class="far fa-clock"></i> <?php echo htmlspecialchars($challenge->getTime()); ?> min</div>
                                        <div><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($challenge->getPlace()); ?></div>
                                    </div>
                                </div>
                                <div class="quiz-card-footer">
                                    <span class="xp-reward"><i class="fas fa-bolt"></i> <?php echo $challenge->getPoints(); ?> XP</span>
                                    <div class="quiz-card-bottom-tags">
                                        <span class="niveau <?php echo $difficultyClass; ?>"><?php echo $difficultyRaw; ?></span>
                                        <?php if($isCompleted): ?>
                                            <span style="color:#00e676; font-weight:bold; font-size:0.9rem; display: flex; align-items: center; gap: 8px;">
                                                <i class="fas fa-check-circle"></i> Completed
                                            </span>
                                        <?php elseif($isStarted): ?>
                                            <span style="color:#29b6f6; font-weight:bold; font-size:0.9rem; display: flex; align-items: center; gap: 8px;">
                                                <i class="fas fa-play-circle" style="font-size: 1rem;"></i> Resume
                                            </span>
                                        <?php else: ?>
                                            <button class="start-btn">Details</button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div id="detailModal" class="modal-overlay">
        <div class="detail-modal-card">
            <button class="close-modal-btn" onclick="closeDetailModal()"><i class="fas fa-times"></i></button>
            <div class="modal-header-content">
                <div>
                    <h2 id="modalTitle" class="modal-title">Challenge Title</h2>
                    <div style="margin-top: 10px;">
                        <span id="modalCategory" class="categorie" style="background: linear-gradient(45deg, #43a047, #66bb6a); color: white; padding: 6px 15px; border-radius: 20px; font-size: 0.8rem; margin-right: 10px;">CAT</span>
                        <span id="modalDifficulty" style="font-weight: bold; text-transform: uppercase; font-size: 0.9rem; color: #ccc;">DIFF</span>
                    </div>
                </div>
                <div style="text-align: center;">
                    <i class="fas fa-star fa-2x" style="color: #ffd54f;"></i>
                    <div id="modalPoints" style="font-weight: 800; color: #ffd54f; font-size: 1.2rem; margin-top: 5px;">0 PTS</div>
                </div>
            </div>
            <div class="info-grid">
                <div class="info-box">
                    <span class="info-label"><i class="fas fa-stopwatch"></i> Duration</span>
                    <div id="modalTime" class="info-value">0 min</div>
                </div>
                <div class="info-box">
                    <span class="info-label"><i class="fas fa-map-marker-alt"></i> Location</span>
                    <div id="modalPlace" class="info-value">Online</div>
                </div>
                <div class="info-box">
                    <span class="info-label"><i class="fas fa-calendar-check"></i> Status</span>
                    <div class="info-value" style="color:#69f0ae">Active</div>
                </div>
            </div>
            <div class="description-box">
                <h5 style="color:#fff; font-weight:700; margin-bottom:10px;">Mission Brief</h5>
                <p id="modalDescription" style="margin:0;">Loading description...</p>
            </div>
            <button class="btn-accept-lg" onclick="openConfirmModal()">Accept Challenge <i class="fas fa-rocket"></i></button>
        </div>
    </div>

    <div id="confirmModal" class="modal-overlay" style="z-index: 10000;">
        <div class="confirm-box">
            <div style="font-size: 3rem; color: #69f0ae; margin-bottom: 20px;"><i class="fas fa-question-circle"></i></div>
            <h3 style="color: white; margin-bottom: 10px;">Are you ready?</h3>
            <p style="color: #ccc; margin-bottom: 25px;">Accepting this challenge will unlock the resources.</p>
            <div class="confirm-actions">
                <button onclick="closeConfirmModal()" class="btn-no">Cancel</button>
                <button id="btnProceed" class="btn-yes">Let's Go!</button>
            </div>
        </div>
    </div>

    <div id="loginModal" class="modal-overlay" style="z-index: 10001;">
        <div class="confirm-box">
            <div style="font-size: 3rem; color: #ff8a80; margin-bottom: 20px;"><i class="fas fa-lock"></i></div>
            <h3 style="color: white; margin-bottom: 10px;">Login Required</h3>
            <p style="color: #ccc; margin-bottom: 25px;">You need to be logged in to generate a personalized challenge.</p>
            <div class="confirm-actions">
                <button onclick="closeLoginModal()" class="btn-no">Cancel</button>
                <a href="login.html" class="btn-yes" style="text-decoration:none; display:inline-block;">Connect</a>
            </div>
        </div>
    </div>

    <div id="levelUpModal" class="levelup-overlay">
        <div class="levelup-card">
            <div class="levelup-shine"></div>
            
            <div class="levelup-header">LEVEL UP!</div>
            
            <div class="levelup-badge-container">
                <i class="fas fa-trophy levelup-icon"></i>
            </div>
            
            <div class="levelup-text">You are now Level <?php echo $userData['level']; ?></div>
            <div class="levelup-sub">New challenges unlocked! Keep pushing your limits.</div>
            
            <button class="levelup-btn" onclick="closeLevelUp()">AWESOME!</button>
        </div>
    </div>

    <div class="loadingpage"><div class="spinner"></div></div>
    <div class="mouse-cursor cursor-outer"></div>
    <div class="mouse-cursor cursor-inner"></div>
    <div class="progress-wrap">
        <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
        </svg>
    </div>
    
    <script src="assets/js/vendor/jquery.min.js"></script>
    <script src="assets/js/vendor/jquery-ui.min.js"></script>
    <script src="assets/js/plugins/nice-select.js"></script>
    <script src="assets/js/vendor/waypoint.js"></script>
    <script src="assets/js/vendor/swiper.js"></script>
    <script src="assets/js/vendor/count-down.js"></script>
    <script src="assets/js/vendor/isotop.min.js"></script> 
    <script src="assets/js/vendor/counterup.min.js"></script>
    <script src="assets/js/plugins/sal.min.js"></script>
    <script src="assets/js/plugins/paper-core.js"></script>
    <script src="assets/js/plugins/simplex-nois.js"></script>
    <script src="assets/js/plugins/contact-form.js"></script>
    <script src="assets/js/vendor/imageloded.js"></script>
    <script src="assets/js/vendor/bootstrap.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

    <script>
        const triggerLevelUp = <?php echo $shouldShowLevelUp ? 'true' : 'false'; ?>;

        let currentChallengeId = 0;
        
        async function generateAiChallenge() {
            const btn = document.getElementById('btnGenerateAI');
            const loading = document.getElementById('aiLoading');
            
            btn.style.display = 'none';
            loading.style.display = 'block';
            
            try {
                const response = await fetch('../../../../Controller/generate_challenge_api.php');
                const data = await response.json();
                
                if (data.status === 'success') {
                    window.location.href = 'challenge-resources.php?id=' + data.challenge_id;
                } else {
                    if (data.message === 'Please login first') {
                        openLoginModal();
                    } else {
                        alert("AI Error: " + data.message);
                    }
                    btn.style.display = 'inline-flex';
                    loading.style.display = 'none';
                }
            } catch (e) {
                console.error(e);
                alert("Connection error. Please try again.");
                btn.style.display = 'inline-flex';
                loading.style.display = 'none';
            }
        }

        function openLoginModal() {
            var modal = document.getElementById('loginModal');
            modal.style.display = 'flex';
            void modal.offsetWidth; 
            setTimeout(() => { modal.classList.add('active'); }, 50);
        }

        function closeLoginModal() {
            var modal = document.getElementById('loginModal');
            modal.classList.remove('active');
            setTimeout(() => { modal.style.display = 'none'; }, 350);
        }

        $(window).on('load', function () {
            var $grid = $('#quiz-grid').isotope({
                itemSelector: '.quiz-card-wrapper',
                layoutMode: 'fitRows', 
                transitionDuration: '0.6s',
                hiddenStyle: { opacity: 0, transform: 'scale(0.9)' },
                visibleStyle: { opacity: 1, transform: 'scale(1)' }
            });

            var filters = { category: '*', difficulty: '*' };

            $('.quiz-filter-controls').on('click', '.filter-btn', function () {
                var $this = $(this);
                var $buttonGroup = $this.closest('.quiz-filter-group');
                var filterGroup = $buttonGroup.attr('id');
                
                $buttonGroup.find('.is-active').removeClass('is-active');
                $this.addClass('is-active');
                
                var filterValue = $this.attr('data-filter');
                
                if (filterGroup === 'category-filters') {
                    filters.category = filterValue;
                } else if (filterGroup === 'difficulty-filters') {
                    filters.difficulty = filterValue;
                }
                
                var combinedFilter = '';
                
                if (filters.category === '*' && filters.difficulty === '*') {
                    combinedFilter = '*';
                } else if (filters.category === '*') {
                    combinedFilter = filters.difficulty;
                } else if (filters.difficulty === '*') {
                    combinedFilter = filters.category;
                } else {
                    combinedFilter = filters.category + filters.difficulty;
                }
                
                $grid.isotope({ filter: combinedFilter });
            });

            if (triggerLevelUp) {
                setTimeout(function(){
                    openLevelUp();
                }, 500);
            }
        });

        let lastScrollTop = 0;
        const header = document.getElementById('mainHeader');

        window.addEventListener('scroll', function() {
            let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            if (scrollTop > lastScrollTop && scrollTop > 100) {
                header.classList.add('header-hidden');
            } else {
                header.classList.remove('header-hidden');
            }
            lastScrollTop = scrollTop;
        });

        function openDetailModal(data) {
            document.getElementById('modalTitle').innerText = data.titre;
            document.getElementById('modalCategory').innerText = data.categorie;
            document.getElementById('modalDifficulty').innerText = data.difficulty;
            document.getElementById('modalPoints').innerText = data.points + ' PTS';
            document.getElementById('modalTime').innerText = data.time + ' min';
            document.getElementById('modalPlace').innerText = data.place;
            document.getElementById('modalDescription').innerHTML = data.description.replace(/\n/g, '<br>');
            currentChallengeId = data.id;
            var modal = document.getElementById('detailModal');
            modal.style.display = 'flex';
            void modal.offsetWidth; 
            setTimeout(() => { modal.classList.add('active'); }, 50); 
            document.body.style.overflow = 'hidden';
        }

        function closeDetailModal() {
            var modal = document.getElementById('detailModal');
            modal.classList.remove('active');
            setTimeout(() => { 
                modal.style.display = 'none'; 
                document.body.style.overflow = '';
            }, 350);
        }

        function openConfirmModal() {
            var modal = document.getElementById('confirmModal');
            modal.style.display = 'flex';
            void modal.offsetWidth; 
            setTimeout(() => { modal.classList.add('active'); }, 50);
        }

        function closeConfirmModal() {
            var modal = document.getElementById('confirmModal');
            modal.classList.remove('active');
            setTimeout(() => { modal.style.display = 'none'; }, 350);
        }

        
        function openLevelUp() {
            var modal = document.getElementById('levelUpModal');
            modal.style.display = 'flex';
            setTimeout(() => { modal.classList.add('active'); }, 50);
            triggerLevelUpCelebration();
        }

        function closeLevelUp() {
            var modal = document.getElementById('levelUpModal');
            modal.classList.remove('active');
            setTimeout(() => { modal.style.display = 'none'; }, 500);
        }

        function triggerLevelUpCelebration() {
            var duration = 3000;
            var end = Date.now() + duration;

            (function frame() {
                confetti({
                    particleCount: 7,
                    angle: 60,
                    spread: 55,
                    origin: { x: 0 },
                    colors: ['#ffd700', '#ff8c00', '#ffffff'], 
                    zIndex: 1000000 
                });
               
                confetti({
                    particleCount: 7,
                    angle: 120,
                    spread: 55,
                    origin: { x: 1 },
                    colors: ['#ffd700', '#ff8c00', '#ffffff'],
                    zIndex: 1000000 
                });

                if (Date.now() < end) {
                    requestAnimationFrame(frame);
                }
            }());
        }

        document.getElementById('btnProceed').onclick = function() {
            var btn = this;
            fetch('../../../../Controller/gamification_api.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({
                    action: 'start_challenge',
                    challenge_id: currentChallengeId
                })
            });

            var duration = 1500;
            var animationEnd = Date.now() + duration;
            var defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 10001 };
            function randomInRange(min, max) { return Math.random() * (max - min) + min; }
            var interval = setInterval(function() {
                var timeLeft = animationEnd - Date.now();
                if (timeLeft <= 0) return clearInterval(interval);
                var particleCount = 50 * (timeLeft / duration);
                confetti(Object.assign({}, defaults, { particleCount, origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 } }));
                confetti(Object.assign({}, defaults, { particleCount, origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 } }));
            }, 250);

            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading Mission...';
            btn.style.background = "#fff";
            btn.style.color = "#1b5e20";

            setTimeout(() => {
                window.location.href = 'challenge-resources.php?id=' + currentChallengeId;
            }, 1500);
        };

        window.onclick = function(event) {
            if (event.target == document.getElementById('detailModal')) closeDetailModal();
            if (event.target == document.getElementById('confirmModal')) closeConfirmModal();
            if (event.target == document.getElementById('loginModal')) closeLoginModal();
        }
    </script>
</body>
</html>