<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Genifty || - NFT Marketplace Template</title>
    <meta name="robots" content="noindex">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="theme-style-mode" content="1"> <!-- 0 == light, 1 == dark -->

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/fab-icon.png">
    <link rel="stylesheet" href="assets/css/plugins/gordita.css">
    <link rel="stylesheet" href="assets/css/plugins/fontawesome-pro-icons.css">
    <link rel="stylesheet" href="assets/css/vendor/swiper.css">
    <link rel="stylesheet" href="assets/css/plugins/unicons.css">
    <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">
    <!-- style css -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="rt_bg-secondary">

    <!-- start header area -->

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
                                    <a class="navmain" href="quiz.php">Quests</a>
                                </li>
                                <li class="single-items off-arrow">
                                    <a class="navmain" href="take-quiz.php">Quiz</a>
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
    <!-- ENd Header Area -->

    <!-- start Mobile menue -->


    <!-- mobile menu start -->
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
            <!-- nav style Start -->
            <nav>
                <ul class="main-menu">
<li class="single-items off-arrow">
                                    <a class="navmain" href="index.php">Home</a>
                                </li>
                                <li class="single-items off-arrow">
                                    <a class="navmain" href="#">Quests</a>
                                </li>
                                <li class="single-items off-arrow">
                                    <a class="navmain" href="#">Quiz</a>
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
            <!-- nav style hear End -->
        </div>
    </div>
    <!-- mobile menu end -->
    <!-- end mobile menue -->

    <!-- banner area start -->
    <div class="rts-banner-area banner-one rt_bg-secondary bg_tr-image--1">
        <div class="container container-banner-one">
            <div class="row  row-reverce-sm">
                <div class="col-lg-6 order-xl-1 order-lg-2 order-md-2 order-sm-2 pt--175 pb--200 pb_md--100 pb_sm--100">
                    <div class="banner-one-left-inner">
                        <span class="floating-icon icon-book"></span>
    <span class="floating-icon icon-star"></span>
                        <h1 class="title" data-sal-delay="150" data-sal-duration="800" data-sal="slide-up">
                            Learn,Grow,and Make a Real-World <span class="border-1">Impact.</span>
                        </h1>
                        <p class="disc" data-sal-delay="300" data-sal-duration="800" data-sal="slide-up">
                            Transform Your Knowledge into Positive Action. Let's go with "Zitouna Quests: Learn, Grow, and Make a Real-World Impact."
                        </p>
                        <div class="button-group-vedio">
                            <a href="shop.html" class="rts-btn btn-primary" data-sal-delay="600" data-sal-duration="1000" data-sal="slide-up">Explore Quizzes</a>
                            <div class="vedio-play-button-banner">
                                <a id="play-video" class="video-play-button" href="#">
                                    <span></span>
                                    <span class="outer-text">How It Works</span>
                                </a>
                                <div id="video-overlay" class="video-overlay">
                                    <a class="video-overlay-close">×</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 order-xl-2 order-lg-1 order-md-1 order-sm-1">
                    <div class="thumbnail-one one">
                        <img src="assets/images/banner/banner-01.png" alt="Nft-banner">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- banner area start -->

<style>
    /* This CSS creates the "awesome" look for your feature cards.
      It matches the glassy, modern style of your template.
    */
    .rts-feature-card {
        background: rgba(255, 255, 255, 0.1); /* The glassy background */
        backdrop-filter: blur(10px);           /* The blur effect */
        border: 1px solid rgba(255, 255, 255, 0.2); /* Subtle border */
        border-radius: 20px;                   /* Rounded corners */
        padding: 35px 30px; 
        text-align: center;
        height: 100%;                          /* Makes all cards in a row the same height */
        transition: all 0.3s ease-in-out;
    }

    /* This is the "awesome" hover effect */
    .rts-feature-card:hover {
        transform: translateY(-10px);          /* Lifts the card up */
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        background: rgba(255, 255, 255, 0.2);
    }

    .rts-feature-card .icon-area {
        margin-bottom: 30px; /* UPDATED & BIGGER */
    }

    /* This styles the placeholder images */
    .rts-feature-card .icon-area img {
        width: 110px;  /* UPDATED & BIGGER */
        height: 110px; /* UPDATED & BIGGER */
        border-radius: 50%;                    /* Makes the icon circular */
        object-fit: cover;
        /*background: #fff;  - Removed */
        /*padding: 10px;     - Removed */
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }

    .rts-feature-card .title {
        color: #ffffff; /* White text for the title */
        font-size: 2.5 rem; /* UPDATED & BIGGER */
        font-weight: 600;
        margin-bottom: 15px; 
    }

    .rts-feature-card .disc {
        color: #eeeeee; /* Lighter text for the description */
        font-size: 1.7rem; /* UPDATED & BIGGER */
        line-height: 1.6;
    }
</style>

<div class="rts-become-creator rts-section-gap">
    <div class="container">
        <div class="row">
            <div class="title-area text-center">
                <span class="sub">Our Core Features</span>
                <h3 class="title">Discover How You Can Learn, Engage, and Impact</h3>
            </div>
        </div>
        
        <div class="row g-5 mt--25">

            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="rts-feature-card"> 
                    <div class="icon-area">
                        <img src="37370.jpg" alt="Quiz System Icon">
                    </div>
                    <h4 class="title">Interactive Quizzes</h4>
                    <p class="disc">Challenge your knowledge with dynamic quizzes that provide instant feedback and explanations.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="rts-feature-card">
                    <div class="icon-area">
                        <img src="pixnio-4000x6000.jpg" alt="Badges & Rewards Icon">
                    </div>
                    <h4 class="title">Badges & Rewards</h4>
                    <p class="disc">Earn points, unlock stunning badges, and celebrate every milestone in your learning journey.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="rts-feature-card">
                    <div class="icon-area">
                        <img src="44135.jpg" alt="Community Forum Icon">
                    </div>
                    <h4 class="title">Community Forum</h4>
                    <p class="disc">Connect with learners, share ideas, and find inspiration in our vibrant and supportive community.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="rts-feature-card">
                    <div class="icon-area">
                        <img src="52916.jpg" alt="Eco-Challenges Icon">
                    </div>
                    <h5 class="title">Eco-Challenges</h5>
                    <p class="disc">Take your knowledge into the real world. Complete eco-friendly tasks and build a greener planet.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="rts-feature-card">
                    <div class="icon-area">
                        <img src="124347-OQJU42-964.jpg" alt="Donate & Impact Icon">
                    </div>
                    <h4 class="title">Social Impact</h4>
                    <p class="disc">Convert your earned points into real-world donations to charities and causes you care about.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="rts-feature-card">
                    <div class="icon-area">
                        <img src="SL-012322-48100-03.jpg" alt="Progress & Leaderboards Icon">
                    </div>
                    <h4 class="title">Leaderboards</h4>
                    <p class="disc">See where you stand! Compete with friends and climb the ranks on our global leaderboards.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="rts-feature-card">
                    <div class="icon-area">
                        <img src="une-pile-de-livres-pour-la-journee-de-l-education.jpg" alt="Educational Content Icon">
                    </div>
                    <h5 class="title">Rich Content</h5>
                    <p class="disc">Learn your way with a huge library of videos, infographics, and articles managed by experts.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="rts-feature-card">
                    <div class="icon-area">
                        <img src="35824.jpg" alt="Personalized Learning Icon">
                    </div>
                    <h4 class="title">Personalized Path</h4>
                    <p class="disc">Our platform adapts to your skill level, suggesting quizzes and content to help you grow.</p>
                </div>
            </div>
            </div>
        </div>
</div>
   <!-- top Creator End -->

<style>
    /* This CSS makes your new card content look great */
    .explore-wrapper .product-discription {
        /* This ensures the bottom card part has the glassy style */
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-top: 1px solid rgba(255, 255, 255, 0.2);
    }

    .explore-wrapper .product-left .deg-description {
        /* Styles the new description under the title */
        font-size: 0.9rem;
        color: #ffffff;
        margin-bottom: 15px;
        display: block; /* Ensures it takes its own line */
    }
    
    .explore-wrapper .product-right .quest-reward {
        /* Replaces the "ETH Price" style */
        font-size: 1.1rem;
        font-weight: 600;
        color: #ffffff; /* Use your theme's highlight color */
        text-align: right;
    }

    .explore-wrapper .product-right .quest-participants {
        font-size: 0.9rem;
        color: #eeeeee;
        text-align: right;
        margin-top: 5px;
    }

    .explore-wrapper .thumbnail img {
        /* Ensures images are high-quality */
        aspect-ratio: 1 / 1;
        object-fit: cover;
    }

</style>


<div class="rts-explore-area iso-expo-one iso-bg rts-section-gap">
    <div class="container">
        <div class="row align-items-end">
            <div class="col-lg-6">
                <div class="title-area text-start">
                    <span class="sub-title">
                        Our Topics
                    </span>
                    <h3 class="title">Explore Our Quests & Challenges</h3>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="button-group filters-button-group d-flex flex-wrap mt_md--30 mt_sm--30">
                    <button class="button is-checked ml_md--0 ml_sm--0" data-filter="*">All</button>
                    <img src="assets/images/icon/sm/Vector01.png" alt="separator">
                    <button class="button" data-filter=".quizzes">Quizzes</button>
                    <img src="assets/images/icon/sm/Vector01.png" alt="separator">
                    <button class="button" data-filter=".challenges">Challenges</button>
                    <img src="assets/images/icon/sm/Vector01.png" alt="separator">
                    <button class="button" data-filter=".environment">Environment</button>
                    <img src="assets/images/icon/sm/Vector01.png" alt="separator">
                    <button class="button" data-filter=".science">Science</button>
                    <img src="assets/images/icon/sm/Vector01.png" alt="separator">
                    <button class="button" data-filter=".history">History</button>
                </div>
            </div>
        </div>
        <div class="row mt--50">
            <div class="col-lg-12 filter-explore">
                <div class="grid">

                    <div class="element-item quizzes environment" data-category="transition">
                        <div class="explore-wrapper">
                            <div class="trending-items_wrapper">
                                <div class="thumbnail">
                                    <a href="quest-details.html">
                                        <img src="https://placehold.co/400x400/00C49F/ffffff?text=🌍" alt="Green Guardians Quest">
                                    </a>
                                    <span class="heart">
                                        <img src="assets/images/live-bidding/ico/01.png" alt="Favorite">
                                    </span>
                                    </div>
                                <div class="product-discription">
                                    <div class="product-left">
                                        <a href="quest-details.html">
                                            <h5 class="title">Green Guardians Quest</h5>
                                        </a>
                                        <span class="deg-description" style="color: #eeeeee;">
                                            Test your knowledge on climate change.
                                        </span>
                                        <a class="rts-btn btn-secondary radious-5" href="quest-details.html">Start Quiz</a>
                                    </div>
                                    <div class="product-right">
                                        <h5 class="quest-reward">+50 Points</h5>
                                        <span class="quest-participants">1,280+ Learners</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="element-item quizzes science" data-category="metalloid">
                        <div class="explore-wrapper">
                            <div class="trending-items_wrapper">
                                <div class="thumbnail">
                                    <a href="quest-details.html"><img src="https://placehold.co/400x400/0088FE/ffffff?text=🔬" alt="Future Innovators Quiz"></a>
                                    <span class="heart">
                                        <img src="assets/images/live-bidding/ico/01.png" alt="Favorite">
                                    </span>
                                </div>
                                <div class="product-discription">
                                    <div class="product-left">
                                        <a href="quest-details.html">
                                            <h5 class="title">Future Innovators</h5>
                                        </a>
                                        <span class="deg-description" style="color: #eeeeee;">
                                            Explore breakthroughs in science & tech.
                                        </span>
                                        <a class="rts-btn btn-secondary radious-5" href="quest-details.html">Start Quiz</a>
                                    </div>
                                    <div class="product-right">
                                        <h5 class="quest-reward">+75 Points</h5>
                                        <span class="quest-participants">950+ Learners</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="element-item challenges community" data-category="post-transition">
                        <div class="explore-wrapper">
                            <div class="trending-items_wrapper">
                                <div class="thumbnail">
                                    <a href="challenge-details.html"><img src="https://placehold.co/400x400/FF8042/ffffff?text=🤝" alt="Local Impact Heroes Challenge"></a>
                                    <span class="heart">
                                        <img src="assets/images/live-bidding/ico/01.png" alt="Favorite">
                                    </span>
                                    </div>
                                <div class="product-discription">
                                    <div class="product-left">
                                        <a href="challenge-details.html">
                                            <h5 class="title">Local Impact Heroes</h5>
                                        </a>
                                        <span class="deg-description" style="color: #eeeeee;">
                                          Organize a cleanup in your area.
                                        </span>
                                        <a class="rts-btn btn-secondary radious-5" href="challenge-details.html">View Challenge</a>
                                    </div>
                                    <div class="product-right">
                                        <h5 class="quest-reward">+200 Points</h5>
                                        <span class="quest-participants">410+ Participants</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="element-item quizzes history" data-category="post-transition">
                        <div class="explore-wrapper">
                            <div class="trending-items_wrapper">
                                <div class="thumbnail">
                                    <a href="quest-details.html"><img src="https://placehold.co/400x400/FFBB28/ffffff?text=🏛️" alt="World Heritage Quest"></a>
                                    <span class="heart">
                                        <img src="assets/images/live-bidding/ico/01.png" alt="Favorite">
                                    </span>
                                </div>
                                <div class="product-discription">
                                    <div class="product-left">
                                        <a href="quest-details.html">
                                            <h5 class="title">World Heritage Journey</h5>
                                        </a>
                                        <span class="deg-description" style="color: #eeeeee;">
                                            Explore ancient wonders and cultures.
                                        </span>
                                        <a class="rts-btn btn-secondary radious-5" href="quest-details.html">Start Quiz</a>
                                    </div>
                                    <div class="product-right">
                                        <h5 class="quest-reward">+50 Points</h5>
                                        <span class="quest-participants">1,020+ Learners</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="element-item challenges environment" data-category="post-transition">
                        <div class="explore-wrapper">
                            <div class="trending-items_wrapper">
                                <div class="thumbnail">
                                    <a href="challenge-details.html"><img src="https://placehold.co/400x400/28a745/ffffff?text=♻️" alt="Eco-Habit Builder Challenge"></a>
                                    <span class="heart">
                                        <img src="assets/images/live-bidding/ico/01.png" alt="Favorite">
                                    </span>
                                    </div>
                                <div class="product-discription">
                                    <div class="product-left">
                                        <a href="challenge-details.html">
                                            <h5 class="title">Eco-Habit Builder</h5>
                                        </a>
                                        <span class="deg-description" style="color: #eeeeee;">
                                            Complete a 7-day waste reduction task.
                                        </span>
                                        <a class="rts-btn btn-secondary radious-5" href="challenge-details.html">View Challenge</a>
                                    </div>
                                    <div class="product-right">
                                        <h5 class="quest-reward">+150 Points</h5>
                                        <span class="quest-participants">760+ Participants</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="element-item quizzes science" data-category="metalloid">
                        <div class="explore-wrapper">
                            <div class="trending-items_wrapper">
                                <div class="thumbnail">
                                    <a href="quest-details.html"><img src="https://placehold.co/400x400/9C27B0/ffffff?text=🧬" alt="Biology Buffs Quiz"></a>
                                    <span class="heart">
                                        <img src="assets/images/live-bidding/ico/01.png" alt="Favorite">
                                    </span>
                                </div>
                                <div class="product-discription">
                                    <div class="product-left">
                                        <a href="quest-details.html">
                                            <h5 class="title">Biology Buffs</h5>
                                        </a>
                                        <span class="deg-description" style="color: #eeeeee;">
                                           Dive into the wonders of life sciences.
                                        </span>
                                        <a class="rts-btn btn-secondary radious-5" href="quest-details.html">Start Quiz</a>
                                    </div>
                                    <div class="product-right">
                                        <h5 class="quest-reward">+50 Points</h5>
                                        <span class="quest-participants">810+ Learners</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
            </div>
        </div>
        <div class="row mt--45">
            <div class="col-12 text-center">
                <a class="rts-btn btn-secondary filter-explore" href="all-quests.html">View All Quests</a>
            </div>
        </div>
    </div>
</div>
    <!-- isotop explore style end -->
<div class="rts-categories-area rts-section-gap bg-shape-one rt_bg-secondary">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="title-area text-center">
                        <span class="sub-title">
                            Our Topics
                        </span>
                        <h3 class="title">Explore Our Quiz Categories</h3>
                    </div>
                </div>
            </div>
            <div class="row mt--50">
                
                <div class="col-lg-20 margin-control-categoriues">
                    <div class="categori-inner text-center" data-sal-delay="150" data-sal-duration="600" data-sal="slide-up">
                        <img src="assets/images/environment.png" alt="Environment Category">
                        <p class="name">Environment</p>
                        <a class="over-link" href="category-environment.html"></a>
                    </div>
                </div>
                <div class="col-lg-20 margin-control-categoriues">
                    <div class="categori-inner text-center" data-sal-delay="150" data-sal-duration="800" data-sal="slide-up">
                        <img src="assets/images/science.png" alt="Science Category">
                        <p class="name">Science</p>
                        <a class="over-link" href="category-science.html"></a>
                    </div>
                </div>
                <div class="col-lg-20 margin-control-categoriues">
                    <div class="categori-inner text-center" data-sal-delay="150" data-sal-duration="1000" data-sal="slide-up">
                        <img src="assets/images/history.png" alt="History Category">
                        <p class="name">History</p>
                        <a class="over-link" href="category-history.html"></a>
                    </div>
                </div>
                <div class="col-lg-20 margin-control-categoriues">
                    <div class="categori-inner text-center" data-sal-delay="150" data-sal-duration="1200" data-sal="slide-up">
                        <img src="assets/images/social.png" alt="Social Impact Category">
                        <p class="name">Social Impact</p>
                        <a class="over-link" href="category-social-impact.html"></a>
                    </div>
                </div>
                <div class="col-lg-20 margin-control-categoriues">
                    <div class="categori-inner text-center" data-sal-delay="150" data-sal-duration="1400" data-sal="slide-up">
                        <img src="assets/images/growth.png" alt="Personal Growth Category">
                        <p class="name">Personal Growth</p>
                        <a class="over-link" href="category-personal-growth.html"></a>
                    </div>
                </div>
                </div>
        </div>
    </div>
    <!-- categories section end -->
<style>
    /* This CSS makes the new activity feed look amazing! */
    .product-discription .product-left .activity-text {
        /* New class for the main action text */
        font-size: 1.6rem;
        font-weight: 600;
        color: #ffffff; /* White title */
        display: block; /* Make it a block element */
        margin-bottom: 5px;
    }

    .product-discription .product-left .username {
        /* Re-styling the original 'deg' class */
        color: #ffffff; /* Your theme's highlight color */
        font-weight: 500;
        font-size: 1.6rem;
    }

    .product-discription .product-right .reward-text {
        /* This replaces the "ETH Price" */
        font-size: 1.4rem;
        font-weight: 700;
        color: #FFBB28; /* A gold/yellow for rewards */
        text-align: right;
    }

    .trending-items_wrapper .thumbnail .user-avatar {
        /* Makes the user image circular */
        border-radius: 50%;
        width: 100%;
        height: 100%;
        object-fit: cover;
        border: 4px solid rgba(255, 255, 255, 0.2);
    }

    /* This adds the "LIVE" pulsing dot */
    .title-area .sub.live-indicator {
        position: relative;
        padding-left: 20px;
    }

    .title-area .sub.live-indicator::before {
        content: '';
        width: 10px;
        height: 10px;
        background-color: #FF4500; /* Bright red/orange */
        border-radius: 50%;
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        box-shadow: 0 0 10px #FF4500, 0 0 5px #FF4500;
        animation: pulse 1.5s infinite;
    }

    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }
</style>


<div class="rts-live-bidding-area rts-section-gap rts-bidding-bg">
    <div class="container">
        <div class="row">
            <div class="col-12 d-flex align-items-center swiper-btn-title-area">
                <div class="title-area text-start">
                    <span class="sub live-indicator">
                        Community Hub
                    </span>
                    <h3 class="title">Live Activity Feed</h3>
                </div>
                <div class="slider-navigation">
                    <div class="swiper-button-prev slider-btn prev"><span></span></div>
                    <div class="swiper-button-next slider-btn next"><span></span></div>
                </div>
            </div>
        </div>
        <div class="row mt--55">
            <div class="col-12">
                <div class="slider-div">
                    <div class="swiper LiveBidding">
                        <div class="swiper-wrapper">
                            
                            <div class="swiper-slide">
                                <div class="trending-items_wrapper live-tranding-count">
                                    <div class="thumbnail">
                                        <a href="profile.html">
                                            <img src="assets/images/collectors/collectors-01.png" alt="User Avatar" class="user-avatar">
                                        </a>
                                        <span class="heart">
                                            <img src="assets/images/live-bidding/ico/01.png" alt="Like">
                                        </span>
                                    </div>
                                    <div class="product-discription">
                                        <div class="product-left">
                                            <span class="activity-text" style="color: #ffffff;">Earned a New Badge!</span>
                                            <span class="username" style="color: #ffffff;">@EcoWarrior</span>
                                            <a class="rts-btn btn-secondary radious-5" href="profile.html">View Profile</a>
                                        </div>
                                        <div class="product-right">
                                            <h5 class="reward-text">"Green Leaf"</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="trending-items_wrapper live-tranding-count">
                                    <div class="thumbnail">
                                        <a href="profile.html">
                                            <img src="assets/images/collectors/collectors-02.png" alt="User Avatar" class="user-avatar">
                                        </a>
                                        <span class="heart">
                                            <img src="assets/images/live-bidding/ico/01.png" alt="Like">
                                        </span>
                                        </div>
                                    <div class="product-discription">
                                        <div class="product-left">
                                            <span class="activity-text" style="color: #ffffff;">Completed a Quiz</span>
                                            <span class="username" style="color: #ffffff;">@ScienceBuff</span>
                                            <a class="rts-btn btn-secondary radious-5" href="quest-details.html">See Quest</a>
                                        </div>
                                        <div class="product-right">
                                            <h5 class="reward-text">+75 Points</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="trending-items_wrapper live-tranding-count">
                                    <div class="thumbnail">
                                        <a href="profile.html">
                                            <img src="assets/images/collectors/collectors-03.png" alt="User Avatar" class="user-avatar">
                                        </a>
                                        <span class="heart">
                                            <img src="assets/images/live-bidding/ico/01.png" alt="Like">
                                        </span>
                                    </div>
                                    <div class="product-discription">
                                        <div class="product-left">
                                            <span class="activity-text" style="color: #ffffff;">Made a Donation!</span>
                                            <span class="username" style="color: #ffffff;">@CommunityFirst</span>
                                            <a class="rts-btn btn-secondary radious-5" href="impact.html">See Impact</a>
                                        </div>
                                        <div class="product-right">
                                            <h5 class="reward-text">100 Points</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="trending-items_wrapper live-tranding-count">
                                    <div class="thumbnail">
                                        <a href="profile.html">
                                            <img src="assets/images/collectors/collectors-04.png" alt="User Avatar" class="user-avatar">
                                        </a>
                                        <span class="heart">
                                            <img src="assets/images/live-bidding/ico/01.png" alt="Like">
                                        </span>
                                    </div>
                                    <div class="product-discription">
                                        <div class="product-left">
                                            <span class="activity-text" style="color: #ffffff;">Joined the Forum</span>
                                            <span class="username" style="color: #ffffff;">@NewLearner</span>
                                            <a class="rts-btn btn-secondary radious-5" href="forum.html">Say Hello!</a>
                                        </div>
                                        <div class="product-right">
                                            <h5 class="reward-text">Welcome!</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="trending-items_wrapper live-tranding-count">
                                    <div class="thumbnail">
                                        <a href="profile.html">
                                            <img src="assets/images/collectors/collectors-05.png" alt="User Avatar" class="user-avatar">
                                        </a>
                                        <span class="heart">
                                            <img src="assets/images/live-bidding/ico/01.png" alt="Like">
                                        </span>
                                    </div>
                                    <div class="product-discription">
                                        <div class="product-left">
                                            <span class="activity-text" style="color: #ffffff;">Finished a Challenge</span>
                                            <span class="username" style="color: #ffffff;">@GreenThumb</span>
                                            <a class="rts-btn btn-secondary radious-5" href="challenge.html">See Challenge</a>
                                        </div>
                                        <div class="product-right">
                                            <h5 class="reward-text">+150 Points</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    /* This CSS makes the new counter section look amazing! */
    .rts-counter-up-area {
        /* This uses the same glassy background as your other sections */
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        border-top: 1px solid rgba(255, 255, 255, 0.2);
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        padding: 120px 0; /* UPDATED & BIGGER - Even more space */
    }

    .counter-up-item {
        text-align: center;
        padding: 20px;
    }

    .counter-up-item .icon-area {
        margin-bottom: 35px; /* UPDATED & BIGGER */
    }

    /* Styles the icons */
    .counter-up-item .icon-area img {
        width: 160px;  /* UPDATED & BIGGER (was 100px) */
        height: 160px; /* UPDATED & BIGGER (was 100px) */
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        padding: 25px; /* UPDATED & BIGGER */
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        object-fit: cover; /* Ensures your images fit well */
    }

    /* This is the main number */
    .counter-up-item .counter-value {
        font-size: 8rem; /* UPDATED & BIGGER (was 5rem) */
        font-weight: 700;
        color: #ffffff; 
        line-height: 1;
        display: inline-block;
        position: relative;
    }

    /* This adds the "k" or "+" sign AFTER the number */
    .counter-up-item .counter-value::after {
        content: attr(data-suffix); /* Reads the "k" or "+" from the HTML */
        font-size: 6rem; /* UPDATED & BIGGER (was 3.5rem) */
        font-weight: 600;
        color: #ffffff; 
        position: relative;
        left: 10px; /* UPDATED & BIGGER */
    }

    .counter-up-item .counter-label {
        font-size: 1.75rem; /* UPDATED & BIGGER (was 1.25rem) */
        color: #ffffff; /* White text */
        margin-top: 25px; /* UPDATED & BIGGER */
        font-weight: 500;
    }
</style>

<div class="rts-counter-up-area rts-section-gap">
    <div class="container">
        <div class="row g-5">

            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="counter-up-item">
                    <div class="icon-area">
                        <img src="assets/images/users.png" alt="Active Users">
                    </div>
                    <h3 class="counter-value" data-suffix="k+">5</h3>
                    <p class="counter-label">Active Learners</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="counter-up-item">
                    <div class="icon-area">
                        <img src="assets/images/quests.png" alt="Quests Completed">
                    </div>
                    <h3 class="counter-value" data-suffix="k+">20</h3>
                    <p class="counter-label">Quests Completed</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="counter-up-item">
                    <div class="icon-area">
                        <img src="assets/images/eco.png" alt="Eco-Challenges">
                    </div>
                    <h3 class="counter-value" data-suffix="+">1500</h3>
                    <p class="counter-label">Eco-Challenges Finished</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="counter-up-item">
                    <div class="icon-area">
                        <img src="assets/images/points.png" alt="Points Donated">
                    </div>
                    <h3 class="counter-value" data-suffix="k+">500</h3>
                    <p class="counter-label">Points Donated to Charity</p>
                </div>
            </div>

        </div>
    </div>
</div>
    <style>
    /* This CSS makes your initiatives look "amazing" */
    
    /* This makes the image thumbnails look better */
    .rts-collection-main-wrapper .thumbnail-lg,
    .rts-collection-main-wrapper .thumbnail-sm {
        border-radius: 10px; /* Add rounded corners to all images */
        overflow: hidden;
        display: block;
        background: rgba(255, 255, 255, 0.1);
    }
    .rts-collection-main-wrapper .thumbnail-lg img,
    .rts-collection-main-wrapper .thumbnail-sm img {
        width: 100%;
        height: 100%;
        object-fit: cover; /* Prevents stretching */
        transition: transform 0.3s ease;
    }
    .rts-collection-main-wrapper a:hover img {
        transform: scale(1.05); /* Subtle zoom on hover */
    }

    /* This styles the bottom details card */
    .colection-details {
        align-items: center;
        width: 100%;
    }
    .colection-details .left span {
        /* This is the "Partner:" text */
        color: #eeeeee; /* Lighter text */
        font-size: 0.9rem;
    }
    .colection-details .left .title {
        /* This is the Initiative Title */
        color: #ffffff;
        font-size: 1.4rem;
        font-weight: 600;
    }
    .colection-details .right button {
        /* This transforms the "25 Items" into a "Goal" button */
        background-color: #FFBB28; /* Your theme's bright green */
        color: #ffffff;
        font-weight: 600;
        font-size: 1.5rem;
        border: none;
        padding: 10px 15px;
        border-radius: 20px;
        cursor: default; /* It's not a real button, just a tag */
        transition: all 0.3s ease;
    }
    .colection-details .right button:hover {
        background-color: #ffffff;
        color: #FFBB28;
        font-size: 1.5rem;
    }
</style>


<div class="rts-collection-area rts-section-gap">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="title-area text-center">
                    <span class="sub">Where Your Points Make a Difference</span>
                    <h3 class="title">Popular Impact Initiatives</h3>
                </div>
            </div>
        </div>
        <div class="row g-5 mt--40">
            
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="rts-collection-main-wrapper">
                    <div class="row mb--15">
                        <div class="col-6">
                            <a href="initiative-details.html" class="thumbnail-lg">
                                <img src="assets/images/charity/images_8.jpg" alt="Lush Forest">
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="initiative-details.html" class="thumbnail-lg">
                                <img src="assets/images/charity/images_9.jpg" alt="Volunteer Planting">
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-4">
                            <a href="initiative-details.html" class="thumbnail-sm">
                                <img src="assets/images/charity/Plant-a-tree-tile.jpg" alt="Close-up of a sapling">
                            </a>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-4">
                            <a href="initiative-details.html" class="thumbnail-sm">
                                <img src="assets/images/charity/planting-your-tree.jpg.webp" alt="Map of impact area">
                            </a>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-4">
                            <a href="initiative-details.html" class="thumbnail-sm">
                                <img src="assets/images/charity/images_13.jpg" alt="CO2 Reduction">
                            </a>
                        </div>
                    </div>
                    <div class="row mt--20">
                        <div class="colection-details">
                            <div class="left" style="color: #FFBB28;">
                                <span style="color: #FFBB28;">Partner: GreentheWorld</span>
                                <a href="initiative-details.html">
                                    <h5 class="title">Plant-a-Tree Fund</h5>
                                </a>
                            </div>
                            <div class="right">
                                <button>Goal: 5,000 Trees</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="rts-collection-main-wrapper">
                    <div class="row mb--15">
                        <div class="col-6">
                            <a href="initiative-details.html" class="thumbnail-lg">
                                <img src="assets/images/charity/images_10.jpg" alt="Clean Water">
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="initiative-details.html" class="thumbnail-lg">
                                <img src="assets/images/charity/images_11.jpg" alt="Water Well">
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-4">
                            <a href="initiative-details.html" class="thumbnail-sm">
                                <img src="assets/images/charity/images_12.jpg" alt="Happy community">
                            </a>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-4">
                            <a href="initiative-details.html" class="thumbnail-sm">
                                <img src="assets/images/charity/india1.avif" alt="Map of impact area">
                            </a>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-4">
                            <a href="initiative-details.html" class="thumbnail-sm">
                                <img src="assets/images/charity/vue-de-mains-realistes-touchant-l-eau-claire-qui-coule.jpg" alt="Water Filter">
                            </a>
                        </div>
                    </div>
                    <div class="row mt--20">
                        <div class="colection-details">
                            <div class="left" >
                                <span style="color: #FFBB28;">Partner: WaterFirst</span>
                                <a href="initiative-details.html">
                                    <h5 class="title">Clean Water Project</h5>
                                </a>
                            </div>
                            <div class="right">
                                <button>Goal: 10 Wells</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="rts-collection-main-wrapper">
                    <div class="row mb--15">
                        <div class="col-6">
                            <a href="initiative-details.html" class="thumbnail-lg">
                                <img src="assets/images/charity/images_7.jpg" alt="School children">
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="initiative-details.html" class="thumbnail-lg">
                                <img src="assets/images/charity/images_14.jpg" alt="School building">
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-4">
                            <a href="initiative-details.html" class="thumbnail-sm">
                                <img src="assets/images/charity/images_15.jpg" alt="Stack of books">
                            </a>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-4">
                            <a href="initiative-details.html" class="thumbnail-sm">
                                <img src="assets/images/charity/images_16.jpg" alt="Pencils and supplies">
                            </a>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-4">
                            <a href="initiative-details.html" class="thumbnail-sm">
                                <img src="assets/images/charity/School-Supplies-for-Kids-Press-Release-Pic.jpg" alt="Graduation cap">
                            </a>
                        </div>
                    </div>
                    <div class="row mt--20">
                        <div class="colection-details">
                            <div class="left">
                                <span style="color: #FFBB28;">Partner: EduFuture</span>
                                <a href="initiative-details.html">
                                    <h5 class="title">School Supplies Fund</h5>
                                </a>
                            </div>
                            <div class="right">
                                <button>Goal: 500 Kits</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
    </div>
</div>
<style>
    /* This CSS makes the new "Hall of Fame" section look amazing */
    .rts-achievement-area .title-area h3.title {
        font-size: 2.5rem; /* A slightly smaller title for a sub-section */
    }
    
    .rts-achievement-area .title-area p.subtitle {
        font-size: 1.1rem;
        color: #eeeeee;
        max-width: 600px;
        margin: 15px auto 0 auto; /* Center the subtitle */
    }

    /* --- Leaderboard Widget Styling --- */
    .leaderboard-widget {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 20.2);
        border-radius: 20px;
        padding: 30px;
        height: 100%;
    }

    .leaderboard-widget .widget-title {
        color: #ffffff;
        font-weight: 600;
        font-size: 2.2rem;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }

    .leaderboard-item {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
        padding: 15px;
        border-radius: 15px;
        transition: all 0.3s ease;
    }
    .leaderboard-item:hover {
        background: rgba(255, 255, 255, 0.1);
    }

    .leaderboard-item .rank {
        font-size: 1.8rem;
        font-weight: 700;
        color: #FFBB28; /* Gold color for rank */
        margin-right: 15px;
        min-width: 30px;
    }

    .leaderboard-item .avatar img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        margin-right: 15px;
        border: 2px solid #00C49F;
    }

    .leaderboard-item .user-info .username {
        font-size: 1.5rem;
        font-weight: 600;
        color: #ffffff;
    }

    .leaderboard-item .user-info .points {
        font-size: 1.4rem;
        color: #eeeeee;
    }

    .leaderboard-item .points-total {
        margin-left: auto;
        font-size: 1.2rem;
        font-weight: 600;
        color: #00C49F;
    }


    /* --- Badge Showcase Styling --- */
    .badge-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 20.2);
        border-radius: 20px;
        padding: 25px;
        text-align: center;
        transition: all 0.3s ease;
        height: 100%;
    }
    .badge-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .badge-card .badge-icon img {
        width: 100px;
        height: 100px;
        margin-bottom: 15px;
        /* --- THIS IS THE "AMAZING" PART --- */
        /* This adds a creative glow effect to the badges */
        animation: glow 3s infinite alternate;
    }

    .badge-card .badge-title {
        font-size: 1.8rem;
        font-weight: 600;
        color: #ffffff;
        margin-bottom: 5px;
    }

    .badge-card .badge-desc {
        font-size: 1.5rem;
        color: #eeeeee;
    }

    /* Keyframes for the badge glow */
    @keyframes glow {
        from {
            filter: drop-shadow(0 0 5px rgba(255, 255, 255, 0.3));
        }
        to {
            filter: drop-shadow(0 0 15px rgba(0, 196, 159, 0.8));
        }
    }

</style>


<div class="rts-achievement-area rts-section-gap">
    <div class="container">
        
        <div class="row mb--50">
            <div class="col-12">
                <div class="title-area text-center">
                    <span class="sub" style="font-size: 3.2rem;">Gamification & Rewards</span>
                    <h3 class="title" style="font-size: 3.5rem;">Showcase Your Achievements</h3>
                    <p class="subtitle" style="font-size: 1.9rem;">
                        Learning is rewarding. Climb the weekly leaderboard, earn exclusive badges for your achievements, and show the community your progress.
                    </p>
                </div>
            </div>
        </div>

        <div class="row g-5 align-items-stretch">

            <div class="col-lg-6">
                <div class="leaderboard-widget">
                    <h4 class="widget-title">🏆 Top Learners This Week</h4>

                    <div class="leaderboard-item">
                        <span class="rank">#1</span>
                        <div class="avatar">
                            <img src="https://placehold.co/50x50/28a745/ffffff?text=E" alt="User Avatar">
                        </div>
                        <div class="user-info">
                            <div class="username">@EcoWarrior</div>
                            <div class="points">Level 15</div>
                        </div>
                        <div class="points-total">15,200 pts</div>
                    </div>

                    <div class="leaderboard-item">
                        <span class="rank">#2</span>
                        <div class="avatar">
                            <img src="https://placehold.co/50x50/0088FE/ffffff?text=S" alt="User Avatar">
                        </div>
                        <div class="user-info">
                            <div class="username">@ScienceNerd</div>
                            <div class="points">Level 12</div>
                        </div>
                        <div class="points-total">12,500 pts</div>
                    </div>

                    <div class="leaderboard-item">
                        <span class="rank">#3</span>
                        <div class="avatar">
                            <img src="https://placehold.co/50x50/FFBB28/000000?text=H" alt="User Avatar">
                        </div>
                        <div class="user-info">
                            <div class="username">@HistoryBuff</div>
                            <div class="points">Level 11</div>
                        </div>
                        <div class="points-total">11,100 pts</div>
                    </div>

                    <div class="leaderboard-item">
                        <span class="rank">#4</span>
                        <div class="avatar">
                            <img src="https://placehold.co/50x50/FF8042/ffffff?text=C" alt="User Avatar">
                        </div>
                        <div class="user-info">
                            <div class="username">@CommunityFirst</div>
                            <div class="points">Level 9</div>
                        </div>
                        <div class="points-total">9,800 pts</div>
                    </div>

                </div>
            </div>

            <div class="col-lg-6">
                <div class="row g-4">
                    
                    <div class="col-md-6 col-sm-6">
                        <div class="badge-card">
                            <div class="badge-icon">
                                <img src="assets/images/badge/earth badge.png" alt="Earth Guardian Badge">
                            </div>
                            <h5 class="badge-title">Earth Protector</h5>
                            <p class="badge-desc">Complete 5 eco-challenges.</p>
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-6">
                        <div class="badge-card">
                            <div class="badge-icon">
                                <img src="assets/images/badge/quiz badge.png" alt="Quiz Master Badge">
                            </div>
                            <h5 class="badge-title">Quiz Master</h5>
                            <p class="badge-desc">Ace 10 quizzes in a row.</p>
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-6">
                        <div class="badge-card">
                            <div class="badge-icon">
                                <img src="assets/images/badge/community-helpers.png" alt="Community Helper Badge">
                            </div>
                            <h5 class="badge-title">Community Helper</h5>
                            <p class="badge-desc">Make your first donation.</p>
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-6">
                        <div class="badge-card">
                            <div class="badge-icon">
                                <img src="assets/images/badge/history badge.png" alt="History Buff Badge">
                            </div>
                            <h5 class="badge-title">History Buff</h5>
                            <p class="badge-desc">Master the History category.</p>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
    <!-- start header area -->
    <div class="rts-footer-area bg-shape-footer pt--120 rt_bg-secondary">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-6 col-sm-12 mb_sm--30 ">
                    <div class="footer-left-wrapper">
                        <a href="index.php"><img src="assets/images/logo/logo3.png" alt="Zitouna Quests Logo" data-sal-delay="150" data-sal-duration="800" data-sal="slide-up"></a>
                        
                        <p class="disc" data-sal-delay="150" data-sal-duration="1000" data-sal="slide-up">
                            Zitouna Quests is an innovative platform combining learning, gamification, and social engagement to empower users to make a positive impact.
                        </p>

                        <ul class="social-wrapper">
                            <li class="icon" data-sal-delay="150" data-sal-duration="800" data-sal="slide-up"><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                            <li class="icon" data-sal-delay="250" data-sal-duration="1000" data-sal="slide-up"><a href="#"><i class="fab fa-twitter"></i></a></li>
                            <li class="icon" data-sal-delay="350" data-sal-duration="1200" data-sal="slide-up"><a href="#"><i class="fab fa-instagram"></i></a></li>
                            <li class="icon" data-sal-delay="450" data-sal-duration="1400" data-sal="slide-up"><a href="#"><i class="fab fa-youtube"></i></a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6 col-sm-12">
                    <div class="footer-single-wized">
                        <h5 class="wized-title" data-sal-delay="150" data-sal-duration="600" data-sal="slide-up">Platform</h5>
                        <ul class="wizid-lists">
                            <li class="item" data-sal-delay="250" data-sal-duration="800" data-sal="slide-up"><a href="about.html">About Us</a></li>
                            <li class="item" data-sal-delay="350" data-sal-duration="1000" data-sal="slide-up"><a href="how-it-works.html">How It Works</a></li>
                            <li class="item" data-sal-delay="450" data-sal-duration="1200" data-sal="slide-up"><a href="quests.html">Quests</a></li>
                            <li class="item" data-sal-delay="550" data-sal-duration="1400" data-sal="slide-up"><a href="challenges.html">Challenges</a></li>
                            <li class="item" data-sal-delay="650" data-sal-duration="1600" data-sal="slide-up"><a href="impact.html">Our Impact</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-12 pl_lg--80">
                    <div class="footer-single-wized">
                        <h5 class="wized-title" data-sal-delay="150" data-sal-duration="600" data-sal="slide-up">Community</h5>
                        <ul class="wizid-lists">
                            <li class="item" data-sal-delay="150" data-sal-duration="600" data-sal="slide-up"><a href="forum.html">Forum</a></li>
                            <li class="item" data-sal-delay="350" data-sal-duration="1000" data-sal="slide-up"><a href="leaderboards.html">Leaderboards</a></li>
                            <li class="item" data-sal-delay="450" data-sal-duration="1200" data-sal="slide-up"><a href="achievements.html">Achievements</a></li>
                            <li class="item" data-sal-delay="550" data-sal-duration="1400" data-sal="slide-up"><a href="partners.html">Our Partners</a></li>
                            <li class="item" data-sal-delay="650" data-sal-duration="1600" data-sal="slide-up"><a href="blog.html">Blog</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6 col-sm-12">
                    <div class="footer-single-wized">
                        <h5 class="wized-title" data-sal-delay="150" data-sal-duration="600" data-sal="slide-up">Support</h5>
                        <ul class="wizid-lists">
                            <li class="item" data-sal-delay="250" data-sal-duration="800" data-sal="slide-up"><a href="contact.html">Contact Us</a></li>
                            <li class="item" data-sal-delay="350" data-sal-duration="1000" data-sal="slide-up"><a href="faq.html">FAQs</a></li>
                            <li class="item" data-sal-delay="450" data-sal-duration="1200" data-sal="slide-up"><a href="help-center.html">Help Center</a></li>
                            <li class="item" data-sal-delay="550" data-sal-duration="1400" data-sal="slide-up"><a href="privacy.html">Privacy Policy</a></li>
                            <li class="item" data-sal-delay="650" data-sal-duration="1600" data-sal="slide-up"><a href="terms.html">Terms of Service</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="copy-right-area ptb--50 ptb_sm--20">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="copy-right">
                            <div class="copy-right-text">
                                <p class="rts-cp">All rights reserved <span>©2025 Zitouna Quests</span></p>
                            </div>
                            <div class="copy-right-link">
                                <a href="privacy.html">Privacy Policy</a>
                                <a href="terms.html">Terms of Service</a>
                                <a href="contact.html">Contact Us</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end Footer area -->
    <!-- ENd Header Area -->


    <div class="loadingpage">
        <div class="spinner"></div>
    </div>

    <!-- The cursor elements -->
    <div class="mouse-cursor cursor-outer"></div>
    <div class="mouse-cursor cursor-inner"></div>

    <!-- progress Back to top -->
    <div class="progress-wrap">
        <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
        </svg>
    </div>
    <!-- progress Back to top End -->

    <!-- all scripts are hear -->
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

    <!-- main js -->
    <script src="assets/js/main.js"></script>
</body>

</html>