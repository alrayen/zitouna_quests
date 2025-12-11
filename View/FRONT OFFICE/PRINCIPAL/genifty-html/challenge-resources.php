<?php
require_once __DIR__ . '/../../../../config.php';
require_once __DIR__ . '/../../../../Model/challenge.php';
require_once __DIR__ . '/../../../../Controller/challenge-controller.php';
require_once __DIR__ . '/../../../../Model/ressources-model.php';
require_once __DIR__ . '/../../../../Controller/ressources-controller.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: challenge.php");
    exit();
}

$id_defi = (int)$_GET['id'];

$challengeController = new ChallengeController();
$challenge = $challengeController->getChallengeById($id_defi);

if (!$challenge) {
    header("Location: challenge.php"); 
    exit();
}

$ressourceController = new RessourceController();
$resources = $ressourceController->getResourcesByDefiId($id_defi);

// --- GAMIFICATION CHECK ---
$userId = $_SESSION['user_id'] ?? 1; // Replace with real session logic
$pdo = config::getConnexion();

// Check if mission is already completed
$stmt = $pdo->prepare("SELECT id FROM user_challenge_progress WHERE user_id = ? AND challenge_id = ? AND status = 'completed'");
$stmt->execute([$userId, $id_defi]);
$isCompleted = $stmt->rowCount() > 0;

// If not completed, mark as 'started' (if not already)
if (!$isCompleted) {
    // Check if entry exists at all
    $checkStart = $pdo->prepare("SELECT id FROM user_challenge_progress WHERE user_id = ? AND challenge_id = ?");
    $checkStart->execute([$userId, $id_defi]);
    if ($checkStart->rowCount() == 0) {
        $insertStart = $pdo->prepare("INSERT INTO user_challenge_progress (user_id, challenge_id, status, started_at) VALUES (?, ?, 'started', NOW())");
        $insertStart->execute([$userId, $id_defi]);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Resources - <?php echo htmlspecialchars($challenge->getTitre()); ?></title>
    
    <!-- CSS Dependencies -->
    <link rel="stylesheet" href="assets/css/plugins/fontawesome-pro-icons.css">
    <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Syntax Highlighting -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/atom-one-dark-reasonable.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <!-- Confetti -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

    <style>
        /* --- CORE ANIMATIONS & BACKGROUND --- */
        @keyframes moveGradient { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
        @keyframes float { 0% { transform: translateY(0) translateX(0); } 50% { transform: translateY(-20px) translateX(20px); } 100% { transform: translateY(0) translateX(0); } }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        
        body.rt_bg-secondary {
            background: linear-gradient(135deg, #14b8a6, #14b8a6, #3ddf43ff, #81c784);
            background-size: 400% 400%;
            animation: moveGradient 25s ease infinite;
            min-height: 100vh;
            color: #fff;
            font-family: 'Gordita', sans-serif;
            overflow-x: hidden;
        }

        .bg-animation { position: fixed; top: 0; left: 0; width: 100%; height: 100vh; z-index: -1; overflow: hidden; }
        .bg-animation .blob { position: absolute; border-radius: 50%; filter: blur(80px); opacity: 0.4; animation: float 25s ease-in-out infinite alternate; }
        .bg-animation .blob1 { width: 500px; height: 500px; background: rgba(144, 238, 144, 0.5); top: -100px; left: -100px; }
        .bg-animation .blob2 { width: 400px; height: 400px; background: rgba(0, 150, 136, 0.4); bottom: -100px; right: -100px; animation-delay: -5s; }

        .container { max-width: 1000px; position: relative; z-index: 2; } 

        /* --- HEADER & RESOURCES --- */
        .header-card {
            background: rgba(20, 60, 20, 0.35);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(100, 255, 100, 0.2);
            border-radius: 30px;
            padding: 50px; 
            text-align: center;
            margin-top: 80px;
            margin-bottom: 50px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.25);
            animation: fadeIn 0.6s ease-out;
        }
        .challenge-title { font-size: 3.5rem; font-weight: 800; margin-bottom: 15px; color: #fff; text-shadow: 0 0 15px rgba(0,0,0,0.2); line-height: 1.2; }
        .challenge-subtitle { color: #a5d6a7; font-size: 1.4rem; letter-spacing: 1.5px; text-transform: uppercase; font-weight: 600; }

        .resource-container { padding-bottom: 50px; }
        
        .resource-card {
            display: flex;
            align-items: center;
            background: rgba(20, 60, 20, 0.45); 
            backdrop-filter: blur(12px);
            border: 1px solid rgba(100, 255, 100, 0.15);
            border-radius: 24px;
            padding: 35px; 
            margin-bottom: 25px;
            transition: transform 0.3s ease, box-shadow 0.3s ease, background 0.3s;
            animation: fadeIn 0.6s ease-out forwards;
            opacity: 0; 
        }
        .resource-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25), 0 0 25px rgba(105, 240, 174, 0.15);
            background: rgba(20, 60, 20, 0.65);
            border-color: rgba(100, 255, 100, 0.5);
        }

        .res-icon-box {
            width: 90px; height: 90px; 
            border-radius: 20px;
            display: flex; justify-content: center; align-items: center;
            font-size: 3rem; 
            margin-right: 35px;
            flex-shrink: 0;
            box-shadow: 0 15px 30px rgba(0,0,0,0.25);
        }
      
        .type-pdf { background: linear-gradient(135deg, #ef5350, #c62828); color: white; }
        .type-video { background: linear-gradient(135deg, #ab47bc, #7b1fa2); color: white; }
        .type-link { background: linear-gradient(135deg, #29b6f6, #0277bd); color: white; }
        .type-image { background: linear-gradient(135deg, #ffa726, #f57c00); color: white; }
        .type-default { background: linear-gradient(135deg, #66bb6a, #2e7d32); color: white; }

        .res-content { flex-grow: 1; }
        .res-title { font-size: 1.6rem; font-weight: 800; margin: 0 0 10px 0; color: #fff; }
        .res-desc { font-size: 1.1rem; color: #e0e0e0; margin: 0; line-height: 1.6; opacity: 0.95; }
        .res-meta { font-size: 0.9rem; color: #ffd54f; margin-top: 12px; text-transform: uppercase; letter-spacing: 1.2px; font-weight: 800; display:flex; align-items:center; gap:8px; }

        .res-action { margin-left: 30px; }
        
        .btn-access {
            background: #fff;
            color: #1b5e20;
            padding: 16px 36px; 
            border-radius: 40px;
            text-decoration: none;
            font-weight: 800;
            font-size: 1.1rem; 
            display: inline-flex; align-items: center; gap: 12px;
            transition: all 0.3s;
            white-space: nowrap;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }
        .btn-access:hover {
            background: #69f0ae;
            color: #000;
            transform: scale(1.05);
            box-shadow: 0 0 25px rgba(105, 240, 174, 0.6);
        }

        .btn-back-float {
            position: fixed; top: 40px; left: 40px;
            background: rgba(0,0,0,0.25);
            color: #fff;
            padding: 12px 30px; 
            border-radius: 30px;
            text-decoration: none;
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.2);
            transition: all 0.3s;
            z-index: 100;
            font-weight: 700;
            font-size: 1rem;
            display: flex; align-items: center; gap: 10px;
        }
        .btn-back-float:hover { background: #fff; color: #1b5e20; transform: translateX(-5px); }

        .empty-state { 
            text-align: center; padding: 80px; 
            background: rgba(0,0,0,0.25); border-radius: 30px;
            border: 2px dashed rgba(255,255,255,0.2);
            color: #ccc; 
        }

        /* --- COMPLETE BUTTON --- */
        .btn-complete {
            background: linear-gradient(45deg, #ffd700, #ffa500);
            color: #000; border: none;
            padding: 12px 30px; border-radius: 50px;
            font-weight: 800; font-size: 1.1rem;
            margin-top: 20px; cursor: pointer;
            box-shadow: 0 0 20px rgba(255, 215, 0, 0.4);
            transition: all 0.3s;
            display: inline-flex; align-items: center; gap: 10px;
        }
        .btn-complete:hover { transform: scale(1.05); box-shadow: 0 0 30px rgba(255, 215, 0, 0.6); }
        .btn-complete.disabled { background: #444; color: #aaa; cursor: default; box-shadow: none; pointer-events: none; }

        /* --- CHAT STYLES --- */
        .chat-section {
            background: rgba(10, 30, 10, 0.55);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(100, 255, 100, 0.2);
            border-radius: 30px;
            padding: 0;
            margin-bottom: 100px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.25);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 600px;
            animation: fadeIn 0.8s ease-out;
        }

        .chat-header {
            padding: 25px;
            background: rgba(0, 50, 0, 0.3);
            border-bottom: 1px solid rgba(100, 255, 100, 0.1);
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        /* Dynamic Avatar Style */
        .chat-avatar {
            width: 50px; height: 50px;
            border-radius: 50%;
            display: flex; justify-content: center; align-items: center;
            font-size: 1.5rem; color: #fff;
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.2);
            transition: all 0.5s ease;
        }

        .chat-title h4 { margin: 0; color: #fff; font-weight: 700; font-size: 1.2rem; }
        .chat-title span { color: #69f0ae; font-size: 0.9rem; letter-spacing: 1px; text-transform: uppercase; }

        .chat-messages {
            flex-grow: 1;
            padding: 30px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 20px;
            background: rgba(0,0,0,0.1);
        }

        .message { max-width: 80%; padding: 15px 20px; border-radius: 20px; font-size: 1rem; line-height: 1.6; }
        
        .message.bot {
            align-self: flex-start;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #e0e0e0;
            border-top-left-radius: 5px;
        }
        
        .message.user {
            align-self: flex-end;
            background: linear-gradient(135deg, #43a047, #00e676);
            color: #003d1a;
            font-weight: 600;
            box-shadow: 0 5px 15px rgba(0, 230, 118, 0.2);
            border-top-right-radius: 5px;
        }

        /* --- STRICT CHAT LAYOUT FIX --- */
        .chat-input-area {
            padding: 20px;
            background: rgba(0, 0, 0, 0.2);
            border-top: 1px solid rgba(100, 255, 100, 0.1);
            display: flex; /* Flexbox is key */
            align-items: center; /* Vertically center everything */
            gap: 15px; /* Spacing between buttons and input */
        }

        /* The Round Buttons (Mic & Send) */
        .chat-action-btn {
            width: 60px !important;
            height: 60px !important;
            min-width: 60px !important; /* Prevents squishing */
            border-radius: 50% !important;
            background: #fff;
            color: #1b5e20;
            border: none;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 1.5rem;
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            transition: transform 0.2s, background 0.3s;
            padding: 0 !important; /* Removes template padding */
            margin: 0 !important; /* Resets template margins */
        }

        .chat-action-btn:hover {
            transform: scale(1.1);
            background: #69f0ae;
        }

        /* The Text Input Field */
        .chat-input {
            flex-grow: 1; /* Takes up all remaining space */
            height: 60px !important; /* Match button height */
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 30px !important;
            padding: 0 25px; /* Text padding inside input */
            color: #fff;
            outline: none;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .chat-input:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: #69f0ae;
            box-shadow: 0 0 15px rgba(105, 240, 174, 0.1);
        }

        .chat-input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        /* --- CODE & REASONING STYLES --- */
        .message code {
            background: rgba(255,255,255,0.1);
            padding: 2px 4px;
            border-radius: 4px;
            font-family: monospace;
            font-size: 0.9em;
        }
        .message pre code.hljs { 
            display: block;
            overflow-x: auto;
            padding: 1em;
            background: rgba(0,0,0,0.4); 
            border-radius: 10px;
            margin-top: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        
        .ai-thought-details {
            background: rgba(100, 255, 100, 0.1);
            padding: 10px 15px;
            border-radius: 10px;
            margin-top: 15px;
            border: 1px solid rgba(105, 240, 174, 0.2);
        }
        .ai-thought-details summary {
            font-weight: 700;
            color: #b9f6ca;
            cursor: pointer;
            list-style: none;
        }
        .ai-thought-details summary:before {
            content: "▶";
            margin-right: 5px;
            transition: transform 0.2s;
            display: inline-block;
        }
        .ai-thought-details[open] summary:before { content: "▼"; }
        .ai-thought-content {
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid rgba(105, 240, 174, 0.1);
            font-size: 0.9rem;
            color: #e0f2f1;
            font-family: monospace;
        }

        /* --- ANIMATIONS & CONTROLS --- */
        .typing-indicator span {
            display: inline-block; width: 6px; height: 6px; background: #fff; border-radius: 50%;
            animation: typing 1.4s infinite ease-in-out both; margin: 0 2px;
        }
        .typing-indicator span:nth-child(1) { animation-delay: -0.32s; }
        .typing-indicator span:nth-child(2) { animation-delay: -0.16s; }
        @keyframes typing { 0%, 80%, 100% { transform: scale(0); } 40% { transform: scale(1); } }

        .typing-cursor::after {
            content: '|';
            animation: blink 0.8s step-start infinite;
            color: #69f0ae;
            margin-left: 2px;
        }
        @keyframes blink { 50% { opacity: 0; } }

        .msg-controls {
            display: flex;
            justify-content: flex-end;
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px solid rgba(255,255,255,0.05);
            opacity: 0.8;
        }
        .btn-speak {
            background: transparent;
            border: none;
            color: #a5d6a7;
            cursor: pointer;
            font-size: 0.85rem;
            display: flex; align-items: center; gap: 5px;
            transition: color 0.3s;
        }
        .btn-speak:hover { color: #fff; }
        .speaking-now { color: #69f0ae; animation: pulse 1s infinite; }
        @keyframes pulse { 0% { transform: scale(1); } 50% { transform: scale(1.05); } 100% { transform: scale(1); } }

        @media (max-width: 992px) {
             .container { max-width: 95%; }
             .challenge-title { font-size: 2.8rem; }
        }

        @media (max-width: 768px) {
            .resource-card { flex-direction: column; text-align: center; padding: 40px 30px; }
            .res-icon-box { margin: 0 0 25px 0; width: 100px; height: 100px; font-size: 3.5rem; }
            .res-action { margin: 30px 0 0 0; width: 100%; }
            .btn-access { width: 100%; justify-content: center; padding: 18px; }
            .btn-back-float { position: static; display: inline-flex; margin-bottom: 20px; margin-top: 20px; width: 100%; justify-content: center;}
            .header-card { margin-top: 20px; padding: 40px 25px; }
            .challenge-title { font-size: 2.2rem; }
            .chat-section { height: 500px; }
        }
    </style>
</head>
<body class="rt_bg-secondary">

    <div class="bg-animation">
        <div class="blob blob1"></div>
        <div class="blob blob2"></div>
    </div>

    <div class="container">
        <a href="challenge.php" class="btn-back-float">
            <i class="fas fa-arrow-left"></i> Back to Challenges
        </a>

        <div class="header-card">
            <h1 class="challenge-title"><?php echo htmlspecialchars($challenge->getTitre()); ?></h1>
            <p class="challenge-subtitle">Mission Resources & Materials</p>
            
            <div style="margin-top: 25px; display: inline-block; background:rgba(0,0,0,0.25); padding:10px 25px; border-radius:30px; font-size:1.1rem; border:1px solid rgba(255,255,255,0.15);">
                <i class="fas fa-folder-open" style="color: #ffd54f; margin-right: 8px;"></i> 
                <strong><?php echo count($resources); ?></strong> Files Available
            </div>

            <!-- COMPLETE MISSION BUTTON -->
            <?php if ($isCompleted): ?>
                <button class="btn-complete disabled" disabled>
                    <i class="fas fa-check-circle"></i> Mission Completed
                </button>
            <?php else: ?>
                <button id="btnComplete" class="btn-complete" onclick="completeMission()">
                    <i class="fas fa-flag-checkered"></i> Mark as Complete (+<?php echo $challenge->getPoints(); ?> XP)
                </button>
            <?php endif; ?>
        </div>

        <div class="resource-container">
            <?php if (empty($resources)): ?>
                <div class="empty-state">
                    <i class="far fa-folder-open fa-4x" style="margin-bottom:25px; opacity: 0.6;"></i>
                    <p style="font-size: 1.4rem; margin:0; font-weight: 600;">No resources have been uploaded for this challenge yet.</p>
                    <p style="font-size: 1.1rem; margin-top: 10px; opacity: 0.8;">Check back later or contact your admin.</p>
                </div>
            <?php else: ?>
                <?php 
                $delay = 0;
                foreach ($resources as $res): 
                    $delay += 100; 
                    
                    $type = strtolower($res->getType());
                    $iconClass = "fa-file-alt"; 
                    $colorClass = "type-default";

                    if (strpos($type, 'pdf') !== false) { $iconClass = "fa-file-pdf"; $colorClass = "type-pdf"; } 
                    elseif (strpos($type, 'video') !== false || strpos($type, 'mp4') !== false) { $iconClass = "fa-play-circle"; $colorClass = "type-video"; } 
                    elseif (strpos($type, 'link') !== false || strpos($type, 'http') !== false) { $iconClass = "fa-link"; $colorClass = "type-link"; } 
                    elseif (strpos($type, 'image') !== false || strpos($type, 'png') !== false || strpos($type, 'jpg') !== false) { $iconClass = "fa-image"; $colorClass = "type-image"; }
                ?>
                    <div class="resource-card" style="animation-delay: <?php echo $delay; ?>ms;">
                        <div class="res-icon-box <?php echo $colorClass; ?>">
                            <i class="fas <?php echo $iconClass; ?>"></i>
                        </div>
                        <div class="res-content">
                            <h3 class="res-title"><?php echo htmlspecialchars($res->getNom()); ?></h3>
                            <p class="res-desc"><?php echo htmlspecialchars($res->getDescription()); ?></p>
                            <?php if($res->getNecessitePreuve()): ?>
                                <div class="res-meta">
                                    <i class="fas fa-exclamation-triangle" style="font-size: 1.1rem;"></i> Proof Submission Required
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="res-action">
                            <a href="<?php echo htmlspecialchars($res->getUrl()); ?>" target="_blank" class="btn-access">
                                <?php echo (strpos($type, 'link') !== false) ? 'Visit Link' : 'Download'; ?> 
                                <i class="fas fa-external-link-alt" style="font-size: 1.2rem;"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="chat-section">
            <div class="chat-header">
                <div class="chat-avatar">
                    <i class="fas fa-robot"></i>
                </div>
                <div class="chat-title">
                    <h4>AI Mentor</h4>
                    <span>Online & Ready to Help</span>
                </div>
            </div>
            
            <div class="chat-messages" id="chatMessages">
                <div class="message bot">
                    Hello! I'm your AI Mentor for <strong><?php echo htmlspecialchars($challenge->getTitre()); ?></strong>. 
                    I have access to the mission details and the resources listed above. How can I help you succeed?
                </div>
            </div>
            
            <div class="chat-input-area">
                <!-- Mic Button with updated class -->
                <button class="chat-action-btn" id="micBtn" onclick="toggleVoice()">
                    <i class="fas fa-microphone"></i>
                </button>
                
                <input type="text" id="chatInput" class="chat-input" placeholder="Ask about this challenge or resources..." onkeypress="handleEnter(event)">
                
                <!-- Send Button with updated class -->
                <button class="chat-action-btn" onclick="sendMessage()">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>

    </div>

    <script>
        const challengeId = <?php echo $id_defi; ?>;
        const challengePoints = <?php echo $challenge->getPoints(); ?>;

        // --- 1. GAMIFICATION FUNCTION ---
        async function completeMission() {
            const btn = document.getElementById('btnComplete');
            
            // 1. Visual feedback immediately
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
            
            try {
                // 2. Call the Gamification API
                const response = await fetch('../../../../Controller/gamification_api.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({
                        action: 'complete_challenge',
                        challenge_id: challengeId,
                        points: challengePoints
                    })
                });
                
                const data = await response.json();
                
                if (data.status === 'success' || data.status === 'already_completed') {
                    // 3. Success State
                    btn.innerHTML = '<i class="fas fa-check-circle"></i> Completed!';
                    btn.classList.add('disabled');
                    btn.disabled = true;
                    
                    // 4. Celebration!
                    confetti({ particleCount: 200, spread: 100, origin: { y: 0.6 } });
                    
                    if (data.level_up) {
                        setTimeout(() => {
                            alert("🎉 LEVEL UP! You reached Level " + data.new_level + "!");
                            confetti({ particleCount: 500, spread: 360, startVelocity: 50 });
                        }, 500);
                    }
                } else {
                    alert("Error: " + data.message);
                    btn.innerHTML = '<i class="fas fa-flag-checkered"></i> Try Again';
                }
            } catch (e) {
                console.error(e);
                btn.innerHTML = 'Error';
            }
        }

        // --- 2. TTS WARM-UP & HELPERS ---
        function warmUpTTS() {
            if ('speechSynthesis' in window && !window.speechSynthesis.getVoices().length) {
                window.speechSynthesis.getVoices(); 
            }
        }
        window.addEventListener('load', warmUpTTS);

        function escapeHtml(text) {
            return text.replace(/"/g, "&quot;").replace(/'/g, "&#039;").replace(/\n/g, " ");
        }

        function handleEnter(e) {
            if (e.key === 'Enter') sendMessage();
        }

        // --- 3. DYNAMIC MENTOR PERSONA ---
        function updateAvatarByDifficulty(difficulty) {
            const avatar = document.querySelector('.chat-avatar');
            const avatarIcon = document.querySelector('.chat-avatar i');
            
            const diffMap = {
                'easy': { bg: 'linear-gradient(135deg, #00e676, #00c853)', icon: 'fas fa-seedling' },
                'medium': { bg: 'linear-gradient(135deg, #29b6f6, #0277bd)', icon: 'fas fa-code-branch' },
                'hard': { bg: 'linear-gradient(135deg, #ffa726, #f57c00)', icon: 'fas fa-brain' },
                'expert': { bg: 'linear-gradient(135deg, #ab47bc, #7b1fa2)', icon: 'fas fa-rocket' },
                'default': { bg: 'linear-gradient(135deg, #00e676, #00c853)', icon: 'fas fa-robot' }
            };
            
            const key = difficulty ? difficulty.toLowerCase() : 'default';
            const style = diffMap[key] || diffMap['default'];
            
            avatar.style.background = style.bg;
            avatarIcon.className = style.icon;
        }
        updateAvatarByDifficulty('<?php echo strtolower($challenge->getDifficulty()); ?>');


        // --- 4. SPEECH RECOGNITION (Voice Input) ---
        let recognition;
        if ('webkitSpeechRecognition' in window) {
            recognition = new webkitSpeechRecognition();
            recognition.continuous = false;
            recognition.lang = 'en-US';

            recognition.onstart = function() {
                document.getElementById('micBtn').innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                document.getElementById('chatInput').placeholder = "Listening...";
            };

            recognition.onresult = function(event) {
                const transcript = event.results[0][0].transcript;
                document.getElementById('chatInput').value = transcript;
                sendMessage(); 
            };

            recognition.onend = function() {
                document.getElementById('micBtn').innerHTML = '<i class="fas fa-microphone"></i>';
                document.getElementById('chatInput').placeholder = "Ask about this challenge...";
            };
        }

        function toggleVoice() {
            if (recognition) recognition.start();
            else alert("Voice input not supported in this browser.");
        }

        // --- 5. TYPEWRITER ---
        function typeWriter(element, text, index = 0) {
            if (index < text.length) {
                if (text.charAt(index) === '<') {
                    let tagEnd = text.indexOf('>', index);
                    if (tagEnd !== -1) {
                        element.innerHTML += text.substring(index, tagEnd + 1);
                        index = tagEnd + 1;
                    } else {
                        element.innerHTML += text.charAt(index);
                        index++;
                    }
                } else {
                    element.innerHTML += text.charAt(index);
                    index++;
                }
                setTimeout(() => typeWriter(element, text, index), 20); 
            } else {
                element.classList.remove('typing-cursor'); 
            }
        }

        // --- 6. OPTIMIZED SPEAK TEXT ---
        function speakText(btn) {
            if (!('speechSynthesis' in window)) {
                alert("Sorry, your browser doesn't support Text-to-Speech!");
                return;
            }

            const text = btn.dataset.speechText; 

            const clearSpeechState = () => {
                window.speechSynthesis.cancel();
                document.querySelectorAll('.btn-speak').forEach(b => {
                    b.classList.remove('speaking-now');
                    b.innerHTML = '<i class="fas fa-volume-up"></i> Listen';
                });
            };

            if (btn.classList.contains('speaking-now')) {
                clearSpeechState();
                return;
            }
            
            clearSpeechState(); 

            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'en-US';
            utterance.rate = 1; 
            utterance.pitch = 1;

            btn.classList.add('speaking-now');
            btn.innerHTML = '<i class="fas fa-stop-circle"></i> Stop';

            utterance.onend = utterance.onerror = function() {
                if (btn.classList.contains('speaking-now')) {
                    btn.classList.remove('speaking-now');
                    btn.innerHTML = '<i class="fas fa-volume-up"></i> Listen';
                }
            };

            window.speechSynthesis.speak(utterance);
        }

        // --- 7. HIGHLIGHTING ---
        function highlightCode() {
            document.querySelectorAll('.message pre code').forEach((block) => {
                hljs.highlightElement(block);
            });
        }

        // --- 8. SEND MESSAGE ---
        async function sendMessage() {
            const inputField = document.getElementById('chatInput');
            const message = inputField.value.trim();
            const messagesContainer = document.getElementById('chatMessages');

            if (!message) return;

            const userMsgDiv = document.createElement('div');
            userMsgDiv.className = 'message user';
            userMsgDiv.innerText = message;
            messagesContainer.appendChild(userMsgDiv);
            
            inputField.value = '';
            messagesContainer.scrollTop = messagesContainer.scrollHeight;

            const loadingDiv = document.createElement('div');
            loadingDiv.className = 'message bot';
            loadingDiv.innerHTML = '<div class="typing-indicator"><span></span><span></span><span></span></div>';
            messagesContainer.appendChild(loadingDiv);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;

            try {
                const response = await fetch('../../../../Controller/chat_api.php', { 
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ message: message, challenge_id: challengeId })
                });

                const data = await response.json();
                messagesContainer.removeChild(loadingDiv);

                let replyRaw = data.reply || "I'm having trouble connecting to my brain right now.";
                
                let thoughtProcess = '';
                // Adjust parsing logic to match the new chat_api.php response
                if (data.thought) {
                    thoughtProcess = data.thought;
                } else {
                    const thoughtMatch = replyRaw.match(/\[\[THOUGHT_START\]\]([\s\S]*?)\[\[THOUGHT_END\]\]/);
                    if (thoughtMatch) {
                        thoughtProcess = thoughtMatch[1];
                        replyRaw = replyRaw.replace(/\[\[THOUGHT_START\]\][\s\S]*?\[\[THOUGHT_END\]\]/g, ''); 
                    }
                }

                let replyHtml = replyRaw.replace(/(https?:\/\/[^\s]+)/g, '<a href="$1" target="_blank" style="color:#69f0ae; text-decoration:underline;">$1</a>');
                replyHtml = replyHtml.replace(/\n/g, '<br>');

                let speechText = replyRaw.replace(/```[\s\S]*?```/g, " [Code Block] ");
                speechText = speechText.replace(/<[^>]*>?/gm, '');

                const botMsgDiv = document.createElement('div');
                botMsgDiv.className = 'message bot';

                if (thoughtProcess) {
                     const thoughtHtml = `<details class="ai-thought-details">
                        <summary>✨ View AI Reasoning</summary>
                        <div class="ai-thought-content">${thoughtProcess.replace(/\n/g, '<br>')}</div>
                    </details>`;
                     botMsgDiv.insertAdjacentHTML('afterbegin', thoughtHtml);
                }
                
                const textContainer = document.createElement('div');
                textContainer.className = 'typing-cursor'; 
                botMsgDiv.appendChild(textContainer);

                const controlsContainer = document.createElement('div');
                controlsContainer.className = 'msg-controls';
                controlsContainer.innerHTML = `
                    <button class="btn-speak" data-speech-text="${escapeHtml(speechText)}" onclick="speakText(this)">
                        <i class="fas fa-volume-up"></i> Listen
                    </button>
                `;

                botMsgDiv.appendChild(controlsContainer);
                messagesContainer.appendChild(botMsgDiv);
                messagesContainer.scrollTop = messagesContainer.scrollHeight;

                typeWriter(textContainer, replyHtml);
                setTimeout(highlightCode, 1500); 

            } catch (error) {
                console.error(error);
                loadingDiv.innerText = "Error connecting to AI.";
            }
        }
    </script>

</body>
</html>