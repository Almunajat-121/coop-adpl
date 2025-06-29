// Hapus baris reviewData karena data ulasan sekarang dari database
// const reviewData = { /* ... */ };

function switchTab(tab) {
    document.querySelectorAll('.nav-tab').forEach(t => t.classList.remove('active'));
    event.target.classList.add('active'); // Event target adalah button yang diklik

    if (tab === 'proses') {
        document.getElementById('proses-content').style.display = 'block';
        document.getElementById('riwayat-content').style.display = 'none';
    } else {
        document.getElementById('proses-content').style.display = 'none';
        document.getElementById('riwayat-content').style.display = 'block';
        // Panggil fungsi untuk update tampilan ulasan jika tab riwayat aktif
        // Jika ada ulasan dinamis, ini akan dihandle oleh DOMContentLoaded loop
    }
}

function showDetail(itemName) {
    // Fungsi ini mungkin tidak lagi relevan jika detail barang langsung ke halaman detail
    // Jika tetap ingin modal, logic perlu disesuaikan untuk memuat data dinamis
    // Untuk saat ini, kita bisa arahkan ke halaman detail barang
    alert(`Membuka detail untuk ${itemName}.`);
    // window.location.href = `/barang/${itemId}`; // Jika ingin mengarahkan ke halaman detail
}

function openChat(whatsappLink) { // Menerima link WhatsApp langsung
    window.open(whatsappLink, '_blank');
}

function setRating(itemId, rating) {
    const ratingContainer = document.querySelector(`[data-item="${itemId}"]`);
    // Simpan rating di DOM element atau variabel sementara jika perlu
    ratingContainer.dataset.currentRating = rating;
    updateRatingDisplay(itemId, rating);
}

function updateRatingDisplay(itemId, currentRating) {
    const ratingContainer = document.querySelector(`[data-item="${itemId}"]`);
    const stars = ratingContainer.querySelectorAll('.interactive-star');

    stars.forEach((star, index) => {
        if (index < currentRating) {
            star.classList.add('active');
        } else {
            star.classList.remove('active');
        }
    });
}

function submitReview(itemId, transaksiId) { // Menerima ID transaksi
    const textarea = document.querySelector(`textarea[data-item="${itemId}"]`);
    const ratingContainer = document.querySelector(`[data-item="${itemId}"]`);
    const rating = parseInt(ratingContainer.dataset.currentRating || '0');
    const reviewText = textarea.value.trim();

    if (rating === 0) {
        alert('Silakan pilih rating terlebih dahulu!');
        return;
    }

    if (reviewText === '') {
        alert('Silakan tulis ulasan Anda!');
        return;
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");

    $.ajax({
        url: `/transaksi/${transaksiId}/ulasan`, // Rute API untuk menyimpan ulasan
        method: "POST",
        headers: { "X-CSRF-TOKEN": csrfToken },
        data: { rating: rating, isi: reviewText },
        success: function (res) {
            alert(res.message || "Ulasan berhasil dikirim.");
            // Update UI setelah berhasil
            textarea.readOnly = true;
            const submitBtn = document.querySelector(`button[onclick="submitReview('${itemId}', ${transaksiId})"]`);
            submitBtn.textContent = 'Ulasan Terkirim';
            submitBtn.disabled = true;
            // Nonaktifkan bintang
            ratingContainer.querySelectorAll('.interactive-star').forEach(star => {
                star.onclick = null; // Hapus event listener
                star.style.cursor = 'default';
            });
        },
        error: function (xhr) {
            console.error("Gagal mengirim ulasan:", xhr.responseText);
            let errorMessage = "Terjadi kesalahan saat mengirim ulasan.";
            if (xhr.status === 401) {
                errorMessage = "Anda harus login untuk memberikan ulasan.";
                window.location.href = '/login';
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            alert(errorMessage);
        },
    });
}

function closeModal() {
    document.querySelectorAll('.modal').forEach(modal => {
        modal.classList.remove('active');
    });
}

// Close modal when clicking outside (sudah ada di app.blade.php)
// document.querySelectorAll('.modal').forEach(modal => {
//     modal.addEventListener('click', function(e) {
//         if (e.target === this) {
//             closeModal();
//         }
//     });
// });

// Inisialisasi ratings saat halaman dimuat (untuk ulasan yang sudah ada)
document.addEventListener('DOMContentLoaded', function() {
    // Loop untuk menginisialisasi rating yang sudah ada di database
    document.querySelectorAll('.interactive-rating').forEach(ratingContainer => {
        const itemId = ratingContainer.dataset.item;
        const initialRating = parseInt(ratingContainer.dataset.initialRating || '0'); // Ambil rating awal dari data-attribute
        if (initialRating > 0) {
            updateRatingDisplay(itemId, initialRating);
            // Jika sudah ada ulasan (berarti initialRating > 0), nonaktifkan interaksi
            ratingContainer.querySelectorAll('.interactive-star').forEach(star => {
                star.onclick = null;
                star.style.cursor = 'default';
            });
            const submitBtn = document.querySelector(`button[onclick^="submitReview('${itemId}'"]`);
            if(submitBtn) {
                submitBtn.textContent = 'Ulasan Terkirim';
                submitBtn.disabled = true;
            }
            const textarea = document.querySelector(`textarea[data-item="${itemId}"]`);
            if(textarea) textarea.readOnly = true;
        }
    });
});