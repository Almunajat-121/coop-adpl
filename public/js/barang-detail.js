let currentSlideIndex = 0;
const totalSlides = 4;

function changeSlide(direction) {
    currentSlideIndex += direction;

    if (currentSlideIndex >= totalSlides) {
        currentSlideIndex = 0;
    } else if (currentSlideIndex < 0) {
        currentSlideIndex = totalSlides - 1;
    }

    updateSlider();
}

function currentSlide(slideIndex) {
    currentSlideIndex = slideIndex - 1;
    updateSlider();
}

function updateSlider() {
    const sliderContainer = document.getElementById("sliderContainer");
    const dots = document.querySelectorAll(".dot");

    sliderContainer.style.transform = `translateX(-${currentSlideIndex * 25}%)`;

    dots.forEach((dot, index) => {
        dot.classList.toggle("active", index === currentSlideIndex);
    });
}

function goBack() {
    if (window.history.length > 1) {
        window.history.back();
    } else {
        window.location.href = "{{ url()->previous() }}"; // fallback jika tidak ada riwayat
    }
}

// Auto slide every 5 seconds
setInterval(() => {
    changeSlide(1);
}, 5000);

function sendMessage() {
    const input = document.getElementById("messageInput");
    const message = input.value.trim();

    if (message) {
        // Simulate WhatsApp redirect
        const whatsappUrl = `https://wa.me/6281234567890?text=${encodeURIComponent(
            message
        )}`;
        window.open(whatsappUrl, "_blank");
        input.value = "";
    }
}

function orderItem() {
    const message =
        "Halo! Saya tertarik dengan tas sekolah pink yang Anda tawarkan. Apakah masih tersedia?";
    const whatsappUrl = `https://wa.me/6281234567890?text=${encodeURIComponent(
        message
    )}`;
    window.open(whatsappUrl, "_blank");
}

// Allow Enter key to send message
document
    .getElementById("messageInput")
    .addEventListener("keypress", function (e) {
        if (e.key === "Enter") {
            sendMessage();
        }
    });

// Touch/swipe support for mobile
let startX = 0;
let endX = 0;

const slider = document.querySelector(".image-slider");

slider.addEventListener("touchstart", function (e) {
    startX = e.touches[0].clientX;
});

slider.addEventListener("touchend", function (e) {
    endX = e.changedTouches[0].clientX;
    handleSwipe();
});

function handleSwipe() {
    const swipeThreshold = 50;
    const swipeDistance = startX - endX;

    if (Math.abs(swipeDistance) > swipeThreshold) {
        if (swipeDistance > 0) {
            changeSlide(1); // Swipe left - next slide
        } else {
            changeSlide(-1); // Swipe right - previous slide
        }
    }
}
