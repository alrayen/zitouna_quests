<?php 
include_once "../../../../Controller/crudSujet.php";
include_once "../../../../Controller/crudCommentaire.php";

$post_id = isset($_GET['id']) ? $_GET['id'] : null;
$commentaires = [];
if ($post_id) {
    $commentaires = afficherCommentaireParPost($post_id);
}
$sujet = afficherSujetParId($post_id);




?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Zitouna Quests || Commentaires du Forum</title>
    <meta name="robots" content="noindex">
    <meta name="description" content="Page de commentaires du forum Zitouna Quests - Participez à la discussion">
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
        /* Styles améliorés pour les commentaires */
        .topic-header {
            background: linear-gradient(135deg, rgba(0, 196, 159, 0.15), rgba(255, 187, 40, 0.15));
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(15px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        }
        
        .comments-section {
            background: rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            overflow: hidden;
            margin-bottom: 30px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
        }
        
        .section-header {
            background: linear-gradient(135deg, rgba(0, 196, 159, 0.25), rgba(255, 187, 40, 0.25));
            padding: 20px 30px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .comments-list {
            list-style: none;
            padding: 0;
            margin: 0;
            max-height: 600px;
            overflow-y: auto;
        }
        
        .comment-item {
            padding: 25px 30px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            transition: all 0.3s ease;
            position: relative;
        }
        
        .comment-item:hover {
            background: rgba(255, 255, 255, 0.05);
            transform: translateX(5px);
        }
        
        .comment-item:last-child {
            border-bottom: none;
        }
        
        .comment-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            position: relative;
        }
        
        .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 15px;
            background: linear-gradient(135deg, #00C49F, #0088FE);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
            font-size: 18px;
            box-shadow: 0 4px 15px rgba(0, 196, 159, 0.3);
            transition: transform 0.3s ease;
        }
        
        .user-avatar:hover {
            transform: scale(1.1);
        }
        
        .user-info {
            flex: 1;
        }
        
        .username {
            font-weight: 600;
            margin-bottom: 5px;
            color: #fff;
            font-size: 16px;
        }
        
        .comment-date {
            font-size: 14px;
            color: #bbb;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .comment-content {
            margin-left: 65px;
            line-height: 1.7;
            color: #e0e0e0;
            font-size: 15px;
            background: rgba(255, 255, 255, 0.03);
            padding: 15px;
            border-radius: 12px;
            border-left: 3px solid #00C49F;
        }
        
        .comment-actions {
            margin-top: 15px;
            display: flex;
            gap: 20px;
            margin-left: 65px;
        }
        
        .comment-action {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #bbb;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 15px;
            border-radius: 8px;
        }
        
        .comment-action:hover {
            color: #00C49F;
            background: rgba(0, 196, 159, 0.1);
            border-color: rgba(0, 196, 159, 0.3);
            transform: translateY(-2px);
        }
        
        .comment-action.delete {
            color: #ff6b6b;
        }
        
        .comment-action.delete:hover {
            background: rgba(255, 107, 107, 0.1);
            border-color: rgba(255, 107, 107, 0.3);
        }
        
        .add-comment-form {
            background: rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            padding: 30px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            margin-bottom: 50px;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 12px;
            font-weight: 600;
            color: #fff;
            font-size: 16px;
        }
        
        .form-textarea {
            width: 100%;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            padding: 18px;
            color: white;
            resize: vertical;
            min-height: 140px;
            transition: all 0.3s ease;
            font-size: 15px;
            line-height: 1.6;
        }
        
        .form-textarea:focus {
            outline: none;
            border-color: #00C49F;
            box-shadow: 0 0 0 3px rgba(0, 196, 159, 0.2);
            background: rgba(255, 255, 255, 0.12);
        }
        
        .form-textarea::placeholder {
            color: #aaa;
        }
        
        .btn-submit {
            background: linear-gradient(135deg, #00C49F, #0088FE);
            border: none;
            color: white;
            padding: 14px 30px;
            border-radius: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            cursor: pointer;
            font-size: 15px;
            box-shadow: 0 4px 15px rgba(0, 196, 159, 0.3);
        }
        
        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 196, 159, 0.4);
        }
        
        .btn-back {
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            padding: 12px 25px;
            border-radius: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            text-decoration: none;
            margin-bottom: 25px;
            backdrop-filter: blur(10px);
        }
        
        .btn-back:hover {
            background: rgba(255, 255, 255, 0.18);
            transform: translateY(-2px);
            color: white;
            box-shadow: 0 4px 15px rgba(255, 255, 255, 0.1);
        }
        
        .no-comments {
            text-align: center;
            padding: 60px 40px;
            color: #aaa;
        }
        
        .no-comments i {
            font-size: 64px;
            margin-bottom: 20px;
            opacity: 0.5;
        }
        
        .comment-count {
            background: rgba(0, 196, 159, 0.25);
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 14px;
            margin-left: 12px;
            color: white;
            font-weight: 600;
        }
        
        .comment-stats {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-left: auto;
        }
        
        .stat-item {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #bbb;
            font-size: 14px;
        }
        
        .delete-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(5px);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        
        .delete-modal-content {
            background: rgba(45, 45, 45, 0.95);
            padding: 30px;
            border-radius: 20px;
            text-align: center;
            max-width: 400px;
            width: 90%;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .delete-modal-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 25px;
        }
        
        .btn-cancel {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-cancel:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        
        .btn-confirm-delete {
            background: linear-gradient(135deg, #ff6b6b, #ff4757);
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-confirm-delete:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(255, 107, 107, 0.4);
        }
        
        .comment-badge {
            background: rgba(255, 187, 40, 0.2);
            color: #FFBB28;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 12px;
            margin-left: 8px;
        }
        
        .active-users {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .users-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 15px;
        }
        
        @media (max-width: 768px) {
            .comment-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .user-avatar {
                margin-bottom: 10px;
            }
            
            .comment-content {
                margin-left: 0;
            }
            
            .comment-actions {
                margin-left: 0;
                justify-content: space-between;
                flex-wrap: wrap;
            }
            
            .comment-action {
                flex: 1;
                min-width: 120px;
                justify-content: center;
                margin-bottom: 8px;
            }
            
            .section-header {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
            
            .comment-stats {
                margin-left: 0;
            }
        }
    </style>
</head>

<body class="rt_bg-secondary">

    <!-- Modal de suppression -->
    <div class="delete-modal" id="deleteModal">
        <div class="delete-modal-content">
            <i class="far fa-exclamation-triangle" style="font-size: 48px; color: #ff6b6b; margin-bottom: 15px;"></i>
            <h4 style="color: white; margin-bottom: 10px;">Confirmer la suppression</h4>
            <p style="color: #bbb; margin-bottom: 20px;">Êtes-vous sûr de vouloir supprimer ce commentaire ? Cette action est irréversible.</p>
            <div class="delete-modal-buttons">
                <button class="btn-cancel" onclick="closeDeleteModal()">Annuler</button>
                <form method="GET" action="../../../../Controller/supprimerCommentaireController.php" id="deleteForm">
                    <input type="hidden" name="id" id="commentaireId">
                    <input type="hidden" name="position" value="front">
                    <button type="submit" name="supprimer_commentaire" class="btn-confirm-delete">
                        Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>

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
                            <li class="icon user"> <a href="author.php"><i class="far fa-user"></i></a></li>
                            <li class="icon notification"> <a href="#"><i class="far fa-bell" alt="notification"></i></a></li>
                        </ul>
                        <a id="connect-wallet" href="login.php" class="rts-btn btn-primary">login / sign up</a>
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
            <!-- nav style hear End -->
        </div>
    </div>
    <!-- end mobile menue -->

    <!-- Commentaires Content -->
    <div class="rts-banner-area banner-one rt_bg-secondary bg_tr-image--1" style="padding: 100px 0 50px;">
        <div class="container container-banner-one">
            <a href="forum.php" class="btn-back" data-sal-delay="150" data-sal-duration="800" data-sal="slide-up">
                <i class="far fa-arrow-left"></i> Retour au forum
            </a>
            
            <div class="topic-header" data-sal-delay="150" data-sal-duration="800" data-sal="slide-up">
                <h1 class="title"><?=$sujet['nom']?></h1>
                <div class="d-flex align-items-center mt-3">
                    <div class="user-avatar">E</div>
                    <div>
                        <div class="username">EcoWarrior</div>
                        <div class="comment-date">
                            <i class="far fa-clock"></i>
                            Posté le <?=$sujet['date_sujets']?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <section class="comments-section">
                        <div class="section-header">
                            <h3 class="title">
                                <i class="far fa-comments"></i>
                                Commentaires 
                                <span class="comment-count">3</span>
                            </h3>
                            <div class="comment-stats">
                                <div class="stat-item">
                                    <i class="far fa-users"></i>
                                    4 participants
                                </div>
                                <div class="stat-item">
                                    <i class="far fa-clock"></i>
                                    Dernière activité: 16 Mars 2025
                                </div>
                            </div>
                        </div>
                        
                        <ul class="comments-list">
                            <?php foreach ($commentaires as $commentaire):?>
                            <li class="comment-item">
                                <div class="comment-header">
                                    <div class="user-avatar">S</div>
                                    <div class="user-info">
                                        <div class="username">
                                           naf
                                        </div>
                                        <div class="comment-date">
                                            <i class="far fa-clock"></i>
                                           <?=$commentaire['date_commentaires']?>- 14:30
                                        </div>
                                    </div>
                                </div>
                                <div class="comment-content">
                                    <p><?=$commentaire['contenu']?></p>
                                </div>
                                <div class="comment-actions">
                                    <button class="comment-action">
                                        <i class="far fa-thumbs-up"></i> Utile
                                    </button>
                                    <button class="comment-action" onclick="window.location.href='modifierCommentaire.php?id=<?=$commentaire['id']?>&contenu=<?=urlencode($commentaire['contenu'])?>';">
                                        <i class="far fa-reply"></i> Modifier
                                    </button>
                                    <button class="comment-action">
                                        <i class="far fa-flag"></i> Signaler
                                    </button>
                                    <button class="comment-action delete" onclick="openDeleteModal(<?=$commentaire['id']?>)">
                                        <i class="far fa-trash"></i> Supprimer
                                    </button>
                                </div>
                            </li> <?php endforeach; ?>
                            
                           
                        </ul>
                    </section>

                    <div class="add-comment-form" data-sal-delay="150" data-sal-duration="800" data-sal="slide-up">
                        <h3 class="title mb-4">
                            <i class="far fa-edit"></i>
                            Ajouter un commentaire
                        </h3>
                        <form method="POST" action="../../../../Controller/ajouterCommentaireController.php?post=<?=$post_id?>&position=front">
                            <div class="form-group">
                                <label class="form-label">Votre commentaire</label>
                                <textarea 
                                    name="contenu" 
                                    class="form-textarea" 
                                    placeholder="Partagez vos pensées, questions ou expériences sur ce sujet..."
                                    required
                                >Je suis d'accord avec les suggestions précédentes. J'ajouterais que...</textarea>
                            </div>
                            <button type="submit" name="ajouter_commentaire" class="btn-submit">
                                <i class="far fa-paper-plane"></i> Publier le commentaire
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="active-users" data-sal-delay="150" data-sal-duration="800" data-sal="slide-up">
                        <h4 class="title">
                            <i class="far fa-users"></i>
                            Participants à la discussion
                        </h4>
                        <div class="users-list mt-3">
                            <div class="user-avatar" title="EcoWarrior">E</div>
                            <div class="user-avatar" title="SustainableLife">S</div>
                            <div class="user-avatar" title="GreenTech">G</div>
                            <div class="user-avatar" title="NatureLover">N</div>
                            <span class="text-muted">+1 autre</span>
                        </div>
                    </div>
                    
                    <div class="active-users" data-sal-delay="250" data-sal-duration="800" data-sal="slide-up">
                        <h4 class="title">Règles de discussion</h4>
                        <ul class="mt-3" style="padding-left: 20px;">
                            <li class="mb-2">Respectez les autres participants</li>
                            <li class="mb-2">Restez sur le sujet de la discussion</li>
                            <li class="mb-2">Partagez des informations vérifiées</li>
                            <li class="mb-2">Évitez le langage offensant</li>
                            <li>Soyez constructif dans vos réponses</li>
                        </ul>
                    </div>
                    
                    <div class="active-users" data-sal-delay="350" data-sal-duration="800" data-sal="slide-up">
                        <h4 class="title">Sujets similaires</h4>
                        <div class="mt-3">
                            <div class="mb-3 pb-3 border-bottom border-secondary">
                                <a href="commentaires.php?id=2" class="text-decoration-none">
                                    <h6 class="mb-1">Les énergies renouvelables en 2025</h6>
                                </a>
                                <small class="text-muted">12 Mars 2025</small>
                            </div>
                            <div class="mb-3 pb-3 border-bottom border-secondary">
                                <a href="commentaires.php?id=3" class="text-decoration-none">
                                    <h6 class="mb-1">Comment réduire son empreinte carbone</h6>
                                </a>
                                <small class="text-muted">10 Mars 2025</small>
                            </div>
                            <div class="mb-3 pb-3 border-bottom border-secondary">
                                <a href="commentaires.php?id=4" class="text-decoration-none">
                                    <h6 class="mb-1">Initiatives écologiques locales</h6>
                                </a>
                                <small class="text-muted">8 Mars 2025</small>
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
                            <li class="item" data-sal-delay="450" data-sal-duration="1200" data-sal="slide-up"><a href="quiz.php">Quêtes</a></li>
                            <li class="item" data-sal-delay="550" data-sal-duration="1400" data-sal="slide-up"><a href="challenge.php">Défis</a></li>
                            <li class="item" data-sal-delay="650" data-sal-duration="1600" data-sal="slide-up"><a href="impact.html">Notre Impact</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-12 pl_lg--80">
                    <div class="footer-single-wized">
                        <h5 class="wized-title" data-sal-delay="150" data-sal-duration="600" data-sal="slide-up">Communauté</h5>
                        <ul class="wizid-lists">
                            <li class="item" data-sal-delay="150" data-sal-duration="600" data-sal="slide-up"><a href="forum.php">Forum</a></li>
                            <li class="item" data-sal-delay="350" data-sal-duration="1000" data-sal="slide-up"><a href="leaderboards.html">Classements</a></li>
                            <li class="item" data-sal-delay="450" data-sal-duration="1200" data-sal="slide-up"><a href="achievements.html">Récompenses</a></li>
                            <li class="item" data-sal-delay="550" data-sal-duration="1400" data-sal="slide-up"><a href="sponsor.php">Partenaires</a></li>
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
        // Script amélioré pour les commentaires
        document.addEventListener('DOMContentLoaded', function() {
            // Animation pour les boutons d'action des commentaires
            const commentActions = document.querySelectorAll('.comment-action');
            commentActions.forEach(action => {
                action.addEventListener('click', function(e) {
                    if (this.classList.contains('delete')) {
                        return; // La suppression est gérée par la modal
                    }
                    
                    const actionType = this.querySelector('i').className;
                    if (actionType.includes('thumbs-up')) {
                        this.classList.toggle('active');
                        if (this.classList.contains('active')) {
                            this.innerHTML = '<i class="far fa-thumbs-up"></i> Utile (' + (parseInt(this.textContent.match(/\((\d+)\)/)?.[1] || 0) + 1) + ')';
                        } else {
                            this.innerHTML = '<i class="far fa-thumbs-up"></i> Utile';
                        }
                    } else if (actionType.includes('reply')) {
                        // Ouvrir un formulaire de réponse
                        const commentItem = this.closest('.comment-item');
                        const author = commentItem.querySelector('.username').textContent.split(' ')[0];
                        const textarea = document.querySelector('.form-textarea');
                        textarea.value = `@${author} `;
                        textarea.focus();
                        textarea.scrollIntoView({ behavior: 'smooth' });
                    } else if (actionType.includes('flag')) {
                        // Ouvrir un modal de signalement
                        alert('Fonctionnalité de signalement à implémenter');
                    }
                });
            });
            
            // Auto-resize du textarea
            const textarea = document.querySelector('.form-textarea');
            if (textarea) {
                textarea.addEventListener('input', function() {
                    this.style.height = 'auto';
                    this.style.height = (this.scrollHeight) + 'px';
                });
            }
            
            // Animation d'entrée des commentaires
            const commentItems = document.querySelectorAll('.comment-item');
            commentItems.forEach((item, index) => {
                setTimeout(() => {
                    item.style.opacity = '0';
                    item.style.transform = 'translateX(-20px)';
                    item.style.transition = 'all 0.5s ease';
                    
                    setTimeout(() => {
                        item.style.opacity = '1';
                        item.style.transform = 'translateX(0)';
                    }, 50);
                }, index * 100);
            });
        });
        
        // Fonctions pour la modal de suppression
        let id = null;
        function openDeleteModal(commentId) {
            id =commentId;
            document.getElementById('commentaireId').value = commentId;
            document.getElementById('deleteModal').style.display = 'flex';
        }
        
        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }
        
        // Fermer la modal en cliquant à l'extérieur
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });
        
        // Fermer la modal avec la touche Échap
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDeleteModal();
            }
        });
        
        
    </script>
</body>
</html>