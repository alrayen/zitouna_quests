<?php
session_start();
require_once __DIR__ . '/../../../../Controller/help-room-controller.php';
require_once __DIR__ . '/../../../../Controller/challenge-controller.php';

if (!isset($_GET['room'])) {
    die("Error: No room code provided.");
}

$roomCode = $_GET['room'];
$helpController = new HelpRoomController();
$room = $helpController->getRoomDetails($roomCode);

if (!$room) {
    die("Error: Room not found or has been closed.");
}

$challengeController = new ChallengeController();
$challenge = $challengeController->getChallengeById($room['challenge_id']);

$basePoints = $challenge->getPoints();
$bonusPoints = floor($basePoints * 1.25);
$myUsername = $_SESSION['username'] ?? 'Student';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Help Room - <?php echo htmlspecialchars($roomCode); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/plugins/fontawesome-pro-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
    
    <script src="https://unpkg.com/peerjs@1.4.7/dist/peerjs.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

    <style>
        body { 
            background: #0f172a; color: white; height: 100vh; 
            display: flex; flex-direction: column; overflow: hidden; 
            font-family: 'Gordita', sans-serif;
        }
        
        /* HEADER */
        .room-header { 
            padding: 15px 30px; background: #1e293b; 
            display: flex; justify-content: space-between; align-items: center; 
            border-bottom: 1px solid rgba(255,255,255,0.1); flex-shrink: 0;
        }
        .room-title h4 { margin: 0; font-weight: 800; color: #fff; font-size: 1.2rem; }
        .bonus-badge { 
            background: linear-gradient(45deg, #ffd700, #ffa000); color: #000; 
            padding: 5px 15px; border-radius: 20px; font-weight: 800; font-size: 0.85rem; 
        }
        .btn-leave {
            background: rgba(255,255,255,0.1); color: #fff; border: 1px solid rgba(255,255,255,0.2);
            padding: 6px 15px; border-radius: 15px; text-decoration: none; transition: 0.3s; font-size: 0.9rem;
        }
        .btn-leave:hover { background: rgba(255, 82, 82, 0.2); color: #ff8a80; border-color: #ff8a80; }

        /* MAIN LAYOUT */
        .main-container {
            display: flex; flex-grow: 1; height: 100%; overflow: hidden;
        }

        /* LEFT SIDE: VOICE & CONTROLS */
        .voice-section {
            flex: 2; display: flex; flex-direction: column;
            justify-content: center; align-items: center;
            background: radial-gradient(circle at center, #1e293b 0%, #0f172a 100%);
            position: relative; border-right: 1px solid rgba(255,255,255,0.1);
        }

        .avatars-grid {
            display: flex; gap: 30px; flex-wrap: wrap; justify-content: center;
            margin-bottom: 60px;
        }

        .avatar-wrapper { text-align: center; }
        .avatar-circle {
            width: 100px; height: 100px; border-radius: 50%; background: #334155; 
            display: flex; justify-content: center; align-items: center; 
            font-size: 2.5rem; color: #fff; border: 3px solid #475569; 
            transition: all 0.2s ease-out; position: relative;
            box-shadow: 0 10px 20px rgba(0,0,0,0.3);
        }
        .avatar-circle.speaking { 
            border-color: #69f0ae; box-shadow: 0 0 0 8px rgba(105, 240, 174, 0.2); transform: scale(1.05); 
        }
        .avatar-circle.muted::after {
            content: "\f131"; font-family: "Font Awesome 5 Pro"; 
            position: absolute; bottom: 0; right: 0;
            background: #ef5350; color: white; width: 30px; height: 30px;
            border-radius: 50%; font-size: 0.9rem; display: flex; 
            justify-content: center; align-items: center; border: 2px solid #0f172a;
        }
        .avatar-label { margin-top: 10px; font-weight: 600; color: #cbd5e1; font-size: 0.9rem; }

        /* CONTROLS BAR (Bottom Center of Voice Section) */
        .controls-bar {
            position: absolute; bottom: 30px;
            display: flex; gap: 15px; align-items: center;
        }
        .btn-control {
            width: 50px !important;
            height: 50px !important;
            min-width: 50px !important; /* Prevents squishing flex items */
            border-radius: 50% !important;
            border: none;
            background: #334155; 
            color: #fff; 
            font-size: 1.2rem;
            cursor: pointer; 
            transition: 0.2s; 
            display: flex !important; 
            justify-content: center !important; 
            align-items: center !important;
            padding: 0 !important; /* Critical for perfect circles */
            line-height: 1 !important;
        }
        .btn-control:hover { background: #475569; transform: translateY(-2px); }
        .btn-control.active { background: #ef5350 !important; color: white !important; } /* Red for Muted */
        .btn-control:hover { background: #475569; transform: translateY(-2px); }
        .btn-control.active { background: #ef5350; color: white; } /* Red for Muted */
        
        .btn-solve-together {
            background: linear-gradient(135deg, #00e676, #00c853);
            color: #003d1a; padding: 0 30px; height: 50px; border-radius: 30px;
            font-weight: 800; font-size: 1rem; border: none;
            box-shadow: 0 0 20px rgba(0, 230, 118, 0.4);
            display: flex; align-items: center; gap: 10px; cursor: pointer; transition: 0.3s;
        }
        .btn-solve-together:hover { transform: scale(1.05); box-shadow: 0 0 30px rgba(0, 230, 118, 0.6); }

        /* RIGHT SIDE: CHAT */
        .chat-section {
            flex: 1; min-width: 300px; max-width: 400px;
            display: flex; flex-direction: column; background: #1e293b;
        }
        
        .chat-messages {
            flex-grow: 1; padding: 20px; overflow-y: auto;
            display: flex; flex-direction: column; gap: 12px;
        }
        
        .chat-msg {
            background: rgba(255,255,255,0.05); padding: 10px 15px; 
            border-radius: 12px; font-size: 0.9rem; line-height: 1.4;
            max-width: 90%; align-self: flex-start;
        }
        .chat-msg.me {
            background: #2563eb; align-self: flex-end; color: white;
        }
        .msg-sender { font-size: 0.75rem; color: #94a3b8; margin-bottom: 2px; display: block; }

        /* ADAPTIVE CHAT INPUT */
        .chat-input-area {
            padding: 15px; background: #0f172a; border-top: 1px solid rgba(255,255,255,0.1);
            display: flex; align-items: flex-end; gap: 10px;
        }
        
        .adaptive-textarea {
            flex-grow: 1;
            background: #334155; border: 1px solid #475569; border-radius: 20px;
            color: white; padding: 10px 15px; font-size: 0.95rem; line-height: 1.4;
            resize: none; /* User cannot manually resize */
            overflow: hidden; /* Hides scrollbar until max-height */
            min-height: 44px; /* 1 line height */
            max-height: 120px; /* ~5 lines max */
            font-family: inherit;
        }
        .adaptive-textarea:focus { outline: none; border-color: #69f0ae; }

        .btn-send {
            width: 44px; height: 44px; border-radius: 50%;
            background: #29b6f6; border: none; color: #0f172a;
            font-size: 1.1rem; cursor: pointer; flex-shrink: 0;
            display: flex; justify-content: center; align-items: center; transition: 0.2s;
        }
        .btn-send:hover { background: #0ea5e9; transform: scale(1.1); }

        @media (max-width: 768px) {
            .main-container { flex-direction: column; }
            .chat-section { height: 40vh; max-width: 100%; }
            .voice-section { height: 60vh; }
        }
    </style>
</head>
<body>

    <div class="room-header">
        <div class="room-title">
            <h4><?php echo htmlspecialchars($challenge->getTitre()); ?></h4>
            <span style="font-size:0.8rem; opacity:0.7;">Room: <?php echo $roomCode; ?></span>
        </div>
        <div class="bonus-badge d-none d-md-flex">
            <i class="fas fa-bolt"></i> BONUS: +<?php echo $bonusPoints; ?> XP
        </div>
        <a href="challenge-resources.php?id=<?php echo $room['challenge_id']; ?>" class="btn-leave">
            <i class="fas fa-sign-out-alt"></i> Leave
        </a>
    </div>

    <div class="main-container">
        
        <div class="voice-section">
            <div class="avatars-grid" id="remoteContainer">
                <div class="avatar-wrapper">
                    <div id="localAvatar" class="avatar-circle">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="avatar-label">You</div>
                </div>
                </div>

            <div class="controls-bar">
                <button id="btnMute" class="btn-control" onclick="toggleMute()" title="Mute Microphone">
                    <i class="fas fa-microphone"></i>
                </button>
                
                <button class="btn-solve-together" onclick="solveTogether()">
                    <i class="fas fa-check-double"></i> WE SOLVED IT!
                </button>
            </div>
        </div>

        <div class="chat-section">
            <div class="chat-messages" id="chatBox">
                <div style="text-align:center; color:#64748b; font-size:0.85rem; margin-top:10px;">
                    Room created. Chat is end-to-end encrypted via P2P.
                </div>
            </div>
            
            <div class="chat-input-area">
                <textarea id="chatInput" class="adaptive-textarea" rows="1" placeholder="Type a message..."></textarea>
                <button class="btn-send" onclick="sendText()">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>

    </div>

    <script>
        // --- CONFIG ---
        const peer = new Peer();
        const myAvatar = document.getElementById('localAvatar');
        const myUsername = "<?php echo $myUsername; ?>";
        let localStream = null;
        let dataConnections = []; // Store connections for chat

        // --- 1. MEDIA & P2P SETUP ---
        navigator.mediaDevices.getUserMedia({ audio: true })
            .then(stream => {
                localStream = stream;
                setupVisualizer(stream, myAvatar);

                peer.on('call', call => {
                    call.answer(stream);
                    call.on('stream', remoteStream => addRemoteUser(call.peer, remoteStream));
                });

                // Listen for text chat connections
                peer.on('connection', conn => {
                    setupDataConnection(conn);
                });
            })
            .catch(err => {
                console.error("Mic access denied:", err);
                alert("Please allow microphone access to use voice features!");
            });

        // --- 2. VOICE LOGIC ---
        function toggleMute() {
            if (localStream) {
                const audioTrack = localStream.getAudioTracks()[0];
                audioTrack.enabled = !audioTrack.enabled;
                
                const btn = document.getElementById('btnMute');
                const avatar = document.getElementById('localAvatar');
                
                if (audioTrack.enabled) {
                    btn.classList.remove('active');
                    btn.innerHTML = '<i class="fas fa-microphone"></i>';
                    avatar.classList.remove('muted');
                } else {
                    btn.classList.add('active');
                    btn.innerHTML = '<i class="fas fa-microphone-slash"></i>';
                    avatar.classList.add('muted');
                    avatar.classList.remove('speaking'); // Stop pulsing
                }
            }
        }

        function addRemoteUser(peerId, stream) {
            if(document.getElementById(peerId)) return;

            const container = document.getElementById('remoteContainer');
            
            const wrapper = document.createElement('div');
            wrapper.className = 'avatar-wrapper';
            wrapper.id = peerId;
            
            const div = document.createElement('div');
            div.className = 'avatar-circle';
            div.style.background = '#29b6f6';
            div.style.borderColor = '#0288d1';
            div.innerHTML = '<i class="fas fa-user-astronaut"></i>';
            
            const label = document.createElement('div');
            label.className = 'avatar-label';
            label.innerText = 'Peer';

            wrapper.appendChild(div);
            wrapper.appendChild(label);
            container.appendChild(wrapper);
            
            const audio = document.createElement('audio');
            audio.srcObject = stream;
            audio.play();
            
            setupVisualizer(stream, div);

            // Connect data channel if not exists
            const conn = peer.connect(peerId);
            setupDataConnection(conn);
        }

        function setupVisualizer(stream, element) {
            const context = new AudioContext();
            const src = context.createMediaStreamSource(stream);
            const analyser = context.createAnalyser();
            src.connect(analyser);
            analyser.fftSize = 32;
            const dataArray = new Uint8Array(analyser.frequencyBinCount);
            
            function check() {
                if(element.classList.contains('muted')) {
                    requestAnimationFrame(check);
                    return;
                }
                analyser.getByteFrequencyData(dataArray);
                const vol = dataArray.reduce((a,b)=>a+b) / dataArray.length;
                if(vol > 10) element.classList.add('speaking');
                else element.classList.remove('speaking');
                requestAnimationFrame(check);
            }
            check();
        }

        // --- 3. TEXT CHAT LOGIC ---
        function setupDataConnection(conn) {
            dataConnections.push(conn);
            conn.on('data', data => {
                if(data.type === 'chat') {
                    addMessageToUI(data.user, data.text, false);
                }
            });
        }

        function sendText() {
            const input = document.getElementById('chatInput');
            const text = input.value.trim();
            if(!text) return;

            // Show my message
            addMessageToUI("You", text, true);

            // Broadcast to peers
            dataConnections.forEach(conn => {
                conn.send({ type: 'chat', user: myUsername, text: text });
            });

            input.value = "";
            input.style.height = 'auto'; // Reset height
        }

        function addMessageToUI(user, text, isMe) {
            const box = document.getElementById('chatBox');
            const div = document.createElement('div');
            div.className = `chat-msg ${isMe ? 'me' : ''}`;
            div.innerHTML = `<span class="msg-sender">${user}</span>${text}`;
            box.appendChild(div);
            box.scrollTop = box.scrollHeight;
        }

        // --- 4. ADAPTIVE TEXTAREA SCRIPT ---
        const textarea = document.getElementById('chatInput');
        textarea.addEventListener('input', function() {
            this.style.height = 'auto'; // Reset to calculate shrinkage
            this.style.height = (this.scrollHeight) + 'px';
        });
        
        // Handle Enter key to send (Shift+Enter for newline)
        textarea.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendText();
            }
        });

        // --- 5. SOLVE LOGIC ---
        function solveTogether() {
            if(!confirm("Are you sure? This will mark the challenge as complete for everyone and award the bonus!")) return;

            const btn = document.querySelector('.btn-solve-together');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Verifying...';

            fetch('../../../../Controller/gamification_api.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({
                    action: 'complete_challenge_coop',
                    challenge_id: <?php echo $room['challenge_id']; ?>,
                    room_code: '<?php echo $roomCode; ?>'
                })
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    confetti({ particleCount: 300, spread: 180, startVelocity: 60 });
                    btn.style.background = "#fff";
                    btn.style.color = "#000";
                    btn.innerHTML = '🎉 SUCCESS!';
                    
                    setTimeout(() => {
                        alert("CO-OP COMPLETE! + " + data.points_awarded + " XP Awarded!");
                        window.location.href = "challenges.php";
                    }, 2000);
                } else {
                    alert("Error: " + data.message);
                    btn.innerHTML = 'Try Again';
                }
            })
            .catch(err => {
                console.error(err);
                alert("Connection failed.");
            });
        }
    </script>
</body>
</html>