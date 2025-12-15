<?php
// Set error reporting high during development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

// CRITICAL: We assume quiz-controller.php is in the same directory for this simple include.
// If it's in a different folder, adjust the path below!
$controller_path = '../../../../Controller/quiz-controller.php'; 

$categories = ["Environment", "Science", "History", "Social Impact", "Personal Growth", "General Knowledge", "Technology", "Arts & Culture"]; 
$has_backend = false;

// Attempt the simple include
if (file_exists($controller_path)) {
    try {
        // Suppress errors during the initial inclusion attempt 
        // as the controller's dependencies might be complex.
        @require_once $controller_path; 
        
        $quizController = new QuizController();
        $allQuizzes = $quizController->listQuizzes();
        
        $uniqueCategories = [];
        foreach ($allQuizzes as $quiz) {
            $uniqueCategories[$quiz->getCategorie()] = true;
        }
        $categories = array_keys($uniqueCategories);
        $has_backend = true;

    } catch (Exception $e) {
        error_log("Failed to load quizzes in offline-quiz.php: " . $e->getMessage());
    }
}
// Note: $_SESSION['error'] display block in the HTML body remains the same.


// Creative Icon/Color Map (for display logic)
$categoryDetails = [
    "Environment" => ["icon" => "fas fa-leaf", "color" => "#38c172"],
    "Science" => ["icon" => "fas fa-flask", "color" => "#1b6f9a"],
    "History" => ["icon" => "fas fa-scroll", "color" => "#e3342f"],
    "Histoire" => ["icon" => "fas fa-scroll", "color" => "#e3342f"], 
    "Technologie" => ["icon" => "fas fa-microchip", "color" => "#8338ec"], 
    "Social Impact" => ["icon" => "fas fa-handshake", "color" => "#f6993f"],
    "Personal Growth" => ["icon" => "fas fa-seedling", "color" => "#6574cd"],
    "General Knowledge" => ["icon" => "fas fa-lightbulb", "color" => "#f7c35f"],
    "Technology" => ["icon" => "fas fa-microchip", "color" => "#8338ec"],
    "Arts & Culture" => ["icon" => "fas fa-palette", "color" => "#d5573b"],
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Zitouna Quests || Offline Battle Mode</title>
    <meta name="robots" content="noindex">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/fab-icon.png">
    
    <link rel="stylesheet" href="assets/css/plugins/gordita.css">
    <link rel="stylesheet" href="assets/css/plugins/fontawesome-pro-icons.css">
    <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        /* Global Background Animation - Inherited from index */
        body.rt_bg-secondary {
            background: linear-gradient(135deg, #005248, #00c49f, #00796b);
            background-size: 400% 400%;
            animation: moveGradient 20s ease infinite;
        }

        /* Main Glassy Container (Larger and more centered) */
        .setup-container {
            background: rgba(255, 255, 255, 0.08); /* More subtle base */
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border: 2px solid rgba(255, 255, 255, 0.3); /* Stronger white border */
            border-radius: 40px; /* More rounded */
            padding: 60px 50px; /* More padding */
            box-shadow: 0 15px 60px rgba(0, 0, 0, 0.6); /* Deeper shadow */
            max-width: 1100px;
            margin: 80px auto;
            color: #fff;
            animation: fadeIn 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
        }

        /* Title Area */
        .setup-container h2 {
            font-size: 3rem;
            font-weight: 800;
            color: #00C49F;
            text-shadow: 0 0 10px rgba(0, 196, 159, 0.5);
            margin-bottom: 10px;
            padding-bottom: 20px;
            border-bottom: 3px dashed rgba(255, 255, 255, 0.1); /* Creative separator */
            text-align: center;
        }

        /* Sub-Title for Category Selection */
        .category-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #FFBB28; /* Gold highlight */
            margin: 30px 0 35px;
            text-align: center;
            letter-spacing: 1px;
        }

        /* Input Fields (Refined Design) */
        .form-label-custom {
            font-weight: 700;
            font-size: 1.2rem;
            color: #fff;
            margin-bottom: 10px;
            display: block;
            text-align: center;
        }
        
        .team-input-group .form-control {
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 18px 20px;
            border-radius: 15px;
            color: #fff;
        }
        .team-input-group .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }
        .team-input-group .form-control:focus {
            box-shadow: 0 0 15px rgba(255, 187, 40, 0.5);
            border-color: #FFBB28;
        }

        /* Category Grid (More open layout) */
        .category-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); /* Responsive grid */
            gap: 20px;
            margin-bottom: 40px;
        }

        /* Individual Category Card (Creative and interactive) */
        .category-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 150px; /* Fixed height for uniformity */
            padding: 20px;
            background: rgba(255, 255, 255, 0.1);
            border: 3px solid transparent; /* Border for selected state */
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            position: relative;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .category-btn:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(255,255,255,0.1), transparent 50%);
            transition: opacity 0.3s ease;
            opacity: 0;
        }
        .category-btn:hover:before {
            opacity: 1;
        }
        .category-btn:hover {
            transform: translateY(-8px) scale(1.03);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.5);
        }

        /* Category Icon */
        .category-btn i {
            font-size: 3.5rem; /* Larger icon */
            margin-bottom: 10px;
            transition: color 0.3s ease;
            color: rgba(255, 255, 255, 0.7);
        }

        /* Category Name */
        .category-btn span {
            font-size: 1.2rem;
            font-weight: 700;
            color: #fff;
            line-height: 1.2;
            transition: color 0.3s ease;
        }

        /* SELECTED STATE */
        .category-btn.selected {
            border-color: #FFBB28; /* Gold outer glow */
            background: rgba(0, 196, 159, 0.3); /* Green translucent fill */
            box-shadow: 0 0 25px rgba(255, 187, 40, 0.8), inset 0 0 10px rgba(0, 196, 159, 0.9);
            transform: scale(1.05);
        }
        .category-btn.selected i {
            color: #FFBB28; /* Gold icon when selected */
        }
        .category-btn.selected span {
            color: #fff;
        }

        /* Disabled State (When 4 are selected) */
        .category-btn[disabled]:not(.selected) {
            opacity: 0.5;
            cursor: default;
            background: rgba(255, 255, 255, 0.05);
            transform: none;
            box-shadow: none;
        }
        .category-btn[disabled]:not(.selected):before {
            opacity: 0;
        }

        /* Selection Message Bar (Prominent) */
        #selection-message {
            padding: 15px;
            border-radius: 15px;
            font-size: 1.1rem;
            font-weight: 600;
            text-align: center;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            margin-top: 20px;
        }
        #selection-message.ready {
            color: #00C49F;
            box-shadow: 0 0 10px rgba(0, 196, 159, 0.5);
            border-color: #00C49F;
        }

        /* Submit Button */
        #start-game-btn {
            font-size: 1.2rem;
            padding: 15px 40px;
            font-weight: 700;
            border-radius: 30px;
            background-color: #00C49F;
            border: 2px solid #00C49F;
            transition: all 0.3s ease;
            margin-top: 30px;
        }
        #start-game-btn:hover:not(:disabled) {
            background-color: #FFBB28;
            border-color: #FFBB28;
            color: #000;
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(255, 187, 40, 0.5);
        }
        #start-game-btn:disabled {
            opacity: 0.6;
            background-color: #666;
            border-color: #666;
        }

        /* Custom animation keyframes */
        @keyframes moveGradient { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(40px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>

<body class="rt_bg-secondary">
    <div class="bg-animation">
        <div class="blob blob1"></div>
        <div class="blob blob2"></div>
    </div>
    <div class="rts-section-gap">
        <div class="container">
            <div class="setup-container">
                <h2 data-sal-delay="100" data-sal-duration="800" data-sal="slide-down">
                    <i class="fas fa-hat-wizard me-3"></i> The Grand Quiz Arena
                </h2>
                
<form id="offline-quiz-form" method="POST" action="start-offline-game.php">                    
                    <div class="row g-5 mb-5">
                        <div class="col-md-6">
                            <label for="team1_name" class="form-label-custom"><i class="fas fa-users me-2"></i> Team 1: The Challenger</label>
                            <div class="input-group team-input-group">
                                <input type="text" class="form-control" id="team1_name" name="team1_name" placeholder="Enter Team 1 Name" required value="The Green Heroes">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="team2_name" class="form-label-custom"><i class="fas fa-users me-2"></i> Team 2: The Conqueror</label>
                            <div class="input-group team-input-group">
                                <input type="text" class="form-control" id="team2_name" name="team2_name" placeholder="Enter Team 2 Name" required value="Quiz Dragons">
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <p class="category-title" data-sal-delay="200" data-sal-duration="800" data-sal="slide-up">
                            <i class="fas fa-dice-d6 me-2"></i> Select Your Battlegrounds (4 Required)
                        </p>
                        
                        <div class="category-grid" id="category-selection-grid">
                            <?php foreach ($categories as $category): ?>
                                <?php 
                                    $details = $categoryDetails[$category] ?? ["icon" => "fas fa-question-circle", "color" => "#999999"];
                                    $icon = $details["icon"];
                                    $color = $details["color"];
                                    $categorySlug = htmlspecialchars(strtolower(str_replace(' ', '-', preg_replace("/[^A-Za-z0-9 ]/", '', $category))));
                                ?>
                                <button type="button" class="category-btn" data-category="<?php echo $categorySlug; ?>">
                                    <i class="<?php echo $icon; ?>" style="color: <?php echo $color; ?>;"></i>
                                    <span><?php echo htmlspecialchars($category); ?></span>
                                </button>
                            <?php endforeach; ?>
                        </div>
                        
                        <p id="selection-message" class="text-center" data-sal-delay="300" data-sal-duration="800" data-sal="slide-up">
                            <i class="fas fa-info-circle me-1"></i> Please select exactly 4 categories to proceed. (0/4)
                        </p>
                        
                        <input type="hidden" name="selected_categories" id="selected_categories" required>
                    </div>

                    <div class="text-center">
                        <button type="submit" id="start-game-btn" class="rts-btn btn-primary" disabled data-sal-delay="400" data-sal-duration="800" data-sal="slide-up">
                            <i class="fas fa-check-circle me-2"></i> Confirm & Launch Battle
                        </button>
                    </div>

                </form>
            </div>
        </div>

        <!-- ==== ERROR MESSAGE DISPLAY ==== -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="container mt-4">
                <div class="alert alert-danger text-center" style="background: rgba(220, 53, 69, 0.85); color: white; border: 2px solid #f5c2c7; border-radius: 15px; padding: 20px; font-size: 1.1rem;">
                    <h4 class="alert-heading" style="color: white;"><i class="fas fa-exclamation-triangle"></i> Erreur de Configuration !</h4>
                    <p style="color: white; margin-bottom: 0;"><?php echo htmlspecialchars($_SESSION['error']); ?></p>
                </div>
            </div>
            <?php unset($_SESSION['error']); // On efface l'erreur après l'avoir affichée ?>
        <?php endif; ?>
        <!-- =============================== -->

    </div>


    <script src="assets/js/vendor/jquery.min.js"></script>
    <script src="assets/js/vendor/bootstrap.min.js"></script>
    <script src="assets/js/plugins/sal.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize SAL (Scroll Animation Library) for the new elements
            sal();

            const maxCategories = 4;
            let selectedCategories = [];
            const $selectionMessage = $('#selection-message');
            const $startGameBtn = $('#start-game-btn');
            const $selectedCategoriesInput = $('#selected_categories');
            const $form = $('#offline-quiz-form'); // Get the form element

            function updateSelection() {
                selectedCategories = $('.category-btn.selected').map(function() {
                    return $(this).data('category');
                }).get();

                // 1. ALWAYS UPDATE HIDDEN INPUT
                $selectedCategoriesInput.val(selectedCategories.join(','));

                // 2. Update UI based on selection count
                if (selectedCategories.length === maxCategories) {
                    $selectionMessage.html('<i class="fas fa-check-circle me-2"></i> **Success!** The battlegrounds are set. Launch the game!');
                    $selectionMessage.addClass('ready');
                    $startGameBtn.prop('disabled', false).removeClass('disabled-temp');
                    $('.category-btn:not(.selected)').prop('disabled', true);
                } else {
                    $selectionMessage.html(`<i class="fas fa-info-circle me-2"></i> Choose exactly ${maxCategories} battlegrounds. (${selectedCategories.length}/${maxCategories})`);
                    $selectionMessage.removeClass('ready');
                    $startGameBtn.prop('disabled', true).addClass('disabled-temp');
                    $('.category-btn[disabled]').prop('disabled', false); 
                }
            }

            // Handle category button clicks
            $('.category-btn').on('click', function() {
                const $this = $(this);
                const isSelected = $this.hasClass('selected');

                if (isSelected) {
                    $this.removeClass('selected');
                } else if (selectedCategories.length < maxCategories) {
                    $this.addClass('selected');
                }
                
                updateSelection();
            });

            // 🔑 CRITICAL FIX: Intercept form submission to force button state recognition.
            $form.on('submit', function(e) {
                // Check if the submit button is disabled. If it is, prevent submission.
                if ($startGameBtn.is(':disabled')) {
                    e.preventDefault(); // Stop submission if criteria aren't met
                    $selectionMessage.html('<i class="fas fa-exclamation-triangle me-2"></i> Error: Please select 4 valid categories.');
                    $selectionMessage.css('color', '#FF6B6B');
                    return false;
                }
                // If this point is reached, the form is allowed to submit.
            });

            // Initial state check
            updateSelection();
        });
    </script>
</body>
</html>