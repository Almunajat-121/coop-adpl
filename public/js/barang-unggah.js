let uploadedImages = [];
let currentSlideIndex = 0;
const AUTO_SLIDE_INTERVAL = 4000; // Interval auto-slide dalam milidetik (misal 4 detik)
let autoSlideTimer;

// Handle image upload preview
document
    .getElementById("upload-image")
    .addEventListener("change", function (e) {
        const files = Array.from(e.target.files);

        // Jika ini upload pertama kali, kosongkan array uploadedImages
        // Ini penting agar tidak menumpuk gambar dari sesi sebelumnya jika ada
        if (uploadedImages.length === 0) {
            // Hanya reset jika sebelumnya kosong
            uploadedImages = [];
            currentSlideIndex = 0;
        }

        files.forEach((file) => {
            if (file && file.type.startsWith("image/")) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    uploadedImages.push({
                        src: e.target.result,
                        file: file,
                    });
                    updateImageDisplay();
                };
                reader.readAsDataURL(file);
            }
        });

        // Reset input value so same files can be selected again
        e.target.value = "";
    });

function updateImageDisplay() {
    const uploadBox = document.getElementById("uploadBox");
    const imageSlider = document.getElementById("imageSlider");
    const sliderContainer = document.getElementById("sliderContainer");
    const sliderDots = document.getElementById("sliderDots");
    const imageCounter = document.getElementById("imageCounter");
    const addMoreBtn = document.getElementById("addMoreBtn");

    if (uploadedImages.length > 0) {
        uploadBox.style.display = "none";
        imageSlider.style.display = "block";
        addMoreBtn.style.display = "flex";

        sliderContainer.innerHTML = "";
        sliderDots.innerHTML = "";

        // Lebar container berdasarkan jumlah gambar (misal 2 gambar = 200% lebar slider)
        sliderContainer.style.width = `${uploadedImages.length * 100}%`;

        uploadedImages.forEach((image, index) => {
            const img = document.createElement("img");
            img.src = image.src;
            img.alt = `Product Image ${index + 1}`;
            // Penting: Setiap gambar mengambil 100% dari lebar view yang terlihat
            img.style.width = `${100 / uploadedImages.length}%`;
            img.style.height = `100%`;
            img.style.objectFit = `contain`;
            img.style.padding = `5px`;
            img.style.backgroundColor = `#fff`;
            img.style.flexShrink = `0`;
            img.style.boxSizing = `border-box`; // Pastikan box-sizing di sini
            sliderContainer.appendChild(img);

            const dot = document.createElement("div");
            dot.className = index === currentSlideIndex ? "dot active" : "dot";
            dot.onclick = () => currentSlide(index + 1);
            sliderDots.appendChild(dot);
        });

        imageCounter.textContent = `${
            uploadedImages.length > 0 ? currentSlideIndex + 1 : 0
        } / ${uploadedImages.length}`;

        if (currentSlideIndex >= uploadedImages.length) {
            currentSlideIndex = 0;
        }

        updateSlider();
        startAutoSlide(); // Mulai auto-slide setelah gambar diperbarui
    } else {
        uploadBox.style.display = "flex";
        imageSlider.style.display = "none";
        addMoreBtn.style.display = "none";
        stopAutoSlide(); // Hentikan auto-slide jika tidak ada gambar
    }
}

function changeSlide(direction) {
    currentSlideIndex += direction;

    if (currentSlideIndex >= uploadedImages.length) {
        currentSlideIndex = 0;
    } else if (currentSlideIndex < 0) {
        currentSlideIndex = uploadedImages.length - 1;
    }

    updateSlider();
    stopAutoSlide(); // Hentikan dan mulai lagi auto-slide setelah interaksi manual
    startAutoSlide();
}

function currentSlide(slideIndex) {
    currentSlideIndex = slideIndex - 1;
    updateSlider();
    stopAutoSlide(); // Hentikan dan mulai lagi auto-slide setelah interaksi manual
    startAutoSlide();
}

function updateSlider() {
    if (uploadedImages.length === 0) return;

    const sliderContainer = document.getElementById("sliderContainer");
    const dots = document.querySelectorAll(".dot");
    const imageCounter = document.getElementById("imageCounter");

    const translateX =
        -currentSlideIndex *
        (100 / uploadedImages.length) *
        uploadedImages.length; // Perbaikan perhitungan geser
    sliderContainer.style.transform = `translateX(-${
        currentSlideIndex * 100
    }%)`; // Geser 100% dari lebar kontainer slider untuk setiap slide

    dots.forEach((dot, index) => {
        dot.classList.toggle("active", index === currentSlideIndex);
    });

    imageCounter.textContent = `${
        uploadedImages.length > 0 ? currentSlideIndex + 1 : 0
    } / ${uploadedImages.length}`;
}

// Fungsi untuk mengontrol auto-slide
function startAutoSlide() {
    stopAutoSlide();
    if (uploadedImages.length > 1) {
        autoSlideTimer = setInterval(() => {
            changeSlide(1);
        }, AUTO_SLIDE_INTERVAL);
    }
}

function stopAutoSlide() {
    clearInterval(autoSlideTimer);
}

// Touch/swipe support for mobile
let startX = 0;
let endX = 0;

const slider = document.getElementById("imageSlider");
if (slider) {
    slider.addEventListener("touchstart", function (e) {
        startX = e.touches[0].clientX;
        stopAutoSlide();
    });

    slider.addEventListener("touchend", function (e) {
        endX = e.changedTouches[0].clientX;
        handleSwipe();
        startAutoSlide();
    });
}

function handleSwipe() {
    const swipeThreshold = 50;
    const swipeDistance = startX - endX;

    if (Math.abs(swipeDistance) > swipeThreshold) {
        if (swipeDistance > 0) {
            changeSlide(1);
        } else {
            changeSlide(-1);
        }
    }
}

// Handle form submission via AJAX
async function handleSubmit(event, kondisi = "store") {
    event.preventDefault();

    const form = event.target;
    const submitBtn = document.querySelector(".confirm-btn");
    const originalText = submitBtn.textContent;
    submitBtn.textContent = "Menyimpan...";
    submitBtn.disabled = true;

    const formData = new FormData(); // Buat FormData baru
    // Kita tidak akan menggunakan new FormData(form) langsung karena masalah hidden input file.
    // Kita akan tambahkan elemen form secara manual.

    // Tambahkan semua field dari form kecuali input type=file (kita tangani terpisah)
    Array.from(form.elements).forEach((element) => {
        if (
            element.name &&
            element.type !== "file" &&
            element.type !== "submit" &&
            !element.disabled
        ) {
            if (element.type === "radio" || element.type === "checkbox") {
                if (element.checked) {
                    formData.append(element.name, element.value);
                }
            } else if (element.name === "harga" && element.disabled) {
                // Jangan tambahkan harga jika disabled (misal donasi)
                // Tapi di controller, harga diatur null jika tipe donasi, jadi ini opsional
            } else {
                formData.append(element.name, element.value);
            }
        }
    });

    console.log(uploadedImages);

    // Client-side validation for 'foto' field if required (for 'store' operation)
    if (uploadedImages.length === 0 && kondisi === "store") {
        alert("Silakan unggah minimal satu foto barang!");
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
        return; // Hentikan pengiriman jika foto wajib tapi tidak ada
    }

    // Tambahkan file asli dari uploadedImages ke FormData secara eksplisit
    uploadedImages.forEach((imgObj) => {
        if (imgObj.file instanceof File) {
            formData.append("foto[]", imgObj.file); // Menggunakan nama 'foto[]' sesuai BarangController
        }
    });

    try {
        const response = await fetch(form.action, {
            method: "POST",
            body: formData,
            headers: {
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
                "X-Requested-With": "XMLHttpRequest", // Penting agar Laravel tahu ini permintaan AJAX
            },
        });

        const contentType = response.headers.get("content-type");
        if (!contentType || !contentType.includes("application/json")) {
            const textResponse = await response.text();
            console.error("Non-JSON Response:", textResponse);
            alert(
                "Terjadi kesalahan server. Respons bukan JSON. Mohon periksa konsol."
            );
            throw new Error(
                "Server did not return JSON. Status: " + response.status
            );
        }

        const result = await response.json();

        if (response.ok) {
            alert(result.message || "Operasi berhasil!");
            if (result.redirect) {
                window.location.href = result.redirect;
            } else {
                location.reload();
            }
        } else if (response.status === 422) {
            const firstError = Object.values(result.errors)[0][0];
            alert("Validasi gagal: " + firstError);
        } else if (response.status >= 400 && response.status < 500) {
            alert(result.message || `Terjadi kesalahan: ${response.status}`);
            if (result.redirect) {
                window.location.href = result.redirect;
            }
        } else {
            throw new Error(
                result.message || `Server error: ${response.status}`
            );
        }
    } catch (error) {
        console.error("AJAX Error:", error);
        alert("Terjadi kesalahan saat mengirim data: " + error.message);
    } finally {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    }
}

// Initial check for existing images (for edit page)
// Untuk halaman unggah baru, uploadedImages akan kosong.
// document.addEventListener('DOMContentLoaded', startAutoSlide);

// Mulai auto-slide saat DOM siap
document.addEventListener("DOMContentLoaded", startAutoSlide);

// Pastikan tombol tipe barang juga diinisialisasi
document.addEventListener("DOMContentLoaded", function () {
    const tipeSelect = document.getElementById("tipe");
    const hargaInput = document.getElementById("harga");

    function toggleHargaInput() {
        if (tipeSelect.value === "donasi") {
            hargaInput.value = "0"; // Set harga 0 jika donasi
            hargaInput.disabled = true;
        } else {
            hargaInput.disabled = false;
            if (hargaInput.value === "0") {
                // Clear if it was 0 from donation, ready for input
                hargaInput.value = "";
            }
        }
    }
    tipeSelect.addEventListener("change", toggleHargaInput);
    toggleHargaInput(); // Panggil saat DOMContentLoaded untuk inisialisasi awal
});
