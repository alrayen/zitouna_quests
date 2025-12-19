<?php
require_once __DIR__ . '/../../../../Controller/question-controller.php';

$quizId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$questionController = new QuestionController();
$questionsFromDb = $questionController->listQuestionsByQuizId($quizId);

$finalQuizData = [];

foreach ($questionsFromDb as $q) {
    $correctAnswer = $q->getBonneReponse(); 

    $answers = [
        [ "text" => $q->getOption1(), "correct" => ($q->getOption1() === $correctAnswer) ],
        [ "text" => $q->getOption2(), "correct" => ($q->getOption2() === $correctAnswer) ],
        [ "text" => $q->getOption3(), "correct" => ($q->getOption3() === $correctAnswer) ],
        [ "text" => $q->getOption4(), "correct" => ($q->getOption4() === $correctAnswer) ]
    ];

    $finalQuizData[] = [
        "question" => $q->getTextQuestion(),
        "answers" => $answers
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zitouna Quests - Take Quiz</title>
    
    <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/plugins/gordita.css">
    <link rel="stylesheet" href="assets/css/plugins/fontawesome-pro-icons.css">
    <link rel="stylesheet" href="assets/css/vendor/swiper.css">
    <link rel="stylesheet" href="assets/css/plugins/unicons.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <style>
        /* --- Animation Keyframes --- */
        @keyframes float { 0% { transform: translateY(0) translateX(0); } 50% { transform: translateY(-20px) translateX(20px); } 100% { transform: translateY(0) translateX(0); } }
        @keyframes moveGradient { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes popIn { 0% { opacity: 0; transform: scale(0.8); } 100% { opacity: 1; transform: scale(1); } }

        /* --- Body & Background --- */
        body {
            background: linear-gradient(135deg, #005248, #00c49f, #00796b) !important;
            background-size: 400% 400% !important;
            animation: moveGradient 20s ease infinite;
            overflow-x: hidden;
            color: #fff;
        }

        /* --- Animated Background Blobs --- */
        .bg-animation {
            position: fixed; top: 0; left: 0; width: 100%; height: 100vh; z-index: -2; overflow: hidden;
        }
        .bg-animation .blob { position: absolute; border-radius: 50%; filter: blur(100px); opacity: 0.4; animation: float 25s ease-in-out infinite alternate; }
        .bg-animation .blob1 { width: 400px; height: 400px; background: rgba(89, 255, 228, 0.5); top: -50px; left: -100px; animation-duration: 20s; }
        .bg-animation .blob2 { width: 300px; height: 300px; background: rgba(255, 255, 255, 0.3); bottom: -80px; right: -80px; animation-duration: 30s; }

        /* --- Header Override --- */
        .rts-header-area { background: transparent !important; position: relative; z-index: 100; }
        
        /* --- Main Quiz Container --- */
        .quiz-container { padding-top: 50px; padding-bottom: 100px; animation: fadeIn 0.8s ease-out; max-width: 800px; margin: 0 auto; position: relative; z-index: 1; }

        /* --- Quiz Header (Progress & Timer) --- */
        .quiz-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }

        /* --- Progress Bar --- */
        .quiz-progress { display: flex; align-items: center; list-style: none; padding: 0; margin: 0; position: relative; z-index: 2; }
        .progress-step { width: 40px; height: 40px; border-radius: 50%; background: rgba(255, 255, 255, 0.1); color: #fff; border: 1px solid rgba(255, 255, 255, 0.2); backdrop-filter: blur(5px); display: flex; align-items: center; justify-content: center; font-weight: 700; margin-right: 20px; transition: all 0.4s ease; }
        .progress-step.active { background: #fff; color: #00796b; border-color: #fff; box-shadow: 0 5px 15px rgba(0, 196, 159, 0.4); transform: scale(1.1); }
        .progress-step.completed { background: #00E6A7; border-color: #00E6A7; color: #fff; }
        .progress-step .icon { font-weight: 900; }
        
        /* --- Timer --- */
        .quiz-timer { width: 80px; height: 80px; border-radius: 50%; background: #FFBB28; color: #0d1117; display: flex; align-items: center; justify-content: center; flex-direction: column; font-weight: 700; box-shadow: 0 0 20px rgba(255, 187, 40, 0.5); border: 4px solid #fff; padding-top: 10px; text-align: center; flex-shrink: 0; }
        .quiz-timer-time { font-size: 1.5rem; line-height: 1; margin: 0; }
        .quiz-timer-text { font-size: 0.7rem; line-height: 1; }
        
        /* --- Quiz Body --- */
        .quiz-body { overflow: hidden; border-radius: 20px; }
        .quiz-slider { display: flex; transition: transform 0.5s cubic-bezier(0.25, 1, 0.5, 1); }
        .quiz-question-slide { width: 100%; flex-shrink: 0; background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(15px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 20px; box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.1); padding: 40px; }
        .question-title { text-align: center; font-size: 2rem; font-weight: 600; margin-bottom: 30px; }
        
        /* --- Answer Options --- */
        .answer-options { list-style: none; padding: 0; margin: 0; display: grid; grid-template-columns: 1fr; gap: 15px; }
        @media (min-width: 768px) { .answer-options { grid-template-columns: 1fr 1fr; } }
        
        .answer-btn { display: block; width: 100%; background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2); backdrop-filter: blur(5px); border-radius: 15px; padding: 20px; color: #fff; font-size: 1.1rem; font-weight: 500; cursor: pointer; transition: all 0.3s ease; text-align: left; }
        .answer-btn:hover { background: rgba(255, 255, 255, 0.2); border-color: rgba(255, 255, 255, 0.4); transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        .answer-options.disabled .answer-btn:not(.selected) { opacity: 0.5; pointer-events: none; }
        .answer-btn.selected { font-weight: 700; }
        .answer-btn.correct { background: rgba(0, 230, 167, 0.3) !important; border-color: #00E6A7 !important; box-shadow: 0 0 20px rgba(0, 230, 167, 0.5) !important; }
        .answer-btn.incorrect { background: rgba(255, 107, 107, 0.3) !important; border-color: #FF6B6B !important; box-shadow: 0 0 20px rgba(255, 107, 107, 0.5) !important; }

        /* --- Navigation Buttons --- */
        .quiz-footer { display: flex; justify-content: space-between; margin-top: 40px; }
        .quiz-btn { border-radius: 25px; font-weight: 700; padding: 12px 30px; transition: all 0.3s ease; font-size: 1rem; text-transform: uppercase; border: none; cursor: pointer; }
        .next-btn { background: #fff; color: #00796b; border: 1px solid #fff; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); }
        .next-btn:hover { background: #00C49F; color: #fff; border-color: #00C49F; box-shadow: 0 5px 15px rgba(0, 196, 159, 0.4); transform: translateY(-2px); }
        .prev-btn { background: transparent; color: #fff; border: 1px solid #fff; }
        .prev-btn:hover { background: rgba(255, 255, 255, 0.1); transform: translateY(-2px); }
        .quiz-btn.hidden { opacity: 0; pointer-events: none; }
        
        /* NEW: Finish Button Mode */
        .next-btn.btn-finish-mode {
            background: #FFBB28 !important; /* Gold */
            color: #111 !important;
            border-color: #FFBB28 !important;
            box-shadow: 0 0 20px rgba(255, 187, 40, 0.6);
        }
        .next-btn.btn-finish-mode:hover {
            transform: scale(1.05);
            box-shadow: 0 0 30px rgba(255, 187, 40, 0.8);
        }

        /* --- NEW: RESULT OVERLAY & CARD --- */
        .result-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 30, 26, 0.85);
            backdrop-filter: blur(10px);
            z-index: 999;
            display: none; /* Hidden by default */
            justify-content: center;
            align-items: center;
            animation: fadeIn 0.5s ease;
        }
        
        .result-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 30px;
            padding: 50px;
            text-align: center;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
            animation: popIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }

        /* Circular Progress */
        .progress-circle {
            position: relative;
            width: 150px;
            height: 150px;
            margin: 0 auto 30px auto;
        }
        .progress-circle svg {
            width: 150px; height: 150px; transform: rotate(-90deg);
        }
        .progress-circle circle {
            fill: none; stroke-width: 10; stroke-linecap: round;
        }
        .progress-bg { stroke: rgba(255,255,255,0.1); }
        .progress-fill {
            stroke: #00E6A7;
            stroke-dasharray: 440;
            stroke-dashoffset: 440; /* Start empty */
            transition: stroke-dashoffset 1.5s ease-in-out;
        }
        .score-text {
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            font-size: 2.5rem;
            font-weight: 800;
            color: #fff;
        }
        .score-text span { font-size: 1rem; font-weight: 400; display: block; }

        .result-title { font-size: 2rem; font-weight: 700; margin-bottom: 10px; color: #fff; }
        .result-message { font-size: 1.1rem; color: rgba(255,255,255,0.8); margin-bottom: 30px; }
        
        .result-actions { display: flex; gap: 15px; justify-content: center; }
        .action-btn {
            padding: 12px 25px; border-radius: 50px; font-weight: 600; cursor: pointer; border: none; transition: 0.3s;
        }
        .btn-home { background: rgba(255,255,255,0.1); color: white; border: 1px solid rgba(255,255,255,0.3); }
        .btn-home:hover { background: rgba(255,255,255,0.2); }
        .btn-retry { background: #00E6A7; color: #004d40; }
        .btn-retry:hover { background: #00c49f; transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0, 230, 167, 0.4); }

    </style>
</head>

<body>
    <div class="bg-animation">
        <div class="blob blob1"></div>
        <div class="blob blob2"></div>
    </div>

    <div class="rts-header-area header-inner-one header--sticky" style="background: transparent; position: relative;">
        <div class="container-header">
            <div class="row align-items-center ptb_sm--20 padding-controler-header">
                <div class="col-xl-2 col-lg-4 col-md-4 col-sm-12 ">
                    <div class="header-left">
                        <a href="index.php" class="logo">
                            <img src="./assets/images/logo/logo3.png" alt="Zitouna Quests Logo">
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
                <div class="col-xl-5 col-lg-8 col-md-8 col-sm-12 justify-content-sm-center d-xsm-flex">
                    <div class="header-right">
                        <ul class="icons">
                            <li class="icon user"> <a href="author.php"><i class="far fa-user"></i></a></li>
                            <li class="icon notification"> <a href="#"><i class="far fa-bell" alt="notification"></i></a></li>
                        </ul>
                        <a id="connect-wallet" href="login.php" class="rts-btn btn-primary">login / sign up</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="quiz-container container">
        
        <div class="quiz-header">
            <ul class="quiz-progress" id="quiz-progress"></ul>
            <div class="quiz-timer" id="quiz-timer">
                <div class="quiz-timer-time" id="timer-time">60</div>
                <div class="quiz-timer-text">sec</div>
            </div>
        </div>

        <div class="quiz-body">
            <div class="quiz-slider" id="quiz-slider"></div>
        </div>

        <div class="quiz-footer">
            <button class="quiz-btn prev-btn hidden" id="prev-btn"><span><i class="fas fa-arrow-left"></i></span> Back</button>
            <button class="quiz-btn next-btn" id="next-btn">Next Question <span><i class="fas fa-arrow-right"></i></span></button>
        </div>
    </div>

    <div class="result-overlay" id="result-overlay">
        <div class="result-card">
            <div class="progress-circle">
                <svg>
                    <circle class="progress-bg" cx="75" cy="75" r="70"></circle>
                    <circle class="progress-fill" id="score-circle" cx="75" cy="75" r="70"></circle>
                </svg>
                <div class="score-text">
                    <div id="final-score-display">0%</div>
                </div>
            </div>
            
            <h2 class="result-title" id="result-title">Completed!</h2>
            <p class="result-message" id="result-message">You scored X out of Y correct answers.</p>
            
            <div class="result-actions">
                <button onclick="window.location.href='index.php'" class="action-btn btn-home">
                    <i class="fas fa-home"></i> Home
                </button>
                <button onclick="location.reload()" class="action-btn btn-retry">
                    <i class="fas fa-redo"></i> Retry Quiz
                </button>
            </div>
        </div>
    </div>
    
    <script src="assets/js/vendor/jquery.min.js"></script>
    <script src="assets/js/main.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const quizData = <?php echo json_encode($finalQuizData); ?>;

            if (!quizData || quizData.length === 0) {
                document.querySelector('.quiz-body').innerHTML = '<h3 style="text-align:center; color:white;">No questions found for this quiz ID.</h3>';
                return; 
            }

            // DOM ELEMENTS
            const progressContainer = document.getElementById('quiz-progress');
            const slider = document.getElementById('quiz-slider');
            const prevBtn = document.getElementById('prev-btn');
            const nextBtn = document.getElementById('next-btn');
            const timerTime = document.getElementById('timer-time');
            const resultOverlay = document.getElementById('result-overlay');
            const scoreCircle = document.getElementById('score-circle');

            // STATE
            let currentQuestionIndex = 0;
            let timerInterval;
            let timeRemaining = 60;
            let userScore = 0;

            // INITIALIZE
            function initQuiz() {
                slider.innerHTML = '';
                progressContainer.innerHTML = '';

                quizData.forEach((questionData, index) => {
                    // Slide HTML
                    const slide = document.createElement('div');
                    slide.className = 'quiz-question-slide';
                    slide.innerHTML = `
                        <h1 class="question-title">${questionData.question}</h1>
                        <ul class="answer-options" data-question-index="${index}">
                            ${questionData.answers.map((answer) => `
                                <li>
                                    <button class="answer-btn" data-correct="${answer.correct}">
                                        ${answer.text}
                                    </button>
                                </li>
                            `).join('')}
                        </ul>
                    `;
                    slider.appendChild(slide);

                    // Progress HTML
                    const step = document.createElement('li');
                    step.className = 'progress-step';
                    step.innerHTML = `<span class="text">${index + 1}</span><span class="icon" style="display:none;"><i class="fas fa-check"></i></span>`;
                    progressContainer.appendChild(step);
                });

                document.querySelectorAll('.answer-options').forEach(optionsList => {
                    optionsList.addEventListener('click', handleAnswerClick);
                });

                startTimer();
                goToSlide(0);
            }

            // SLIDER LOGIC (UPDATED)
            function goToSlide(index) {
                slider.style.transform = `translateX(-${index * 100}%)`;
                
                document.querySelectorAll('.progress-step').forEach((step, i) => {
                    const text = step.querySelector('.text');
                    const icon = step.querySelector('.icon');
                    
                    if (i < index) { 
                        step.classList.add('completed'); step.classList.remove('active');
                        text.style.display = 'none'; icon.style.display = 'inline';
                    } else if (i === index) { 
                        step.classList.add('active'); step.classList.remove('completed');
                        text.style.display = 'inline'; icon.style.display = 'none';
                    } else { 
                        step.classList.remove('active', 'completed');
                        text.style.display = 'inline'; icon.style.display = 'none';
                    }
                });
                
                currentQuestionIndex = index;
                
                // BUTTON VISIBILITY LOGIC
                prevBtn.classList.toggle('hidden', index === 0);
                
                // *** FIX: Ensure nextBtn is VISIBLE on last slide (it becomes Finish) ***
                nextBtn.classList.remove('hidden');

                if (index === quizData.length - 1) {
                    nextBtn.innerHTML = 'Finish Quiz <span><i class="fas fa-flag-checkered"></i></span>';
                    nextBtn.classList.add('btn-finish-mode'); // Turn Gold
                } else {
                    nextBtn.innerHTML = 'Next Question <span><i class="fas fa-arrow-right"></i></span>';
                    nextBtn.classList.remove('btn-finish-mode');
                }
            }

            // ANSWER CLICK LOGIC
            function handleAnswerClick(event) {
                const clickedButton = event.target.closest('.answer-btn');
                if (!clickedButton) return;

                const optionsList = clickedButton.closest('.answer-options');
                if (optionsList.classList.contains('disabled')) return; 

                optionsList.classList.add('disabled'); 
                clickedButton.classList.add('selected');

                const isCorrect = clickedButton.dataset.correct === 'true';
                if (isCorrect) {
                    clickedButton.classList.add('correct');
                    userScore++;
                } else {
                    clickedButton.classList.add('incorrect');
                }
            }

            prevBtn.addEventListener('click', () => {
                if (currentQuestionIndex > 0) goToSlide(currentQuestionIndex - 1);
            });

            nextBtn.addEventListener('click', () => {
                if (currentQuestionIndex < quizData.length - 1) {
                    goToSlide(currentQuestionIndex + 1);
                } else {
                    // FINISH TRIGGER
                    finishQuiz();
                }
            });

            // TIMER
            function startTimer() {
                timeRemaining = 60;
                timerTime.textContent = timeRemaining;
                clearInterval(timerInterval);
                
                timerInterval = setInterval(() => {
                    timeRemaining--;
                    timerTime.textContent = timeRemaining;
                    if (timeRemaining <= 0) {
                        clearInterval(timerInterval);
                        alert('Time is up! Calculating results...');
                        finishQuiz();
                    }
                }, 1000);
            }

            // RESULT DISPLAY
            function finishQuiz() {
                clearInterval(timerInterval);
                
                const totalQuestions = quizData.length;
                const percentage = Math.round((userScore / totalQuestions) * 100);
                
                let title = "Good Effort!";
                let message = `You got ${userScore} out of ${totalQuestions} questions right.`;
                let color = "#FFBB28"; 

                if (percentage === 100) {
                    title = "Legendary!";
                    message = "Perfect score! You are a true master.";
                    color = "#00E6A7";
                } else if (percentage >= 80) {
                    title = "Excellent Work!";
                    message = "You have a great understanding of this topic.";
                    color = "#00E6A7";
                } else if (percentage < 50) {
                    title = "Don't Give Up!";
                    message = "Review the material and try again.";
                    color = "#FF6B6B";
                }

                document.getElementById('result-title').innerText = title;
                document.getElementById('result-message').innerText = message;
                document.getElementById('final-score-display').innerText = percentage + "%";
                document.getElementById('result-title').style.color = color;

                resultOverlay.style.display = "flex";

                const circumference = 440; 
                const offset = circumference - (percentage / 100) * circumference;
                
                setTimeout(() => {
                    scoreCircle.style.strokeDashoffset = offset;
                    scoreCircle.style.stroke = color;
                }, 100);
            }

            initQuiz();
        });
    </script>
</body>
</html>