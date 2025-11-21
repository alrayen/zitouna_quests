<?php
session_start();
require_once "../../../../Model/user.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Vous devez être connecté pour modifier votre profil.'); window.location.href='login.html';</script>";
    exit;
}

// Get current user data
$user_id = $_SESSION['user_id'];
$user = User::getUserById($user_id);

if (!$user) {
    echo "<script>alert('Utilisateur non trouvé.'); window.location.href='author.php';</script>";
    exit;
}

// Set default values
$nom = htmlspecialchars($user['nom'] ?? '');
$prenom = htmlspecialchars($user['Prenom'] ?? '');
$email = htmlspecialchars($user['email'] ?? '');
$birthdate = htmlspecialchars($user['birthdate'] ?? '');
$bio = htmlspecialchars($user['bio'] ?? '');
$photo = htmlspecialchars($user['photo'] ?? '');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Genifty || Modifier Profil || - NFT Marketplace Template</title>
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
    <style>
        .profile-edit-container {
            max-width: 800px;
            margin: 80px auto;
            padding: 40px;
            background: var(--rt-bg-secondary);
            border-radius: 10px;
        }
        .profile-header {
            text-align: center;
            margin-bottom: 40px;
        }
        .profile-header h2 {
            color: var(--rt-color-primary);
            margin-bottom: 10px;
        }
        .form-group {
            margin-bottom: 25px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--rt-color-white);
            font-weight: 500;
        }
        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="date"],
        .form-group input[type="password"],
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 5px;
            color: var(--rt-color-white);
            font-size: 14px;
            transition: all 0.3s;
        }
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--rt-color-primary);
            background: rgba(255, 255, 255, 0.08);
        }
        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }
        .password-note {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.6);
            margin-top: 5px;
        }
        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }
        .btn-cancel {
            background: rgba(255, 255, 255, 0.1);
            color: var(--rt-color-white);
        }
        .btn-cancel:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        .photo-upload-wrapper {
            text-align: center;
            margin-bottom: 25px;
        }
        .photo-preview-container {
            position: relative;
            display: inline-block;
            margin-bottom: 15px;
        }
        .photo-preview {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--rt-color-primary);
            cursor: pointer;
            transition: all 0.3s;
            background: rgba(255, 255, 255, 0.05);
        }
        .photo-preview:hover {
            opacity: 0.8;
            transform: scale(1.05);
        }
        .photo-preview-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s;
            cursor: pointer;
        }
        .photo-preview-container:hover .photo-preview-overlay {
            opacity: 1;
        }
        .photo-preview-overlay i {
            color: white;
            font-size: 24px;
        }
        #photoInput {
            display: none;
        }
        .photo-upload-label {
            display: inline-block;
            padding: 10px 20px;
            background: var(--rt-color-primary);
            color: white;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .photo-upload-label:hover {
            background: var(--rt-color-primary);
            opacity: 0.9;
        }
        .photo-info {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.6);
            margin-top: 10px;
        }
    </style>
</head>

<body class="rt_bg-secondary">

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
    <!-- ENd Header Area -->
<style>
    /* ---------------------------------- */
    /* 1. Main Form Container
    /* ---------------------------------- */
    .profile-edit-container {
        background: rgba(255, 255, 255, 0.1); /* Semi-transparent white */
        backdrop-filter: blur(12px);          /* The "frost" effect */
        -webkit-backdrop-filter: blur(12px);  /* Safari support */
        padding: 40px;
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.18);
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
        max-width: 700px; /* Profile forms are often wider */
        width: 100%;
        margin: 40px auto; /* Center it on the page */
        color: #fff;
    }

    /* ---------------------------------- */
    /* 2. Form Header
    /* ---------------------------------- */
    .profile-header {
        text-align: left;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }
    .profile-header h2 {
        font-weight: 600;
        margin: 0;
    }
    .profile-header p {
        color: rgba(255, 255, 255, 0.7);
        font-size: 1rem;
        margin-top: 5px;
    }

    /* ---------------------------------- */
    /* 3. Form Fields & Labels
    /* ---------------------------------- */
    .form-group {
        margin-bottom: 25px;
    }
    .form-group label {
        display: block;
        margin-bottom: 10px;
        color: rgba(255, 255, 255, 0.9);
        font-size: 15px;
        font-weight: 500;
    }

    /* Style for all text inputs and textareas */
    .profile-edit-container input[type="text"],
    .profile-edit-container input[type="email"],
    .profile-edit-container input[type="password"],
    .profile-edit-container input[type="date"],
    .profile-edit-container textarea {
        width: 100%;
        padding: 14px 20px;
        font-size: 16px;
        color: #fff; /* Text the user types */
        background: rgba(255, 255, 255, 0.15); /* Slightly different transparency */
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 10px;
        outline: none; /* Remove ugly default outline */
        transition: all 0.3s ease;
        box-sizing: border-box; /* Important for padding to work right */
    }

    .profile-edit-container textarea {
        min-height: 120px;
        resize: vertical;
        font-family: inherit; /* Make textarea use the same font */
    }

    /* Glow effect when the user clicks into an input */
    .profile-edit-container input:focus,
    .profile-edit-container textarea:focus {
        background: rgba(255, 255, 255, 0.2);
        border-color: #fff;
        box-shadow: 0 0 15px rgba(255, 255, 255, 0.25);
    }

    /* Style the placeholder text */
    .profile-edit-container input::placeholder,
    .profile-edit-container textarea::placeholder {
        color: rgba(255, 255, 255, 0.6);
    }

    /* Style the date picker icon to be white */
    .profile-edit-container input[type="date"]::-webkit-calendar-picker-indicator {
        filter: invert(1); /* This makes the dark icon white */
        cursor: pointer;
        opacity: 0.8;
    }

    /* Helper text for password and photo fields */
    .password-note, .photo-info {
        font-size: 13px;
        color: rgba(255, 255, 255, 0.6);
        margin-top: 10px;
    }

    /* ---------------------------------- */
    /* 4. Photo Upload Section (Custom)
    /* ---------------------------------- */
    .photo-upload-wrapper {
        display: flex;
        align-items: center;
        gap: 25px; /* Space between photo and button */
    }
    
    .photo-preview-container {
        position: relative;
        width: 120px; /* Size of the circle */
        height: 120px;
        border-radius: 50%;
        flex-shrink: 0; /* Prevents shrinking */
    }

    .photo-preview {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover; /* Prevents image stretching */
        border: 2px solid rgba(255, 255, 255, 0.3);
    }
    
    .photo-preview-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: rgba(0, 0, 0, 0.6);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        opacity: 0;
        transition: opacity 0.3s ease;
        cursor: pointer;
    }

    .photo-preview-container:hover .photo-preview-overlay {
        opacity: 1;
    }

    /* This hides the default "Choose File" button */
    #photoInput {
        display: none;
    }

    /* This styles our custom "Choisir une photo" button */
    .photo-upload-label {
        padding: 12px 20px;
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 10px;
        color: #fff;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
    }
    .photo-upload-label:hover {
        background: rgba(255, 255, 255, 0.3);
        border-color: #fff;
    }
    .photo-upload-label i {
        margin-right: 8px;
    }

    /* ---------------------------------- */
    /* 5. Button Group
    /* ---------------------------------- */
    .button-group {
        display: flex;
        flex-wrap: wrap; /* For mobile */
        gap: 15px;
        margin-top: 35px;
        padding-top: 25px;
        border-top: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    
    .button-group .rts-btn.btn-primary {
        padding: 14px 25px;
        font-size: 16px;
        font-weight: 700;
        border-radius: 10px;
        border: none;
        background: #fff; 
        color: #00796B; 
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .button-group .rts-btn.btn-primary:hover {
        background: #f0f0f0;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    
    .button-group .rts-btn.btn-cancel {
        padding: 14px 25px;
        font-size: 16px;
        font-weight: 500;
        border-radius: 10px;
        background: transparent;
        border: 1px solid rgba(255, 255, 255, 0.4);
        color: #fff;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none; 
    }
    .button-group .rts-btn.btn-cancel:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: #fff;
    }

</style>
    
    <div class="profile-edit-container">
        <div class="profile-header">
            
            <h2>Modifier mon profil</h2>
            <p style="color: rgba(255, 255, 255, 0.7);">Mettez à jour vos informations personnelles</p>
        </div>

        <form action="../../../../Controller/updateProfile.php" method="POST" id="profileForm" enctype="multipart/form-data">
            <div class="form-group">
                <label for="prenom">Prénom *</label>
                <input type="text" id="prenom" name="prenom" value="<?php echo $prenom; ?>" required>
            </div>

            <div class="form-group">
                <label for="nom">Nom *</label>
                <input type="text" id="nom" name="nom" value="<?php echo $nom; ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
            </div>

            <div class="form-group">
                <label for="birthdate">Date de naissance *</label>
                <input type="date" id="birthdate" name="birthdate" value="<?php echo $birthdate; ?>" required>
            </div>

            <div class="form-group">
                <label for="bio">Biographie</label>
                <textarea id="bio" name="bio" placeholder="Parlez-nous de vous..."><?php echo $bio; ?></textarea>
            </div>

            <div class="form-group">
                <label>Photo de profil</label>
                <div class="photo-upload-wrapper">
                    <div class="photo-preview-container">
                        <?php 
                        $photoPath = 'assets/images/team/team-01.png'; 
                        if (!empty($photo)) {
                            
                            $localPath = '../../../../uploads/profiles/' . $photo;
                            if (file_exists(__DIR__ . '/../../../../uploads/profiles/' . $photo)) {
                                $photoPath = '../../../../uploads/profiles/' . $photo;
                            } else {
                                
                                $photoPath = $photo;
                            }
                        }
                        ?>
                        <img id="photoPreview" src="<?php echo htmlspecialchars($photoPath); ?>" alt="Photo de profil" class="photo-preview">
                        <div class="photo-preview-overlay" onclick="document.getElementById('photoInput').click()">
                            <i class="fal fa-camera"></i>
                        </div>
                    </div>
                    <input type="file" id="photoInput" name="photo" accept="image/*" onchange="previewPhoto(this)">
                    <label for="photoInput" class="photo-upload-label">
                        <i class="fal fa-cloud-upload"></i> Choisir une photo
                    </label>
                    <p class="photo-info">Cliquez sur la photo ou le bouton pour changer votre photo de profil</p>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Nouveau mot de passe</label>
                <input type="password" id="password" name="password" placeholder="Laisser vide pour ne pas changer">
                <p class="password-note">Laissez ce champ vide si vous ne souhaitez pas changer votre mot de passe</p>
            </div>

            <div class="form-group">
                <label for="password2">Confirmer le nouveau mot de passe</label>
                <input type="password" id="password2" name="password2" placeholder="Confirmer le nouveau mot de passe">
            </div>

            <div class="button-group">
                <button type="submit" class="rts-btn btn-primary radious-5" style="color: #fff;">Enregistrer les modifications</button>
                <a href="author.php" class="rts-btn btn-cancel radious-5">Annuler</a>
            </div>
        </form>
    </div>
    <!-- End Profile Edit Section -->

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
  
        function previewPhoto(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    document.getElementById('photoPreview').src = e.target.result;
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }

  
        document.getElementById('photoPreview').addEventListener('click', function() {
            document.getElementById('photoInput').click();
        });

      
        document.getElementById('profileForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const password2 = document.getElementById('password2').value;
            
            if (password || password2) {
                if (password !== password2) {
                    e.preventDefault();
                    alert('Les mots de passe ne correspondent pas.');
                    return false;
                }
                if (password.length < 6) {
                    e.preventDefault();
                    alert('Le mot de passe doit contenir au moins 6 caractères.');
                    return false;
                }
            }
        });
    </script>
</body>

</html>
