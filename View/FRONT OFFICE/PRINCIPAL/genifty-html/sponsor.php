<?php
// --- CONFIGURATION & CONTROLLER SETUP ---
require_once __DIR__ . '/../../../../config.php';
require_once __DIR__ . '/../../../../Model/Sponsor.php';
require_once __DIR__ . '/../../../../Controller/SponsorC.php';
require_once __DIR__ . '/../../../../Controller/DonationC.php';

session_start();

$sponsorC = new SponsorC();
$allSponsors = $sponsorC->afficherSponsors()->fetchAll(PDO::FETCH_ASSOC);

$donationController = new DonationC();
$donationsResult = $donationController->listDonations();
$allDonations = $donationsResult->fetchAll(PDO::FETCH_ASSOC);

$uniqueSectors = [];
foreach ($allSponsors as $s) {
    if (!empty($s['secteur'])) {
        $uniqueSectors[$s['secteur']] = true; 
    }
}
$uniqueSectors = array_keys($uniqueSectors);

function getUserAvatar($photo, $nom) {
    if (!empty($photo) && $photo !== 'null' && $photo !== 'default.png') {
        if (filter_var($photo, FILTER_VALIDATE_URL)) {
            return $photo;
        }
        $path = "../../../../uploads/profiles/" . $photo;
        if (file_exists($path)) {
            return $path;
        }
    }
    return 'https://ui-avatars.com/api/?name=' . urlencode($nom) . '&background=random&color=fff';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Zitouna Quests - Our Partners & Impact</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/fab-icon.png">
    <link rel="stylesheet" href="assets/css/plugins/gordita.css">
    <link rel="stylesheet" href="assets/css/plugins/fontawesome-pro-icons.css">
    <link rel="stylesheet" href="assets/css/vendor/swiper.css">
    <link rel="stylesheet" href="assets/css/plugins/unicons.css">
    <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        /* --- Animations (Mirrored from challenge.php) --- */
        @keyframes float { 0% { transform: translateY(0) translateX(0); } 50% { transform: translateY(-20px) translateX(20px); } 100% { transform: translateY(0) translateX(0); } }
        @keyframes moveGradient { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px) scale(0.95); } to { opacity: 1; transform: translateY(0) scale(1); } }

        /* --- Body & Background (Mirrored from challenge.php) --- */
        body.rt_bg-secondary {
            background: linear-gradient(135deg, #14b8a6, #14b8a6, #3ddf43ff, #81c784);
            background-size: 400% 400%;
            animation: moveGradient 25s ease infinite;
            overflow-x: hidden;
            min-height: 100vh;
            color: white;
        }
        
        .bg-animation { position: fixed; top: 0; left: 0; width: 100%; height: 100vh; z-index: -1; overflow: hidden; }
        .bg-animation .blob { position: absolute; border-radius: 50%; filter: blur(80px); opacity: 0.4; animation: float 25s ease-in-out infinite alternate; }
        .bg-animation .blob1 { width: 400px; height: 400px; background: rgba(144, 238, 144, 0.5); top: -50px; left: -100px; animation-duration: 22s; }
        .bg-animation .blob2 { width: 300px; height: 300px; background: rgba(0, 150, 136, 0.4); bottom: -80px; right: -80px; animation-duration: 28s; animation-delay: -5s; }

        /* --- Header Styling (Mirrored from challenge.php) --- */
        .rts-header-area { 
            position: fixed !important; top: 0; left: 0; width: 100%; z-index: 999; 
            background: rgba(20, 60, 20, 0.65) !important; 
            backdrop-filter: blur(12px); 
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1); 
            border-bottom: 1px solid rgba(255, 255, 255, 0.1); 
            transition: transform 0.4s ease-in-out; padding-top: 10px; padding-bottom: 10px; 
        }

        /* --- Filter Buttons (Mirrored from challenge.php) --- */
        .quiz-filter-controls { margin-bottom: 40px; text-align: center; }
        .quiz-filter-group { margin: 0; padding: 0; list-style: none; display: inline-block; margin-bottom: 15px; }
        .quiz-filter-group li { display: inline-block; margin: 0 5px; }
        .filter-btn { background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.25); color: #fff; padding: 10px 20px; border-radius: 20px; cursor: pointer; transition: all 0.3s ease; font-weight: 600; backdrop-filter: blur(5px); }
        .filter-btn:hover { background: rgba(255, 255, 255, 0.25); border-color: rgba(255, 255, 255, 0.5); transform: translateY(-2px); }
        .filter-btn.is-active { background: #fff; color: #1b5e20; box-shadow: 0 5px 15px rgba(76, 175, 80, 0.5); border-color: #fff; }

        /* --- SPONSOR CARD STYLES (Mirrored from challenge.php quiz-card) --- */
        .sponsor-card {
            background: rgba(20, 60, 20, 0.35); backdrop-filter: blur(15px);
            border: 1px solid rgba(100, 255, 100, 0.2); border-radius: 24px; 
            padding: 30px; cursor: pointer; transition: all 0.3s; position: relative;
            height: 100%; display: flex; flex-direction: column;
            animation: fadeIn 0.6s ease-out forwards;
            text-decoration: none; color: #fff;
            text-align: center;
        }
        .sponsor-card:hover { 
            transform: translateY(-10px) scale(1.02); 
            background: rgba(20, 60, 20, 0.5); 
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3), 0 0 30px rgba(100, 255, 100, 0.5);
            border-color: rgba(100, 255, 100, 0.6);
        }

        .sponsor-avatar {
            width: 80px; height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #43a047, #66bb6a);
            display: flex; align-items: center; justify-content: center;
            font-size: 2rem; font-weight: 800; color: #fff;
            margin: 0 auto 20px auto;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            border: 3px solid rgba(255,255,255,0.2);
        }

        .sponsor-sector {
            background: linear-gradient(45deg, #43a047, #66bb6a); 
            color: white; padding: 6px 15px; border-radius: 20px; 
            text-transform: uppercase; letter-spacing: 0.5px; 
            font-size: 0.75rem; font-weight: 700; 
            box-shadow: 0 2px 10px rgba(67, 160, 71, 0.3);
            margin-bottom: 15px;
            display: inline-block;
        }

        .sponsor-name { font-size: 1.6rem; font-weight: 700; margin-bottom: 10px; color: #fff; }
        .sponsor-contact { font-size: 0.9rem; color: #c8e6c9; margin-bottom: 20px; }
        .sponsor-contact i { color: #69f0ae; }

        .sponsor-footer {
            margin-top: auto; padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            display: flex; justify-content: space-between; align-items: center;
        }

        .contribution-badge { text-align: left; }
        .contribution-label { font-size: 0.7rem; text-transform: uppercase; color: #a5d6a7; display: block; }
        .contribution-value { font-size: 1.2rem; font-weight: 800; color: #ffd700; }

        .contact-btn {
            background: linear-gradient(45deg, #43a047, #81c784); color: white;
            width: 40px; height: 40px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            transition: 0.3s; border: none;
        }
        .contact-btn:hover { background: #fff; color: #1b5e20; transform: rotate(15deg) scale(1.1); }

        /* --- CREATIVE DONATIONS SECTION --- */
        .impact-hall-of-fame {
            background: rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            padding: 80px 0;
            margin-top: 100px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }
        
        .donation-bubble {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(100, 255, 100, 0.2);
            border-radius: 20px;
            padding: 25px;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
            height: 100%;
        }
        .donation-bubble:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: scale(1.05);
            border-color: #69f0ae;
        }
        .donation-bubble::before {
            content: '\f004'; font-family: 'Font Awesome 5 Free'; font-weight: 900;
            position: absolute; top: 10px; right: 10px;
            font-size: 3rem; opacity: 0.1; color: #69f0ae;
        }

        .donator-name { font-weight: 800; font-size: 1.2rem; color: #fff; margin-bottom: 5px; }
        .donator-meta { font-size: 0.8rem; color: #a5d6a7; text-transform: uppercase; margin-bottom: 15px; }
        .donator-amount { 
            font-size: 1.5rem; font-weight: 900; 
            background: linear-gradient(to right, #ffd700, #fff);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }

        .page-title-area { text-align: center; margin-bottom: 50px; }
        .page-title-area .title { font-size: 3.5rem; font-weight: 800; color: #fff; text-shadow: 0 4px 15px rgba(0, 0, 0, 0.3); }
        .page-title-area .sub { display: block; font-size: 1.1rem; color: #69f0ae; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 10px; }
        .page-title-area .disc { font-size: 1.3rem; color: #e8f5e9; opacity: 0.95; }

        /* Search Area (Mirrored) */
        .search-area {
            background: rgba(0,0,0,0.2);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 30px;
            padding: 5px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .search-area input {
            background: transparent;
            border: none;
            color: #fff;
            padding: 8px 0;
            width: 200px;
        }
        .search-area input:focus { outline: none; }
    </style>
</head>

<body class="rt_bg-secondary">

    <div class="bg-animation">
        <div class="blob blob1"></div>
        <div class="blob blob2"></div>
    </div>

    <!-- Header -->
    <div id="mainHeader" class="rts-header-area header-inner-one">
        <div class="container-header">
            <div class="row align-items-center ptb_sm--20 padding-controler-header">
                <div class="col-xl-2 col-lg-4 col-md-4 col-sm-12">
                    <div class="header-left">
                        <a href="index.php" class="logo">
                            <img src="assets/images/logo/logo3.png" alt="Zitouna Logo">
                        </a>
                    </div>
                </div>
                <div class="col-xl-5 d-xl-block d-none">
                    <div class="main-menu-wrapepr">
                        <nav class="mainmenu-nav d-none d-xl-block">
                            <ul class="main-menu">
                                <li><a class="navmain" href="index.php">Home</a></li>
                                <li><a class="navmain" href="quiz.php">Quiz</a></li>
                                <li><a class="navmain" href="challenge.php">Challenge</a></li>
                                <li><a class="navmain" href="forum.php">Forum</a></li>
                                <li><a class="navmain" href="sponsor.php">Sponsor</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="col-xl-5 col-lg-8 col-md-8 col-sm-12">
                    <div class="header-right">
                        <div class="search-area d-none d-md-flex">
                            <input type="text" id="sponsorSearch" placeholder="Find a partner...">
                            <i class="far fa-search"></i>
                        </div>
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <div class="user-info-header" style="display: flex; align-items: center; gap: 12px; margin-left: 20px; background: rgba(255,255,255,0.1); padding: 5px 15px; border-radius: 30px;">
                                <img src="<?= getUserAvatar($_SESSION['user_image'], $_SESSION['user_nom']) ?>" alt="User" style="width: 35px; height: 35px; border-radius: 50%; object-fit: cover; border: 2px solid #69f0ae;">
                                <span style="font-weight: 700; font-size: 0.9rem;"><?= htmlspecialchars($_SESSION['user_nom']) ?></span>
                                <a href="../../../../Controller/logout.php" class="rts-btn btn-primary" style="padding: 6px 12px; font-size: 0.7rem;">Logout</a>
                            </div>
                        <?php else: ?>
                            <a href="login.php" class="rts-btn btn-primary">Login</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="rts-explore-area rts-section-gap" style="padding-top: 250px; position: relative; z-index: 2;">
        <div class="container">

            <div class="row">
                <div class="col-12">
                    <div class="page-title-area">
                        <span class="sub">Zitouna Ecosystem</span>
                        <h1 class="title">Our Strategic Partners</h1>
                        <p class="disc">Driving innovation and rewarding excellence in our community.</p>
                    </div>
                </div>
            </div>

            <!-- Sector Filters (Mirrored Style) -->
            <div class="row">
                <div class="col-12">
                    <div class="quiz-filter-controls">
                        <ul id="sector-filters" class="quiz-filter-group">
                            <li><button class="filter-btn is-active" data-filter="*">All Sectors</button></li>
                            <?php foreach ($uniqueSectors as $sector): ?>
                                <?php 
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

            <!-- Sponsors Grid -->
            <div class="row g-5 mt--20" id="sponsor-grid">
                <?php if (empty($allSponsors)): ?>
                    <div class="col-12 text-center">
                        <div class="sponsor-card" style="padding: 100px;">
                            <i class="fas fa-handshake-slash fa-4x mb-4 opacity-20" style="color: #69f0ae;"></i>
                            <h3>No active partners yet.</h3>
                            <p>Become the first to support our mission!</p>
                        </div>
                    </div>
                <?php else: ?>
                    <?php $delay = 0; foreach ($allSponsors as $sponsor): 
                        $sector = $sponsor['secteur'] ?? 'General';
                        $name = $sponsor['nom'] ?? 'Anonymous';
                        $contact = $sponsor['contact'] ?? 'N/A';
                        $contribution = $sponsor['contribution'] ?? 0;
                        
                        $sectorClass = strtolower(str_replace(' ', '-', preg_replace("/[^A-Za-z0-9 ]/", '', $sector)));
                        $initial = strtoupper(substr($name, 0, 1));
                    ?>
                        <div class="col-lg-4 col-md-6 col-sm-12 sponsor-card-wrapper <?php echo $sectorClass; ?>">
                            <div class="sponsor-card" style="animation-delay: <?= $delay += 100 ?>ms;">
                                <div class="sponsor-avatar"><?= $initial ?></div>
                                <span class="sponsor-sector"><?= htmlspecialchars($sector) ?></span>
                                <h4 class="sponsor-name"><?= htmlspecialchars($name) ?></h4>
                                <div class="sponsor-contact">
                                    <i class="far fa-envelope me-2"></i> <?= htmlspecialchars($contact) ?>
                                </div>
                                <div class="sponsor-footer">
                                    <div class="contribution-badge">
                                        <span class="contribution-label">Total Impact</span>
                                        <span class="contribution-value"><?= number_format((float)$contribution, 0) ?> DT</span>
                                    </div>
                                    <a href="mailto:<?= htmlspecialchars($contact) ?>" class="contact-btn">
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

    <!-- IMPACT HALL OF FAME -->
    <section class="impact-hall-of-fame" style="position: relative; z-index: 2;">
        <div class="container">
            <div class="row mb-5 text-center">
                <div class="col-12">
                    <span class="sub" style="color: #ffd700; font-weight: 800; letter-spacing: 3px; text-transform: uppercase;">Hall of Fame</span>
                    <h2 class="title" style="font-size: 3rem; font-weight: 900; margin-top: 10px; color: #fff;">Community Heroes</h2>
                    <p class="disc" style="max-width: 700px; margin: 20px auto; color: #e8f5e9;">Celebrating those who turn their achievements into real-world change.</p>
                </div>
            </div>
            
            <div class="row g-4">
                <?php if (empty($allDonations)): ?>
                    <div class="col-12 text-center opacity-50">
                        <p>No donations recorded yet. Complete challenges to earn points and donate!</p>
                    </div>
                <?php else: 
                    // Take the most recent 8 donations for a creative grid
                    $displayDonations = array_slice($allDonations, 0, 8);
                    foreach ($displayDonations as $don): 
                ?>
                    <div class="col-lg-3 col-md-6">
                        <div class="donation-bubble">
                            <div class="donator-name"><?= htmlspecialchars($don['nom_donateur']) ?></div>
                            <div class="donator-meta"><?= htmlspecialchars($don['type_don']) ?></div>
                            <div class="donator-amount"><?= number_format($don['montant'], 0) ?> DT</div>
                            <div style="font-size: 0.7rem; margin-top: 15px; opacity: 0.6;">
                                <i class="far fa-calendar-alt me-1"></i> <?= date('M d, Y', strtotime($don['date_don'])) ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="row mt-5 text-center">
                <div class="col-12">
                    <div style="background: rgba(255,255,255,0.05); border: 1px dashed rgba(105, 240, 174, 0.3); border-radius: 20px; padding: 40px; max-width: 800px; margin: 0 auto;">
                        <h4 style="color: #69f0ae; font-weight: 800;">Want to see your name here?</h4>
                        <p style="margin-bottom: 25px;">Earn points by completing quests and challenges, then donate them to your favorite causes!</p>
                        <a href="challenge.php" class="rts-btn btn-primary" style="padding: 15px 40px;">Earn Points Now</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer (Mirrored Style) -->
    <footer class="py-5 mt-5" style="border-top: 1px solid rgba(255,255,255,0.1); background: rgba(0,0,0,0.2);">
        <div class="container text-center">
            <div class="mb-4">
                <img src="assets/images/logo/logo3.png" alt="Logo" style="height: 40px;">
            </div>
            <p class="opacity-50">© 2025 Zitouna Quests Ecosystem. Empowering Impact Through Knowledge.</p>
        </div>
    </footer>

    <script src="assets/js/vendor/jquery.min.js"></script>
    <script src="assets/js/vendor/isotop.min.js"></script> 
    <script src="assets/js/main.js"></script>

    <script>
        $(window).on('load', function () {
            // Header Scroll Logic (Mirrored)
            let lastScrollTop = 0;
            const header = document.getElementById('mainHeader');
            window.addEventListener('scroll', function() {
                let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                if (scrollTop > lastScrollTop && scrollTop > 100) {
                    header.style.transform = 'translateY(-100%)';
                } else {
                    header.style.transform = 'translateY(0)';
                }
                lastScrollTop = scrollTop;
            });

            // Isotope Init
            var $grid = $('#sponsor-grid').isotope({
                itemSelector: '.sponsor-card-wrapper',
                layoutMode: 'fitRows',
                transitionDuration: '0.6s'
            });

            // Filter Logic
            $('.filter-btn').on('click', function () {
                $('.filter-btn').removeClass('is-active');
                $(this).addClass('is-active');
                $grid.isotope({ filter: $(this).attr('data-filter') });
            });

            // Real-time search
            $('#sponsorSearch').on('keyup', function() {
                var val = $(this).val().toLowerCase();
                $('.sponsor-card-wrapper').each(function() {
                    var name = $(this).find('.sponsor-name').text().toLowerCase();
                    if(name.indexOf(val) > -1) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
                $grid.isotope('layout');
            });
        });
    </script>
</body>
</html>
