<?php
require_once __DIR__ . '/../../../../config.php';
require_once __DIR__ . '/../../../../Model/challenge.php';
require_once __DIR__ . '/../../../../Controller/challenge-controller.php';

$challengeController = new ChallengeController();
$allChallenges = $challengeController->listChallenges();

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
        html {
            overflow-y: scroll; 
        }

        #quiz-grid {
            min-height: 100vh; 
        }

        .rts-header-area {
            position: fixed !important;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 999;
            background: rgba(20, 60, 20, 0.65) !important; 
            backdrop-filter: blur(12px); 
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: transform 0.4s ease-in-out; 
            padding-top: 10px; 
            padding-bottom: 10px;
        }

        .header-hidden {
            transform: translateY(-100%);
        }

        @keyframes float { 0% { transform: translateY(0) translateX(0); } 50% { transform: translateY(-20px) translateX(20px); } 100% { transform: translateY(0) translateX(0); } }
        @keyframes moveGradient { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px) scale(0.95); } to { opacity: 1; transform: translateY(0) scale(1); } }

        body.rt_bg-secondary {
            background: linear-gradient(135deg, #14b8a6, #14b8a6, #3ddf43ff, #81c784);
            background-size: 400% 400%;
            animation: moveGradient 25s ease infinite;
            overflow-x: hidden;
        }
        
        .mouse-cursor {
            z-index: 10000 !important; 
            pointer-events: none;
        }

        .bg-animation { position: fixed; top: 0; left: 0; width: 100%; height: 100vh; z-index: -1; overflow: hidden; }
        .bg-animation .blob { position: absolute; border-radius: 50%; filter: blur(80px); opacity: 0.4; animation: float 25s ease-in-out infinite alternate; }
        .bg-animation .blob1 { width: 400px; height: 400px; background: rgba(144, 238, 144, 0.5); top: -50px; left: -100px; animation-duration: 22s; }
        .bg-animation .blob2 { width: 300px; height: 300px; background: rgba(0, 150, 136, 0.4); bottom: -80px; right: -80px; animation-duration: 28s; animation-delay: -5s; }

        .quiz-filter-controls { margin-bottom: 40px; text-align: center; }
        .quiz-filter-group { margin: 0; padding: 0; list-style: none; display: inline-block; margin-bottom: 15px; }
        .quiz-filter-group li { display: inline-block; margin: 0 5px; }
        .filter-btn {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.25);
            color: #fff;
            padding: 10px 20px;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            backdrop-filter: blur(5px);
        }
        .filter-btn:hover { background: rgba(255, 255, 255, 0.25); border-color: rgba(255, 255, 255, 0.5); transform: translateY(-2px); }
        .filter-btn.is-active {
            background: #fff;
            color: #1b5e20; 
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.5); 
            border-color: #fff;
        }
        
        .quiz-card {
            background: rgba(20, 60, 20, 0.35);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(100, 255, 100, 0.2);
            border-radius: 24px; 
            padding: 25px;
            text-decoration: none;
            color: #fff;
            display: flex;
            flex-direction: column;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            height: 100%;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            animation: fadeIn 0.6s ease-out forwards;
        }
        .quiz-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3), 0 0 30px rgba(100, 255, 100, 0.5);
            border-color: rgba(100, 255, 100, 0.6);
        }
        
        .quiz-card-header { display: flex; justify-content: space-between; align-items: center; font-size: 0.9rem; font-weight: 600; margin-bottom: 20px; }
        .quiz-card-header .categorie { 
            background: linear-gradient(45deg, #43a047, #66bb6a);
            color: white;
            padding: 6px 15px;
            border-radius: 20px; 
            text-transform: uppercase; 
            letter-spacing: 0.5px; 
            font-size: 0.75rem;
            font-weight: 700;
            box-shadow: 0 2px 10px rgba(67, 160, 71, 0.3);
        }
        .quiz-card-header .niveau { font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
        
        .niveau.easy { color: #69f0ae; text-shadow: 0 0 10px rgba(105, 240, 174, 0.7); }
        .niveau.medium { color: #ffd54f; text-shadow: 0 0 10px rgba(255, 213, 79, 0.7); }
        .niveau.hard { color: #ff8a80; text-shadow: 0 0 10px rgba(255, 138, 128, 0.7); }
        .niveau.expert { color: #ea80fc; text-shadow: 0 0 15px rgba(234, 128, 252, 0.8); font-weight: 800; }

        .quiz-card-body { flex-grow: 1; }
        .quiz-card-body .titre { font-size: 1.5rem; font-weight: 700; margin: 0 0 10px 0; line-height: 1.3; color: #fff; }
        .quiz-card-body .description { font-size: 0.95rem; color: #e0e0e0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; margin-bottom: 20px; opacity: 0.9; }
        
        .challenge-meta { display: flex; flex-wrap: wrap; gap: 15px; margin-bottom: 20px; font-size: 0.85rem; color: #c8e6c9; }
        .challenge-meta div { display: flex; align-items: center; gap: 6px; background: rgba(0,0,0,0.2); padding: 4px 10px; border-radius: 10px;}
        .challenge-meta i { color: #66bb6a; }

        .quiz-card-footer { display: flex; justify-content: space-between; align-items: center; margin-top: auto; border-top: 1px solid rgba(255, 255, 255, 0.1); padding-top: 20px; }
        .quiz-card-footer .points { font-size: 1.2rem; font-weight: 800; color: #ffd54f; text-shadow: 0 0 8px rgba(255, 213, 79, 0.5); display: flex; align-items: center; gap: 5px;}
        
        .quiz-card-footer .start-btn { 
            background: linear-gradient(45deg, #43a047, #81c784);
            color: white;
            padding: 10px 28px; 
            border-radius: 25px; 
            font-weight: 700; 
            transition: all 0.3s ease; 
            box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
            border: none;
        }
        .quiz-card:hover .start-btn { 
            background: linear-gradient(45deg, #66bb6a, #a5d6a7); 
            color: #0a3d0c;
            box-shadow: 0 5px 20px rgba(102, 187, 106, 0.5); 
            transform: translateY(-2px);
        }
        
        .page-title-area { text-align: center; margin-bottom: 50px; }
        .page-title-area .title { font-size: 3.5rem; color: #fff; font-weight: 800; text-shadow: 0 4px 15px rgba(0, 0, 0, 0.3); }
        .page-title-area .sub { display: block; font-size: 1.1rem; color: #69f0ae; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 10px;}
        .page-title-area .disc { font-size: 1.3rem; color: #e8f5e9; opacity: 0.95; }

        .modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.85);
            backdrop-filter: blur(8px);
            z-index: 9999;
            display: none;
            justify-content: center;
            align-items: center;
            padding: 20px;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
            will-change: opacity;
        }
        .modal-overlay.active { opacity: 1; }

        .detail-modal-card {
            width: 100%;
            max-width: 800px;
            background: rgba(20, 60, 20, 0.85); 
            backdrop-filter: blur(20px);
            border: 1px solid rgba(100, 255, 100, 0.3);
            border-radius: 30px;
            padding: 40px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.4);
            color: #fff;
            position: relative;
            
            transform: scale(0.9);
            opacity: 0;
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), opacity 0.4s ease;
            will-change: transform, opacity;
            
            max-height: 90vh;
            overflow-y: auto;
            z-index: 1;
        }
        
        .modal-overlay.active .detail-modal-card { 
            transform: scale(1); 
            opacity: 1;
        }

        .close-modal-btn {
            position: absolute; top: 20px; right: 20px;
            background: rgba(255,255,255,0.1);
            border: none; color: #fff;
            width: 40px; height: 40px; border-radius: 50%;
            cursor: pointer; font-size: 1.2rem;
            display: flex; justify-content: center; align-items: center;
            transition: all 0.2s;
            z-index: 10;
        }
        .close-modal-btn:hover { background: rgba(255,0,0,0.3); transform: rotate(90deg); }

        .modal-header-content { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 30px; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 20px; }
        .modal-title { font-size: 2.5rem; font-weight: 800; margin: 0; background: linear-gradient(to right, #fff, #b9f6ca); -webkit-background-clip: text; -webkit-text-fill-color: transparent; line-height: 1.2;}
        
        .info-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-bottom: 25px; }
        .info-box { background: rgba(0,0,0,0.3); padding: 15px; border-radius: 15px; text-align: center; border: 1px solid rgba(255,255,255,0.05); }
        .info-label { display: block; font-size: 0.7rem; color: #a5d6a7; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px; }
        .info-value { font-size: 1.1rem; font-weight: 700; color: #fff; }

        .description-box { 
            background: rgba(0, 0, 0, 0.3);
            padding: 25px; 
            border-radius: 20px; 
            line-height: 1.8; 
            color: #ffffff;
            font-size: 1rem; 
            border-left: 4px solid #69f0ae; 
            margin-bottom: 30px;
            position: relative;
            z-index: 5;
        }

        .btn-accept-lg { 
            background: linear-gradient(45deg, #43a047, #00e676); 
            border: none; color: #003d1a; width: 100%;
            padding: 15px; border-radius: 50px; 
            font-weight: 800; font-size: 1.2rem; 
            box-shadow: 0 0 20px rgba(0, 230, 118, 0.4); 
            cursor: pointer; transition: all 0.3s; text-transform: uppercase;
        }
        .btn-accept-lg:hover { transform: scale(1.02); box-shadow: 0 0 40px rgba(0, 230, 118, 0.6); }

        .confirm-box {
            background: #1a1a1a; border: 2px solid #69f0ae; padding: 30px; border-radius: 20px; text-align: center; max-width: 400px;
        }
        .confirm-actions { display: flex; gap: 10px; justify-content: center; margin-top: 20px; }
        .btn-yes { background: #69f0ae; color: #000; padding: 10px 30px; border-radius: 10px; font-weight: 700; border: none; cursor: pointer; }
        .btn-no { background: transparent; color: #ff8a80; padding: 10px 30px; border-radius: 10px; font-weight: 700; border: 1px solid #ff8a80; cursor: pointer; }

        @media (max-width: 768px) {
            .info-grid { grid-template-columns: 1fr; }
            .modal-title { font-size: 1.8rem; }
        }
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
                                <li class="single-items off-arrow"><a class="single" href="contact.html">Contact</a></li>
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

    <div class="rts-explore-area rts-section-gap" style="padding-top: 280px; position: relative; z-index: 2;">
        <div class="container">

            <div class="row">
                <div class="col-12">
                    <div class="page-title-area">
                        <span class="sub">Zitouna Quests</span>
                        <h3 class="title">Real-World Challenges</h3>
                        <p class="disc">Apply your skills, solve problems, and level up!</p>
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
                    <?php $delayCounter = 0; ?>
                    <?php foreach ($allChallenges as $challenge): ?>
                        <?php
                        if (strtolower($challenge->getStatus()) === 'inactive') continue;

                        $delayCounter++;
                        $difficultyRaw = $challenge->getDifficulty();
                        $difficultyClass = strtolower($difficultyRaw);
                        $categoryFromDB = htmlspecialchars($challenge->getCategorie());
                        $categoryClass = strtolower(str_replace(' ', '-', preg_replace("/[^A-Za-z0-9 ]/", '', $categoryFromDB)));

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
                            <div class="quiz-card" onclick='openDetailModal(<?php echo $jsonChallenge; ?>)'>
                                <div class="quiz-card-header">
                                    <span class="categorie"><?php echo $categoryFromDB; ?></span>
                                    <span class="niveau <?php echo $difficultyClass; ?>"><?php echo $difficultyRaw; ?></span>
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
                                    <span class="points"><i class="fas fa-star" style="color: #ffd54f; font-size: 0.9rem;"></i> <?php echo htmlspecialchars($challenge->getPoints()); ?> Pts</span>
                                    <button class="start-btn">Details</button>
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
        let currentChallengeId = 0;

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
            
            setTimeout(() => {
                modal.classList.add('active');
            }, 50); 
            
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
            setTimeout(() => {
                modal.classList.add('active');
            }, 50);
        }

        function closeConfirmModal() {
            var modal = document.getElementById('confirmModal');
            modal.classList.remove('active');
            setTimeout(() => { modal.style.display = 'none'; }, 350);
        }
        document.getElementById('btnProceed').onclick = function() {
            var btn = this;
            
            var duration = 1500;
            var animationEnd = Date.now() + duration;
            var defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 10001 };

            function randomInRange(min, max) {
            return Math.random() * (max - min) + min;
            }

            var interval = setInterval(function() {
            var timeLeft = animationEnd - Date.now();

            if (timeLeft <= 0) {
                return clearInterval(interval);
            }

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
        }
    </script>
</body>
</html>