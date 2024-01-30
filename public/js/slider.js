// JavaScript pour ajuster la largeur du carrousel
const carousel = document.getElementById('carousel');
const carouselTrack = document.getElementById('carousel-track');
//const slides = carousel.querySelectorAll('img');

carouselTrack.style.width = `${carousel.clientWidth * slides.length}px`;

// Fonction pour ajuster la largeur du carrousel lors du redimensionnement de la fenêtre
window.addEventListener('resize', () => {
    carouselTrack.style.width = `${carousel.clientWidth * slides.length}px`;
});