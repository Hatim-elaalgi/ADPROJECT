const slides = document.querySelectorAll('.slide');
let currentSlide = 0;

function showSlide(index) {
    slides.forEach((slide, i) => {
        slide.classList.remove('active');
        slide.style.display = "none"; // Masque toutes les slides
        if (i === index) {
            slide.classList.add('active');
            slide.style.display = "block"; // Affiche uniquement la slide active
        }
    });
}

function nextSlide() {
    currentSlide = (currentSlide + 1) % slides.length;
    showSlide(currentSlide);
}

function prevSlide() {
    currentSlide = (currentSlide - 1 + slides.length) % slides.length;
    showSlide(currentSlide);
}

// Initialize
showSlide(currentSlide);


// Fonction pour aller directement à un slide spécifique
function goToSlide(slideId) {
    const targetSlide = document.querySelector(`.slide#${slideId}`);
    const slidesArray = Array.from(slides);

    if (targetSlide) {
        const slideIndex = slidesArray.indexOf(targetSlide);
        if (slideIndex !== -1) {
            currentSlide = slideIndex; // Met à jour l'index du slide actuel
            showSlide(currentSlide); // Affiche le slide ciblé

            // Défile jusqu'à la section des thèmes
            const themesSection = document.querySelector('.themes-section');
            themesSection.scrollIntoView({ behavior: 'smooth' });
        }
    }
}

// Ajout d'écouteurs d'événements pour les liens de la navbar
document.querySelectorAll('.navbar ul li a').forEach(link => {
    link.addEventListener('click', (event) => {
        event.preventDefault(); // Empêche le comportement par défaut du lien

        const targetId = link.textContent.toLowerCase().replace(/\s/g, '-'); // Génère l'ID cible
        goToSlide(targetId); // Appelle la fonction pour aller au slide
    });
});
