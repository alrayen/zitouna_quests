<!-- 
=============================================================================
SYSTÈME DE NOTIFICATIONS - ZITOUNA QUEST
Fichier à inclure dans toutes vos pages : <?php include 'notifications.php'; ?>
=============================================================================
-->

<style>
/* ===== TOAST NOTIFICATIONS ===== */
.toast-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    gap: 15px;
    max-width: 400px;
}

.toast {
    background: white;
    padding: 18px 24px;
    border-radius: 12px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.15);
    display: flex;
    align-items: center;
    gap: 15px;
    min-width: 300px;
    animation: slideIn 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    border-left: 5px solid;
    position: relative;
    overflow: hidden;
}

.toast::before {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    height: 3px;
    background: currentColor;
    animation: shrink 5s linear forwards;
}

@keyframes shrink {
    from { width: 100%; }
    to { width: 0%; }
}

@keyframes slideIn {
    from {
        transform: translateX(400px);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOut {
    to {
        transform: translateX(400px);
        opacity: 0;
    }
}

.toast.hiding {
    animation: slideOut 0.3s ease-out forwards;
}

.toast-icon {
    font-size: 28px;
    flex-shrink: 0;
    animation: bounce 0.6s;
}

@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

.toast-content {
    flex-grow: 1;
}

.toast-title {
    font-weight: 700;
    font-size: 15px;
    margin-bottom: 4px;
    color: #2c3e50;
}

.toast-message {
    font-size: 13px;
    color: #7f8c8d;
    line-height: 1.4;
}

.toast-close {
    background: none;
    border: none;
    font-size: 20px;
    color: #95a5a6;
    cursor: pointer;
    padding: 0;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.3s;
}

.toast-close:hover {
    background: #ecf0f1;
    color: #2c3e50;
}

.toast.success {
    border-left-color: #27ae60;
}

.toast.success .toast-icon {
    color: #27ae60;
}

.toast.error {
    border-left-color: #e74c3c;
}

.toast.error .toast-icon {
    color: #e74c3c;
}

.toast.warning {
    border-left-color: #f39c12;
}

.toast.warning .toast-icon {
    color: #f39c12;
}

.toast.info {
    border-left-color: #3498db;
}

.toast.info .toast-icon {
    color: #3498db;
}

/* ===== NOTIFICATION BELL ===== */
.notification-bell {
    position: relative;
    cursor: pointer;
    padding: 10px;
    border-radius: 50%;
    background: white;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.notification-bell:hover {
    background: #f8f9fa;
    transform: scale(1.05);
}

.notification-bell-icon {
    font-size: 24px;
    color: #2c3e50;
}

.notification-badge {
    position: absolute;
    top: 5px;
    right: 5px;
    background: #e74c3c;
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: bold;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

/* ===== NOTIFICATION PANEL ===== */
.notification-panel {
    position: fixed;
    top: 80px;
    right: 20px;
    width: 380px;
    max-height: 500px;
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.15);
    display: none;
    flex-direction: column;
    z-index: 9998;
    animation: fadeInDown 0.3s;
}

@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.notification-panel.show {
    display: flex;
}

.notification-header {
    padding: 20px;
    border-bottom: 2px solid #ecf0f1;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.notification-header h3 {
    font-size: 18px;
    font-weight: 700;
    color: #2c3e50;
}

.notification-clear {
    background: none;
    border: none;
    color: #3498db;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    padding: 5px 10px;
    border-radius: 5px;
    transition: all 0.3s;
}

.notification-clear:hover {
    background: #e3f2fd;
}

.notification-list {
    overflow-y: auto;
    max-height: 380px;
    padding: 10px 0;
}

.notification-item {
    padding: 15px 20px;
    border-bottom: 1px solid #f8f9fa;
    display: flex;
    gap: 15px;
    align-items: start;
    transition: all 0.3s;
    cursor: pointer;
}

.notification-item:hover {
    background: #f8f9fa;
}

.notification-item.unread {
    background: #e8f5e9;
}

.notification-item-icon {
    font-size: 24px;
    flex-shrink: 0;
}

.notification-item-content {
    flex-grow: 1;
}

.notification-item-title {
    font-weight: 600;
    font-size: 14px;
    color: #2c3e50;
    margin-bottom: 4px;
}

.notification-item-message {
    font-size: 13px;
    color: #7f8c8d;
    line-height: 1.4;
}

.notification-item-time {
    font-size: 11px;
    color: #95a5a6;
    margin-top: 5px;
}

.notification-empty {
    padding: 40px 20px;
    text-align: center;
    color: #95a5a6;
}

.notification-empty-icon {
    font-size: 48px;
    margin-bottom: 15px;
    opacity: 0.5;
}

/* ===== LOADING OVERLAY ===== */
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 10000;
}

.loading-overlay.show {
    display: flex;
}

.spinner {
    width: 50px;
    height: 50px;
    border: 5px solid #f3f3f3;
    border-top: 5px solid #27ae60;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* ===== PROGRESS BAR ===== */
.progress-bar-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 3px;
    background: #ecf0f1;
    z-index: 9997;
    display: none;
}

.progress-bar-container.show {
    display: block;
}

.progress-bar {
    height: 100%;
    background: linear-gradient(90deg, #27ae60, #2ecc71);
    width: 0%;
    transition: width 0.3s;
    box-shadow: 0 0 10px #27ae60;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .toast-container {
        right: 10px;
        left: 10px;
        top: 10px;
    }
    
    .toast {
        min-width: auto;
        width: 100%;
    }
    
    .notification-panel {
        right: 10px;
        left: 10px;
        width: auto;
    }
}
</style>

<!-- HTML ELEMENTS -->
<div id="toastContainer" class="toast-container"></div>

<div id="loadingOverlay" class="loading-overlay">
    <div class="spinner"></div>
</div>

<div id="progressBarContainer" class="progress-bar-container">
    <div id="progressBar" class="progress-bar"></div>
</div>

<!-- JAVASCRIPT -->
<script>
// ===== TOAST NOTIFICATIONS =====
const ToastNotification = {
    container: null,
    
    init() {
        this.container = document.getElementById('toastContainer');
        if (!this.container) {
            this.container = document.createElement('div');
            this.container.id = 'toastContainer';
            this.container.className = 'toast-container';
            document.body.appendChild(this.container);
        }
    },
    
    show(type, title, message, duration = 5000) {
        this.init();
        
        const icons = {
            success: '✅',
            error: '❌',
            warning: '⚠️',
            info: 'ℹ️'
        };
        
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.innerHTML = `
            <div class="toast-icon">${icons[type] || '📢'}</div>
            <div class="toast-content">
                <div class="toast-title">${title}</div>
                <div class="toast-message">${message}</div>
            </div>
            <button class="toast-close" onclick="ToastNotification.close(this)">×</button>
        `;
        
        this.container.appendChild(toast);
        
        setTimeout(() => {
            this.close(toast.querySelector('.toast-close'));
        }, duration);
    },
    
    close(button) {
        const toast = button.closest('.toast');
        if (toast) {
            toast.classList.add('hiding');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }
    },
    
    success(title, message, duration) {
        this.show('success', title, message, duration);
    },
    
    error(title, message, duration) {
        this.show('error', title, message, duration);
    },
    
    warning(title, message, duration) {
        this.show('warning', title, message, duration);
    },
    
    info(title, message, duration) {
        this.show('info', title, message, duration);
    }
};

// ===== NOTIFICATION CENTER =====
const NotificationCenter = {
    notifications: [],
    unreadCount: 0,
    
    init() {
        this.loadFromStorage();
        this.updateBadge();
    },
    
    add(type, title, message) {
        const notification = {
            id: Date.now(),
            type: type,
            title: title,
            message: message,
            time: new Date().toISOString(),
            read: false
        };
        
        this.notifications.unshift(notification);
        this.unreadCount++;
        this.saveToStorage();
        this.updateBadge();
        this.render();
        
        ToastNotification.show(type, title, message);
    },
    
    markAsRead(id) {
        const notif = this.notifications.find(n => n.id === id);
        if (notif && !notif.read) {
            notif.read = true;
            this.unreadCount--;
            this.saveToStorage();
            this.updateBadge();
            this.render();
        }
    },
    
    clearAll() {
        if (confirm('Effacer toutes les notifications ?')) {
            this.notifications = [];
            this.unreadCount = 0;
            this.saveToStorage();
            this.updateBadge();
            this.render();
        }
    },
    
    updateBadge() {
        const badge = document.getElementById('notificationBadge');
        if (badge) {
            badge.textContent = this.unreadCount;
            badge.style.display = this.unreadCount > 0 ? 'flex' : 'none';
        }
    },
    
    render() {
        const list = document.getElementById('notificationList');
        if (!list) return;
        
        if (this.notifications.length === 0) {
            list.innerHTML = `
                <div class="notification-empty">
                    <div class="notification-empty-icon">🔔</div>
                    <div>Aucune notification</div>
                </div>
            `;
            return;
        }
        
        const icons = {
            success: '✅',
            error: '❌',
            warning: '⚠️',
            info: 'ℹ️'
        };
        
        list.innerHTML = this.notifications.map(notif => `
            <div class="notification-item ${notif.read ? '' : 'unread'}" 
                 onclick="NotificationCenter.markAsRead(${notif.id})">
                <div class="notification-item-icon">${icons[notif.type] || '📢'}</div>
                <div class="notification-item-content">
                    <div class="notification-item-title">${notif.title}</div>
                    <div class="notification-item-message">${notif.message}</div>
                    <div class="notification-item-time">${this.timeAgo(notif.time)}</div>
                </div>
            </div>
        `).join('');
    },
    
    toggle() {
        const panel = document.getElementById('notificationPanel');
        if (panel) {
            panel.classList.toggle('show');
            this.render();
        }
    },
    
    timeAgo(dateString) {
        const date = new Date(dateString);
        const seconds = Math.floor((new Date() - date) / 1000);
        
        if (seconds < 60) return 'À l\'instant';
        if (seconds < 3600) return `Il y a ${Math.floor(seconds / 60)} min`;
        if (seconds < 86400) return `Il y a ${Math.floor(seconds / 3600)} h`;
        return `Il y a ${Math.floor(seconds / 86400)} j`;
    },
    
    saveToStorage() {
        try {
            localStorage.setItem('zitouna_notifications', JSON.stringify(this.notifications));
            localStorage.setItem('zitouna_unread_count', this.unreadCount);
        } catch (e) {
            console.warn('LocalStorage non disponible:', e);
        }
    },
    
    loadFromStorage() {
        try {
            const stored = localStorage.getItem('zitouna_notifications');
            if (stored) {
                this.notifications = JSON.parse(stored);
                this.unreadCount = parseInt(localStorage.getItem('zitouna_unread_count') || '0');
            }
        } catch (e) {
            console.warn('Erreur de chargement:', e);
        }
    }
};

// ===== LOADING OVERLAY =====
const LoadingOverlay = {
    show() {
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) overlay.classList.add('show');
    },
    
    hide() {
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) overlay.classList.remove('show');
    }
};

// ===== PROGRESS BAR =====
const ProgressBar = {
    show() {
        const container = document.getElementById('progressBarContainer');
        if (container) container.classList.add('show');
        this.setProgress(0);
    },
    
    hide() {
        const container = document.getElementById('progressBarContainer');
        if (container) {
            this.setProgress(100);
            setTimeout(() => {
                container.classList.remove('show');
            }, 300);
        }
    },
    
    setProgress(percent) {
        const bar = document.getElementById('progressBar');
        if (bar) bar.style.width = percent + '%';
    }
};

// ===== AUTO-INIT =====
document.addEventListener('DOMContentLoaded', () => {
    NotificationCenter.init();
});

// ===== CLOSE PANEL ON OUTSIDE CLICK =====
document.addEventListener('click', (e) => {
    const panel = document.getElementById('notificationPanel');
    const bell = document.querySelector('.notification-bell');
    
    if (panel && bell && !panel.contains(e.target) && !bell.contains(e.target)) {
        panel.classList.remove('show');
    }
});
</script>