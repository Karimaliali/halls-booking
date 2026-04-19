// ===== OPTIMIZED SCRIPT - CONSOLIDATED VERSION =====
// Merged DOMContentLoaded handlers, removed duplicate code, improved performance

// Helper: Read query parameters
function getQueryParam(name) {
    return new URLSearchParams(window.location.search).get(name);
}

// API Helper Functions
async function checkAvailability(hallId, startDate, endDate) {
    let url = `/check-availability?hall_id=${hallId}`;
    if (startDate) url += `&start_date=${encodeURIComponent(startDate)}`;
    if (endDate) url += `&end_date=${encodeURIComponent(endDate)}`;
    return apiGet(url);
}

async function getMyBookings() {
    return apiGet(`/my-bookings`);
}

async function getOwnerBookings() {
    return apiGet(`/owner/bookings`);
}

async function blockOwnerDate(hallId, date) {
    return apiPost(`/owner/block-date`, { hall_id: hallId, date });
}

async function confirmBooking(paymentData) {
    return apiPost(`/bookings/${paymentData.booking_id}/confirm`, paymentData);
}

// ===== PAGE TRANSITION SYSTEM =====
let pageTransitionActive = false;
let loaderTimer = null;

const createTransitionOverlay = () => {
    if (document.getElementById("page-transition-overlay")) return;
    const overlay = document.createElement("div");
    overlay.id = "page-transition-overlay";
    overlay.innerHTML = `<div class="transition-loader"><div class="loader-spinner"></div><div class="loader-text">جاري التحميل...</div></div>`;
    document.body.appendChild(overlay);
};

const showPageLoader = () => {
    createTransitionOverlay();
    document.getElementById("page-transition-overlay")?.classList.add("show");
};

const hidePageLoader = () => {
    document
        .getElementById("page-transition-overlay")
        ?.classList.remove("show");
};

const isInternalPageLink = (link) => {
    if (!link || !link.href) return false;
    if (link.target && link.target !== "_self") return false;
    const href = link.getAttribute("href");
    if (!href || href.startsWith("#")) return false;
    try {
        const url = new URL(link.href, window.location.href);
        return url.origin === window.location.origin;
    } catch {
        return false;
    }
};

const initPageTransition = () => {
    const body = document.body;
    createTransitionOverlay();
    document.addEventListener("click", (e) => {
        const link = e.target.closest("a");
        if (!isInternalPageLink(link)) return;
        const href = link.getAttribute("href");
        if (!href) return;
        e.preventDefault();
        if (pageTransitionActive) return;
        pageTransitionActive = true;
        showPageLoader();
        body.classList.add("page-exit");
        setTimeout(() => {
            window.location.href = href;
        }, 400);
    });
};

const resetPageTransitionState = () => {
    pageTransitionActive = false;
    hidePageLoader();
    document.body.classList.remove("page-exit");
    document.body.style.opacity = "1";
};

window.addEventListener("pageshow", (event) => {
    if (event.persisted) {
        resetPageTransitionState();
    }
});

// Initialize on DOM load or immediate if already loaded
if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initPageTransition);
} else {
    initPageTransition();
}

// ===== CONSOLIDATED MAIN INITIALIZATION =====
document.addEventListener("DOMContentLoaded", () => {
    // Cache DOM elements
    const navbar = document.querySelector(".navbar");
    const hamburger = document.getElementById("hamburger");
    const navLinks = document.querySelector(".nav-links");
    const modal = document.getElementById("bookingModal");
    const closeModal = document.querySelector(".close-modal");
    const bookingForm = document.querySelector(
        "form#bookingForm:not([data-native-booking])",
    );
    const depositDisplay = document.getElementById("depositAmount");
    const addHallBtn = document.querySelector(".add-hall-btn");
    const addHallModal = document.getElementById("addHallModal");
    const addHallForm = document.getElementById("addHallForm");
    const navBarScrollHandler = () => {
        if (!navbar) return;
        if (window.scrollY > 50) {
            navbar.classList.add("scrolled");
            navbar.style.boxShadow = "0 10px 15px -3px rgba(0,0,0,0.1)";
            navbar.style.height = "80px";
        } else {
            navbar.classList.remove("scrolled");
            navbar.style.boxShadow = "none";
            navbar.style.height = "90px";
        }
    };

    // ===== NAVBAR SETUP (Single scroll listener) =====
    if (navbar) {
        window.addEventListener("scroll", navBarScrollHandler, {
            passive: true,
        });
    }

    // Scroll bottom when clicking the home about link
    document.addEventListener("click", (event) => {
        const link = event.target.closest("a[href='#how-to-book']");
        if (!link) return;
        if (location.pathname !== "/" && location.pathname !== "") return;
        event.preventDefault();
        window.scrollTo({
            top: Math.max(
                document.documentElement.scrollHeight,
                document.body.scrollHeight,
            ),
            behavior: "smooth",
        });
    });

    // Hamburger menu toggle
    if (hamburger && navLinks) {
        hamburger.addEventListener("click", () => {
            hamburger.classList.toggle("active");
            navLinks.classList.toggle("active");
        });
        navLinks.querySelectorAll("a").forEach((link) => {
            link.addEventListener("click", () => {
                hamburger.classList.remove("active");
                navLinks.classList.remove("active");
            });
        });
    }

    // ===== USER AUTHENTICATION STATE =====
    const updateUserUI = () => {
        const userLoggedIn = localStorage.getItem("userLoggedIn") === "true";
        const userType = localStorage.getItem("userType");
        const userName =
            JSON.parse(localStorage.getItem("user") || "{}")?.name || "مستخدم";
        const addHallNavItem = document.getElementById("add-hall-nav-item");

        if (userLoggedIn) {
            navLinks
                ?.querySelectorAll(".login-btn, .nav-signup-btn")
                .forEach((el) => (el.style.display = "none"));
            if (addHallNavItem)
                addHallNavItem.style.display =
                    userType === "owner" ? "" : "none";

            const oldProfile = navLinks?.querySelector(".profile-nav-li");
            if (oldProfile) oldProfile.remove();

            const profileLi = document.createElement("li");
            profileLi.className = "profile-nav-li";
            profileLi.innerHTML = `
                <span class="user-name"><i class="fa fa-user"></i> ${userName}</span>
                <a href="#" class="nav-auth-btn notification-btn"><i class="fa fa-bell"></i></a>
                <a href="#" class="nav-auth-btn logout-btn">تسجيل الخروج</a>
            `;
            navLinks?.appendChild(profileLi);
        } else {
            navLinks
                ?.querySelectorAll(".login-btn, .nav-signup-btn")
                .forEach((el) => (el.style.display = ""));
            if (addHallNavItem) addHallNavItem.style.display = "none";
            navLinks?.querySelector(".profile-nav-li")?.remove();
        }
    };
    updateUserUI();

    // Logout handler
    document.addEventListener("click", (e) => {
        if (e.target?.classList.contains("logout-btn")) {
            localStorage.clear();
            window.location.reload();
        }
    });

    // ===== RENDER HALLS FUNCTION =====
    function renderHalls(response) {
        const grid = document.querySelector(".results-grid");
        if (!grid) return;
        let halls = Array.isArray(response) ? response : response?.data || [];
        const total = response?.total || halls.length;
        const displayCount = halls.length;
        const resultsCount = document.querySelector(".results-count");
        if (resultsCount) {
            const h3 = resultsCount.querySelector("h3");
            const p = resultsCount.querySelector("p");
            if (!displayCount) {
                if (h3) h3.textContent = "لا توجد نتائج";
                if (p) p.textContent = "لم يتم العثور على قاعات مطابقة لبحثك";
            } else if (total > displayCount) {
                if (h3)
                    h3.textContent = `${displayCount} من ${total} قاعة متاحة`;
                if (p)
                    p.textContent = `تم العثور على ${total} قاعة تطابق معايير بحثك`;
            } else {
                if (h3) h3.textContent = `${displayCount} قاعة متاحة`;
                if (p)
                    p.textContent = `تم العثور على ${displayCount} قاعة تطابق معايير بحثك`;
            }
        }
        if (!halls || halls.length === 0) {
            grid.innerHTML =
                '<div style="text-align: center; padding: 40px; color: #666;">لم يتم العثور على قاعات</div>';
            return;
        }
        grid.innerHTML = "";
        halls.forEach((hall) => {
            const imageUrl = hall.main_image_url || hall.main_image || "";
            const rating = hall.reviews_avg_rating || 4.6;
            const reviewsCount = hall.reviews_count || 0;
            const status = (hall.status || "متاح").trim();
            const isAvailable = status === "متاح" || status === "active";
            const badgeClass = isAvailable ? "available" : "unavailable";
            const badgeText = isAvailable ? "متاحة" : "غير متاحة";
            const category = hall.category || "قاعة فاخرة";
            const description = (
                hall.description || "قاعة مميزة مجهزة بكل الخدمات المطلوبة."
            ).substring(0, 100);
            const features = hall.features
                ? Array.isArray(hall.features)
                    ? hall.features.slice(0, 3)
                    : []
                : [];

            const card = document.createElement("div");
            card.className = "result-card";
            card.dataset.id = hall.id;
            card.onclick = () => (window.location.href = `/halls/${hall.id}`);
            card.innerHTML = `
                <div class="result-img">
                    <img src="${imageUrl}" alt="${hall.name}">
                    <span class="result-badge ${badgeClass}">
                        ${badgeText}
                    </span>
                    <button class="fav-btn" type="button" onclick="event.stopPropagation();">
                        <i class="fa fa-heart"></i>
                    </button>
                </div>
                <div class="result-content">
                    <div class="result-header">
                        <div>
                            <h4>${hall.name}</h4>
                            <span class="result-tag">${category}</span>
                        </div>
                        <div class="rating">
                            <i class="fa fa-star"></i>
                            <span>${rating.toFixed(1)}</span>
                            <span class="reviews-count">(${reviewsCount})</span>
                        </div>
                    </div>
                    <p class="result-excerpt">${description}</p>
                    <p class="result-location">
                        <i class="fa fa-map-pin"></i> ${hall.location || "لم يتم تحديد الموقع"}
                    </p>
                    <div class="result-features">
                        <span><i class="fa fa-users"></i> ${hall.capacity || "غير محدد"} ضيف</span>
                        ${features.map((f) => `<span><i class="fa fa-check"></i> ${f}</span>`).join("")}
                    </div>
                    <div class="result-footer">
                        <div class="result-price">
                            <span class="price">${(hall.price || 0).toLocaleString()} ج.م</span>
                            <span class="price-note">/ ليلة</span>
                        </div>
                        <a href="/halls/${hall.id}" class="btn-view" onclick="event.stopPropagation()">عرض التفاصيل</a>
                    </div>
                </div>
            `;
            grid.appendChild(card);
        });
    }

    // ===== SCROLL REVEAL ANIMATION =====
    const revealItems = document.querySelectorAll(
        ".card, .section-header, .stat-box, .step-item, .hero-content",
    );
    const revealOnScroll = () => {
        const triggerBottom = (window.innerHeight / 5) * 4;
        revealItems.forEach((item) => {
            const itemTop = item.getBoundingClientRect().top;
            if (itemTop < triggerBottom) {
                item.style.opacity = "1";
                item.style.transform = "translateY(0)";
            }
        });
    };
    revealItems.forEach((item) => {
        item.style.opacity = "0";
        item.style.transform = "translateY(50px)";
        item.style.transition = "all 0.6s ease-out";
    });
    window.addEventListener("scroll", revealOnScroll, { passive: true });
    revealOnScroll();

    // ===== BOOKING MODAL =====
    if (modal && closeModal) {
        const closeBookingModal = () => {
            modal.classList.remove("show");
            document.documentElement.classList.remove("modal-open");
            document.body.classList.remove("modal-open");
        };

        closeModal.onclick = closeBookingModal;
        window.onclick = (event) => {
            if (event.target == modal) closeBookingModal();
        };
    }

    const setupImagePreview = (inputId) => {
        const input = document.getElementById(inputId);
        if (!input) return;
        const container = input.closest(".file-upload");
        if (!container) return;

        input.addEventListener("change", () => {
            const file = input.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = (e) => {
                // Find or create preview container
                let previewContainer = container.querySelector(".file-preview");
                if (!previewContainer) {
                    previewContainer = document.createElement("div");
                    previewContainer.className = "file-preview";
                    container.appendChild(previewContainer);
                }

                // Clear previous preview
                previewContainer.innerHTML = "";

                // Create and add image
                const img = document.createElement("img");
                img.className = "preview";
                img.src = e.target.result;
                img.style.maxWidth = "100%";
                img.style.maxHeight = "120px";
                img.style.display = "block";
                img.style.marginTop = "8px";
                img.style.borderRadius = "8px";
                img.style.objectFit = "contain";
                previewContainer.appendChild(img);

                // Hide upload area when image is added
                const uploadArea = container.querySelector(".upload-area");
                if (uploadArea) {
                    uploadArea.style.display = "none";
                }

                // Update container for better display
                container.style.minHeight = "auto";
            };
            reader.readAsDataURL(file);
        });
    };
    setupImagePreview("idCardImage");
    setupImagePreview("receiptImage");

    // Booking button handlers
    document.querySelectorAll(".btn-full").forEach((button) => {
        button.addEventListener("click", (e) => {
            const cardBody = e.target.closest(".card-body");
            const hallCard = e.target.closest(".card");
            if (!cardBody || !modal) return;
            const hallName = cardBody.querySelector("h3")?.innerText;
            const priceText =
                cardBody.querySelector(".price-info span")?.innerText;
            const price = parseInt(priceText?.replace(/[^0-9]/g, "") || 0);
            if (depositDisplay)
                depositDisplay.innerText = (price * 0.1).toLocaleString();
            if (bookingForm) {
                bookingForm.setAttribute("data-current-hall", hallName);
                if (hallCard)
                    bookingForm.setAttribute(
                        "data-current-id",
                        hallCard.dataset.id,
                    );
            }
            modal.classList.add("show");
            document.documentElement.classList.add("modal-open");
            document.body.classList.add("modal-open");
        });
    });

    // Booking form submission
    if (bookingForm) {
        bookingForm.addEventListener("submit", async (e) => {
            e.preventDefault();
            const submitBtn = bookingForm.querySelector(".btn-confirm-final");
            if (!submitBtn) return;
            submitBtn.innerHTML =
                '<i class="fa fa-spinner fa-spin"></i> جاري المعالجة...';
            submitBtn.style.pointerEvents = "none";
            try {
                const formData = new FormData(bookingForm);
                await apiPost("/bookings", formData);
                alert(`تم تقديم طلب الحجز بنجاح`);
                modal.classList.remove("show");
                document.documentElement.classList.remove("modal-open");
                document.body.classList.remove("modal-open");
                bookingForm.reset();
            } catch (err) {
                alert(err.message || "حدث خطأ");
            } finally {
                submitBtn.innerHTML = "تأكيد طلب الحجز";
                submitBtn.style.pointerEvents = "auto";
            }
        });
    }

    // ===== ADD HALL MODAL =====
    if (addHallBtn && addHallModal) {
        addHallBtn.addEventListener("click", (e) => {
            e.preventDefault();
            addHallModal.style.display = "block";
        });
        addHallModal
            .querySelector(".close-modal")
            ?.addEventListener("click", () => {
                addHallModal.style.display = "none";
            });
        window.addEventListener("click", (e) => {
            if (e.target === addHallModal) addHallModal.style.display = "none";
        });
    }

    if (addHallForm) {
        addHallForm.addEventListener("submit", async (e) => {
            e.preventDefault();
            const formData = new FormData(addHallForm);
            try {
                await axios.post("/api/halls", formData, {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem("token")}`,
                    },
                });
                alert("تمت إضافة القاعة بنجاح!");
                addHallModal.style.display = "none";
                addHallForm.reset();
                window.location.reload();
            } catch (err) {
                alert("خطأ: " + (err.response?.data?.message || err.message));
            }
        });
    }

    // ===== HALL DETAILS PAGE =====
    const hallIdParam = getQueryParam("id");
    if (hallIdParam) {
        apiGet(`/halls/${hallIdParam}`)
            .then((resp) => {
                const hallData = resp?.data || resp;
                if (!hallData?.id) return;
                const hallHeader = document.querySelector(".hall-header h1");
                const hallLocation = document.querySelector(
                    ".hall-header .location",
                );
                const hallPrice = document.querySelector(".price-tag .price");
                if (hallHeader)
                    hallHeader.innerText = hallData.name || "غير معروف";
                if (hallLocation)
                    hallLocation.innerHTML = `<i class="fa fa-map-pin"></i> ${hallData.location || "N/A"}`;
                if (hallPrice)
                    hallPrice.innerText =
                        (hallData.price || 0).toLocaleString() + " ج.م";
            })
            .catch((err) => console.error("خطأ في تحميل تفاصيل القاعة:", err));
    }

    // ===== SEARCH PAGE FUNCTIONALITY =====
    const filterTags = document.querySelectorAll(".filter-tag");
    filterTags.forEach((tag) => {
        tag.addEventListener("click", function () {
            filterTags.forEach((t) => t.classList.remove("active"));
            this.classList.add("active");
        });
    });

    const toggleMapBtn = document.querySelector(".btn-toggle-map");
    const mapContainer = document.querySelector(".map-container");
    if (toggleMapBtn && mapContainer) {
        toggleMapBtn.addEventListener("click", () => {
            mapContainer.classList.toggle("hidden");
            toggleMapBtn.innerHTML = mapContainer.classList.contains("hidden")
                ? '<i class="fa fa-map"></i> عرض الخريطة'
                : '<i class="fa fa-times"></i> إخفاء الخريطة';
        });
    }

    // Favorite buttons
    document.querySelectorAll(".fav-btn").forEach((btn) => {
        btn.addEventListener("click", function (e) {
            e.preventDefault();
            const icon = this.querySelector("i");
            icon.classList.toggle("fa-regular");
            icon.classList.toggle("fa-solid");
            this.classList.toggle("active");
        });
    });

    // Advanced search
    async function performAdvancedSearch(event) {
        if (event) event.preventDefault();

        const city =
            document.querySelector("#searchLocationInput")?.value.trim() || "";
        const date = document.querySelector('input[name="date"]')?.value || "";
        const guests =
            document.querySelector('select[name="guests"]')?.value || "";
        const btn = document.querySelector(".btn-search-primary");

        if (btn) {
            btn.disabled = true;
            btn.innerHTML =
                '<i class="fa fa-spinner fa-spin"></i> جاري البحث...';
        }

        try {
            let query = "/halls/search?";
            if (city) query += `location=${encodeURIComponent(city)}&`;
            if (date) query += `date=${encodeURIComponent(date)}&`;
            if (guests) query += `guests=${encodeURIComponent(guests)}&`;
            const resp = await apiGet(query);
            renderHalls(resp.data);
        } catch (err) {
            alert("خطأ: " + (err.message || "فشل البحث"));
        } finally {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa fa-search"></i> بحث';
            }
        }
    }

    const searchForm = document.querySelector("#search-form");
    if (searchForm) {
        searchForm.addEventListener("submit", performAdvancedSearch);
    }
    document
        .querySelector(".btn-search-primary")
        ?.addEventListener("click", performAdvancedSearch);

    // Auto-search if location param exists
    const initialCity = getQueryParam("location");
    if (initialCity) {
        const input = document.querySelector("#searchLocationInput");
        if (input) input.value = initialCity;
        performAdvancedSearch();
    }

    // Home page search button
    const searchBtn = document.querySelector(".btn-search");
    if (searchBtn) {
        searchBtn.addEventListener("click", () => {
            const locationInput = document.querySelector("#homeLocationInput");
            if (!locationInput?.value.trim()) {
                alert("يرجى تحديد المدينة");
                return;
            }
            window.location.href = `/search?location=${encodeURIComponent(locationInput.value.trim())}`;
        });
    }
});

// Page fade-in effect
document.body.style.opacity = "0";
setTimeout(() => {
    document.body.style.opacity = "1";
}, 50);
