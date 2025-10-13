/**
 * MONITA Global Loading Overlay
 * Aktif untuk semua aksi: fetch, form submit, link click, dan grafik dinamis
 */

window.monitaLoader = {
    overlay: null,
    init() {
        if (!this.overlay) {
            this.overlay = document.createElement("div");
            this.overlay.id = "monita-loading-overlay";
            this.overlay.innerHTML = `
                <div class="loader-wrapper">
                    <div class="monita-spinner"></div>
                    <p class="mt-3 fw-semibold text-dark">Memuat data, harap tunggu...</p>
                </div>
            `;
            Object.assign(this.overlay.style, {
                position: "fixed",
                top: 0,
                left: 0,
                width: "100%",
                height: "100%",
                background: "rgba(255,255,255,0.8)",
                backdropFilter: "blur(3px)",
                zIndex: 9999,
                display: "none",
                alignItems: "center",
                justifyContent: "center",
            });
            document.body.appendChild(this.overlay);

            const style = document.createElement("style");
            style.textContent = `
                .loader-wrapper { text-align: center; }
                .monita-spinner {
                    width: 56px;
                    height: 56px;
                    border: 5px solid #e0e0e0;
                    border-top-color: #a31d1d;
                    border-radius: 50%;
                    animation: spin 0.9s ease-in-out infinite;
                    margin: 0 auto;
                }
                @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
                #monita-loading-overlay.show {
                    display: flex !important;
                    animation: fadeIn 0.3s ease-in-out;
                }
                @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
            `;
            document.head.appendChild(style);
        }
    },
    show() {
        this.overlay?.classList.add("show");
    },
    hide() {
        this.overlay?.classList.remove("show");
    },
};

// ========== Aktivasi Loader Universal ==========
document.addEventListener("DOMContentLoaded", () => {
    monitaLoader.init();

    // 🌀 FETCH hijack: tampilkan loader setiap fetch() dipanggil
    const originalFetch = window.fetch;
    window.fetch = async (...args) => {
        monitaLoader.show();
        try {
            const response = await originalFetch(...args);
            return response;
        } finally {
            monitaLoader.hide();
        }
    };

    // 🌀 Form submission
    document.addEventListener(
        "submit",
        (e) => {
            if (e.target.tagName === "FORM") {
                monitaLoader.show();
                setTimeout(() => monitaLoader.hide(), 3000);
            }
        },
        true
    );

    // 🌀 Sidebar / Link navigation
    document.querySelectorAll("a[href]").forEach((link) => {
        const href = link.getAttribute("href");
        if (href && !href.startsWith("#") && !href.startsWith("javascript:")) {
            link.addEventListener("click", (e) => {
                if (link.target !== "_blank") {
                    monitaLoader.show();
                }
            });
        }
    });

    // 🌀 Grafik dinamis (gunakan event global)
    document.addEventListener("monita:loading:start", () =>
        monitaLoader.show()
    );
    document.addEventListener("monita:loading:end", () => monitaLoader.hide());

    // 🌀 Auto-hide jika halaman selesai
    window.addEventListener("load", () => monitaLoader.hide());
});
