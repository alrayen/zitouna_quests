<?php
session_start();
// Get user name from session, or use default if not logged in
$user_name = "Guest";
if (isset($_SESSION['user_prenom']) && isset($_SESSION['user_nom'])) {
    $user_name = $_SESSION['user_prenom'] . " " . $_SESSION['user_nom'];
} elseif (isset($_SESSION['user_prenom'])) {
    $user_name = $_SESSION['user_prenom'];
} elseif (isset($_SESSION['user_nom'])) {
    $user_name = $_SESSION['user_nom'];
}
$default_image = "assets/images/team/team-01.png"; // <-- Create a default placeholder image
$user_image_path = $default_image; // Start with the default

// ▼▼▼ IMPORTANT ▼▼▼
// Make sure 'user_image' is the correct session variable name!
if (isset($_SESSION['user_image']) && !empty($_SESSION['user_image'])) {
    
    // If your session only stores the filename (e.g., "my-pic.jpg"),
    // you must add the path to your uploads folder, like this:
    // $user_image_path = "uploads/" . $_SESSION['user_image'];
    
    // If your session stores the full path, just use it:
    $user_image_path ="../../../../uploads/profiles/" . $_SESSION['user_image'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Genifty || Authore || - NFT Marketplace Template</title>
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


    <div class="authore-banner bg_image--5 bg_image">
    </div>

    <div class="authore-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="author-inner text-center">
                        <div class="user-thumbnail">
    <img src="<?php echo htmlspecialchars($user_image_path); ?>" alt="<?php echo htmlspecialchars($user_name); ?>'s Profile Picture">
</div>
                        <div class="content-inner">
                            <h5 class="title">
                                <?php echo htmlspecialchars($user_name); ?>
                            </h5>
                            <span>Joined Secpenber <span>2022</span></span>
                            <div class="share-wrapper">
                                <button id="copyProfileButton" data-userid="<?php echo htmlspecialchars($_SESSION['user_id']); ?>" title="Copy profile link"><i class="fas fa-share"></i></button>
                                <button><i class="far fa-ellipsis-v"></i></button>
                                <a href="modifierprofil.php" title="Modifier le profil"><button><i class="fad fa-edit"></i></button></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <style>
    /* This CSS transforms the NFT card into a Quest/Badge card */
    .explore-wrapper .trending-items_wrapper {
        /* This ensures the glassy card style is applied */
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        overflow: hidden; /* Important for the rounded corners */
    }

    .explore-wrapper .header {
        padding: 20px 25px 15px; /* Add padding to the header */
    }
    
    .explore-wrapper .header .title {
        color: #ffffff;
        font-size: 1.5rem; /* Make title bigger */
    }
    .explore-wrapper .header span {
        color: #00C49F; /* Use theme color for sub-title */
        font-weight: 500;
    }

    .explore-wrapper .thumbnail {
        position: relative; /* Keep this for the (hidden) button */
    }

    .explore-wrapper .thumbnail img {
        aspect-ratio: 16 / 10; /* Give images a uniform shape */
        object-fit: cover;
    }

    /* --- THIS IS THE "INNOVATIVE" PART --- */

    /* 1. Hide the "Place a Bid" button */
    .explore-wrapper .thumbnail a.rts-btn {
        display: none; 
    }

    /* 2. Hide the "Remaining Time" and "Countdown" */
    .explore-wrapper .product-discription .product-right {
        display: none;
    }

    /* 3. Re-style the "Price" section on the left */
    .explore-wrapper .product-discription .product-left {
        /* Make it take the full width */
        flex-basis: 100%;
        max-width: 100%;
    }
    .explore-wrapper .product-discription {
        padding: 20px 25px; /* Add padding */
    }
    .explore-wrapper .product-discription .product-left span {
        font-size: 0.9rem;
        color: #eeeeee;
    }
    .explore-wrapper .product-discription .product-left .price {
        font-size: 1.5rem; /* Make stat bigger */
        font-weight: 700;
        color: #FFBB28; /* Gold/Reward color */
    }

</style>


<div class="container"> <div class="row align-items-center justify-content-center">
        <div class="col-lg-12">
            <div class="button-group filters-button-group mt--15">
                <button class="button is-checked" data-filter="*" data-sal-delay="300" data-sal-duration="800" data-sal="slide-up">All Activity</button>
                <button class="button" data-filter=".badges" data-sal-delay="400" data-sal-duration="800" data-sal="slide-up">My Badges</button>
                <button class="button" data-filter=".quests" data-sal-delay="500" data-sal-duration="800" data-sal="slide-up">Completed Quests</button>
                <button class="button" data-filter=".challenges" data-sal-delay="600" data-sal-duration="800" data-sal="slide-up">Finished Challenges</button>
                <button class="button" data-filter=".forum" data-sal-delay="700" data-sal-duration="800" data-sal="slide-up">Forum Activity</button>
            </div>
        </div>
    </div>
    <div class="row mt--35 pb--120">
        <div class="col-lg-12 filter-explore">
            <div class="grid">

                <div class="element-item badges" data-category="transition">
                    <div class="explore-wrapper">
                        <div class="trending-items_wrapper" data-sal-delay="300" data-sal-duration="800" data-sal="slide-up">
                            <div class="header">
                                <h4 class="title">Badge Unlocked!</h4>
                                <span>Rarity: Gold</span>
                            </div>
                            <div class="thumbnail">
                                <a href="#"><img src="assets/images/badge/earth badge.png" alt="Gold Medal Badge"></a>
                                </div>
                            <div class="product-discription">
                                <div class="product-left">
                                    <span>Earned for "Eco-Warrior"</span>
                                    <h5 class="price">10 Challenges Completed</h5>
                                </div>
                                <div class="product-right">
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="element-item quests" data-category="metalloid">
                    <div class="explore-wrapper">
                        <div class="trending-items_wrapper" data-sal-delay="400" data-sal-duration="800" data-sal="slide-up">
                            <div class="header">
                                <h4 class="title">Quest Completed!</h4>
                                <span>Science Category</span>
                            </div>
                            <div class="thumbnail">
                                <a href="#"><img src="assets/images/science.png" alt="Science Quest"></a>
                            </div>
                            <div class="product-discription">
                                <div class="product-left">
                                    <span>"Future Innovators" Quiz</span>
                                    <h5 class="price">+100 Points</h5>
                                </div>
                                <div class="product-right">
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="element-item challenges" data-category="post-transition">
                    <div class="explore-wrapper">
                        <div class="trending-items_wrapper" data-sal-delay="500" data-sal-duration="800" data-sal="slide-up">
                            <div class="header">
                                <h4 class="title">Challenge Finished!</h4>
                                <span>Environment Category</span>
                            </div>
                            <div class="thumbnail">
                                <a href="#"><img src="assets/images/charity/images_8.jpg" alt="Eco Challenge"></a>
                            </div>
                            <div class="product-discription">
                                <div class="product-left">
                                    <span>"Local Park Cleanup"</span>
                                    <h5 class="price">+250 Points</h5>
                                </div>
                                <div class="product-right">
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="element-item forum" data-category="post-transition">
                    <div class="explore-wrapper">
                        <div class="trending-items_wrapper" data-sal-delay="300" data-sal-duration="800" data-sal="slide-up">
                            <div class="header">
                                <h4 class="title">New Forum Post</h4>
                                <span>In "Community Ideas"</span>
                            </div>
                            <div class="thumbnail">
                                <a href="#"><img src="44135.jpg" alt="Forum Post"></a>
                            </div>
                            <div class="product-discription">
                                <div class="product-left">
                                    <span>Topic: "New Challenge Idea!"</span>
                                    <h5 class="price">5 Replies</h5>
                                </div>
                                <div class="product-right">
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="element-item quests" data-category="post-transition">
                    <div class="explore-wrapper">
                        <div class="trending-items_wrapper" data-sal-delay="400" data-sal-duration="800" data-sal="slide-up">
                            <div class="header">
                                <h4 class="title">Quest Completed!</h4>
                                <span>History Category</span>
                            </div>
                            <div class="thumbnail">
                                <a href="#"><img src="35824.jpg" alt="History Quest"></a>
                            </div>
                            <div class="product-discription">
                                <div class="product-left">
                                    <span>"Ancient Wonders" Quiz</span>
                                    <h5 class="price">+75 Points</h5>
                                </div>
                                <div class="product-right">
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="element-item badges" data-category="metalloid">
                    <div class="explore-wrapper">
                        <div class="trending-items_wrapper" data-sal-delay="500" data-sal-duration="800" data-sal="slide-up">
                            <div class="header">
                                <h4 class="title">Badge Unlocked!</h4>
                                <span>Rarity: Bronze</span>
                            </div>
                            <div class="thumbnail">
                                <a href="#"><img src="assets/images/users.png" alt="Bronze Badge"></a>
                            </div>
                            <div class="product-discription">
                                <div class="product-left">
                                    <span>Earned for "Forum First"</span>
                                    <h5 class="price">Your First Forum Post</h5>
                                </div>
                                <div class="product-right">
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






    <!-- start header area -->
    <!-- start Footer area -->
        <div class="rts-footer-area bg-shape-footer pt--120 rt_bg-secondary">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-6 col-sm-12 mb_sm--30 ">
                    <div class="footer-left-wrapper">
                        <a href="index.html"><img src="assets/images/logo/logo3.png" alt="Zitouna Quests Logo" data-sal-delay="150" data-sal-duration="800" data-sal="slide-up"></a>
                        
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
    <script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Find the button by its new ID
    const copyButton = document.getElementById('copyProfileButton');
    
    if (copyButton) {
        // 2. Add a click event listener
        copyButton.addEventListener('click', function() {
            // 3. Get the user ID we stored in the button
            const userId = this.getAttribute('data-userid');
            
            // 4. !!! IMPORTANT !!!
            // Replace 'https://www.yourwebsite.com/author.php' 
            // with your website's real URL.
            const baseUrl = 'https://www.yourwebsite.com/author.php';
            
            // 5. Create the full URL to share
            const profileUrl = `${baseUrl}?user_id=${userId}`;
            
            // 6. Use the modern clipboard API to copy the text
            navigator.clipboard.writeText(profileUrl).then(function() {
                // 7. Show a success message
                alert('Profile URL copied to clipboard!\n' + profileUrl);
            }, function(err) {
                // 8. Show an error message if it fails
                console.error('Could not copy text: ', err);
                alert('Failed to copy URL.');
            });
        });
    }
});
</script>
</body>
</html>


