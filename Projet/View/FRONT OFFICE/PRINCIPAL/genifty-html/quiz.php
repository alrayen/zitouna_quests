<?php

require_once __DIR__ . '/../../../../config.php';
require_once __DIR__ . '/../../../../Model/quiz.php';
require_once __DIR__ . '/../../../../Controller/quiz-controller.php';

$quizController = new QuizController();
$allQuizzes = $quizController->listQuizzes();

$uniqueCategories = [];
$uniqueDifficulties = [];
$difficultyMap = [
    'Facile' => 'Easy',
    'Moyen' => 'Medium',
    'Difficile' => 'Hard', 
    'Extreme'   => 'Extreme'
];

foreach ($allQuizzes as $quiz) {
    $uniqueCategories[$quiz->getCategorie()] = true; 
    
    $dbDifficulty = $quiz->getNiveau();
    if (isset($difficultyMap[$dbDifficulty])) {
        $uniqueDifficulties[$difficultyMap[$dbDifficulty]] = true; 
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

        /* --- Creative Filter Styles --- */
        .quiz-filter-controls {
            margin-bottom: 40px;
            text-align: center;
        }
        .quiz-filter-group {
            margin: 0;
            padding: 0;
            list-style: none;
            display: inline-block;
            margin-bottom: 15px;
        }
        .quiz-filter-group li {
            display: inline-block;
            margin: 0 5px;
        }
        .filter-btn {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
            padding: 10px 20px;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            backdrop-filter: blur(5px);
        }
        .filter-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.4);
            transform: translateY(-2px);
        }
        .filter-btn.is-active {
            background: #fff;
            color: #00796b;
            box-shadow: 0 5px 15px rgba(0, 196, 159, 0.4);
            border-color: #fff;
        }
        
        /* --- Isotope Card Wrapper --- */
        .quiz-card-wrapper {
            /* Isotope handles transitions */
        }
        .quiz-card-wrapper.isotope-hidden {
            opacity: 0;
            transform: scale(0.8);
        }

        .quiz-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 25px;
            text-decoration: none;
            color: #fff;
            display: flex;
            flex-direction: column;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            height: 100%;
            animation: fadeIn 0.6s ease-out forwards;
            opacity: 0; 
        }
        .quiz-card:hover {
            transform: translateY(-10px) scale(1.03);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3), 0 0 30px rgba(0, 196, 159, 0.6);
            border-color: rgba(255, 255, 255, 0.3);
        }
        
        /* --- Card Content Styles (All English) --- */
        .quiz-card-header { display: flex; justify-content: space-between; align-items: center; font-size: 0.9rem; font-weight: 600; margin-bottom: 20px; }
        .quiz-card-header .categorie { background: rgba(0, 196, 159, 0.2); color: #94FFEA; padding: 5px 12px; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.5px; }
        .quiz-card-header .niveau { font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
        .niveau.easy { color: #00E6A7; text-shadow: 0 0 10px rgba(0, 230, 167, 0.7); }
        .niveau.medium { color: #FFBB28; text-shadow: 0 0 10px rgba(255, 187, 40, 0.7); }
        .niveau.hard { color: #FF6B6B; text-shadow: 0 0 10px rgba(255, 107, 107, 0.7); }
        .niveau.extreme { 
    color: #d051ff; /* Bright Neon Purple */
    text-shadow: 0 0 15px rgba(208, 81, 255, 0.8); 
    font-weight: 800; /* Extra bold */
}
        .quiz-card-body { flex-grow: 1; }
        .quiz-card-body .titre { font-size: 1.75rem; font-weight: 600; margin: 0; line-height: 1.3; color: #fff; }
        .quiz-card-footer { display: flex; justify-content: space-between; align-items: center; margin-top: 30px; border-top: 1px solid rgba(255, 255, 255, 0.1); padding-top: 20px; }
        .quiz-card-footer .points { font-size: 1.1rem; font-weight: 700; color: #FFBB28; text-shadow: 0 0 8px rgba(255, 187, 40, 0.5); }
        .quiz-card-footer .start-btn { background: rgba(255, 255, 255, 0.9); color: #00796B; padding: 10px 25px; border-radius: 25px; font-weight: 700; transition: all 0.3s ease; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); }
        .quiz-card:hover .start-btn { background: #00C49F; color: #fff; box-shadow: 0 5px 15px rgba(0, 196, 159, 0.4); transform: scale(1.05); }
        
        /* --- Page Title (All English) --- */
        .page-title-area { text-align: center; margin-bottom: 50px; }
        .page-title-area .title { font-size: 3.5rem; color: #fff; font-weight: 700; text-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); }
        .page-title-area .disc { font-size: 1.3rem; color: #d4fcf5; opacity: 0.9; }
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
                        <a href="index.html" class="logo">
                            <img src="assets/images/logo/logo3.png" alt="NFT_image">
                        </a>
                    </div>
                </div>
                <div class="col-xl-5 d-xl-block d-none">
                    <div class="main-menu-wrapepr">
                        <nav class="mainmenu-nav d-none d-xl-block">
                            <ul class="main-menu">
                                <li class="single-items off-arrow">
                                    <a class="navmain" href="index.html">Home</a>
                                </li>
                                <li class="single-items off-arrow">
                                    <a class="navmain" href="#">Quests</a>
                                </li>
                                <li class="single-items off-arrow">
                                    <a class="navmain" href="quizzes.php">Quiz</a>
                                </li>
                                <li class="single-items off-arrow">
                                    <a class="navmain" href="#">Forum</a>
                                </li>
                                <li class="single-items off-arrow">
                                    <a class="navmain" href="#">Blog</a>
                                </li>
                                <li class="single-items off-arrow"><a class="single" href="contact.html">Contact</a></li>
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
                        <div class="setting-option rts-icon-list d-block d-lg-none">
                            <div class="icon-box search-mobile-icon">
                                <button><i class="far fa-search"></i></button>
                            </div>
                            <form id="header-search-1" action="#" method="GET" class="large-mobile-blog-search">
                                <div class="rts-search-mobile form-group">
                                    <button type="submit" class="search-button"><i class="far fa-search"></i></button>
                                    <input type="text" placeholder="Search ...">
                                </div>
                            </form>
                        </div>
                        <ul class="icons">
                            <li class="icon user"> <a href="author.html"><i class="far fa-user"></i></a></li>
                            <li class="icon notification"> <a href="#"><i class="far fa-bell" alt="notification"></i></a></li>
                        </ul>
                        <a id="connect-wallet" href="login.html" class="rts-btn btn-primary">login / sign up</a>
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
                    <a href="index.html"><img src="assets/images/logo/logo3.png" alt="_logo"></a>
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
                        <a class="navmain" href="index.html">Home</a>
                    </li>
                    <li class="single-items off-arrow">
                        <a class="navmain" href="#">Quests</a>
                    </li>
                    <li class="single-items off-arrow">
                        <a class="navmain" href="quizzes.php">Quiz</a>
                    </li>
                    <li class="single-items off-arrow">
                        <a class="navmain" href="#">Forum</a>
                    </li>
                    <li class="single-items off-arrow">
                        <a class="navmain" href="#">Blog</a>
                    </li>
                    <li class="single-items off-arrow"><a class="single" href="contact.html">Contact</a></li>
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

                <?php if (empty($allQuizzes)): ?>
                    <div class="col-12">
                        <p class="text-center" style="font-size: 1.2rem; color: #fff;">No quizzes are available at the moment. Check back soon!</p>
                    </div>
                <?php else: ?>
                    <?php 
                    $delayCounter = 0; 
                    ?>
                    <?php foreach ($allQuizzes as $quiz): ?>
                        <?php
                        $delayCounter++;
                        $animationDelay = $delayCounter * 100;
                        
                        $dbDifficulty = $quiz->getNiveau();
                        $difficultyEnglish = $difficultyMap[$dbDifficulty] ?? 'medium';
                        $difficultyClass = strtolower($difficultyEnglish);
                        
                        $categoryFromDB = htmlspecialchars($quiz->getCategorie());
                        $titleFromDB = htmlspecialchars($quiz->getTitre());
                        
                        // Create a "slug" for the category class
                        $categoryClass = strtolower(str_replace(' ', '-', preg_replace("/[^A-Za-z0-9 ]/", '', $categoryFromDB)));
                        ?>
                    
                        <div class="col-lg-4 col-md-6 col-sm-12 quiz-card-wrapper <?php echo $categoryClass; ?> <?php echo $difficultyClass; ?>">
                            
                            <a href="take-quiz.php?id=<?php echo htmlspecialchars($quiz->getIdQuiz()); ?>" class="quiz-card" style="animation-delay: <?php echo $animationDelay; ?>ms;">

                                <div class="quiz-card-header">
                                    <span class="categorie"><?php echo $categoryFromDB; ?></span>
                                    <span class="niveau <?php echo $difficultyClass; ?>"><?php echo $difficultyEnglish; ?></span>
                                </div>

                                <div class="quiz-card-body">
                                    <h5 class="titre"><?php echo $titleFromDB; ?></h5>
                                </div>

                                <div class="quiz-card-footer">
                                    <span class="points"><?php echo htmlspecialchars($quiz->getPoints()); ?> Points</span>
                                    <span class="start-btn">Start</span>
                                </div>

                            </a>
                        </div>
                    <?php endforeach; ?>

                <?php endif; ?>

            </div>
        </div>
    </div>

    
    <div class="loadingpage">
        <div class="spinner"></div>
    </div>
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


    <script>
        $(window).on('load', function () {
                        var $grid = $('#quiz-grid').isotope({
                itemSelector: '.quiz-card-wrapper',
                layoutMode: 'fitRows', 
                transitionDuration: '0.6s',
                hiddenStyle: { opacity: 0, transform: 'scale(0.9)' },
                visibleStyle: { opacity: 1, transform: 'scale(1)' }
            });

            var filters = {
                category: '*', 
                difficulty: '*'
            };

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
                
                var catFilter = filters.category;
                var diffFilter = filters.difficulty;


                if (catFilter === '*' && diffFilter === '*') {
                    combinedFilter = '*';
                } 
                else if (catFilter === '*' && diffFilter !== '*') {
                    combinedFilter = diffFilter;
                }
                else if (catFilter !== '*' && diffFilter === '*') {
                    combinedFilter = catFilter;
                }
                else {
                    combinedFilter = catFilter + diffFilter;
                }
                
                // 5. Apply the combined filter to Isotope
                $grid.isotope({ filter: combinedFilter });
            });
        });
    </script>
    </body>
</html>