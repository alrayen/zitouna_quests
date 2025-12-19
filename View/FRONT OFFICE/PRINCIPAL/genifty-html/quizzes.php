<?php
session_start(); // Assurez-vous que la session est démarrée pour récupérer l'ID utilisateur
require_once __DIR__ . '/../../../../config.php';
require_once __DIR__ . '/../../../../Model/quiz.php';
require_once __DIR__ . '/../../../../Controller/quiz-controller.php';

// Vérification de l'utilisateur connecté (requis pour créer un lobby)
$current_user_id = $_SESSION['user_id'] ?? 0;


$quizController = new QuizController();
$allQuizzes = $quizController->listQuizzes(); // Assurez-vous que cette méthode existe et fonctionne

$uniqueCategories = [];
$uniqueDifficulties = [];
$difficultyMap = [
    'Facile' => 'Easy',
    'Moyen' => 'Medium',
    'Difficile' => 'Hard', 
    'Extreme'   => 'Extreme'
];

foreach ($allQuizzes as $quiz) {
    // Vérification que $quiz est bien un objet Quiz avant d'appeler getCategorie()
    if (is_object($quiz) && method_exists($quiz, 'getCategorie')) {
        $uniqueCategories[$quiz->getCategorie()] = true; 
        
        $dbDifficulty = $quiz->getNiveau();
        if (isset($difficultyMap[$dbDifficulty])) {
            $uniqueDifficulties[$difficultyMap[$dbDifficulty]] = true; 
        }
    }
}
$uniqueCategories = array_keys($uniqueCategories);
$uniqueDifficulties = array_keys($uniqueDifficulties);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Zitouna Quests - Explore Our Quests</title>
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
        /* Vos styles existants (keyframes, body, quiz-card, etc.) */
        @keyframes float { 0% { transform: translateY(0) translateX(0); } 50% { transform: translateY(-20px) translateX(20px); } 100% { transform: translateY(0) translateX(0); } }
        @keyframes moveGradient { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px) scale(0.95); } to { opacity: 1; transform: translateY(0) scale(1); } }

        body.rt_bg-secondary {
            background: linear-gradient(135deg, #005248, #00c49f, #00796b);
            background-size: 400% 400%;
            animation: moveGradient 20s ease infinite;
            overflow-x: hidden;
        }
        
        .bg-animation { position: fixed; top: 0; left: 0; width: 100%; height: 100vh; z-index: -1; overflow: hidden; }
        .bg-animation .blob { position: absolute; border-radius: 50%; filter: blur(100px); opacity: 0.4; animation: float 25s ease-in-out infinite alternate; }
        .bg-animation .blob1 { width: 400px; height: 400px; background: rgba(89, 255, 228, 0.5); top: -50px; left: -100px; animation-duration: 20s; }
        .bg-animation .blob2 { width: 300px; height: 300px; background: rgba(255, 255, 255, 0.3); bottom: -80px; right: -80px; animation-duration: 30s; animation-delay: -5s; }

        .quiz-filter-controls { margin-bottom: 40px; text-align: center; }
        .quiz-filter-group { margin: 0; padding: 0; list-style: none; display: inline-block; margin-bottom: 15px; }
        .quiz-filter-group li { display: inline-block; margin: 0 5px; }
        .filter-btn {
            background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff; padding: 10px 20px; border-radius: 20px; cursor: pointer;
            transition: all 0.3s ease; font-weight: 600; backdrop-filter: blur(5px);
        }
        .filter-btn:hover { background: rgba(255, 255, 255, 0.2); border-color: rgba(255, 255, 255, 0.4); transform: translateY(-2px); }
        .filter-btn.is-active {
            background: #fff; color: #00796b; box-shadow: 0 5px 15px rgba(0, 196, 159, 0.4); border-color: #fff;
        }
        
        .quiz-card {
            background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(15px); border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px; padding: 25px; text-decoration: none; color: #fff;
            display: flex; flex-direction: column; transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            height: 100%; animation: fadeIn 0.6s ease-out forwards; opacity: 0; 
        }
        .quiz-card:hover {
            transform: translateY(-10px) scale(1.03); box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3), 0 0 30px rgba(0, 196, 159, 0.6);
            border-color: rgba(255, 255, 255, 0.3);
        }
        
        .quiz-card-header .categorie { background: rgba(0, 196, 159, 0.2); color: #94FFEA; padding: 5px 12px; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.5px; }
        .niveau.easy { color: #00E6A7; text-shadow: 0 0 10px rgba(0, 230, 167, 0.7); }
        .niveau.medium { color: #FFBB28; text-shadow: 0 0 10px rgba(255, 187, 40, 0.7); }
        .niveau.hard { color: #FF6B6B; text-shadow: 0 0 10px rgba(255, 107, 107, 0.7); }
        .niveau.extreme { color: #d051ff; text-shadow: 0 0 15px rgba(208, 81, 255, 0.8); font-weight: 800; }
        .quiz-card-body .titre { font-size: 1.75rem; font-weight: 600; margin: 0; line-height: 1.3; color: #fff; }
        .quiz-card-footer { display: flex; justify-content: space-between; align-items: center; margin-top: 30px; border-top: 1px solid rgba(255, 255, 255, 0.1); padding-top: 20px; }
        .quiz-card-footer .points { font-size: 1.1rem; font-weight: 700; color: #FFBB28; text-shadow: 0 0 8px rgba(255, 187, 40, 0.5); }
        .quiz-card-footer .start-btn { background: rgba(255, 255, 255, 0.9); color: #00796B; padding: 10px 25px; border-radius: 25px; font-weight: 700; transition: all 0.3s ease; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); }
        .quiz-card:hover .start-btn { background: #00C49F; color: #fff; box-shadow: 0 5px 15px rgba(0, 196, 159, 0.4); transform: scale(1.05); }
        
        .page-title-area { text-align: center; margin-bottom: 50px; }
        .page-title-area .title { font-size: 3.5rem; color: #fff; font-weight: 700; text-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); }
        .page-title-area .disc { font-size: 1.3rem; color: #d4fcf5; opacity: 0.9; }

        /* Styles spécifiques aux contrôles du mode Online */
        .online-mode-controls button, .online-mode-controls a {
            transition: all 0.3s ease;
        }
        .online-mode-controls .btn-join {
             background: #FFBB28 !important;
             color: #333 !important;
        }
        .online-mode-controls .btn-join:hover {
             background: #e6a81a !important;
        }
    </style>
</head>

<body class="rt_bg-secondary">

    <div class="bg-animation">
        <div class="blob blob1"></div>
        <div class="blob blob2"></div>
    </div>

    <div class="rts-header-area header-inner-one header--sticky">
        <div class="container-header">
            <div class="row align-items-center ptb_sm--20 padding-controler-header">
                <div class="col-xl-2 col-lg-4 col-md-4 col-sm-12 ">
                    <div class="header-left">
                        <a href="index.php" class="logo">
                            <img src="assets/images/logo/logo3.png" alt="NFT_image">
                        </a>
                    </div>
                </div>
                <div class="col-xl-5 d-xl-block d-none">
                    <div class="main-menu-wrapepr">
                                                <nav class="mainmenu-nav d-none d-xl-block">
                            <ul class="main-menu">
                                <li class="single-items off-arrow">
                                    <a class="navmain" href="index.php">Home</a>
                                </li>
                                <li class="single-items off-arrow">
                                    <a class="navmain" href="quiz.php">Quiz</a>
                                </li>
                                <li class="single-items off-arrow">
                                    <a class="navmain" href="challenge.php">Challenge</a>
                                </li>
                                <li class="single-items off-arrow">
                                    <a class="navmain" href="forum.php">Forum</a>
                                </li>
                                <li class="single-items off-arrow">
                                    <a class="navmain" href="sponsor.php">Sponsor</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="col-xl-5 col-lg-8 col-md-8 col-sm-12 justify-content-sm-center d-xsm-flex justify-content-sm-center d-xsm-flex">
                    <div class="header-right">
                        <div class="input-group d-none d-lg-block">
                            <i class="fal fa-search"></i>
                            <input type="text" placeholder="Search Collections" aria-label="Search Collections"
                                style="background: linear-gradient(90deg, rgba(255,255,255,0.03), rgba(255,255,255,0.01)); border:1px solid rgba(255,255,255,0.12); color:#fff; padding:10px 14px; border-radius:8px; box-shadow: 0 6px 18px rgba(0,0,0,0.45), inset 0 0 0 4px rgba(255,215,0,0.02); transition:box-shadow 0.18s ease, transform 0.12s ease;"
                                onfocus="this.style.boxShadow='0 10px 30px rgba(0,0,0,0.6), 0 0 0 6px rgba(255,215,0,0.18)'; this.style.transform='translateY(-1px)';"
                                onblur="this.style.boxShadow='0 6px 18px rgba(0,0,0,0.45), inset 0 0 0 4px rgba(255,215,0,0.02)'; this.style.transform='none';" />
                        </div>
                        <ul class="icons">
                            <li class="icon user"> <a href="author.php"><i class="far fa-user"></i></a></li>
                            <li class="icon notification"> <a href="#"><i class="far fa-bell" alt="notification"></i></a></li>
                        </ul>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a id="connect-wallet" href="../../../../Controller/logout.php" class="rts-btn btn-primary">Disconnect</a>
                        <?php else: ?>
                            <a id="connect-wallet" href="login.php" class="rts-btn btn-primary">login / sign up</a>
                        <?php endif; ?>
                        <div class="mobile-menu-bar d-block d-xl-none">
                            <div class="hamberger">
                                <button class="hamberger-button">
                                    <i class="fal fa-bars"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="popup-mobile-menu">
        <div class="inner">
            <div class="header-top">
                <div class="logo logo-custom-css">
                    <a href="index.php"><img src="assets/images/logo/logo3.png" alt="_logo"></a>
                </div>
                <div class="close-menu">
                    <button class="close-button">
                        <i class="fal fa-times"></i>
                    </button>
                </div>
            </div>
            <nav>
                <ul class="main-menu">
                    <li class="single-items off-arrow">
                        <a class="navmain" href="index.php">Home</a>
                    </li>
                    <li class="single-items off-arrow">
                        <a class="navmain" href="quiz.php">Quiz</a>
                    </li>
                    <li class="single-items off-arrow">
                        <a class="navmain" href="challenge.php">Challenge</a>
                    </li>
                    <li class="single-items off-arrow">
                        <a class="navmain" href="forum.php">Forum</a>
                    </li>
                    <li class="single-items off-arrow">
                        <a class="navmain" href="sponsor.php">Sponsor</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

    <div class="rts-explore-area rts-section-gap" style="padding-top: 150px; position: relative; z-index: 2;">
        <div class="container">

            <div class="row">
                <div class="col-12">
                    <div class="page-title-area">
                        <span class="sub">Zitouna Quests</span>
                        <h3 class="title">Explore Our Quests</h3>
                        <p class="disc">Test your knowledge and earn points!</p>
                    </div>
                </div>
            </div>

            <div class="online-mode-controls mb-5">
                <div class="row justify-content-center">
                    
                    <?php if ($current_user_id > 0): ?>
                        <div class="col-md-4 mb-3">
                            <button id="create-session-btn" class="rts-btn btn-primary w-100" style="padding: 15px 0;">
                                <i class="fas fa-users"></i> **Créer une Partie Online**
                            </button>
                        </div>

                        <div class="col-md-4 mb-3">
                            <a href="join_form.html" class="rts-btn btn-primary w-100 btn-join" style="padding: 15px 0;">
                                <i class="fas fa-link"></i> **Rejoindre avec Code**
                            </a>
                        </div>
                    <?php else: ?>
                         <div class="col-12 text-center">
                             <p class="disc" style="color:#FFBB28; font-size:1.5rem;">Veuillez vous **connecter** pour créer ou rejoindre une partie en ligne.</p>
                             <a href="login.php" class="rts-btn btn-primary">Login / Sign Up</a>
                         </div>
                    <?php endif; ?>
                </div>
                <p id="online-status-message" class="text-center mt-3" style="color: #fff; font-weight: 600;"></p>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="quiz-filter-controls">
                        </div>
                </div>
            </div>
            <div class="row g-5 mt--20" id="quiz-grid">

                <?php if (empty($allQuizzes)): ?>
                    <?php else: ?>
                    <?php endif; ?>

            </div>
        </div>
    </div>

    <script src="assets/js/vendor/jquery.min.js"></script>
    <script src="assets/js/vendor/jquery-ui.min.js"></script>
    <script src="assets/js/vendor/isotop.min.js"></script> 
    <script src="assets/js/vendor/bootstrap.min.js"></script>
    <script src="assets/js/main.js"></script>


    <script>
        // Votre logique Isotope existante
        $(window).on('load', function () {
            // ... Votre code Isotope ...
        });
        
        // ===============================================
        // LOGIQUE JAVASCRIPT POUR LA CRÉATION DU LOBBY
        // ===============================================
        $(document).ready(function() {
            $('#create-session-btn').on('click', function() {
                const $btn = $(this);
                const originalText = $btn.html();
                
                // 1. Affichage du statut en cours
                $('#online-status-message').css('color', '#fff').text('Création de la session en cours...');
                $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Création...');

                // 2. Appel AJAX à create_session.php
                $.ajax({
                    url: '../../../../Controller/create_session.php', // Assurez-vous que le chemin est correct
                    type: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            // 3. Succès : Affichage du code et redirection
                            $('#online-status-message').css('color', '#00E6A7').html(`Session créée! Code: <b>${response.code}</b>. Redirection...`);
                            
                            setTimeout(() => {
                                // Rediriger vers la page du lobby avec l'ID de session
                                window.location.href = `online_lobby.php?session=${response.session_id}`;
                            }, 1500); 

                        } else {
                            // 4. Échec : Affichage du message d'erreur
                            $('#online-status-message').css('color', '#FF6B6B').text('Erreur de création: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#online-status-message').css('color', '#FF6B6B').text('Erreur de communication avec le serveur. Vérifiez PHP.');
                        console.error("AJAX Error:", status, error, xhr.responseText);
                    },
                    complete: function() {
                        // Réactiver le bouton en cas d'échec
                        if ($('#online-status-message').css('color') === 'rgb(255, 107, 107)' || $('#online-status-message').css('color') === 'rgb(255, 187, 40)') {
                            $btn.prop('disabled', false).html(originalText);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>