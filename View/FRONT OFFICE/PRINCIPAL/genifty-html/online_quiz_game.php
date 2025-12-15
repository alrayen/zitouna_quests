<?php
session_start();
require_once __DIR__ . '/../../../../config.php'; 

$session_id = isset($_GET['session']) ? (int)$_GET['session'] : 0;
$current_user_id = $_SESSION['user_id'] ?? 0;

if ($session_id === 0 || $current_user_id === 0) {
    header("Location: quizzes.php"); 
    exit;
}

$puzzle_bg = "https://images.unsplash.com/photo-1620641788421-7a1c342ea42e?auto=format&fit=crop&w=300&q=80"; 
$quiz_title = "Mystery Quest";

try {
    $pdo = config::getConnexion(); 
    $stmt = $pdo->prepare("SELECT q.titre FROM online_sessions os JOIN quiz q ON os.current_quiz_id = q.id_quiz WHERE os.session_id = ?");
    $stmt->execute([$session_id]);
    $fetched_title = $stmt->fetchColumn();

    if ($fetched_title) {
        $quiz_title = $fetched_title;
        $prompt = urlencode($fetched_title . " epic futuristic video game poster style");
        $puzzle_bg = "https://image.pollinations.ai/prompt/$prompt?width=300&height=450&nologo=true";
    }
} catch (Exception $e) {}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Quiz: <?php echo htmlspecialchars($quiz_title); ?> - Zitouna Quest</title>
    <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/plugins/fontawesome-pro-icons.css">
	<script src="https://cdn.jsdelivr.net/npm/@mediapipe/camera_utils/camera_utils.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@mediapipe/control_utils/control_utils.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@mediapipe/drawing_utils/drawing_utils.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@mediapipe/hands/hands.js" crossorigin="anonymous"></script>
    <style>
        @keyframes moveGradient { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
        body.rt_bg-secondary {
            background: linear-gradient(135deg, #005248, #00c49f, #00796b);
            background-size: 400% 400%;
            animation: moveGradient 20s ease infinite;
            min-height: 100vh;
        }
        .puzzle-sidebar {
            background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px);
            padding: 20px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3); margin-bottom: 20px;
        }
        .puzzle-grid {
            width: 100%; max-width: 300px; height: 450px; margin: 0 auto;
            background-image: url('<?php echo $puzzle_bg; ?>');
            background-size: cover; background-position: center;
            display: grid; grid-template-columns: repeat(3, 1fr); grid-template-rows: repeat(4, 1fr);
            border: 3px solid rgba(255,255,255,0.5); border-radius: 10px; overflow: hidden; position: relative;
        }
        .puzzle-tile {
            background-color: #2d3436; border: 1px solid rgba(255,255,255,0.1);
            transition: opacity 0.8s ease; display: flex; align-items: center; justify-content: center; z-index: 10;
        }
        .puzzle-tile i { color: rgba(255,255,255,0.1); }
        .puzzle-tile.revealed { opacity: 0; pointer-events: none; }
        .game-container {
            padding: 40px; background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px);
            border-radius: 20px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5); color: #fff; text-align: center; position: relative;
        }
        #question-box { background: rgba(0, 0, 0, 0.2); padding: 25px; border-radius: 15px; margin-bottom: 30px; min-height: 150px; }
        #options-list { list-style: none; padding: 0; margin: 0; }
        .option-btn {
            background: rgba(255, 255, 255, 0.15); border: 1px solid rgba(255, 255, 255, 0.3); color: #fff;
            padding: 15px; margin-bottom: 10px; border-radius: 10px; font-size: 1.1rem; width: 100%; text-align: left; transition: background 0.2s; cursor: pointer;
        }
        .option-btn:hover:not(.disabled) { background: rgba(0, 196, 159, 0.5); }
        .disabled { opacity: 0.6; cursor: not-allowed; }
        #scoreboard { background: rgba(0, 0, 0, 0.3); padding: 15px; border-radius: 10px; display: flex; justify-content: space-around; margin-bottom: 20px; }
        .player-score { text-align: center; font-size: 1.1rem; }
        .streak-fire { border: 3px solid #FF4500 !important; animation: burn-animation 0.8s infinite alternate; }
        @keyframes burn-animation { 0% { box-shadow: 0 0 15px #FF4500; } 100% { box-shadow: 0 0 35px #FF4500; } }
        #combo-badge {
            position: absolute; top: -20px; right: -20px; background: linear-gradient(45deg, #FF512F, #DD2476);
            color: white; font-weight: 800; font-size: 1.5rem; padding: 10px 20px; border-radius: 50px; transform: rotate(15deg); display: none; z-index: 100; border: 2px solid white;
        }
    </style>
    <script src="https://code.responsivevoice.org/responsivevoice.js?key=46M25bDj"></script>
</head>

<body class="rt_bg-secondary">
    <div id="gesture-container" style="position: fixed; bottom: 20px; right: 20px; width: 220px; height: 165px; border: 3px solid #00E6A7; border-radius: 12px; z-index: 9999; background: #000; box-shadow: 0 0 20px rgba(0,230,167,0.5);">
    <video id="input_video" style="display: none;"></video>
    <canvas id="output_canvas" width="220" height="165" style="width: 100%; height: 100%; border-radius: 8px;"></canvas>
    <div id="gesture-status" style="position: absolute; top: 0; left: 0; width:100%; background: rgba(0,0,0,0.8); color: #fff; padding: 4px; font-size: 12px; text-align: center; border-radius: 8px 8px 0 0;">
        🖐 Loading AI...
    </div>
</div>
    <div class="container-fluid" style="padding-top: 50px;">
        <div class="row justify-content-center">
            
            <div class="col-md-3 d-none d-lg-block">
                <div class="puzzle-sidebar">
                    <h4 class="text-white text-center mb-3">Hidden Reward</h4>
                    <div id="puzzle-grid" class="puzzle-grid"></div>
                    <p class="text-center text-white mt-3" style="font-size: 0.9rem; opacity: 0.8;">
                        Current Topic: <br><strong><?php echo htmlspecialchars($quiz_title); ?></strong>
                    </p>
                </div>
            </div>

            <div class="col-md-7">
                <div class="game-container" id="main-card">
                    <span id="combo-badge">COMBO x3! 🔥</span>
                    <h1>Quiz Online</h1>
                    
                    <div id="game-info" class="mb-4 d-flex justify-content-between align-items-center">
                        <span id="question-number" style="font-weight: 700;">Question 1 / X</span>
                        <div>
                            <button id="ai-hint-btn" class="btn btn-sm btn-info text-white mr-2" style="border-radius: 20px; background: #6c5ce7; border:none;">
                                <i class="fas fa-magic"></i> Ask AI Hint
                            </button>
                            <button id="tts-toggle" class="btn btn-sm btn-light" style="border-radius: 20px;">
                                <i class="fas fa-volume-up"></i> Voice: ON
                            </button>
                        </div>
                    </div>

                    <div id="ai-hint-box" style="display:none; background: rgba(108, 92, 231, 0.2); border: 1px solid #6c5ce7; padding: 10px; border-radius: 10px; margin-bottom: 20px; font-style: italic;">
                        🌿 <strong>Wise Zitouna says:</strong> <span id="hint-text">...</span>
                    </div>

                    <div id="question-box">
                        <h2 id="question-text">Chargement de la question...</h2>
                    </div>

                    <ul id="options-list"></ul>
                    
                    <button id="next-question-btn" class="rts-btn btn-primary" style="display:none; margin-top: 20px;">
                        Question Suivante (Hôte)
                    </button>

                    <div id="game-status" style="margin-top: 20px; font-style: italic; color: #d4fcf5;">
                        En attente des joueurs...
                    </div>

                    <h3 class="mt-4">Scores</h3>
                    <div id="scoreboard"></div>
                </div>
            </div>
        </div>
    </div>

<script src="assets/js/vendor/jquery.min.js"></script>
<script>
  
    const videoElement = document.getElementById('input_video');
    const canvasElement = document.getElementById('output_canvas');
    const canvasCtx = canvasElement.getContext('2d');
    const statusDiv = document.getElementById('gesture-status');

    const REQUIRED_HOLD_FRAMES = 15; 
    let currentHoldFrames = 0;
    let lastDetectedFingerCount = -1;

    function onResults(results) {
        canvasCtx.save();
        canvasCtx.clearRect(0, 0, canvasElement.width, canvasElement.height);
        canvasCtx.drawImage(results.image, 0, 0, canvasElement.width, canvasElement.height);

        if (results.multiHandLandmarks && results.multiHandLandmarks.length > 0) {
            const landmarks = results.multiHandLandmarks[0];
            
            drawConnectors(canvasCtx, landmarks, HAND_CONNECTIONS, {color: '#00E6A7', lineWidth: 2});
            drawLandmarks(canvasCtx, landmarks, {color: '#FF0000', lineWidth: 1});

            let fingersUp = 0;
            if (landmarks[8].y < landmarks[6].y) fingersUp++;
            if (landmarks[12].y < landmarks[10].y) fingersUp++;
            if (landmarks[16].y < landmarks[14].y) fingersUp++;
            if (landmarks[20].y < landmarks[18].y) fingersUp++;

            statusDiv.innerHTML = `🖐 Fingers: <strong>${fingersUp}</strong>`;

            if (fingersUp >= 1 && fingersUp <= 4) {
                highlightPotentialChoice(fingersUp);

                if (fingersUp === lastDetectedFingerCount) {
                    currentHoldFrames++;
                    let percent = Math.min(100, Math.round((currentHoldFrames/REQUIRED_HOLD_FRAMES)*100));
                    statusDiv.innerHTML += ` <div style="height:4px; width:${percent}%; background:#00E6A7; margin-top:2px;"></div>`;

                    if (currentHoldFrames === REQUIRED_HOLD_FRAMES) {
                        triggerAnswerClick(fingersUp);
                        currentHoldFrames = 0; 
                    }
                } else {
                    currentHoldFrames = 0;
                    lastDetectedFingerCount = fingersUp;
                    resetHighlights();
                }
            } else {
                currentHoldFrames = 0;
                lastDetectedFingerCount = -1;
                resetHighlights();
            }
        } else {
            statusDiv.innerHTML = "👀 Show hand to answer";
            currentHoldFrames = 0;
            resetHighlights();
        }
        canvasCtx.restore();
    }

    function highlightPotentialChoice(num) {
        $('.option-btn').css('border', '1px solid rgba(255, 255, 255, 0.3)');
        $(`#option-btn-${num}`).css('border', '3px solid #00E6A7');
    }

    function resetHighlights() {
        $('.option-btn').css('border', '1px solid rgba(255, 255, 255, 0.3)');
    }

    function triggerAnswerClick(number) {
        const btnId = `option-btn-${number}`;
        const btn = document.getElementById(btnId);
        
        if (btn && !btn.classList.contains('disabled')) {
            statusDiv.innerHTML = `✅ Selected Option ${number}`;
            btn.click();
            
            btn.style.transform = "scale(0.98)";
            setTimeout(() => btn.style.transform = "scale(1)", 100);
        }
    }

    const hands = new Hands({locateFile: (file) => {
        return `https://cdn.jsdelivr.net/npm/@mediapipe/hands/${file}`;
    }});

    hands.setOptions({
        maxNumHands: 1,
        modelComplexity: 1,
        minDetectionConfidence: 0.7,
        minTrackingConfidence: 0.5
    });

    hands.onResults(onResults);

    const camera = new Camera(videoElement, {
        onFrame: async () => {
            await hands.send({image: videoElement});
        },
        width: 320,
        height: 240
    });
    
    camera.start();
</script>
<script>
    const SESSION_ID = <?php echo $session_id; ?>;
    const USER_ID = <?php echo $current_user_id; ?>;
    
    // Variables
    let currentStreak = 0;
    let syncIntervalId = null; 
    let isHost = false; 
    let currentQuestionIndex = 0;
    let totalQuestions = 0;
    let answered = false;
    let isProcessingNext = false; 
    let voiceEnabled = true;

    $(document).ready(function() {
        const grid = $('#puzzle-grid');
        for (let i = 1; i <= 12; i++) {
            grid.append(`<div class="puzzle-tile" id="tile-${i}"><i class="fas fa-question"></i></div>`);
        }
        
        if (SESSION_ID) {
            updateGameStatus(); 
            syncIntervalId = setInterval(updateGameStatus, 2000); 

            $('#tts-toggle').click(function() {
                voiceEnabled = !voiceEnabled;
                $(this).html(voiceEnabled ? '<i class="fas fa-volume-up"></i> Voice: ON' : '<i class="fas fa-volume-mute"></i> Voice: OFF');
                if(!voiceEnabled && window.responsiveVoice) responsiveVoice.cancel();
            });

            $('#next-question-btn').on('click', function() {
                if (isHost && !$(this).prop('disabled')) {
                    isProcessingNext = true; 
                    const $btn = $(this);
                    $btn.prop('disabled', true).text('Chargement...');
                    $.ajax({
                        url: '../../../../Controller/next_question.php', 
                        type: 'POST', dataType: 'json', data: { session_id: SESSION_ID },
                        success: function(response) {
                             if (!response.success) {
                                alert('Erreur: ' + response.message);
                                $btn.prop('disabled', false).text('Réessayer');
                                isProcessingNext = false; 
                            }
                        }
                    });
                }
            });

            $('#ai-hint-btn').click(function() {
                const $btn = $(this);
                const $hintBox = $('#ai-hint-box');
                const $hintText = $('#hint-text');

                $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Thinking...');
                
                $.ajax({
                    // Basic Relative path. If this fails (404), double check your folders.
                    url: '../../../../Controller/get_ai_hint.php', 
                    type: 'POST',
                    dataType: 'json',
                    // NO JSON.STRINGIFY - Send standard Form Data
                    data: { 
                        session_id: SESSION_ID, 
                        question_index: currentQuestionIndex 
                    },
                    success: function(response) {
                        if (response.success) {
                            $hintText.text(response.hint);
                            $hintBox.fadeIn();
                            speakText("Hint: " + response.hint);
                            $btn.html('<i class="fas fa-check"></i> Hint Used');
                        } else {
                            $btn.prop('disabled', false).html('Error');
                            alert(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        $btn.prop('disabled', false).html('Error');
                        console.log("AJAX Error Response:", xhr.responseText);
                        alert("Connection Error. Check console.");
                    }
                });
            });
        }
    });

    function speakText(text) {
        if (!voiceEnabled) return;
        if(window.responsiveVoice) responsiveVoice.speak(text, "French Male");
    }

    function updateGameStatus() {
        $.ajax({
            url: '../../../../Controller/get_game_status.php', 
            type: 'POST', dataType: 'json', data: { session_id: SESSION_ID },
            success: function(response) {
                if (response.success) {
                    isHost = (response.host_id === USER_ID);
                    displayScoreboard(response.players);

                    if (response.state === 'ENDED') {
                        clearInterval(syncIntervalId);
                        window.location.href = 'display_results.php?session=' + SESSION_ID;
                        return;
                    }
                    if (response.current_question_index !== currentQuestionIndex && response.current_question_index > 0) {
                        currentQuestionIndex = response.current_question_index;
                        answered = false; isProcessingNext = false; 
                        loadQuestion(currentQuestionIndex);
                    }
                    if (isHost) {
                        if (isProcessingNext) return;
                        if (response.all_answered) {
                            $('#next-question-btn').show().prop('disabled', false).text('Question Suivante (' + response.players_answered + '/' + response.players_total + ')');
                        } else {
                            $('#next-question-btn').hide();
                            $('#game-status').text(`En attente des réponses... (${response.players_answered}/${response.players_total})`);
                        }
                    } else {
                        $('#next-question-btn').hide();
                        $('#game-status').text(`En attente de l'hôte... (${response.players_answered}/${response.players_total})`);
                    }
                }
            }
        });
    }

    function loadQuestion(index) {
        $('#question-text').text(`Question ${index} : Chargement...`);
        $('#options-list').empty();
        answered = false;
        
        $('#ai-hint-box').hide();
        $('#ai-hint-btn').prop('disabled', false).html('<i class="fas fa-magic"></i> Ask AI Hint');

        $.ajax({
            url: '../../../../Controller/get_question.php', 
            type: 'POST', dataType: 'json', data: { session_id: SESSION_ID, question_index: index },
            success: function(response) {
                if (response.success) {
                    totalQuestions = parseInt(response.question.total_questions);
                    $('#question-text').text(response.question.text);
                    $('#question-number').text(`Question ${index} / ${totalQuestions}`);
                    
                    let speechText = response.question.text;
                    response.options.forEach((option, i) => { speechText += ". Réponse " + (i + 1) + " : " + option.text; });
                    speakText(speechText);

                    if (isHost) {
                        let botDelay = Math.floor(Math.random() * 5000) + 3000;
                        setTimeout(function() {
                            $.ajax({
                                url: '../../../../Controller/bot_brain.php',
                                type: 'POST', dataType: 'json',
                                data: { session_id: SESSION_ID, question_index: index, total_questions: totalQuestions }
                            });
                        }, botDelay);
                    }

                    // --- MODIFIED SECTION FOR GESTURE CONTROL ---
response.options.forEach((option, index) => {
    // We add an ID like "option-btn-1", "option-btn-2" based on the index
    const optionHtml = $(`<li class="option-btn" id="option-btn-${index + 1}">${option.text}</li>`);
    
    // Add a visual badge so user knows 1 finger = this button
    optionHtml.prepend(`<span style="background:rgba(0,0,0,0.3); padding:2px 8px; border-radius:5px; margin-right:10px; font-weight:bold;">${index + 1}🖐</span>`);
    
    optionHtml.data('option-id', option.option_id);
    optionHtml.on('click', submitAnswer);
    $('#options-list').append(optionHtml);
});
// ---------------------------------------------
                }
            }
        });
    }

    function submitAnswer(event) {
        if (answered) return;
        answered = true;
        if(window.responsiveVoice) responsiveVoice.cancel(); 

        const selectedOption = $(event.target);
        const optionId = selectedOption.data('option-id');
        $('.option-btn').addClass('disabled').off('click');
        selectedOption.css('background', '#FFBB28'); 

        $.ajax({
            url: '../../../../Controller/submit_answer.php', 
            type: 'POST', dataType: 'json',
            data: { session_id: SESSION_ID, question_index: currentQuestionIndex, option_id: optionId },
            success: function(response) {
                if (response.correct) {
                    currentStreak++; updateStreakVisuals(); 
                    let tileIndex = ((currentQuestionIndex - 1) % 12) + 1;
                    $('#tile-' + tileIndex).addClass('revealed');
                    
                    if (currentQuestionIndex >= totalQuestions) {
                        setTimeout(function() {
                            $('.puzzle-tile').addClass('revealed'); 
                            $('#game-status').append(" <br><strong>🖼️ Poster saved to your collection!</strong>");
                        }, 500);
                    }

                    selectedOption.css('background', '#00E6A7').text(selectedOption.text() + ' (CORRECT)');
                    let msg = `Bien joué ! +${response.points} points.`;
                    if(currentStreak >= 3) msg += " 🔥 ON FIRE!";
                    $('#game-status').text(msg);
                } else {
                    currentStreak = 0; updateStreakVisuals(); 
                    selectedOption.css('background', '#FF6B6B').text(selectedOption.text() + ' (FAUX)');
                    $('#game-status').text('Dommage, réponse incorrecte.');
                }
            }
        });
    }

    function updateStreakVisuals() {
        const card = $('#main-card');
        const badge = $('#combo-badge');
        card.removeClass('streak-fire');
        if (currentStreak >= 3) {
            card.addClass('streak-fire');
            badge.html(`COMBO x${currentStreak} 🔥`).fadeIn().css('display', 'block');
        } else if (currentStreak === 2) {
            badge.html(`DOUBLE! ⚡`).fadeIn().css('display', 'block');
        } else {
            badge.fadeOut();
        }
    }

    function displayScoreboard(players) {
        if(!players) return;
        let scoreHtml = '';
        players.sort((a, b) => b.score_total - a.score_total);
        players.forEach(player => {
            const isCurrent = player.id === USER_ID ? 'style="border-bottom: 2px solid #00E6A7;"' : '';
            scoreHtml += `<div class="player-score" ${isCurrent}><strong>${player.username.split(' ')[0]}</strong><br><span>${player.score_total} pts</span></div>`;
        });
        $('#scoreboard').html(scoreHtml);
    }
</script>
</body>
</html>