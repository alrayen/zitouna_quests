<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="google-signin-client_id" content="555243072540-moo7gfq76t7tu7nt9hg1kr76pqjlp9s6.apps.googleusercontent.com">
    <title>Genifty || Log In || - NFT Marketplace Template</title>
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
.github-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    padding: 12px 24px;
    background: #24292e;
    color: #ffffff;
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    font-size: 15px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    cursor: pointer;
    margin-top: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.github-btn:hover {
    background: #2f363d;
    border-color: rgba(255, 255, 255, 0.2);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
    color: #ffffff;
    text-decoration: none;
}

.github-btn:active {
    transform: translateY(0);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

.github-icon {
    width: 20px;
    height: 20px;
    margin-right: 12px;
    fill: currentColor;
}



        /* Style pour la superposition de la caméra */

        .face-capture-wrapper {
            position: relative;
            width: 320px;
            height: 240px;
            margin: 0 auto;
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

    <!-- start BreadCrumb area -->
    <div class="rts-brad-crumb-area breadcrumb-image bg_image">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb-inner ptb--130 pt_sm--80 pb_sm--80">
                        <h1 class="title" data-sal-delay="300" data-sal-duration="800" data-sal="slide-up">Log In</h1>
                        <div class="breadcrumb-list">
                            <a href="index.php" data-sal-delay="300" data-sal-duration="800" data-sal="slide-up">Home</a>
                            <span>/</span>
                            <a class="deactive" href="#" data-sal-delay="400" data-sal-duration="800" data-sal="slide-up">Pages</a>
                            <span>/</span>
                            <a class="active" href="#" data-sal-delay="500" data-sal-duration="800" data-sal="slide-up">Log In</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End BreadCrumb area -->

    <!-- Start Log-In area -->
    <!-- <div class="rts-login-area rts-section-gap">
        <div class="container">
            <div class="row">
                <div class="col-12 d-flex justify-content-center">
                    <form class="login-wrapper">
                        <div class="mb-5">
                            <label for="exampleInputEmail1" class="form-label">Email address</label>
                            <input type="email" id="exampleInputEmail1">
                        </div>
                        <div class="mb--15">
                            <label for="exampleInputPassword1" class="form-label">Password</label>
                            <input type="password" id="exampleInputPassword1">
                        </div>
                        <div class="mb--10">
                            <input type="checkbox" id="exampleCheck1">
                            <label for="exampleCheck1">Remember me leter</label>
                        </div>
                        <button type="submit" class="rts-btn btn-primary radious-5 mr--15 mb--15">Log In</button>
                        <a href="registration.html" class="rts-btn btn-secondary radious-5">Sign Up</a>
                    </form>
                </div>
            </div>
        </div>
    </div> -->
    <div class="rts-login-area rts-section-gap">
    <div class="container">
        <div class="row">
            <div class="col-12 d-flex justify-content-center">
                <form id="loginForm" class="login-wrapper" method="POST" action="../../../../Controller/login.php">
                    <div class="registration-container">
                        <h2>Connect to your account</h2>
                        <?php if (isset($_SESSION['error_login'])): ?>
                            <p style="color:red; text-align: center; margin-bottom: 20px;"><?php echo $_SESSION['error_login']; ?></p>
                        <?php endif; ?>
                        <?php if (isset($_SESSION['success_verify'])): ?>
                            <p style="color:green; text-align: center; margin-bottom: 20px;"><?php echo $_SESSION['success_verify']; unset($_SESSION['success_verify']); ?></p>
                        <?php endif; ?>
                        <?php if (isset($_SESSION['error_not_verified'])): ?>
                            <p style="color:orange; text-align: center; margin-bottom: 20px;"><?php echo $_SESSION['error_not_verified']; unset($_SESSION['error_not_verified']); ?> <a href="../../../../Controller/resend_verification.php?email=<?php echo urlencode($_SESSION['email_not_verified']); unset($_SESSION['email_not_verified']); ?>">Renvoyer l'e-mail</a></p>
                        <?php endif; ?>
                    <div class="mb-5">
                        <label for="email" class="form-label">Email address</label>
                        <input type="Text" id="email" name="email" >
                        <p id="emailError" style="color:red; margin-top: 5px;"></p>
                    </div>
                    <div class="mb--15">
                        <label for="password" class="form-label">Password</label>
                        <input type="Password" id="password" name="password" >
                        <p id="passwordError" style="color:red; margin-top: 5px;"></p>
                    </div>
                    <div class="mb--10">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Remember me later</label>
                    </div>
                    <div class="forget-password-link" style="text-align: right; margin-bottom: 15px;">
                     <a href="forgot_password.php" style="color: #ffffffff; font-size: 14px;">Mot de passe oublié ?</a>
                    </div>
                    <!-- Ajout du widget reCAPTCHA -->
                    <div class="mb-4">
                        <div class="g-recaptcha" data-sitekey="6LezTR8sAAAAAM70nCpDXMJ1Zb5zasamA7zuPtrE"></div>
                        <?php if (isset($_SESSION['error_recaptcha'])): ?><p style="color:red; margin-top: 5px;"><?php echo $_SESSION['error_recaptcha']; unset($_SESSION['error_recaptcha']); ?></p><?php endif; ?>
                    </div>

                    <button type="submit" class="rts-btn btn-primary radious-5 mr--15 mb--15" style="color: #fff;">Log In</button>
                    <button type="button" id="face-login-btn" class="rts-btn btn-primary radious-5 mr--15 mb--15" style="background-color: #00c49f; color: #fff;">
                        <i class="far fa-camera-alt"></i> Se connecter avec le visage
                    </button>
                    <a href="registration.php" class="rts-btn btn-secondary radious-5">Sign Up</a>

                   
                    <div class="d-flex align-items-center my-4">
                        <hr class="flex-grow-1">
                        <span class="mx-3" style="color: #888;">OU</span>
                        <hr class="flex-grow-1">
                    </div>
                    <div id="g_id_onload" data-client_id="555243072540-moo7gfq76t7tu7nt9hg1kr76pqjlp9s6.apps.googleusercontent.com" data-callback="onSignIn" data-auto_prompt="false"></div>
                    <div class="g_id_signin" data-type="standard" data-size="large" data-theme="outline" data-text="continue_with" data-shape="rectangular" data-logo_alignment="left"></div>
                    
                   
              <a href="../../../../Controller/github_login.php" class="github-btn">
    <svg class="github-icon" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
        <path fill="currentColor" d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0016 8c0-4.42-3.58-8-8-8z"/>
    </svg>
    Continuer avec GitHub
</a>

                    
                    <div class="face-capture-wrapper mt-5" id="face-login-container" style="display: none;">
                        <video id="video" width="320" height="240" autoplay muted style="display: none; object-fit: cover;"></video>
                    

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
    
    unset($_SESSION['error_login']);
?>
    

    
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

    <script src="https://accounts.google.com/gsi/client" async defer></script>

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>

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
        function onSignIn(googleUser) {
            var id_token = googleUser.credential;
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '../../../../Controller/google_auth.php');
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                console.log('Signed in as: ' + xhr.responseText);
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success && response.redirect_url) {
                        window.location.href = response.redirect_url;
                    } else {
                        alert('Erreur de connexion Google : ' + response.message);
                    }
                } catch (e) {
                    alert('Une erreur inattendue est survenue.');
                }
            };
            xhr.send('idtoken=' + id_token);
        }

        document.getElementById('loginForm').addEventListener('submit', function(event) {
            event.preventDefault();

            let isValid = true;

            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const emailError = document.getElementById('emailError');
            const passwordError = document.getElementById('passwordError');

            emailError.textContent = '';
            passwordError.textContent = '';

            if (emailInput.value.trim() === '') {
                emailError.textContent = 'L\'adresse email est requise.';
                isValid = false;
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value)) {
                emailError.textContent = 'Le format de l\'email est invalide.';
                isValid = false;
            }

            if (passwordInput.value.trim() === '') {
                passwordError.textContent = 'Le mot de passe est requis.';
                isValid = false;
            }

            if (isValid) {
                this.submit();
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            const faceLoginBtn = document.getElementById('face-login-btn');
            const faceLoginContainer = document.getElementById('face-login-container');
            const video = document.getElementById('video');
            const faceStatus = document.getElementById('face-status');
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d', { willReadFrequently: true });

            const MODEL_URL = '/Projet2/View/FRONT OFFICE/PRINCIPAL/genifty-html/models';

            let modelsLoaded = false;
            let stream = null; 

            function setStatus(message, color = '#fff') {
                faceStatus.style.display = 'block';
                faceStatus.innerText = message;
                faceStatus.style.color = color;
            }

            async function loadModels() {
                if (modelsLoaded) return;
                try {
                    setStatus('Chargement des modèles de reconnaissance...');
                    await Promise.all([
                        faceapi.nets.ssdMobilenetv1.loadFromUri(MODEL_URL), 
                        faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
                        faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL)
                    ]);
                    modelsLoaded = true;
                    console.log('Modèles chargés.');
                } catch (error) {
                    console.error('Erreur chargement modèles:', error);
                    setStatus("Impossible de charger les modèles. Veuillez rafraîchir la page.", '#FF6B6B');
                }
            }

            async function startDetection() {
                if (!stream || !stream.active) {
                    try {
                        stream = await navigator.mediaDevices.getUserMedia({ video: {} });
                        video.srcObject = stream;
                    } catch (err) {
                        console.error("Erreur d'accès à la webcam: ", err);
                        setStatus("Erreur d'accès à la webcam. Veuillez autoriser l'accès.", '#FF6B6B');
                        video.style.display = 'none';
                        return;
                    }
                }

                const detectionInterval = setInterval(async () => {
                    if (video.paused || video.ended || !video.videoWidth) {
                        return; 
                    }

                    if (canvas.width !== video.videoWidth) {
                        canvas.width = video.videoWidth;
                        canvas.height = video.videoHeight;
                        setStatus('Veuillez regarder la caméra...');
                    }

                    ctx.drawImage(video, 0, 0, video.videoWidth, video.videoHeight);

                    const detection = await faceapi.detectSingleFace(canvas, new faceapi.SsdMobilenetv1Options({ minConfidence: 0.2 })).withFaceLandmarks().withFaceDescriptor();
                    
                    if (detection) {
                        clearInterval(detectionInterval);
                        processFace(detection);
                    } else {
                        setStatus('Aucun visage détecté. Rapprochez-vous et assurez-vous d\'être bien éclairé.', '#FFBB28');
                    }
                }, 500);

                video.play().catch(err => console.error("Erreur de lecture vidéo:", err));
            }

            faceLoginBtn.addEventListener('click', async () => {
                const emailInput = document.getElementById('email');
                const email = emailInput.value.trim();
                const emailError = document.getElementById('emailError');
                emailError.textContent = ''; 
                if (email === '') {
                    emailError.textContent = "Veuillez d'abord saisir votre adresse e-mail.";
                    setStatus("Veuillez d'abord saisir votre adresse e-mail.", '#FF6B6B');
                    return;
                }
                
                faceLoginContainer.style.display = 'flex';
                await loadModels();

                if (!modelsLoaded) return;
                video.style.display = 'block';
                startDetection();
            });

            async function processFace(detection) {
                setStatus('Visage détecté. Vérification en cours...', '#FFBB28');
                
                video.pause();
                document.querySelector('#face-login-container .face-overlay').style.display = 'none';

                const descriptor = Array.from(detection.descriptor);
                const email = document.getElementById('email').value.trim(); 
                const truncatedDescriptor = descriptor.map(num => parseFloat(num.toFixed(10)));
                const bodyPayload = JSON.stringify({ email: email, descriptor: truncatedDescriptor });
                console.log("--- SENDING FACE DESCRIPTOR FOR 1-to-1 VERIFICATION ---");

                const controllerPath = '/Projet2/Controller/login_face.php';

                try {
                    const response = await fetch(controllerPath, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: bodyPayload
                    });

                    const contentType = response.headers.get("content-type");
                    if (!response.ok || !contentType || !contentType.includes("application/json")) {
                        const errorText = await response.text();
                        throw new Error(`Erreur serveur (${response.status}): ${errorText || "Réponse invalide."}`);
                    }

                    const result = await response.json();
                    console.log("Réponse du serveur:", result);

                    if (result.success) {
                        setStatus('Connexion réussie ! Redirection...', '#00E6A7');
                        window.location.href = result.redirect_url;
                    } else {
                        setStatus(result.message || 'Visage non reconnu. Veuillez réessayer.', '#FF6B6B');
                        setTimeout(startDetection, 2000);
                    }
                } catch (error) {
                    console.error("Erreur Fetch:", error);
                    setStatus("Erreur technique: " + error.message, '#FF6B6B');
                }
            }
        });

    </script>
    <?php include_once($_SERVER['DOCUMENT_ROOT'] . '/Projet2/View/includes/chatbot_ui.php'); ?>
</body>

</html>