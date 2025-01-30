document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    
    // Navigation
    const navLinks = document.querySelectorAll('.sidebar-nav a, .section-link');
    const sections = document.querySelectorAll('.content-section');

    function showSection(sectionId) {
        // Cacher toutes les sections
        sections.forEach(section => {
            section.classList.remove('active');
        });

        // Afficher la section sélectionnée
        const targetSection = document.querySelector(sectionId);
        if (targetSection) {
            targetSection.classList.add('active');
        }

        // Mettre à jour les classes actives dans la navigation
        navLinks.forEach(link => {
            if (link.getAttribute('href') === sectionId) {
                link.classList.add('active');
            } else {
                link.classList.remove('active');
            }
        });

        // Faire défiler jusqu'à la section si nécessaire
        if (targetSection) {
            targetSection.scrollIntoView({ behavior: 'smooth' });
        }
    }

    // Gestionnaire d'événements pour les liens de navigation
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const sectionId = this.getAttribute('href');
            showSection(sectionId);
        });
    });

    // Afficher la section active au chargement de la page
    const activeSection = document.querySelector('.content-section.active');
    if (activeSection) {
        const sectionId = '#' + activeSection.id;
        showSection(sectionId);
    }

    // Comment deletion
    const commentsList = document.querySelector('.comments-list');
    if (commentsList) {
        commentsList.addEventListener('click', function(e) {
            const deleteBtn = e.target.closest('.delete-comment-btn');
            if (deleteBtn) {
                const commentCard = deleteBtn.closest('.comment-card');
                const commentId = commentCard.dataset.commentId;
                
                if (confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?')) {
                    deleteComment(commentId, commentCard);
                }
            }
        });
    }

    function deleteComment(commentId, commentCard) {
        fetch(`/subscriber/comments/${commentId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
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
                }, 300);
                showNotification(data.message, 'success');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Erreur lors de la suppression du commentaire', 'error');
        });
    }

    // Form validation
    const articleForm = document.querySelector('.article-form');
    if (articleForm) {
        articleForm.addEventListener('submit', function(e) {
            const title = this.querySelector('#title').value.trim();
            const content = this.querySelector('#content').value.trim();
            
            if (title.length < 3) {
                e.preventDefault();
                showNotification('Le titre doit contenir au moins 3 caractères', 'error');
                return;
            }
            
            if (content.length < 100) {
                e.preventDefault();
                showNotification('Le contenu doit contenir au moins 100 caractères', 'error');
                return;
            }
        });
    }
});
