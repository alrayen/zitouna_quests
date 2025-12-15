<?php
session_start();
require_once __DIR__ . '/../../../../config.php'; 
$pdo = config::getConnexion();

$session_id = isset($_GET['session']) ? (int)$_GET['session'] : 0;
$current_user_id = $_SESSION['user_id'] ?? 0;
$code_invitation = 'N/A';

if ($session_id > 0) {
    try {
        $stmt = $pdo->prepare("SELECT code_invitation FROM online_sessions WHERE session_id = ?");
        $stmt->execute([$session_id]);
        $result = $stmt->fetch();
        if ($result) {
            $code_invitation = htmlspecialchars($result['code_invitation']);
            
            $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
            $currentDir = dirname($_SERVER['PHP_SELF']);
            $joinLink = $protocol . "://" . $_SERVER['HTTP_HOST'] . $currentDir . "/join_scan.php?code=" . $code_invitation;
            
            $qrImage = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($joinLink);
        }
    } catch (Exception $e) { }
}

if ($session_id === 0 || $current_user_id === 0) {
    header("Location: quizzes.php"); 
    exit;
}

$categories = [];
try {
    $catStmt = $pdo->query("SELECT DISTINCT categorie FROM quiz WHERE categorie IS NOT NULL AND categorie != '' ORDER BY categorie"); 
    $categories = $catStmt->fetchAll(PDO::FETCH_COLUMN);
} catch (Exception $e) {
    $categories = ['Science', 'Technologie', 'Histoire'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Zitouna Quests - Salle d'Attente</title>
    <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/plugins/fontawesome-pro-icons.css">

    <style>
        @keyframes moveGradient { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
        body.rt_bg-secondary {
            background: linear-gradient(135deg, #005248, #00c49f, #00796b);
            background-size: 400% 400%;
            animation: moveGradient 20s ease infinite; min-height: 100vh;
        }
        .lobby-container {
            max-width: 800px; margin: 50px auto; padding: 40px; background: rgba(255, 255, 255, 0.1); 
            backdrop-filter: blur(10px); border-radius: 20px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            color: #fff; text-align: center;
        }
        .qr-section {
            background: rgba(0,0,0,0.2); padding: 20px; border-radius: 15px; display: inline-block; margin-bottom: 20px;
        }
        .qr-code { border: 5px solid white; border-radius: 10px; }
        
        #player-list { list-style: none; padding: 0; margin-top: 20px; text-align: left; }
        #player-list li {
            background: rgba(255, 255, 255, 0.15); padding: 10px; margin-bottom: 8px; border-radius: 10px;
            font-weight: 600; display: flex; justify-content: space-between; align-items: center;
        }
        .host-badge { background: #FFBB28; color: #333; padding: 3px 8px; border-radius: 5px; font-size: 0.8rem; font-weight: 700; }
        #start-button { margin-top: 30px; background: #00C49F; border: none; }
        
        #host-controls {
            background: rgba(0,0,0,0.2); padding: 20px; border-radius: 10px; margin-top: 20px;
            display: none; text-align: left;
        }
        .lobby-select {
            background: rgba(255,255,255,0.9); border: none; color: #333; padding: 10px;
            border-radius: 5px; width: 100%; margin-bottom: 10px;
        }
        label { font-size: 0.9rem; margin-bottom: 5px; display: block; color: #00E6A7; }

        /* MODAL STYLES */
        .modal-overlay {
            display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.8); z-index: 1000; justify-content: center; align-items: center;
        }
        .modal-content {
            background: #2d3436; color: white; padding: 30px; border-radius: 15px;
            width: 90%; max-width: 500px; position: relative; border: 2px solid #00E6A7;
        }
        .close-modal { position: absolute; top: 10px; right: 15px; font-size: 24px; cursor: pointer; color: #aaa; }
        .ai-input {
            width: 100%; padding: 10px; margin-bottom: 15px; background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2); color: white; border-radius: 5px;
        }
        .ai-input option { background: #333; }
        .ai-btn {
            background: linear-gradient(45deg, #6c5ce7, #a29bfe); border: none; width: 100%; padding: 12px;
            border-radius: 5px; color: white; font-weight: bold; cursor: pointer; transition: 0.3s;
        }
        .ai-btn:hover { opacity: 0.9; transform: scale(1.02); }
    </style>
</head>

<body class="rt_bg-secondary">
    
    <div class="lobby-container">
        <div class="row">
            <div class="col-md-5">
                <div class="qr-section">
                    <p style="margin-bottom: 10px; color: #00E6A7; font-weight: bold;">Scan to Join</p>
                    <img src="<?php echo $qrImage; ?>" alt="QR Code" class="qr-code" width="150">
                    <h2 style="font-size: 2.5rem; color: #FFBB28; letter-spacing: 5px; margin-top: 10px; margin-bottom:0;"><?php echo $code_invitation; ?></h2>
                </div>
            </div>

            <div class="col-md-7">
                <h1 class="title">Salle d'Attente</h1>
                
                <div id="host-controls">
                    <h5 style="border-bottom: 1px solid rgba(255,255,255,0.3); padding-bottom: 10px;">Paramètres de la partie</h5>
                    
                    <button id="open-ai-modal" class="rts-btn btn-light" style="width:100%; margin-bottom:15px; background: #6c5ce7; color: white; border: none;">
                        ✨ Generate AI Quiz
                    </button>

                    <label>Catégorie :</label>
                    <select id="quiz-category" class="lobby-select">
                        <option value="any">Aléatoire (Toutes)</option>
                        <?php foreach($categories as $cat): ?>
                            <option value="<?php echo htmlspecialchars($cat); ?>"><?php echo htmlspecialchars($cat); ?></option>
                        <?php endforeach; ?>
                    </select>

                    <label>Difficulté :</label>
                    <select id="quiz-difficulty" class="lobby-select">
                        <option value="any">Aléatoire (Toutes)</option>
                        <option value="Easy">Easy</option>
                        <option value="Medium">Medium</option>
                        <option value="Hard">Hard</option>
                    </select>

                    <button id="add-bot-btn" class="btn btn-sm btn-outline-light" style="width:100%; margin-top:5px;">
                        <i class="fas fa-robot"></i> Ajouter un Bot IA
                    </button>
                </div>

                <h3 class="mt-4" style="text-align: left;">Joueurs Connectés</h3>
                <ul id="player-list">
                    <li>Chargement des joueurs...</li>
                </ul>
                
                <button id="start-button" class="rts-btn btn-primary" style="width: 100%; display:none;">Lancer le Quiz !</button>
                <div id="lobby-status" style="margin-top: 20px; font-style: italic;">En attente...</div>
            </div>
        </div>
    </div>

    <div id="ai-modal" class="modal-overlay">
        <div class="modal-content">
            <span class="close-modal" id="close-ai-modal">&times;</span>
            <h3 style="text-align: center; margin-bottom: 20px;">🧠 AI Quiz Creator</h3>
            <form id="ai-form">
                <label>Topic</label>
                <input type="text" id="ai_topic" class="ai-input" placeholder="e.g. Chemistry">
                <div style="display: flex; gap: 10px;">
                    <div style="flex: 1;">
                        <label>Level</label>
                        <select id="ai_level" class="ai-input">
                            <option value="Easy">Easy</option>
                            <option value="Medium" selected>Medium</option>
                            <option value="Hard">Hard</option>
                        </select>
                    </div>
                    <div style="flex: 1;">
                        <label>Questions</label>
                        <input type="number" id="ai_count" class="ai-input" value="5">
                    </div>
                </div>
                <label>Points per Question</label>
                <input type="number" id="ai_points" class="ai-input" value="10">
                <button type="submit" class="ai-btn" id="generate-btn">✨ Generate & Select</button>
                <div id="ai-status" style="margin-top:10px; text-align:center; font-size:0.9rem;"></div>
            </form>
        </div>
    </div>

    <script src="assets/js/vendor/jquery.min.js"></script>
    <script src="assets/js/quiz_validator.js"></script> 
    <script>
        const SESSION_ID = <?php echo $session_id; ?>;
        const USER_ID = <?php echo $current_user_id; ?>;
        let intervalId = null; 
        let isHost = false; 
        let forcedQuizId = null; 

        function updateLobby() {
            $.ajax({
                url: '/../../../../Controller/get_lobby_status.php',
                type: 'POST', dataType: 'json', data: { session_id: SESSION_ID },
                success: function(response) {
                    if (response.success) {
                        isHost = (response.host_id === USER_ID);
                        displayPlayers(response.players);

                        if (response.state === 'IN_PROGRESS') {
                            clearInterval(intervalId); 
                            $('#lobby-status').html('Partie lancée! Redirection...');
                            window.location.href = 'online_quiz_game.php?session=' + SESSION_ID; 
                            return;
                        }

                        if (response.state === 'LOBBY') {
                            if (isHost) {
                                $('#host-controls').slideDown();
                                if (response.players.length > 0) { 
                                    $('#start-button').show().prop('disabled', false);
                                }
                            } else {
                                $('#host-controls').hide();
                                $('#start-button').hide();
                                let hostName = response.players.find(p => p.is_host)?.username || 'l\'hôte';
                                $('#lobby-status').text(`En attente de ${hostName}...`);
                            }
                        }
                    } else {
                        clearInterval(intervalId); 
                        window.location.href = 'quizzes.php';
                    }
                }
            });
        }

        function displayPlayers(players) {
            let listHtml = '';
            players.forEach(player => {
                let status = player.is_host ? '<span class="host-badge">HÔTE</span>' : '';
                let currentUserClass = (player.id === USER_ID) ? 'style="border: 2px solid #00E6A7;"' : '';
                listHtml += `<li ${currentUserClass}>${player.username} ${status}</li>`;
            });
            $('#player-list').html(listHtml);
        }

        function startGame() {
            if (!isHost) return;
            const category = $('#quiz-category').val();
            const difficulty = $('#quiz-difficulty').val();
            $('#start-button').prop('disabled', true).text('Lancement...');
            let payload = { session_id: SESSION_ID, category: category, difficulty: difficulty };
            if (forcedQuizId) payload.forced_quiz_id = forcedQuizId;

            $.ajax({
                url: '/../../../../Controller/start_game.php', 
                type: 'POST', dataType: 'json', data: payload,
                success: function(response) {
                    if (!response.success) {
                        alert(response.message);
                        $('#start-button').prop('disabled', false).text('Lancer le Quiz !');
                    }
                },
                error: function() {
                    alert('Erreur technique.');
                    $('#start-button').prop('disabled', false).text('Lancer le Quiz !');
                }
            });
        }

        $('#open-ai-modal').click(function() { $('#ai-modal').fadeIn(); });
        $('#close-ai-modal').click(function() { $('#ai-modal').fadeOut(); });

        $('#ai-form').submit(function(e) {
            e.preventDefault(); 
            if (validateAiForm() === false) return; 

            const btn = $('#generate-btn');
            const status = $('#ai-status');
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Generating...');
            status.text('Connecting to DeepSeek AI...');
            status.css('color', 'white');

            $.ajax({
                url: '/../../../../Controller/generate_ai_quiz.php',
                type: 'POST', dataType: 'json',
                data: {
                    topic: $('#ai_topic').val(),
                    level: $('#ai_level').val(),
                    count: $('#ai_count').val(),
                    points: $('#ai_points').val()
                },
                success: function(response) {
                    if (response.success) {
                        forcedQuizId = response.quiz_id;
                        $('#ai-modal').fadeOut();
                        $('#quiz-category').html(`<option value="generated" selected>✨ Generated: ${$('#ai_topic').val()}</option>`);
                        $('#quiz-difficulty').html(`<option value="any" selected>Custom</option>`);
                        alert("Quiz Generated Successfully!");
                        btn.prop('disabled', false).text('✨ Generate & Select');
                        status.text('');
                    } else {
                        status.text('Error: ' + response.message).css('color', '#ff6b6b');
                        btn.prop('disabled', false).text('Try Again');
                    }
                },
                error: function(xhr) {
                    status.text('Server Error: ' + xhr.responseText).css('color', '#ff6b6b');
                    btn.prop('disabled', false).text('Try Again');
                }
            });
        });

        $('#add-bot-btn').click(function() {
            if(!isHost) return;
            $(this).prop('disabled', true).text('...');
            $.ajax({
                url: '../../../../Controller/add_bot.php', 
                type: 'POST', data: { session_id: SESSION_ID },
                success: function() { $('#add-bot-btn').prop('disabled',false).text('Ajouter un Bot IA'); }
            });
        });

        $(document).ready(function() {
            if (SESSION_ID) {
                intervalId = setInterval(updateLobby, 2000); 
                updateLobby(); 
                $('#start-button').click(startGame);
            }
        });
    </script>
</body>
</html>