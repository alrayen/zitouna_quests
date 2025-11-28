<?php 
include "../../controller/crudSujet.php";

$sujets=afficherSujet();

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Zitouna Quests || Forum Communautaire</title>
    <meta name="robots" content="noindex">
    <meta name="description" content="Forum communautaire Zitouna Quests - Échangez, apprenez et collaborez avec d'autres apprenants">
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
    
    <style>
        /* Styles spécifiques au forum */
        .forum-header {
            background: linear-gradient(135deg, rgba(0, 196, 159, 0.1), rgba(255, 187, 40, 0.1));
            border-radius: 20px;
            padding: 40px;
            margin-bottom: 50px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }
        
        .forum-categories {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 50px;
        }
        
        .forum-category-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            padding: 25px;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }
        
        .forum-category-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.08);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }
        
        .category-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #00C49F, #0088FE);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            font-size: 24px;
        }
        
        .category-stats {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
            font-size: 14px;
            color: #aaa;
        }
        
        .topics-section {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            overflow: hidden;
            margin-bottom: 50px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .section-header {
            background: linear-gradient(135deg, rgba(0, 196, 159, 0.2), rgba(255, 187, 40, 0.2));
            padding: 20px 30px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .topics-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .topic-item {
            padding: 20px 30px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            display: flex;
            align-items: center;
            transition: background 0.3s ease;
        }
        
        .topic-item:hover {
            background: rgba(255, 255, 255, 0.03);
        }
        
        .topic-item:last-child {
            border-bottom: none;
        }
        
        .topic-icon {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
            font-size: 20px;
        }
        
        .topic-content {
            flex: 1;
        }
        
        .topic-title {
            font-weight: 600;
            margin-bottom: 5px;
            font-size: 18px;
        }
        
        .topic-meta {
            font-size: 14px;
            color: #aaa;
        }
        
        .topic-stats {
            text-align: right;
        }
        
        .topic-count {
            display: block;
            font-weight: 600;
            color: #00C49F;
            font-size: 18px;
        }
        
        .btn-new-topic {
            background: linear-gradient(135deg, #00C49F, #0088FE);
            border: none;
            color: white;
            padding: 12px 25px;
            border-radius: 10px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }
        
        .btn-new-topic:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 196, 159, 0.3);
        }
        
        .forum-search {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 12px 20px;
            color: white;
            width: 100%;
            margin-bottom: 30px;
        }
        
        .forum-search:focus {
            outline: none;
            border-color: #00C49F;
            box-shadow: 0 0 0 2px rgba(0, 196, 159, 0.2);
        }
        
        .active-users {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
            border: 2px solid #00C49F;
        }
        
        .users-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 15px;
        }
        
        @media (max-width: 768px) {
            .forum-categories {
                grid-template-columns: 1fr;
            }
            
            .topic-item {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .topic-stats {
                margin-top: 10px;
                text-align: left;
            }
        }
    </style>
</head>

<body class="rt_bg-secondary">

    <!-- start header area -->
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
                                    <a class="navmain" href="quiz.php">Quests</a>
                                </li>
                                <li class="single-items off-arrow">
                                    <a class="navmain" href="take-quiz.php">Quiz</a>
                                </li>
                                <li class="single-items off-arrow">
                                    <a class="navmain" href="forum.html">Forum</a>
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
    <!-- ENd Header Area -->

    <!-- start Mobile menue -->
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
            <!-- nav style Start -->
            <nav>
                <ul class="main-menu">
                    <li class="single-items off-arrow">
                        <a class="navmain" href="index.html">Home</a>
                    </li>
                    <li class="single-items off-arrow">
                        <a class="navmain" href="quiz.php">Quests</a>
                    </li>
                    <li class="single-items off-arrow">
                        <a class="navmain" href="take-quiz.php">Quiz</a>
                    </li>
                    <li class="single-items off-arrow">
                        <a class="navmain" href="forum.html">Forum</a>
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
    <!-- end mobile menue -->

    <!-- Forum Content -->
    <div class="rts-banner-area banner-one rt_bg-secondary bg_tr-image--1" style="padding: 100px 0 50px;">
        <div class="container container-banner-one">
            <div class="forum-header">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h1 class="title" data-sal-delay="150" data-sal-duration="800" data-sal="slide-up">
                            Forum Communautaire
                        </h1>
                        <p class="disc" data-sal-delay="300" data-sal-duration="800" data-sal="slide-up">
                            Échangez, apprenez et collaborez avec notre communauté d'apprenants passionnés. Posez vos questions, partagez vos connaissances et progressez ensemble.
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <button onclick="window.location.href='post.php';" class="btn-new-topic" data-sal-delay="600" data-sal-duration="1000" data-sal="slide-up">
                            <i class="far fa-plus"></i> Nouveau Post
                        </button>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <input type="text" class="forum-search" placeholder="Rechercher dans le forum...">
                    
                    <div class="forum-categories">
                        <div class="forum-category-card" data-sal-delay="150" data-sal-duration="800" data-sal="slide-up">
                            <div class="category-icon">
                                🌍
                            </div>
                            <h4 class="title">Environnement & Écologie</h4>
                            <p class="disc">Discussions sur le changement climatique, la biodiversité et les solutions durables.</p>
                            <div class="category-stats">
                                <span>245 sujets</span>
                                <span>1.2k messages</span>
                            </div>
                        </div>
                        
                        <div class="forum-category-card" data-sal-delay="250" data-sal-duration="800" data-sal="slide-up">
                            <div class="category-icon">
                                🔬
                            </div>
                            <h4 class="title">Science & Technologie</h4>
                            <p class="disc">Explorez les dernières avancées scientifiques et innovations technologiques.</p>
                            <div class="category-stats">
                                <span>189 sujets</span>
                                <span>856 messages</span>
                            </div>
                        </div>
                        
                        <div class="forum-category-card" data-sal-delay="350" data-sal-duration="800" data-sal="slide-up">
                            <div class="category-icon">
                                🏛️
                            </div>
                            <h4 class="title">Histoire & Culture</h4>
                            <p class="disc">Plongez dans le passé et découvrez les richesses culturelles du monde.</p>
                            <div class="category-stats">
                                <span>156 sujets</span>
                                <span>723 messages</span>
                            </div>
                        </div>
                        
                        <div class="forum-category-card" data-sal-delay="450" data-sal-duration="800" data-sal="slide-up">
                            <div class="category-icon">
                                🤝
                            </div>
                            <h4 class="title">Impact Social</h4>
                            <p class="disc">Partagez vos expériences et idées pour créer un impact positif.</p>
                            <div class="category-stats">
                                <span>98 sujets</span>
                                <span>412 messages</span>
                            </div>
                        </div>
                    </div>

                    <section class="topics-section">
                        <div class="section-header">
                            <h3 class="title">Derniers Posts Actifs</h3>
                        </div>
                        <ul class="topics-list">
                            <?php
                             foreach($sujets as $sujet):
                            ?>
                            <li class="topic-item">
                                <div class="topic-icon">💬</div>
                                <div onclick="window.location.href='modifierPost.php?id=<?=$sujet['id']?>&contenu=<?=urlencode($sujet['nom'])?>';"class="topic-content">
                                    <a  class="topic-title"><?=$sujet["nom"]?></a>
                                    <div class="topic-meta">Par <a href="#">@EcoWarrior</a> <?=$sujet["date_sujets"]?></div>
                                </div>
                                <div onclick="window.location.href='commentaires.php?id=<?=$sujet['id']?>'"; class="topic-stats">
                                    <span class="topic-count">24</span>
                                    <span>Commentaires</span>
                                </div>
                            </li>
                            <?php
                            endforeach;
                            ?>
                        </ul>
                    </section>
                </div>
                
                <div class="col-lg-4">
                    <div class="active-users" data-sal-delay="150" data-sal-duration="800" data-sal="slide-up">
                        <h4 class="title">Utilisateurs en ligne</h4>
                        <div class="users-list">
                            <img src="https://placehold.co/40x40/00C49F/ffffff?text=E" alt="User" class="user-avatar">
                            <img src="https://placehold.co/40x40/0088FE/ffffff?text=S" alt="User" class="user-avatar">
                            <img src="https://placehold.co/40x40/FFBB28/000000?text=H" alt="User" class="user-avatar">
                            <img src="https://placehold.co/40x40/FF8042/ffffff?text=C" alt="User" class="user-avatar">
                            <img src="https://placehold.co/40x40/9C27B0/ffffff?text=G" alt="User" class="user-avatar">
                            <span class="text-muted">+12 autres</span>
                        </div>
                    </div>
                    
                    <div class="active-users" data-sal-delay="250" data-sal-duration="800" data-sal="slide-up">
                        <h4 class="title">Statistiques du Forum</h4>
                        <div class="mt-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Sujets totaux:</span>
                                <strong>688</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Messages totaux:</span>
                                <strong>3,211</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Membres:</span>
                                <strong>1,245</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>En ligne:</span>
                                <strong>17</strong>
                            </div>
                        </div>
                    </div>
                    
                    <div class="active-users" data-sal-delay="350" data-sal-duration="800" data-sal="slide-up">
                        <h4 class="title">Règles du Forum</h4>
                        <ul class="mt-3" style="padding-left: 20px;">
                            <li class="mb-2">Respectez tous les membres</li>
                            <li class="mb-2">Restez dans le sujet de la catégorie</li>
                            <li class="mb-2">Pas de spam ou publicité</li>
                            <li class="mb-2">Partagez des sources fiables</li>
                            <li>Soyez constructif dans vos réponses</li>
                        </ul>
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
                        <a href="index.html"><img src="assets/images/logo/logo3.png" alt="Zitouna Quests Logo" data-sal-delay="150" data-sal-duration="800" data-sal="slide-up"></a>
                        
                        <p class="disc" data-sal-delay="150" data-sal-duration="1000" data-sal="slide-up">
                            Zitouna Quests est une plateforme innovante combinant apprentissage, gamification et engagement social pour permettre aux utilisateurs d'avoir un impact positif.
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
                        <h5 class="wized-title" data-sal-delay="150" data-sal-duration="600" data-sal="slide-up">Plateforme</h5>
                        <ul class="wizid-lists">
                            <li class="item" data-sal-delay="250" data-sal-duration="800" data-sal="slide-up"><a href="about.html">À propos</a></li>
                            <li class="item" data-sal-delay="350" data-sal-duration="1000" data-sal="slide-up"><a href="how-it-works.html">Comment ça marche</a></li>
                            <li class="item" data-sal-delay="450" data-sal-duration="1200" data-sal="slide-up"><a href="quests.html">Quêtes</a></li>
                            <li class="item" data-sal-delay="550" data-sal-duration="1400" data-sal="slide-up"><a href="challenges.html">Défis</a></li>
                            <li class="item" data-sal-delay="650" data-sal-duration="1600" data-sal="slide-up"><a href="impact.html">Notre Impact</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-12 pl_lg--80">
                    <div class="footer-single-wized">
                        <h5 class="wized-title" data-sal-delay="150" data-sal-duration="600" data-sal="slide-up">Communauté</h5>
                        <ul class="wizid-lists">
                            <li class="item" data-sal-delay="150" data-sal-duration="600" data-sal="slide-up"><a href="forum.html">Forum</a></li>
                            <li class="item" data-sal-delay="350" data-sal-duration="1000" data-sal="slide-up"><a href="leaderboards.html">Classements</a></li>
                            <li class="item" data-sal-delay="450" data-sal-duration="1200" data-sal="slide-up"><a href="achievements.html">Récompenses</a></li>
                            <li class="item" data-sal-delay="550" data-sal-duration="1400" data-sal="slide-up"><a href="partners.html">Partenaires</a></li>
                            <li class="item" data-sal-delay="650" data-sal-duration="1600" data-sal="slide-up"><a href="blog.html">Blog</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6 col-sm-12">
                    <div class="footer-single-wized">
                        <h5 class="wized-title" data-sal-delay="150" data-sal-duration="600" data-sal="slide-up">Support</h5>
                        <ul class="wizid-lists">
                            <li class="item" data-sal-delay="250" data-sal-duration="800" data-sal="slide-up"><a href="contact.html">Contact</a></li>
                            <li class="item" data-sal-delay="350" data-sal-duration="1000" data-sal="slide-up"><a href="faq.html">FAQ</a></li>
                            <li class="item" data-sal-delay="450" data-sal-duration="1200" data-sal="slide-up"><a href="help-center.html">Centre d'aide</a></li>
                            <li class="item" data-sal-delay="550" data-sal-duration="1400" data-sal="slide-up"><a href="privacy.html">Confidentialité</a></li>
                            <li class="item" data-sal-delay="650" data-sal-duration="1600" data-sal="slide-up"><a href="terms.html">Conditions</a></li>
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
                                <p class="rts-cp">Tous droits réservés <span>©2025 Zitouna Quests</span></p>
                            </div>
                            <div class="copy-right-link">
                                <a href="privacy.html">Confidentialité</a>
                                <a href="terms.html">Conditions</a>
                                <a href="contact.html">Contact</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end Footer area -->

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
    
    <script>
        // Script pour la fonctionnalité du forum
        document.addEventListener('DOMContentLoaded', function() {
            // Animation pour le bouton nouveau sujet
            const newTopicBtn = document.querySelector('.btn-new-topic');
            if (newTopicBtn) {
                newTopicBtn.addEventListener('click', function() {
                    alert('Fonctionnalité de création de sujet à implémenter');
                    // Ici vous pouvez ouvrir un modal ou rediriger vers une page de création
                });
            }
            
            // Animation de recherche
            const searchInput = document.querySelector('.forum-search');
            if (searchInput) {
                searchInput.addEventListener('focus', function() {
                    this.style.boxShadow = '0 0 0 2px rgba(0, 196, 159, 0.3)';
                });
                
                searchInput.addEventListener('blur', function() {
                    this.style.boxShadow = 'none';
                });
            }
        });
    </script>
</body>

</html>