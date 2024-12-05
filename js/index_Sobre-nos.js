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
        renderBullet: function (index, className) {
            return '<div class="' + className + '">' + keys[index] + "</div>";
        }
    }
});
document.addEventListener('DOMContentLoaded', function () {
    const container = document.querySelector('.container');
    const scene = document.getElementById('scene');

    // Inicializar parallax
    var parallaxInstance = new Parallax(scene);

    container.addEventListener('mousemove', function (e) {
        const boxes = document.querySelectorAll('.box');
        boxes.forEach(box => {
            const boxRect = box.getBoundingClientRect();
            const containerRect = container.getBoundingClientRect();
            const mouseX = e.clientX - containerRect.left;
            const mouseY = e.clientY - containerRect.top;
            const centerX = boxRect.left + boxRect.width / 2;
            const centerY = boxRect.top + boxRect.height / 2;
            const deltaX = mouseX - centerX;
            const deltaY = mouseY - centerY;
            const percentageX = deltaX / (containerRect.width / 2);
            const percentageY = deltaY / (containerRect.height / 2);

            const rotateX = -percentageY * 30;
            const rotateY = percentageX * 15;

            box.style.transform = `translate(${percentageX * 20}px, ${percentageY * 20}px) rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
        });
    });

    container.addEventListener('mouseleave', function () {
        const boxes = document.querySelectorAll('.box');
        boxes.forEach(box => {
            box.style.transform = 'none';
        });
    });
});
