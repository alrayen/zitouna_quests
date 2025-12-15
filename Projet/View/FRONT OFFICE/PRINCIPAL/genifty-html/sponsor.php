<?php
// --- CONFIGURATION & CONTROLLER SETUP ---
// Adjust these paths to match your exact folder structure
require_once __DIR__ . '/../../../../config.php';
require_once __DIR__ . '/../../../../Model/sponsor.php'; // Make sure filename matches
require_once __DIR__ . '/../../../../Controller/SponsorC.php';

$sponsorController = new SponsorController();
$allSponsors = $sponsorController->listSponsors();

$uniqueSectors = [];

// Process sponsors to get unique Sectors for the filter
foreach ($allSponsors as $sponsor) {
    $uniqueSectors[$sponsor->getSecteur()] = true; 
}

$uniqueSectors = array_keys($uniqueSectors);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Zitouna Quests - Our Partners</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/fab-icon.png">
    <link rel="stylesheet" href="assets/css/plugins/gordita.css">
    <link rel="stylesheet" href="assets/css/plugins/fontawesome-pro-icons.css">
    <link rel="stylesheet" href="assets/css/vendor/swiper.css">
    <link rel="stylesheet" href="assets/css/plugins/unicons.css">
    <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        /* --- Animations (Kept from your original file) --- */
        @keyframes float { 0% { transform: translateY(0) translateX(0); } 50% { transform: translateY(-20px) translateX(20px); } 100% { transform: translateY(0) translateX(0); } }
        @keyframes moveGradient { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px) scale(0.95); } to { opacity: 1; transform: translateY(0) scale(1); } }

        /* --- Background Theme --- */
        body.rt_bg-secondary {
            background: linear-gradient(135deg, #275a42ff, #3a9a64ff, #44bd78ff); /* Darker professional teal/slate */
            background-size: 400% 400%;
            animation: moveGradient 25s ease infinite;
            overflow-x: hidden;
            color: white;
        }
        
        .bg-animation { position: fixed; top: 0; left: 0; width: 100%; height: 100vh; z-index: -1; overflow: hidden; }
        .bg-animation .blob { position: absolute; border-radius: 50%; filter: blur(80px); opacity: 0.3; animation: float 25s ease-in-out infinite alternate; }
        .bg-animation .blob1 { width: 500px; height: 500px; background: #4ca1af; top: -100px; left: -100px; animation-duration: 22s; }
        .bg-animation .blob2 { width: 400px; height: 400px; background: #2c3e50; bottom: -80px; right: -80px; animation-duration: 28s; animation-delay: -5s; }

        /* --- Filter Buttons --- */
        .quiz-filter-controls { margin-bottom: 50px; text-align: center; }
        .quiz-filter-group { list-style: none; padding: 0; margin: 0; }
        .quiz-filter-group li { display: inline-block; margin: 5px; }
        
        .filter-btn {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: #b0bec5;
            padding: 10px 24px;
            border-radius: 30px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
            letter-spacing: 0.5px;
            backdrop-filter: blur(4px);
        }
        .filter-btn:hover, .filter-btn.is-active {
            background: #fff;
            color: #263238;
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }

        /* --- SPONSOR CARD STYLES --- */
        .sponsor-card {
            background: rgba(255, 255, 255, 0.07);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 30px;
            text-decoration: none;
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            height: 100%;
            animation: fadeIn 0.6s ease-out forwards;
            opacity: 0;
            position: relative;
            overflow: hidden;
        }

        /* Hover Effect: Glass Shine */
        .sponsor-card::before {
            content: ''; position: absolute; top: 0; left: -100%; width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
            transition: 0.5s; pointer-events: none;
        }
        .sponsor-card:hover::before { left: 100%; }
        .sponsor-card:hover {
            transform: translateY(-10px);
            background: rgba(255, 255, 255, 0.12);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            border-color: rgba(255, 255, 255, 0.3);
        }

        /* Avatar / Logo Placeholder */
        .sponsor-avatar {
            width: 80px; height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ffd700, #fdb931); /* Gold Gradient */
            display: flex; align-items: center; justify-content: center;
            font-size: 2rem; font-weight: 800; color: #fff;
            margin-bottom: 20px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        
        /* Different colors for avatar based on random CSS logic usually, but hardcoded gold for now */

        .sponsor-sector {
            background: rgba(255, 255, 255, 0.15);
            padding: 5px 15px;
            border-radius: 15px;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
            margin-bottom: 15px;
            color: #81d4fa;
        }

        .sponsor-name { font-size: 1.5rem; font-weight: 700; margin-bottom: 10px; color: #fff; }
        .sponsor-contact { font-size: 0.9rem; color: #cfd8dc; margin-bottom: 20px; word-break: break-all; }
        .sponsor-contact i { margin-right: 8px; color: #81d4fa; }

        .sponsor-footer {
            margin-top: auto; width: 100%;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 20px;
            display: flex; justify-content: space-between; align-items: center;
        }

        .contribution-badge {
            display: flex; flex-direction: column; align-items: flex-start;
        }
        .contribution-label { font-size: 0.7rem; text-transform: uppercase; opacity: 0.7; }
        .contribution-value { font-size: 1.1rem; font-weight: 700; color: #69f0ae; } /* Green for money */

        .contact-btn {
            background: white; color: #263238;
            width: 40px; height: 40px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            transition: 0.3s; text-decoration: none;
        }
        .contact-btn:hover { background: #81d4fa; color: white; transform: rotate(15deg); }

        .page-title-area { text-align: center; margin-bottom: 60px; }
        .page-title-area .title { font-size: 3.5rem; font-weight: 800; margin-bottom: 10px; }
        .page-title-area .sub { color: #81d4fa; letter-spacing: 2px; text-transform: uppercase; font-weight: 700; }
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
                                <li class="single-items"><a class="navmain" href="index.html">Home</a></li>
                                <li class="single-items"><a class="navmain" href="challenges.php">Challenges</a></li>
                                <li class="single-items"><a class="navmain" href="sponsors.php">Partners</a></li> <li class="single-items"><a class="single" href="contact.html">Contact</a></li>
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
                        <span class="sub">Zitouna Ecosystem</span>
                        <h3 class="title">Our Valued Partners</h3>
                        <p class="disc">Driving innovation and supporting the next generation of tech leaders.</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="quiz-filter-controls">
                        <ul id="sector-filters" class="quiz-filter-group">
                            <li><button class="filter-btn is-active" data-filter="*">All Sectors</button></li>
                            <?php foreach ($uniqueSectors as $sector): ?>
                                <?php 
                                    // Create a safe class name from the sector string
                                    $sectorSelector = strtolower(str_replace(' ', '-', preg_replace("/[^A-Za-z0-9 ]/", '', $sector))); 
                                ?>
                                <li><button class="filter-btn" data-filter=".<?php echo $sectorSelector; ?>">
                                    <?php echo htmlspecialchars($sector); ?>
                                </button></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row g-5 mt--20" id="sponsor-grid">

                <?php if (empty($allSponsors)): ?>
                    <div class="col-12">
                        <div class="text-center p-5" style="background: rgba(255,255,255,0.05); border-radius: 20px;">
                            <h3>No sponsors found yet.</h3>
                            <p>Become our first partner!</p>
                        </div>
                    </div>
                <?php else: ?>
                    <?php $delayCounter = 0; ?>
                    <?php foreach ($allSponsors as $sponsor): ?>
                        <?php
                        $delayCounter++;
                        $animationDelay = $delayCounter * 100;
                        
                        // Prepare classes for Isotope filtering
                        $sectorFromDB = $sponsor->getSecteur();
                        $sectorClass = strtolower(str_replace(' ', '-', preg_replace("/[^A-Za-z0-9 ]/", '', $sectorFromDB)));
                        
                        // Get first letter for avatar
                        $initial = substr($sponsor->getNom(), 0, 1);
                        ?>
                    
                        <div class="col-lg-4 col-md-6 col-sm-12 sponsor-card-wrapper <?php echo $sectorClass; ?>">
                            
                            <div class="sponsor-card" style="animation-delay: <?php echo $animationDelay; ?>ms;">
                                
                                <div class="sponsor-avatar">
                                    <?php echo strtoupper($initial); ?>
                                </div>

                                <span class="sponsor-sector"><?php echo htmlspecialchars($sectorFromDB); ?></span>

                                <h4 class="sponsor-name"><?php echo htmlspecialchars($sponsor->getNom()); ?></h4>

                                <div class="sponsor-contact">
                                    <i class="far fa-envelope"></i> <?php echo htmlspecialchars($sponsor->getContact()); ?>
                                </div>

                                <div class="sponsor-footer">
                                    <div class="contribution-badge">
                                        <span class="contribution-label">Contribution</span>
                                        <span class="contribution-value"><?php echo number_format($sponsor->getContribution(), 0, '.', ','); ?> DT</span>
                                    </div>
                                    <a href="mailto:<?php echo htmlspecialchars($sponsor->getContact()); ?>" class="contact-btn" title="Contact Sponsor">
                                        <i class="fas fa-paper-plane"></i>
                                    </a>
                                </div>

                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>
        </div>
    </div>


    <div class="mouse-cursor cursor-outer"></div>
    <div class="mouse-cursor cursor-inner"></div>
    
    <script src="assets/js/vendor/jquery.min.js"></script>
    <script src="assets/js/vendor/jquery-ui.min.js"></script>
    <script src="assets/js/vendor/isotop.min.js"></script> 
    <script src="assets/js/vendor/bootstrap.min.js"></script>
    <script src="assets/js/main.js"></script>

    <script>
        $(window).on('load', function () {
            // Init Isotope
            var $grid = $('#sponsor-grid').isotope({
                itemSelector: '.sponsor-card-wrapper',
                layoutMode: 'fitRows', 
                transitionDuration: '0.6s',
                hiddenStyle: { opacity: 0, transform: 'translateY(20px)' },
                visibleStyle: { opacity: 1, transform: 'translateY(0)' }
            });

            // Filter Logic
            $('.quiz-filter-controls').on('click', '.filter-btn', function () {
                var $this = $(this);
                
                // Toggle Active Class
                $('.quiz-filter-controls .filter-btn').removeClass('is-active');
                $this.addClass('is-active');
                
                // Get Filter Value
                var filterValue = $this.attr('data-filter');
                
                // Apply Filter
                $grid.isotope({ filter: filterValue });
            });
        });
    </script>
</body>
</html>