<?php
// Adjust these paths if your file structure is different
require_once __DIR__ . '/../../../../config.php';
require_once __DIR__ . '/../../../../Model/challenge.php';
require_once __DIR__ . '/../../../../Controller/challenge-controller.php';

$challengeController = new ChallengeController();
$allChallenges = $challengeController->listChallenges();

$uniqueCategories = [];
$uniqueDifficulties = [];

// Process challenges to get unique filter options
foreach ($allChallenges as $challenge) {
    // Store Category
    $uniqueCategories[$challenge->getCategorie()] = true; 
    
    // Store Difficulty
    $uniqueDifficulties[$challenge->getDifficulty()] = true; 
}

$uniqueCategories = array_keys($uniqueCategories);
$uniqueDifficulties = array_keys($uniqueDifficulties);
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
        /* --- Animations --- */
        @keyframes float { 0% { transform: translateY(0) translateX(0); } 50% { transform: translateY(-20px) translateX(20px); } 100% { transform: translateY(0) translateX(0); } }
        @keyframes moveGradient { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px) scale(0.95); } to { opacity: 1; transform: translateY(0) scale(1); } }

        /* --- NEW Green Background Theme --- */
        body.rt_bg-secondary {
            /* New gradient: Deep Forest Green to vibrant Lime Green */
            background: linear-gradient(135deg, #14b8a6, #14b8a6, #3ddf43ff, #81c784);
            background-size: 400% 400%;
            animation: moveGradient 25s ease infinite;
            overflow-x: hidden;
        }
        
        .bg-animation { position: fixed; top: 0; left: 0; width: 100%; height: 100vh; z-index: -1; overflow: hidden; }
        .bg-animation .blob { position: absolute; border-radius: 50%; filter: blur(80px); opacity: 0.4; animation: float 25s ease-in-out infinite alternate; }
        /* Updated blob colors to green/teal */
        .bg-animation .blob1 { width: 400px; height: 400px; background: rgba(144, 238, 144, 0.5); top: -50px; left: -100px; animation-duration: 22s; }
        .bg-animation .blob2 { width: 300px; height: 300px; background: rgba(0, 150, 136, 0.4); bottom: -80px; right: -80px; animation-duration: 28s; animation-delay: -5s; }

        /* --- Filter Buttons (Updated for Green Theme) --- */
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
            color: #1b5e20; /* Dark green text */
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.5); /* Green shadow */
            border-color: #fff;
        }
        
        /* --- NEW Card Styles --- */
        .quiz-card {
            /* Darker, greener translucent background */
            background: rgba(20, 60, 20, 0.35);
            backdrop-filter: blur(15px);
            /* Subtle green border */
            border: 1px solid rgba(100, 255, 100, 0.2);
            border-radius: 24px; /* Slightly rounder corners */
            padding: 25px;
            text-decoration: none;
            color: #fff;
            display: flex;
            flex-direction: column;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            height: 100%;
            animation: fadeIn 0.6s ease-out forwards;
            opacity: 0;
            overflow: hidden; /* For the header pill effect */
        }
        .quiz-card:hover {
            transform: translateY(-10px) scale(1.02);
            /* Strong neon green glow on hover */
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3), 0 0 30px rgba(100, 255, 100, 0.5);
            border-color: rgba(100, 255, 100, 0.6);
        }
        
        .quiz-card-header { display: flex; justify-content: space-between; align-items: center; font-size: 0.9rem; font-weight: 600; margin-bottom: 20px; }
        /* New style for category pill */
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
        
        /* Difficulty Colors (Kept similar but adjusted slightly for green bg) */
        .niveau.easy { color: #69f0ae; text-shadow: 0 0 10px rgba(105, 240, 174, 0.7); }
        .niveau.medium { color: #ffd54f; text-shadow: 0 0 10px rgba(255, 213, 79, 0.7); }
        .niveau.hard { color: #ff8a80; text-shadow: 0 0 10px rgba(255, 138, 128, 0.7); }
        .niveau.expert { color: #ea80fc; text-shadow: 0 0 15px rgba(234, 128, 252, 0.8); font-weight: 800; }

        .quiz-card-body { flex-grow: 1; }
        .quiz-card-body .titre { font-size: 1.5rem; font-weight: 700; margin: 0 0 10px 0; line-height: 1.3; color: #fff; }
        .quiz-card-body .description { font-size: 0.95rem; color: #e0e0e0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; margin-bottom: 20px; opacity: 0.9; }
        
        /* --- Specific Challenge Metadata (Time/Place) --- */
        .challenge-meta { display: flex; flex-wrap: wrap; gap: 15px; margin-bottom: 20px; font-size: 0.85rem; color: #c8e6c9; }
        .challenge-meta div { display: flex; align-items: center; gap: 6px; background: rgba(0,0,0,0.2); padding: 4px 10px; border-radius: 10px;}
        .challenge-meta i { color: #66bb6a; /* Bright green icons */ }

        .quiz-card-footer { display: flex; justify-content: space-between; align-items: center; margin-top: auto; border-top: 1px solid rgba(255, 255, 255, 0.1); padding-top: 20px; }
        .quiz-card-footer .points { font-size: 1.2rem; font-weight: 800; color: #ffd54f; text-shadow: 0 0 8px rgba(255, 213, 79, 0.5); display: flex; align-items: center; gap: 5px;}
        
        /* New Green Button Style */
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

    <div class="rts-explore-area rts-section-gap" style="padding-top: 150px; position: relative; z-index: 2;">
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
                        // Skip if status is inactive
                        if (strtolower($challenge->getStatus()) === 'inactive') continue;

                        $delayCounter++;
                        $animationDelay = $delayCounter * 100;
                        
                        $difficultyRaw = $challenge->getDifficulty();
                        $difficultyClass = strtolower($difficultyRaw);
                        
                        $categoryFromDB = htmlspecialchars($challenge->getCategorie());
                        $categoryClass = strtolower(str_replace(' ', '-', preg_replace("/[^A-Za-z0-9 ]/", '', $categoryFromDB)));
                        ?>
                    
                        <div class="col-lg-4 col-md-6 col-sm-12 quiz-card-wrapper <?php echo $categoryClass; ?> <?php echo $difficultyClass; ?>">
                            
                            <a href="challenge-details.php?id=<?php echo htmlspecialchars($challenge->getIdDefi()); ?>" class="quiz-card" style="animation-delay: <?php echo $animationDelay; ?>ms;">

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
                                    <button class="start-btn">Accept Challenge</button>
                                </div>

                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

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

    <script>
        $(window).on('load', function () {
            // Init Isotope
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
                
                // Logic to combine filters (Category AND Difficulty)
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
    </script>
</body>
</html>