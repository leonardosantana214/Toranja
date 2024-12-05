//parallax

let scene = document.getElementById("scene");
let parallaxInstance = new Parallax(scene);

//swiper

let keys = [
    "Mercury",
    "Venus",
    "Earth",
    "Mars",
    "Jupiter",
    "Saturn",
    "Uranus",
    "Neptune"
];

let slider = new Swiper(".swiper-container", {
    slidesPerView: "auto",
    spaceBetween: 100,
    centeredSlides: true,
    mousewheel: true,
    pagination: {
        el: ".planet-links",
        clickable: true,
        renderBullet: function(index, className) {
            return '<div class="' + className + '">' + keys[index] + "</div>";
        }
    }
});