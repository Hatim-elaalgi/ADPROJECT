document.addEventListener('DOMContentLoaded', function() {
    const themeId = document.querySelector('meta[name="theme-id"]').content;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    
    // Navigation
    const navLinks = document.querySelectorAll('.sidebar-nav a');
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const section = this.dataset.section;
            
            // Update active nav link
            navLinks.forEach(l => l.classList.remove('active'));
            this.classList.add('active');
            
            // Show selected section
            document.querySelectorAll('.content-section').forEach(s => s.classList.remove('active'));
            document.getElementById(section).classList.add('active');
        });
    });

    // Article Status Update
    const statusSelects = document.querySelectorAll('.status-select');
    statusSelects.forEach(select => {
        // Stocker le statut initial
        select.dataset.originalStatus = select.value;
        
        select.addEventListener('change', function() {
            const articleId = this.dataset.articleId;
            const newStatus = this.value;
            const originalStatus = this.dataset.originalStatus;
            const card = this.closest('.article-card');

            fetch(`/articles/${articleId}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ status: newStatus })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Mettre à jour le statut original
                    this.dataset.originalStatus = newStatus;
                    // Mettre à jour le data-status de la carte
                    card.dataset.status = newStatus;
                    showNotification('Statut mis à jour avec succès', 'success');
                    updateStats();
                } else {
                    throw new Error(data.error || 'Une erreur est survenue');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Revenir au statut original en cas d'erreur
                this.value = originalStatus;
                showNotification('Erreur lors de la mise à jour du statut', 'error');
            });
        });
    });

    // Delete Article
    const deleteButtons = document.querySelectorAll('.delete-article');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cet article ?')) {
                return;
            }

            const articleId = this.dataset.articleId;
            const card = this.closest('.article-card');

            fetch(`/articles/${articleId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    fadeOut(card, function() {
                        card.remove();
                        updateStats();
                    });
                    showNotification('Article supprimé avec succès', 'success');
                } else {
                    throw new Error(data.error || 'Une erreur est survenue');
                }
            })
            .catch(error => {
                showNotification('Erreur lors de la suppression de l\'article', 'error');
                console.error('Error:', error);
            });
        });
    });

    // Remove Subscriber
    const removeSubscriberButtons = document.querySelectorAll('.remove-subscriber');
    removeSubscriberButtons.forEach(button => {
        button.addEventListener('click', function() {
            if (!confirm('Êtes-vous sûr de vouloir retirer cet abonné ? Tous ses articles dans ce thème seront supprimés.')) {
                return;
            }

            const subscriberId = this.dataset.subscriberId;
            const card = this.closest('.subscriber-card');

            fetch(`/themes/${themeId}/subscribers/${subscriberId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => {
                if (!response.ok) {
                    console.error('Server response:', response.status, response.statusText);
                    return response.json().then(data => {
                        throw new Error(data.error || `HTTP error! status: ${response.status}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Remove subscriber card with animation
                    fadeOut(card, function() {
                        card.remove();
                    });

                    // Remove all articles by this subscriber
                    const articles = document.querySelectorAll(`.article-card[data-user-id="${subscriberId}"]`);
                    articles.forEach(article => {
                        fadeOut(article, function() {
                            article.remove();
                        });
                    });

                    showNotification(`Abonné retiré avec succès. ${data.articles_deleted} articles supprimés.`, 'success');
                    updateStats();
                } else {
                    throw new Error(data.error || 'Une erreur est survenue');
                }
            })
            .catch(error => {
                showNotification('Erreur lors du retrait de l\'abonné', 'error');
                console.error('Error:', error);
            });
        });
    });

    // Article Status Filter
    const statusFilter = document.getElementById('statusFilter');
    statusFilter.addEventListener('change', function() {
        const status = this.value;
        const cards = document.querySelectorAll('.article-card');
        
        cards.forEach(card => {
            if (status === 'all' || card.dataset.status === status) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    });

    // Subscriber Search
    const subscriberSearch = document.getElementById('subscriberSearch');
    subscriberSearch.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const cards = document.querySelectorAll('.subscriber-card');
        
        cards.forEach(card => {
            const name = card.querySelector('h3').textContent.toLowerCase();
            const email = card.querySelector('p').textContent.toLowerCase();
            
            if (name.includes(searchTerm) || email.includes(searchTerm)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    });

    // Update Statistics
    function updateStats() {
        const cards = document.querySelectorAll('.article-card');
        const publishedCards = document.querySelectorAll('.article-card[data-status="publie"]');
        const pendingCards = document.querySelectorAll('.article-card[data-status="en_cours"]');
        
        document.querySelectorAll('.stat-card').forEach(card => {
            const statLabel = card.querySelector('.stat-label').textContent;
            const statValue = card.querySelector('.stat-value');
            
            switch(statLabel) {
                case 'Articles':
                    statValue.textContent = cards.length;
                    break;
                case 'Articles Publiés':
                    statValue.textContent = publishedCards.length;
                    break;
                case 'En Attente':
                    statValue.textContent = pendingCards.length;
                    break;
            }
        });
    }

    // Utility Functions
    function fadeOut(element, callback) {
        element.style.opacity = 1;
        
        (function fade() {
            if ((element.style.opacity -= .1) < 0) {
                element.style.display = 'none';
                if (callback) callback();
            } else {
                requestAnimationFrame(fade);
            }
        })();
    }

    function showNotification(message, type) {
        // Remove any existing notifications
        const existingNotifications = document.querySelectorAll('.notification');
        existingNotifications.forEach(n => n.remove());

        // Create new notification
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.innerHTML = `<p>${message}</p>`;
        document.body.appendChild(notification);

        // Remove notification after 3 seconds
        setTimeout(() => {
            fadeOut(notification, function() {
                notification.remove();
            });
        }, 3000);
    }

    // Add notification styles
    const style = document.createElement('style');
    style.textContent = `
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 25px;
            border-radius: 5px;
            color: white;
            z-index: 1000;
            animation: slideIn 0.3s ease-out;
        }

        .notification.success {
            background-color: #4CAF50;
        }

        .notification.error {
            background-color: #f44336;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    `;
    document.head.appendChild(style);

    // Comment Moderation
    const commentsGrid = document.querySelector('.comments-grid');
    const commentStatusFilter = document.getElementById('commentStatusFilter');
    const commentArticleFilter = document.getElementById('commentArticleFilter');

    if (commentsGrid) {
        commentsGrid.addEventListener('click', function(e) {
            const toggleBtn = e.target.closest('.toggle-visibility-btn');
            const deleteBtn = e.target.closest('.delete-comment-btn');
            
            if (toggleBtn) {
                const commentCard = toggleBtn.closest('.comment-card');
                const commentId = commentCard.dataset.commentId;
                toggleCommentVisibility(commentId, commentCard);
            }
            
            if (deleteBtn) {
                const commentCard = deleteBtn.closest('.comment-card');
                const commentId = commentCard.dataset.commentId;
                deleteComment(commentId, commentCard);
            }
        });
    }

    if (commentStatusFilter) {
        commentStatusFilter.addEventListener('change', filterComments);
    }

    if (commentArticleFilter) {
        commentArticleFilter.addEventListener('change', filterComments);
    }

    function filterComments() {
        const statusValue = commentStatusFilter.value;
        const articleValue = commentArticleFilter.value;
        
        document.querySelectorAll('.comment-card').forEach(card => {
            const matchesStatus = statusValue === 'all' || card.dataset.visibility === statusValue;
            const matchesArticle = articleValue === 'all' || card.dataset.articleId === articleValue;
            
            card.style.display = matchesStatus && matchesArticle ? 'block' : 'none';
        });
    }

    function toggleCommentVisibility(commentId, commentCard) {
        fetch(`/comments/${commentId}/toggle-visibility`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                const toggleBtn = commentCard.querySelector('.toggle-visibility-btn');
                const statusBadge = commentCard.querySelector('.status-badge');
                const icon = toggleBtn.querySelector('i');
                
                commentCard.dataset.visibility = data.visibility;
                
                if (data.visibility === 'hidden') {
                    statusBadge.textContent = 'Masqué';
                    statusBadge.className = 'status-badge hidden';
                    icon.className = 'fas fa-eye';
                    toggleBtn.title = 'Afficher';
                    toggleBtn.innerHTML = '<i class="fas fa-eye"></i> Afficher';
                } else {
                    statusBadge.textContent = 'Visible';
                    statusBadge.className = 'status-badge visible';
                    icon.className = 'fas fa-eye-slash';
                    toggleBtn.title = 'Masquer';
                    toggleBtn.innerHTML = '<i class="fas fa-eye-slash"></i> Masquer';
                }
                
                showNotification(data.message, 'success');
                filterComments(); // Re-apply filters
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Erreur lors de la modification de la visibilité', 'error');
        });
    }

    function deleteComment(commentId, commentCard) {
        if (!confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?')) {
            return;
        }

        fetch(`/comments/${commentId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                commentCard.classList.add('deleting');
                setTimeout(() => {
                    commentCard.remove();
                    filterComments(); // Re-apply filters
                }, 300);
                showNotification(data.message, 'success');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Erreur lors de la suppression du commentaire', 'error');
        });
    }
});
