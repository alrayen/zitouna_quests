<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    /* --- BOUTON FLOTTANT --- */
    .chat-btn-toggle {
        position: fixed; bottom: 30px; right: 30px; width: 60px; height: 60px;
        background: linear-gradient(135deg, #b34b00ff, #b34b00ff);
        color: white; border-radius: 50%; border: none;
        cursor: pointer; box-shadow: 0 4px 15px rgba(0,86,179,0.4); z-index: 99999;
        font-size: 28px; display: flex; align-items: center; justify-content: center;
        transition: transform 0.3s;
    }
    .chat-btn-toggle:hover { transform: scale(1.1); }

    /* --- FENÊTRE --- */
    .chat-container {
        position: fixed; bottom: 100px; right: 30px; width: 350px; height: 500px;
        background: white; border-radius: 15px; 
        box-shadow: 0 10px 40px rgba(0,0,0,0.25);
        z-index: 99999; display: none; flex-direction: column; overflow: hidden;
        font-family: 'Segoe UI', sans-serif; border: 1px solid rgba(0,0,0,0.1);
        animation: slideIn 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    @keyframes slideIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* --- HEADER (C'est ici qu'on répare tout) --- */
    .chat-header { 
        background: linear-gradient(135deg, #b34b00ff, #822e00ff); 
        color: white; 
        padding: 15px; 
        
        /* ALIGNEMENT STRICT */
        display: flex; 
        flex-direction: row; /* Force la ligne horizontale */
        justify-content: space-between; /* Pousse les éléments aux extrémités */
        align-items: center; /* Centre verticalement */
        gap: 10px;
    }
    
    /* Groupe Gauche : Icone + Texte */
    .header-left {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1; /* Prend toute la place disponible */
    }
    
    .robot-icon {
        width: 42px; height: 42px;
        background: rgba(255,255,255,0.15);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 22px;
        flex-shrink: 0; /* Empêche l'icône de s'écraser */
    }

    .header-info h4 { 
        margin: 0; 
        font-size: 16px; /* Taille ajustée pour ne pas dépasser */
        font-weight: 700; 
        color: #fff;
        line-height: 1.2;
    }
    
    /* Badge En ligne Vert */
    .status-badge {
        font-size: 12px; 
        color: #2ecc71; /* Vert */
        font-weight: 600; 
        margin-top: 3px;
        display: flex; align-items: center; gap: 5px;
    }

    .status-dot {
        width: 8px; height: 8px;
        background-color: #2ecc71; 
        border-radius: 50%;
        display: inline-block;
        box-shadow: 0 0 6px #2ecc71;
    }

    /* Bouton Fermer (X) */
    .close-chat { 
        background: transparent; 
        border: none; 
        color: rgba(255,255,255,0.7); 
        font-size: 24px; 
        cursor: pointer; 
        padding: 5px;
        width: 32px; height: 32px;
        display: flex; align-items: center; justify-content: center;
        border-radius: 50%;
        transition: all 0.2s;
        flex-shrink: 0; /* Empêche le bouton de s'écraser */
    }
    .close-chat:hover { background: rgba(255,255,255,0.1); color: white; }

    /* --- CORPS --- */
    .chat-body { flex: 1; padding: 20px; overflow-y: auto; background: #f8f9fa; display: flex; flex-direction: column; gap: 12px; }
    
    .message { max-width: 80%; padding: 12px 15px; border-radius: 18px; font-size: 14px; line-height: 1.5; word-wrap: break-word; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
    .bot-message { background: white; color: #333; align-self: flex-start; border-bottom-left-radius: 4px; border: 1px solid #e9ecef; }
    .user-message { background: #b34b00ff; color: white; align-self: flex-end; border-bottom-right-radius: 4px; }

    /* --- FOOTER --- */
    .chat-footer { padding: 15px; background: white; border-top: 1px solid #eee; display: flex; gap: 10px; align-items: center; }
    .chat-footer input { flex: 1; padding: 12px 15px; border: 1px solid #ddd; border-radius: 25px; outline: none; font-size: 14px; }
    .chat-footer input:focus { border-color: #b34b00ff; }
    .chat-footer button { background: #b34b00ff; color: white; border: none; width: 42px; height: 42px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; }
    
    /* Chips */
    .quick-replies { display: flex; gap: 8px; margin-top: 10px; flex-wrap: wrap; }
    .chip { background: white; color: #b34b00ff; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; cursor: pointer; border: 1px solid #0056b3; transition: all 0.2s; }
    .chip:hover { background: #b34b00ff; color: white; }
</style>

<button class="chat-btn-toggle" onclick="toggleChat()">
    <i class="fas fa-comment-dots"></i>
</button>

<div class="chat-container" id="chatWindow">
    <div class="chat-header">
        <div class="header-left">
            <div class="robot-icon">
                <i class="fas fa-robot"></i>
            </div>
            <div class="header-info">
                <h4>Assistant Zitouna</h4>
                <div class="status-badge">
                    <span class="status-dot"></span> En ligne
                </div>
            </div>
        </div>
        
        <button class="close-chat" onclick="toggleChat()" title="Fermer">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="chat-body" id="chatBody">
        <div class="message bot-message">
            Bonjour ! 👋 Je suis l'IA de Zitouna Quest.<br>
            <strong>Comment puis-je vous aider ?</strong>
            <div class="quick-replies">
                <span class="chip" onclick="sendQuickMsg('Comment faire le Login ?')">Connexion</span>
                <span class="chip" onclick="sendQuickMsg('Comment utiliser Face ID ?')">Face ID</span>
                <span class="chip" onclick="sendQuickMsg('Modifier mon profil')">Profil</span>
            </div>
        </div>
    </div>

    <div class="chat-footer">
        <input type="text" id="userMsg" placeholder="Écrivez votre question..." onkeypress="handleEnter(event)">
        <button onclick="sendMessage()"><i class="fas fa-paper-plane"></i></button>
    </div>
</div>

<script>
    function toggleChat() {
        const chat = document.getElementById('chatWindow');
        if (chat.style.display === 'none' || chat.style.display === '') {
            chat.style.display = 'flex';
            setTimeout(() => document.getElementById('userMsg').focus(), 100);
        } else {
            chat.style.display = 'none';
        }
    }

    function handleEnter(e) {
        if (e.key === 'Enter') sendMessage();
    }

    function sendQuickMsg(msg) {
        document.getElementById('userMsg').value = msg;
        sendMessage();
    }

    function sendMessage() {
        const input = document.getElementById('userMsg');
        const text = input.value.trim();
        const chatBody = document.getElementById('chatBody');

        if (text === "") return;

        chatBody.innerHTML += `<div class="message user-message">${text}</div>`;
        input.value = "";
        chatBody.scrollTop = chatBody.scrollHeight;

        const loadingId = 'loading-' + Date.now();
        chatBody.innerHTML += `
            <div class="message bot-message" id="${loadingId}" style="width:fit-content; color:#888;">
                <i class="fas fa-circle-notch fa-spin"></i> Écrit...
            </div>`;
        chatBody.scrollTop = chatBody.scrollHeight;

        const formData = new FormData();
        formData.append('message', text);

        fetch('/Projet2/Controller/chatbotHandler.php', { 
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById(loadingId).remove();
            chatBody.innerHTML += `<div class="message bot-message">${data.response}</div>`;
            chatBody.scrollTop = chatBody.scrollHeight;
        })
        .catch(error => {
            document.getElementById(loadingId).remove();
            chatBody.innerHTML += `<div class="message bot-message" style="color:red">Erreur de connexion.</div>`;
        });
    }
</script>