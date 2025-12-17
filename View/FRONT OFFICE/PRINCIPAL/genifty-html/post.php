<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Créer un Post - Zitouna Quests</title>
    <meta name="robots" content="noindex">
    <meta name="description" content="Créez un nouveau post dans le forum Zitouna Quests">
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
        .custom-file-input {
    position: relative;
    display: inline-block;
    width: 250px;
}

.custom-file-input input[type="file"] {
    width: 100%;
    height: 40px;
    opacity: 0;
    position: absolute;
    z-index: 2;
    cursor: pointer;
}

.custom-file-input label {
    display: block;
    background-color: #6ebe71ff;
    color: white;
    padding: 10px;
    text-align: center;
    border-radius: 5px;
    cursor: pointer;
    z-index: 1;
    position: relative;
    transition: background 0.3s;
}

.custom-file-input label:hover {
    background-color: #45a049;
}

.custom-file-input .file-name {
    display: block;
    margin-top: 5px;
    font-size: 0.9em;
    color: #555;
}
        /* Styles spécifiques à la création de post */
        .create-post-container {
            min-height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 50px 0;
        }
        
        .create-post-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 800px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .post-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .post-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #00C49F, #0088FE);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 32px;
        }
        
        .content-editor {
            margin-bottom: 30px;
        }
        
        .editor-toolbar {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }
        
        .toolbar-btn {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            padding: 8px 12px;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .toolbar-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }
        
        .content-textarea {
            width: 100%;
            min-height: 300px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            padding: 20px;
            color: white;
            font-size: 16px;
            line-height: 1.6;
            resize: vertical;
            transition: all 0.3s ease;
        }
        
        .content-textarea:focus {
            outline: none;
            border-color: #00C49F;
            box-shadow: 0 0 0 2px rgba(0, 196, 159, 0.2);
            background: rgba(255, 255, 255, 0.08);
        }
        
        .character-count {
            text-align: right;
            font-size: 14px;
            color: #aaa;
            margin-top: 10px;
        }
        
        .post-actions {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            margin-top: 30px;
        }
        
        .btn-cancel {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            padding: 12px 25px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-cancel:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
            color: white;
            text-decoration: none;
        }
        
        .btn-submit {
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
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 196, 159, 0.3);
        }
        
        .preview-section {
            margin-top: 30px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .preview-section h4 {
            margin-bottom: 15px;
            color: #00C49F;
        }
        
        .preview-content {
            color: #ddd;
            line-height: 1.6;
        }
        
        @media (max-width: 768px) {
            .create-post-card {
                padding: 25px;
                margin: 20px;
            }
            
            .post-actions {
                flex-direction: column;
            }
            
            .btn-cancel, .btn-submit {
                width: 100%;
                justify-content: center;
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
                            <img src="assets/images/logo/logo3.png" alt="Zitouna Quests Logo">
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
                                    <a class="navmain" href="forum.php">Forum</a>
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
                        <a class="navmain" href="forum.php">Forum</a>
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

    <!-- Create Post Content -->
    <div class="create-post-container">
        <div class="create-post-card" data-sal-delay="150" data-sal-duration="800" data-sal="slide-up">
            <div class="post-header">
                <div class="post-icon">
                    ✍️
                </div>
                <h2 class="title">Créer un Nouveau Post</h2>
                <p class="disc">Partagez vos pensées, questions ou expériences avec la communauté</p>
            </div>

            <form  action="../../../../Controller/ajouterSujetcontroller.php?position=front" method="POST" enctype="multipart/form-data">
                <div class="content-editor">
                    
                    
                    <textarea 
                        id="postContent" 
                        class="content-textarea" 
                        placeholder="Qu'avez-vous en tête ? Partagez vos idées, posez des questions ou racontez vos expériences..."
                        maxlength="5000"
                        name="nom"
                    ></textarea>
                    <div class="custom-file-input">
                    <input type="file" name="image" id="imageInput">
                    <label for="imageInput">Choisir une image</label>
                    <span class="file-name">Aucune image sélectionnée</span>
                    </div><br><br>
                    <div class="character-count">
                        <span id="charCount">0</span>/5000 caractères
                    </div>
                </div>

                <div class="preview-section" id="previewSection" style="display: none;">
                    <h4>Aperçu du Post</h4>
                    <div class="preview-content" id="previewContent">
                        <!-- L'aperçu sera généré ici -->
                    </div>
                </div>

                <div class="post-actions">
                    <a href="forum.php" class="btn-cancel">
                        <i class="fas fa-arrow-left"></i> Annuler
                    </a>
                    <button type="button" class="btn-cancel" id="previewBtn">
                        <i class="fas fa-eye"></i> Aperçu
                    </button>
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-paper-plane"></i> Publier le Post
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- start header area -->
    <div class="rts-footer-area bg-shape-footer pt--120 rt_bg-secondary">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-6 col-sm-12 mb_sm--30 ">
                    <div class="footer-left-wrapper">
                        <a href="index.html"><img src="assets/images/logo/logo3.png" alt="Zitouna Quests Logo"></a>
                        
                        <p class="disc">
                            Zitouna Quests est une plateforme innovante combinant apprentissage, gamification et engagement social pour permettre aux utilisateurs d'avoir un impact positif.
                        </p>

                        <ul class="social-wrapper">
                            <li class="icon"><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                            <li class="icon"><a href="#"><i class="fab fa-twitter"></i></a></li>
                            <li class="icon"><a href="#"><i class="fab fa-instagram"></i></a></li>
                            <li class="icon"><a href="#"><i class="fab fa-youtube"></i></a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6 col-sm-12">
                    <div class="footer-single-wized">
                        <h5 class="wized-title">Plateforme</h5>
                        <ul class="wizid-lists">
                            <li class="item"><a href="about.html">À propos</a></li>
                            <li class="item"><a href="how-it-works.html">Comment ça marche</a></li>
                            <li class="item"><a href="quests.html">Quêtes</a></li>
                            <li class="item"><a href="challenges.html">Défis</a></li>
                            <li class="item"><a href="impact.html">Notre Impact</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-12 pl_lg--80">
                    <div class="footer-single-wized">
                        <h5 class="wized-title">Communauté</h5>
                        <ul class="wizid-lists">
                            <li class="item"><a href="forum.php">Forum</a></li>
                            <li class="item"><a href="leaderboards.html">Classements</a></li>
                            <li class="item"><a href="achievements.html">Récompenses</a></li>
                            <li class="item"><a href="partners.html">Partenaires</a></li>
                            <li class="item"><a href="blog.html">Blog</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6 col-sm-12">
                    <div class="footer-single-wized">
                        <h5 class="wized-title">Support</h5>
                        <ul class="wizid-lists">
                            <li class="item"><a href="contact.html">Contact</a></li>
                            <li class="item"><a href="faq.html">FAQ</a></li>
                            <li class="item"><a href="help-center.html">Centre d'aide</a></li>
                            <li class="item"><a href="privacy.html">Confidentialité</a></li>
                            <li class="item"><a href="terms.html">Conditions</a></li>
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
                    const input = document.getElementById('imageInput');
            const fileName = document.querySelector('.file-name');

            input.addEventListener('change', function() {
                if(this.files.length > 0){
                    fileName.textContent = this.files[0].name;
                } else {
                    fileName.textContent = "Aucune image sélectionnée";
                }
            });
        document.addEventListener('DOMContentLoaded', function() {
            const postContent = document.getElementById('postContent');
            const charCount = document.getElementById('charCount');
            const previewBtn = document.getElementById('previewBtn');
            const previewSection = document.getElementById('previewSection');
            const previewContent = document.getElementById('previewContent');
            const createPostForm = document.getElementById('createPostForm');
            
            // Compteur de caractères
            postContent.addEventListener('input', function() {
                const length = this.value.length;
                charCount.textContent = length;
                
                if (length > 4500) {
                    charCount.style.color = '#ff6b6b';
                } else if (length > 4000) {
                    charCount.style.color = '#ffa500';
                } else {
                    charCount.style.color = '#aaa';
                }
            });
            
            // Fonctionnalité de la barre d'outils
            const toolbarBtns = document.querySelectorAll('.toolbar-btn');
            toolbarBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const format = this.getAttribute('data-format');
                    applyFormat(format);
                });
            });
            
            function applyFormat(format) {
                const start = postContent.selectionStart;
                const end = postContent.selectionEnd;
                const selectedText = postContent.value.substring(start, end);
                let formattedText = '';
                
                switch(format) {
                    case 'bold':
                        formattedText = `**${selectedText}**`;
                        break;
                    case 'italic':
                        formattedText = `_${selectedText}_`;
                        break;
                    case 'link':
                        const url = prompt('Entrez l\'URL:');
                        if (url) {
                            formattedText = `[${selectedText}](${url})`;
                        } else {
                            return;
                        }
                        break;
                    case 'code':
                        formattedText = "```\n" + selectedText + "\n```";
                        break;
                    case 'quote':
                        formattedText = `> ${selectedText}`;
                        break;
                }
                
                postContent.setRangeText(formattedText, start, end, 'select');
                postContent.focus();
            }
            
            // Fonction d'aperçu
            previewBtn.addEventListener('click', function() {
                const content = postContent.value;
                
                if (!content.trim()) {
                    alert('Veuillez écrire quelque chose avant de prévisualiser');
                    return;
                }
                
                // Conversion basique du markdown en HTML
                let htmlContent = content
                    .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                    .replace(/_(.*?)_/g, '<em>$1</em>')
                    .replace(/\[(.*?)\]\((.*?)\)/g, '<a href="$2" target="_blank">$1</a>')
                    .replace(/```\n([\s\S]*?)\n```/g, '<pre><code>$1</code></pre>')
                    .replace(/> (.*?)(\n|$)/g, '<blockquote>$1</blockquote>')
                    .replace(/\n/g, '<br>');
                
                previewContent.innerHTML = htmlContent;
                previewSection.style.display = 'block';
                
                // Faire défiler jusqu'à l'aperçu
                previewSection.scrollIntoView({ behavior: 'smooth' });
            });
            
            // Soumission du formulaire
            createPostForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const content = postContent.value.trim();
                
                if (!content) {
                    alert('Veuillez écrire quelque chose avant de publier');
                    return;
                }
                
                if (content.length < 10) {
                    alert('Votre post est trop court. Veuillez écrire au moins 10 caractères.');
                    return;
                }
                
                // Simulation d'envoi (à remplacer par votre logique backend)
                const submitBtn = this.querySelector('.btn-submit');
                const originalText = submitBtn.innerHTML;
                
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Publication...';
                submitBtn.disabled = true;
                
                setTimeout(() => {
                    alert('Post publié avec succès !');
                    window.location.href = 'forum.php';
                }, 1500);
            });
            
            // Focus automatique sur le textarea
            postContent.focus();
        });
    </script>
</body>

</html>