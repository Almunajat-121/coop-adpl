function loadProductsFromAPI(kategoriId = null, keyword = null) {
    const params = {};
    if (kategoriId && kategoriId !== "Semua") params.kategori = kategoriId;
    if (keyword) params.keyword = keyword;

    $.ajax({
        url: "/products",
        method: "GET",
        data: params,
        success: function (data) {
            const grid = document.getElementById("productsGrid");
            grid.innerHTML = "";
            if (data.length === 0) {
                grid.innerHTML = `<p style="text-align:center;">Produk tidak ditemukan.</p>`;
                return;
            }
            data.forEach((product) => {
                const gambarArray = JSON.parse(product.gambar || "[]");
                const gambarPertama =
                    gambarArray.length > 0
                        ? `/storage/${gambarArray[0]}`
                        : "/img/avatar.jpg";
                const card = document.createElement("div");
                card.className = "product-card";
                card.innerHTML = `
                    <div class="product-image" style="background-image: url('${gambarPertama}'); background-size: cover; position: relative;">
                        <div class="product-badge">Tersedia</div>
                        <button class="report-icon-btn" onclick="reportProduct(${product.id})" title="Laporkan Produk">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M2.5 1a.5.5 0 0 0-.5.5V15h1V10h2.293l1.5 1.5a.5.5 0 0 0 .707-.707L6.707 10H13a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.5-.5H5.707L4.207.5a.5.5 0 0 0-.707.707L5.293 3H2.5V1.5a.5.5 0 0 0-.5-.5z"/>
                            </svg>
                        </button>
                    </div>
                    <div class="product-info">
                        <div class="product-title">${product.nama}</div>
                        <div class="product-description">${product.deskripsi}</div>
                        <div class="product-footer">
                            <div class="product-location">📍 ${product.lokasi}</div>
                            <a href="/barang/${product.id}" class="contact-btn">Detail</a>
                        </div>
                    </div>
                `;
                grid.appendChild(card);
            });
        },
        error: function (xhr, status, error) {
            console.error("Gagal mengambil data produk:", xhr.responseText);
            const grid = document.getElementById("productsGrid");
            grid.innerHTML = `<p style="text-align:center; color: red;">Gagal memuat produk. Silakan coba lagi nanti.</p>`;
        },
    });
}

function reportProduct(productId) {
    const alasan = prompt("Tulis alasan laporan untuk produk ini:");
    if (alasan === null) return;
    if (alasan.trim() === "") {
        alert("Alasan laporan tidak boleh kosong.");
        return;
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");

    $.ajax({
        url: `/products/report/${productId}`,
        method: "POST",
        headers: { "X-CSRF-TOKEN": csrfToken },
        data: { alasan: alasan },
        success: function (res) {
            alert(res.message || "Laporan berhasil dikirim.");
        },
        error: function (xhr, status, error) {
            console.error("Gagal mengirim laporan:", xhr.responseText);
            let errorMessage = "Terjadi kesalahan saat mengirim laporan.";
            if (xhr.status === 401) {
                errorMessage = "Anda harus login sebagai pengguna untuk melapor.";
                window.location.href = '/login';
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            alert(errorMessage);
        },
    });
}

document.addEventListener("DOMContentLoaded", function () {
    loadProductsFromAPI();
    document.querySelectorAll(".filter-pill").forEach((pill) => {
        pill.addEventListener("click", function () {
            document.querySelectorAll(".filter-pill").forEach((p) => p.classList.remove("active"));
            pill.classList.add("active");
            const kategoriDipilih = pill.dataset.category;
            const currentKeyword = document.getElementById("searchInput").value.trim();
            loadProductsFromAPI(kategoriDipilih, currentKeyword);
        });
    });

    const searchBtn = document.getElementById("searchBtn");
    const searchInput = document.getElementById("searchInput");

    searchBtn.addEventListener("click", function () {
        const keyword = searchInput.value.trim();
        const aktifPill = document.querySelector(".filter-pill.active");
        const kategori = aktifPill ? aktifPill.dataset.category : null;
        loadProductsFromAPI(kategori, keyword);
    });

    searchInput.addEventListener("keypress", function (e) {
        if (e.key === "Enter") {
            e.preventDefault();
            searchBtn.click();
        }
    });
});