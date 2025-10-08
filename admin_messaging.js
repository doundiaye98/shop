// Système de messagerie pour l'admin
let currentConversationId = null;
let conversations = [];
let messagesRefreshInterval = null;

// Charger le contenu de la messagerie
function loadMessagesContent() {
    const content = document.querySelector('.admin-content');
    content.innerHTML = `
        ${getMessagingStyles()}
        <div class="messaging-container">
            <div class="row h-100">
                <!-- Liste des conversations -->
                <div class="col-md-4 conversations-panel">
                    <div class="conversations-header">
                        <h5><i class="bi bi-chat-dots"></i> Conversations</h5>
                        <button class="btn btn-sm btn-outline-primary" onclick="refreshConversations()">
                            <i class="bi bi-arrow-clockwise"></i>
                        </button>
                    </div>
                    <div class="conversations-search">
                        <input type="text" class="form-control" placeholder="Rechercher..." id="conversationsSearch" onkeyup="filterConversations()">
                    </div>
                    <div class="conversations-list" id="conversationsList">
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-chat-dots fs-1"></i>
                            <p>Chargement des conversations...</p>
                        </div>
                    </div>
                </div>

                <!-- Zone de chat -->
                <div class="col-md-8 chat-panel">
                    <div id="chatEmpty" class="chat-empty">
                        <i class="bi bi-chat-left-text fs-1 text-muted"></i>
                        <p class="text-muted">Sélectionnez une conversation pour commencer</p>
                    </div>
                    
                    <div id="chatActive" class="chat-active" style="display: none;">
                        <!-- En-tête du chat -->
                        <div class="chat-header">
                            <div class="chat-user-info">
                                <div class="chat-avatar">
                                    <i class="bi bi-person-circle"></i>
                                </div>
                                <div>
                                    <h6 id="chatUserName">-</h6>
                                    <small class="text-muted" id="chatUserEmail">-</small>
                                </div>
                            </div>
                    <div class="chat-actions">
                        <button class="btn btn-sm btn-outline-secondary" onclick="markConversationAsRead()" title="Marquer comme lu">
                            <i class="bi bi-check-all"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteConversation()" title="Supprimer">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                        </div>

                        <!-- Messages -->
                        <div class="chat-messages" id="chatMessages">
                            <!-- Messages chargés dynamiquement -->
                        </div>

                        <!-- Zone de saisie -->
                        <div class="chat-input">
                            <form onsubmit="sendMessage(event)" id="messageForm">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Tapez votre message..." id="messageInput" required>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-send"></i> Envoyer
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    loadConversations();
    startMessagesRefresh();
}

// Obtenir les styles CSS inline
function getMessagingStyles() {
    return `
    <style>
        .messaging-container {
            height: calc(100vh - 200px);
            min-height: 600px;
        }
        .conversations-panel, .chat-panel {
            height: 100%;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }
        .conversations-panel {
            border-right: 1px solid #e2e8f0;
        }
        .conversations-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid #e2e8f0;
        }
        .conversations-header h5 {
            margin: 0;
            color: #1e293b;
        }
        .conversations-search {
            padding: 1rem;
            border-bottom: 1px solid #e2e8f0;
        }
        .conversations-list {
            height: calc(100% - 130px);
            overflow-y: auto;
        }
        .conversation-item {
            padding: 1rem;
            border-bottom: 1px solid #e2e8f0;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .conversation-item:hover {
            background-color: #f8fafc;
        }
        .conversation-item.active {
            background-color: #e0e7ff;
            border-left: 3px solid #4f46e5;
        }
        .conversation-item.unread {
            background-color: #fef3c7;
        }
        .conversation-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }
        .conversation-name {
            font-weight: 600;
            color: #1e293b;
        }
        .conversation-time {
            font-size: 0.75rem;
            color: #64748b;
        }
        .conversation-subject {
            font-size: 0.875rem;
            color: #475569;
            margin-bottom: 0.25rem;
        }
        .conversation-preview {
            font-size: 0.875rem;
            color: #64748b;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .conversation-unread-badge {
            background: #dc2626;
            color: white;
            border-radius: 12px;
            padding: 0.125rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .chat-empty {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: #94a3b8;
        }
        .chat-active {
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .chat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid #e2e8f0;
        }
        .chat-user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .chat-avatar {
            font-size: 2rem;
            color: #4f46e5;
        }
        .chat-user-info h6 {
            margin: 0;
            color: #1e293b;
        }
        .chat-actions {
            display: flex;
            gap: 0.5rem;
        }
        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 1.5rem;
            background: #f8fafc;
        }
        .message {
            display: flex;
            margin-bottom: 1.5rem;
        }
        .message.sent {
            justify-content: flex-end;
        }
        .message.received {
            justify-content: flex-start;
        }
        .message-content {
            max-width: 70%;
            padding: 0.75rem 1rem;
            border-radius: 12px;
        }
        .message.sent .message-content {
            background: #4f46e5;
            color: white;
            border-bottom-right-radius: 4px;
        }
        .message.received .message-content {
            background: white;
            color: #1e293b;
            border-bottom-left-radius: 4px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }
        .message-text {
            margin-bottom: 0.25rem;
            line-height: 1.5;
        }
        .message-time {
            font-size: 0.75rem;
            opacity: 0.7;
        }
        .chat-input {
            padding: 1rem;
            border-top: 1px solid #e2e8f0;
            background: white;
        }
        .chat-input .input-group {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
        }
        .chat-input input {
            border: none;
            padding: 0.75rem;
        }
        .chat-input input:focus {
            box-shadow: none;
        }
        .chat-input button {
            border: none;
            border-radius: 0;
        }
    </style>
    `;
}

// Charger les conversations
function loadConversations() {
    fetch('admin_api.php?action=get_conversations')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                conversations = data.conversations;
                displayConversations(conversations);
                updateMessagesBadge();
            } else {
                document.getElementById('conversationsList').innerHTML = '<p class="text-center text-muted p-4">Aucune conversation</p>';
            }
        })
        .catch(error => {
            console.error('Erreur chargement conversations:', error);
            document.getElementById('conversationsList').innerHTML = '<p class="text-center text-danger p-4">Erreur de chargement</p>';
        });
}

// Afficher les conversations
function displayConversations(conversationsList) {
    const container = document.getElementById('conversationsList');
    if (!conversationsList || conversationsList.length === 0) {
        container.innerHTML = '<p class="text-center text-muted p-4">Aucune conversation</p>';
        return;
    }
    
    container.innerHTML = conversationsList.map(conv => `
        <div class="conversation-item ${conv.unread_count > 0 ? 'unread' : ''} ${currentConversationId === conv.id ? 'active' : ''}" onclick="openConversation(${conv.id})">
            <div class="conversation-header">
                <span class="conversation-name">${conv.customer_name || 'Client'}</span>
                <div class="d-flex align-items-center gap-2">
                    ${conv.unread_count > 0 ? `<span class="conversation-unread-badge">${conv.unread_count}</span>` : ''}
                    <span class="conversation-time">${formatTime(conv.last_message_at)}</span>
                </div>
            </div>
            <div class="conversation-subject">${conv.subject || 'Pas de sujet'}</div>
            <div class="conversation-preview">${conv.last_message || 'Aucun message'}</div>
        </div>
    `).join('');
}

// Ouvrir une conversation
function openConversation(conversationId) {
    currentConversationId = conversationId;
    
    // Mettre à jour l'interface
    document.querySelectorAll('.conversation-item').forEach(item => {
        item.classList.remove('active');
    });
    event.currentTarget.classList.add('active');
    
    // Afficher la zone de chat
    document.getElementById('chatEmpty').style.display = 'none';
    document.getElementById('chatActive').style.display = 'flex';
    
    // Charger les messages
    loadConversationMessages(conversationId);
}

// Charger les messages d'une conversation
function loadConversationMessages(conversationId) {
    fetch(`admin_api.php?action=get_conversation_messages&id=${conversationId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Mettre à jour l'en-tête
                document.getElementById('chatUserName').textContent = data.conversation.customer_name || 'Client';
                document.getElementById('chatUserEmail').textContent = data.conversation.customer_email || '';
                
                // Afficher les messages
                displayMessages(data.messages);
                
                // Marquer les messages comme lus
                markMessagesAsRead(conversationId);
            } else {
                showNotification('Erreur: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Erreur chargement messages:', error);
            showNotification('Erreur de connexion', 'error');
        });
}

// Afficher les messages
function displayMessages(messages) {
    const container = document.getElementById('chatMessages');
    if (!messages || messages.length === 0) {
        container.innerHTML = '<p class="text-center text-muted">Aucun message</p>';
        return;
    }
    
    container.innerHTML = messages.map(msg => {
        const isSent = msg.sender_role === 'admin';
        return `
            <div class="message ${isSent ? 'sent' : 'received'}">
                <div class="message-content">
                    <div class="message-text">${msg.message}</div>
                    <div class="message-time">${formatTime(msg.created_at)}</div>
                </div>
            </div>
        `;
    }).join('');
    
    // Scroll vers le bas
    container.scrollTop = container.scrollHeight;
}

// Envoyer un message
function sendMessage(event) {
    event.preventDefault();
    
    const input = document.getElementById('messageInput');
    const message = input.value.trim();
    
    if (!message || !currentConversationId) return;
    
    fetch('admin_api.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            action: 'send_message',
            conversation_id: currentConversationId,
            message: message
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            input.value = '';
            loadConversationMessages(currentConversationId);
            refreshConversations();
        } else {
            showNotification('Erreur: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Erreur envoi message:', error);
        showNotification('Erreur de connexion', 'error');
    });
}

// Marquer les messages comme lus
function markMessagesAsRead(conversationId) {
    fetch('admin_api.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            action: 'mark_messages_read',
            conversation_id: conversationId
        })
    })
    .then(() => {
        refreshConversations();
    })
    .catch(error => {
        console.error('Erreur marquage messages:', error);
    });
}

// Marquer la conversation comme lue
function markConversationAsRead() {
    if (currentConversationId) {
        markMessagesAsRead(currentConversationId);
        showNotification('Messages marqués comme lus', 'success');
    }
}

// Supprimer une conversation
function deleteConversation() {
    if (!currentConversationId) return;
    
    if (!confirm('Êtes-vous sûr de vouloir supprimer cette conversation ? Cette action est irréversible.')) {
        return;
    }
    
    fetch('admin_api.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            action: 'delete_conversation',
            conversation_id: currentConversationId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Conversation supprimée', 'success');
            currentConversationId = null;
            document.getElementById('chatEmpty').style.display = 'flex';
            document.getElementById('chatActive').style.display = 'none';
            refreshConversations();
        } else {
            showNotification('Erreur: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Erreur suppression conversation:', error);
        showNotification('Erreur de connexion', 'error');
    });
}

// Rafraîchir les conversations
function refreshConversations() {
    loadConversations();
}

// Filtrer les conversations
function filterConversations() {
    const search = document.getElementById('conversationsSearch').value.toLowerCase();
    const filtered = conversations.filter(conv => 
        (conv.customer_name && conv.customer_name.toLowerCase().includes(search)) ||
        (conv.subject && conv.subject.toLowerCase().includes(search)) ||
        (conv.last_message && conv.last_message.toLowerCase().includes(search))
    );
    displayConversations(filtered);
}

// Mettre à jour le badge de messages
function updateMessagesBadge() {
    const totalUnread = conversations.reduce((sum, conv) => sum + parseInt(conv.unread_count || 0), 0);
    const badge = document.getElementById('messagesBadge');
    if (badge) {
        if (totalUnread > 0) {
            badge.textContent = totalUnread;
            badge.style.display = 'inline-block';
        } else {
            badge.style.display = 'none';
        }
    }
}

// Démarrer le rafraîchissement automatique
function startMessagesRefresh() {
    // Rafraîchir toutes les 10 secondes
    messagesRefreshInterval = setInterval(() => {
        if (currentSection === 'messages') {
            refreshConversations();
            if (currentConversationId) {
                loadConversationMessages(currentConversationId);
            }
        }
    }, 10000);
}

// Arrêter le rafraîchissement automatique
function stopMessagesRefresh() {
    if (messagesRefreshInterval) {
        clearInterval(messagesRefreshInterval);
        messagesRefreshInterval = null;
    }
}

// Nettoyer lors du changement de section
document.addEventListener('sectionChanged', function(e) {
    if (e.detail.section !== 'messages') {
        stopMessagesRefresh();
        currentConversationId = null;
    }
});
