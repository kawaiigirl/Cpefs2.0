let slideIndex = 1;
showSlides(slideIndex);

function plusSlides(n) {
    showSlides(slideIndex += n);
}
function showSlides(n) {
    let i;
    let slides = document.getElementsByClassName("mySlides");
    if (n > slides.length) {
        slideIndex = 1
    }
    if (n < 1) {
        slideIndex = slides.length
    }
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    slides[slideIndex - 1].style.display = "block";
}
function openModal() {
    document.getElementById("myModal").classList.add("modal");
    document.getElementById("myModal").style.display = "block";
    document.getElementById("closeButton").style.display = "block";
    document.getElementById("images").classList.add("modal-content");
}
function closeModal() {
    document.getElementById("closeButton").style.display = "none";
    document.getElementById("myModal").classList.remove("modal")
    document.getElementById("images").classList.remove("modal-content");
}
