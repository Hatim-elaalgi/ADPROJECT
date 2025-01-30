document.addEventListener('DOMContentLoaded', function() {
    const ratingSection = document.querySelector('.rating-section');
    const stars = document.querySelectorAll('.star');
    const commentForm = document.getElementById('comment-form');
    const commentsList = document.querySelector('.comments-list');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    // Star Rating System
    if (ratingSection) {
        const articleId = ratingSection.dataset.articleId;
        let userRating = null;

        stars.forEach(star => {
            // Hover effect
            star.addEventListener('mouseover', function() {
                const rating = this.dataset.rating;
                updateStarsDisplay(rating);
            });

            star.addEventListener('mouseout', function() {
                updateStarsDisplay(userRating);
            });

            // Click to rate
            star.addEventListener('click', function() {
                const rating = this.dataset.rating;
                submitRating(articleId, rating);
            });
        });

        function updateStarsDisplay(rating) {
            stars.forEach(star => {
                const starRating = star.dataset.rating;
                if (rating && starRating <= rating) {
                    star.classList.add('active');
                } else {
                    star.classList.remove('active');
                }
            });
        }

        function submitRating(articleId, rating) {
            fetch(`/articles/${articleId}/rate`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ rating: rating })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    userRating = rating;
                    updateStarsDisplay(rating);
                    updateAverageRating(data.average_rating);
                    showNotification('Note enregistrée avec succès', 'success');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Erreur lors de l\'enregistrement de la note', 'error');
            });
        }

        function updateAverageRating(average) {
            const averageElement = ratingSection.querySelector('.average-rating span');
            if (averageElement) {
                averageElement.textContent = average;
            }
        }
    }

    // Comment System
    if (commentForm) {
        const articleId = commentForm.dataset.articleId;
        
        commentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const messageInput = this.querySelector('textarea[name="message"]');
            const message = messageInput.value.trim();

            if (!message) return;

            fetch(`/articles/${articleId}/comment`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ message: message })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    addCommentToList(data.comment);
                    messageInput.value = '';
                    showNotification('Commentaire ajouté avec succès', 'success');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Erreur lors de l\'ajout du commentaire', 'error');
            });
        });
    }

    function addCommentToList(comment) {
        const commentElement = document.createElement('div');
        commentElement.className = 'comment';
        commentElement.innerHTML = `
            <div class="comment-header">
                <span class="comment-author">${comment.user_name}</span>
                <span class="comment-date">${comment.created_at}</span>
            </div>
            <div class="comment-content">
                ${comment.message}
            </div>
        `;

        commentsList.insertBefore(commentElement, commentsList.firstChild);
    }
});
