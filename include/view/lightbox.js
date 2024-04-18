// scripts.js
document.addEventListener('DOMContentLoaded', function() {
    const lightboxLinks = document.querySelectorAll('.lightbox');
    const lightboxImg = document.querySelector('.lightbox-content img');
    let currentImageIndex = 0;

    lightboxLinks.forEach(function(link, index) {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            currentImageIndex = index;
            const imageUrl = this.getAttribute('href');
            lightboxImg.setAttribute('src', imageUrl);
            document.querySelector('.lightbox-overlay').style.display = 'flex';
        });
    });

    document.querySelector('.lightbox-overlay').addEventListener('click', function(event) {
        if (event.target === this) {
            this.style.display = 'none';
        }
    });

    document.querySelector('.lightbox-prev').addEventListener('click', function() {
        currentImageIndex = (currentImageIndex - 1 + lightboxLinks.length) % lightboxLinks.length;
        const imageUrl = lightboxLinks[currentImageIndex].getAttribute('href');
        lightboxImg.setAttribute('src', imageUrl);
    });

    document.querySelector('.lightbox-next').addEventListener('click', function() {
        currentImageIndex = (currentImageIndex + 1) % lightboxLinks.length;
        const imageUrl = lightboxLinks[currentImageIndex].getAttribute('href');
        lightboxImg.setAttribute('src', imageUrl);
    });
});
