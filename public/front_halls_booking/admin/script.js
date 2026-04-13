// Dashboard JavaScript
document.addEventListener("DOMContentLoaded", () => {
    // Menu items
    const menuItems = document.querySelectorAll(".menu-item:not(.logout)");
    menuItems.forEach((item) => {
        item.addEventListener("click", function (e) {
            e.preventDefault();
            menuItems.forEach((i) => i.classList.remove("active"));
            this.classList.add("active");
        });
    });

    // Remove from favorites
    const removeButtons = document.querySelectorAll(".remove-fav");
    removeButtons.forEach((btn) => {
        btn.addEventListener("click", function (e) {
            e.preventDefault();
            const favoriteCard = this.closest(".favorite-card");
            favoriteCard.style.opacity = "0";
            setTimeout(() => {
                favoriteCard.remove();
            }, 300);
        });
    });

    // Notification bell
    const notificationBtn = document.querySelector(".notification-badge");
    if (notificationBtn) {
        notificationBtn.addEventListener("click", () => {
            const badge = notificationBtn.querySelector(".badge");
            if (badge) {
                badge.style.display = "none";
            }
        });
    }

    // Booking details buttons
    const detailsButtons = document.querySelectorAll(".btn-details");
    detailsButtons.forEach((btn) => {
        btn.addEventListener("click", function (e) {
            e.preventDefault();
            const bookingItem = this.closest(".booking-item");
            const hallName =
                bookingItem.querySelector(".booking-info h4").textContent;
            alert(`عرض تفاصيل حجز ${hallName}`);
        });
    });
});

// Auth Pages JavaScript
document.addEventListener("DOMContentLoaded", () => {
    // debug log
    console.log(
        "Auth page loaded",
        window.location.pathname,
        window.location.href,
    );
    // ensure signup link works
    const signupLink = document.getElementById("switchToSignup");
    if (signupLink) {
        signupLink.addEventListener("click", (e) => {
            e.preventDefault();
            console.log(
                "signup link clicked (auth page) - navigating explicitly",
            );
            window.location.href = "signup.html";
        });
    }

    // nothing dynamic on auth pages anymore; login and signup live separately
    // hooks for future auth-page behavior could go here if needed

    // load halls list on admin dashboard and attach edit/delete
    const hallsListContainer = document.querySelector(".halls-list");
    if (hallsListContainer) {
        apiGet("/halls")
            .then((response) => {
                // Handle both array and {data: array} response formats
                const halls = Array.isArray(response)
                    ? response
                    : response?.data || [];

                hallsListContainer.innerHTML = "";
                halls.forEach((hall) => {
                    const item = document.createElement("div");
                    item.className = "hall-item";
                    item.dataset.id = hall.id;
                    item.innerHTML = `
            <div class="hall-info">
              <img src="${hall.main_image || ""}" alt="قاعة" onerror="this.src='https://via.placeholder.com/100x100'" />
              <div>
                <h4>${hall.name || "غير معروف"}</h4>
                <p>${hall.location || "موقع غير محدد"} · ${hall.capacity || "غير محدد"} ضيف</p>
              </div>
            </div>
            <div class="hall-status">
              <span class="status ${hall.status === "active" ? "active" : "inactive"}">${hall.status === "active" ? "نشط" : "غير نشط"}</span>
            </div>
            <div class="hall-actions">
              <button class="icon-btn edit" title="تعديل">
                <i class="fa-regular fa-pen-to-square"></i>
              </button>
              <button class="icon-btn delete" title="حذف">
                <i class="fa-regular fa-trash-can"></i>
              </button>
            </div>
          `;
                    hallsListContainer.appendChild(item);
                });

                // attach listeners after rendering
                hallsListContainer
                    .querySelectorAll(".hall-item")
                    .forEach((item) => {
                        const id = item.dataset.id;
                        item.querySelector(".icon-btn.edit").addEventListener(
                            "click",
                            () => {
                                window.location.href = `edit-hall.html?id=${id}`;
                            },
                        );
                        item.querySelector(".icon-btn.delete").addEventListener(
                            "click",
                            async () => {
                                if (
                                    confirm("هل أنت متأكد من حذف هذه القاعة؟")
                                ) {
                                    try {
                                        await apiPost(`/halls/${id}`, {
                                            _method: "DELETE",
                                        });
                                        item.remove();
                                        alert("تم حذف القاعة بنجاح");
                                    } catch (err) {
                                        console.error(err);
                                        alert(
                                            err.message || "فشل في حذف القاعة",
                                        );
                                    }
                                }
                            },
                        );
                    });
            })
            .catch((err) => {
                console.error("Failed to load halls:", err);
                hallsListContainer.innerHTML = `<p style="color: red;">فشل في تحميل القاعات: ${err.message}</p>`;
            });
    }

    // Login form (talk to backend)
    const loginForm = document.getElementById("loginForm");
    if (loginForm) {
        loginForm.addEventListener("submit", async (e) => {
            e.preventDefault();
            const email = loginForm.querySelector('input[type="email"]').value;
            const password = loginForm.querySelector(
                'input[type="password"]',
            ).value;
            const role = loginForm.querySelector('select[name="role"]').value;

            try {
                const resp = await apiPost("/login", { email, password, role });
                if (resp.token) {
                    localStorage.setItem("token", resp.token);
                    localStorage.setItem("user", JSON.stringify(resp.user));
                    localStorage.setItem("userLoggedIn", "true");
                    if (resp.user && resp.user.role) {
                        localStorage.setItem("userType", resp.user.role);
                    } else {
                        localStorage.setItem("userType", role);
                    }
                }
                // التوجيه حسب نوع المستخدم
                if (resp.user && resp.user.role === "admin") {
                    window.location.href =
                        "/front_halls_booking/admin/index.html";
                } else {
                    window.location.href = "/front_halls_booking/index.html";
                }
            } catch (err) {
                console.error(err);
                alert(err.message || "خطأ في تسجيل الدخول");
            }
        });
        // حماية لوحة تحكم الأدمن: لا تظهر إلا إذا كان المستخدم أدمن
        if (window.location.pathname.includes("/admin/index.html")) {
            const userType = localStorage.getItem("userType");
            if (userType !== "admin") {
                alert("غير مصرح لك بدخول لوحة تحكم الأدمن!");
                window.location.href = "/front_halls_booking/index.html";
            }
        }
    }

    // Signup form (use backend register route)
    const signupForm = document.getElementById("signupForm");
    if (signupForm) {
        signupForm.addEventListener("submit", async (e) => {
            e.preventDefault();
            const name = signupForm.querySelector('input[type="text"]').value;
            const role = signupForm.querySelector('select[name="role"]').value;
            const email = signupForm.querySelector('input[type="email"]').value;
            const passwords = signupForm.querySelectorAll(
                'input[type="password"]',
            );
            const password = passwords[0].value;
            const confirmPassword = passwords[1].value;

            if (!name || !role || !email || !password || !confirmPassword) {
                alert("الرجاء ملء جميع الحقول");
                return;
            }
            if (password !== confirmPassword) {
                alert("كلمة المرور غير متطابقة");
                return;
            }
            if (password.length < 6) {
                alert("كلمة المرور يجب أن تكون 6 أحرف على الأقل");
                return;
            }

            try {
                const resp = await apiPost("/register", {
                    name,
                    role,
                    email,
                    password,
                    password_confirmation: password,
                });
                if (resp.token) {
                    localStorage.setItem("token", resp.token);
                    localStorage.setItem("user", JSON.stringify(resp.user));
                }
                window.location.href = "../index.html";
            } catch (err) {
                console.error(err);
                alert(err.message || "خطأ في التسجيل");
            }
        });
    }

    // Social buttons
    const socialBtns = document.querySelectorAll(".social-btn");
    socialBtns.forEach((btn) => {
        btn.addEventListener("click", () => {
            alert("سيتم تفعيل التسجيل عبر وسائل التواصل الاجتماعية قريباً");
        });
    });
});

// Add Hall Form JavaScript
// this section also handles edit since many pieces overlap
function collectHallFormData(form) {
    const data = new FormData(form);
    // gather checkboxes features
    const features = [];
    form.querySelectorAll(
        '.features-checkbox-grid input[type="checkbox"]',
    ).forEach((cb) => {
        if (cb.checked) features.push(cb.nextSibling.textContent.trim());
    });
    data.append("features", JSON.stringify(features));
    return data;
}

document.addEventListener("DOMContentLoaded", () => {
    const form =
        document.getElementById("addHallForm") ||
        document.getElementById("editHallForm");

    // if we're editing, fetch existing hall data and populate fields
    if (form && form.id === "editHallForm") {
        const hallId = getQueryParam("id");
        if (hallId) {
            apiGet(`/halls/${hallId}`)
                .then((response) => {
                    // Handle both direct hall object and wrapped response
                    const hall = response?.data || response;
                    if (!hall || !hall.id) {
                        console.warn("Invalid hall data:", response);
                        alert("فشل في تحميل بيانات القاعة");
                        return;
                    }

                    // text fields
                    form.querySelector('input[name="name"]').value =
                        hall.name || "";
                    form.querySelector('select[name="province"]').value =
                        hall.province || "";
                    form.querySelector('input[name="address"]').value =
                        hall.address || "";
                    form.querySelector('input[name="map_url"]').value =
                        hall.map_url || "";
                    form.querySelector('textarea[name="description"]').value =
                        hall.description || "";

                    // capacity / pricing
                    form.querySelector('input[name="capacity"]').value =
                        hall.capacity || "";
                    form.querySelector('input[name="min_guests"]').value =
                        hall.min_guests || "";
                    form.querySelector('input[name="weekday_price"]').value =
                        hall.weekday_price || "";
                    form.querySelector('input[name="weekend_price"]').value =
                        hall.weekend_price || "";
                    form.querySelector('input[name="special_price"]').value =
                        hall.special_price || "";
                    form.querySelector('select[name="duration"]').value =
                        hall.duration || "";
                    form.querySelector('select[name="extend_option"]').value =
                        hall.extend_option || "";

                    // contact
                    form.querySelector('input[name="phone"]').value =
                        hall.phone || "";
                    form.querySelector('input[name="contact_email"]').value =
                        hall.contact_email || "";

                    // features checkboxes
                    const features = hall.features || [];
                    form.querySelectorAll(
                        '.features-checkbox-grid input[type="checkbox"]',
                    ).forEach((cb) => {
                        const label = cb.nextSibling.textContent.trim();
                        cb.checked = features.includes(label);
                    });

                    // status radio
                    if (hall.status) {
                        const radio = form.querySelector(
                            `input[name="status"][value="${hall.status}"]`,
                        );
                        if (radio) radio.checked = true;
                    }
                })
                .catch((err) => {
                    console.error("Failed to load hall:", err);
                    alert(
                        "خطأ في تحميل بيانات القاعة: " +
                            (err.message || "حدث خطأ ما"),
                    );
                });
        }
    }

    // Image upload preview (optional enhancement)
    const mainImageInput = document.getElementById("mainImage");
    const galleryInput = document.getElementById("galleryImages");

    if (mainImageInput) {
        mainImageInput.addEventListener("change", function (e) {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const uploadBox = document.querySelector(
                        ".upload-box.main-image",
                    );
                    uploadBox.style.backgroundImage = `url(${e.target.result})`;
                    uploadBox.style.backgroundSize = "cover";
                    uploadBox.style.backgroundPosition = "center";
                    uploadBox.innerHTML = "";
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    }

    if (galleryInput) {
        galleryInput.addEventListener("change", function (e) {
            const count = this.files.length;
            const uploadBox = document.querySelector(
                ".upload-box:not(.main-image)",
            );
            if (count > 0) {
                uploadBox.innerHTML = `
                    <i class="fa-regular fa-check-circle" style="color: var(--success)"></i>
                    <span>تم اختيار ${count} صور</span>
                `;
            }
        });
    }

    // Form submission
    if (form) {
        form.addEventListener("submit", async (e) => {
            e.preventDefault();
            const submitBtn = form.querySelector(".btn-save");
            submitBtn.innerHTML =
                '<i class="fa fa-spinner fa-spin"></i> جاري المعالجة...';
            submitBtn.disabled = true;
            try {
                const data = collectHallFormData(form);
                if (form.id === "addHallForm") {
                    await apiPost("/halls", data);
                    alert("تم إضافة القاعة بنجاح");
                } else {
                    const hallId = getQueryParam("id");
                    if (hallId) {
                        await apiPut(`/halls/${hallId}`, data);
                        alert("تم تحديث بيانات القاعة بنجاح");
                    }
                }
                window.location.href = "index.html";
            } catch (err) {
                console.error(err);
                alert(err.message || "حدث خطأ");
            } finally {
                submitBtn.innerHTML =
                    form.id === "addHallForm"
                        ? '<i class="fa-regular fa-plus"></i> إضافة القاعة'
                        : '<i class="fa-regular fa-pen-to-square"></i> حفظ التعديلات';
                submitBtn.disabled = false;
            }
        });
    }

    // Cancel button
    const cancelBtn = document.querySelector(".btn-cancel");
    if (cancelBtn) {
        cancelBtn.addEventListener("click", () => {
            if (confirm("هل أنت متأكد من إلغاء الإضافة؟ سيتم فقدان البيانات")) {
                window.location.href = "admin-dashboard.html";
            }
        });
    }
});

// Edit Hall Form JavaScript
document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("editHallForm");

    // Delete image buttons
    const deleteButtons = document.querySelectorAll(".image-delete");
    deleteButtons.forEach((btn) => {
        btn.addEventListener("click", function (e) {
            e.preventDefault();
            const imageItem = this.closest(".image-item");
            const imageName = imageItem.querySelector("img").alt;

            if (confirm(`هل أنت متأكد من حذف هذه الصورة؟`)) {
                imageItem.style.opacity = "0";
                setTimeout(() => {
                    imageItem.remove();
                }, 300);
            }
        });
    });

    // Add new images preview
    const newImagesInput = document.getElementById("newImages");
    if (newImagesInput) {
        newImagesInput.addEventListener("change", function (e) {
            const count = this.files.length;
            const uploadBox = document.querySelector(".upload-box");

            if (count > 0) {
                uploadBox.innerHTML = `
                    <i class="fa-regular fa-check-circle" style="color: var(--success)"></i>
                    <span>تم اختيار ${count} صور جديدة</span>
                `;

                // Reset after 3 seconds
                setTimeout(() => {
                    uploadBox.innerHTML = `
                        <i class="fa-regular fa-images"></i>
                        <span>اختر صوراً جديدة للإضافة</span>
                    `;
                }, 3000);
            }
        });
    }

    // Form submission
    if (form) {
        form.addEventListener("submit", (e) => {
            e.preventDefault();

            const submitBtn = form.querySelector(".btn-save");
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML =
                '<i class="fa fa-spinner fa-spin"></i> جاري الحفظ...';
            submitBtn.disabled = true;

            setTimeout(() => {
                alert("تم حفظ التغييرات بنجاح");
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 2000);
        });
    }

    // Cancel button
    const cancelBtn = document.querySelector(".btn-cancel");
    if (cancelBtn) {
        cancelBtn.addEventListener("click", () => {
            if (confirm("هل أنت متأكد من إلغاء التعديلات؟")) {
                window.location.href = "admin-dashboard.html";
            }
        });
    }

    // Set main image (click to set as main)
    const imageItems = document.querySelectorAll(".image-item");
    imageItems.forEach((item) => {
        item.addEventListener("dblclick", function (e) {
            e.preventDefault();

            // Remove main badge from all
            document
                .querySelectorAll(".main-badge")
                .forEach((badge) => badge.remove());

            // Add main badge to this image
            const badge = document.createElement("span");
            badge.className = "main-badge";
            badge.textContent = "الأساسية";
            this.appendChild(badge);

            alert("تم تعيين هذه الصورة كصورة أساسية");
        });
    });
});

// Booking Details JavaScript
document.addEventListener("DOMContentLoaded", () => {
    // Status action buttons
    const confirmBtn = document.querySelector(".status-action.confirm");
    const pendingBtn = document.querySelector(".status-action.pending");
    const cancelBtn = document.querySelector(".status-action.cancel");
    const completeBtn = document.querySelector(".status-action.complete");
    const rejectionBox = document.querySelector(".rejection-reason");
    const statusBadge = document.querySelector(".info-row .status-badge");

    if (confirmBtn) {
        confirmBtn.addEventListener("click", () => {
            if (confirm("تأكيد الحجز؟")) {
                statusBadge.className = "status-badge confirmed";
                statusBadge.textContent = "مؤكد";
                alert("تم تأكيد الحجز وإرسال إشعار للعميل");
            }
        });
    }

    if (pendingBtn) {
        pendingBtn.addEventListener("click", () => {
            statusBadge.className = "status-badge pending";
            statusBadge.textContent = "قيد المراجعة";
            alert("تم إعادة الحجز للمراجعة");
        });
    }

    if (cancelBtn) {
        cancelBtn.addEventListener("click", () => {
            rejectionBox.classList.remove("hidden");
        });
    }

    if (completeBtn) {
        completeBtn.addEventListener("click", () => {
            if (confirm("تأكيد اكتمال الحجز؟")) {
                statusBadge.className = "status-badge completed";
                statusBadge.textContent = "مكتمل";
                alert("تم تحديث حالة الحجز إلى مكتمل");
            }
        });
    }

    // Submit rejection
    const submitRejection = document.querySelector(".btn-submit-rejection");
    if (submitRejection) {
        submitRejection.addEventListener("click", () => {
            const reason = document.querySelector(
                ".rejection-reason textarea",
            ).value;
            if (!reason) {
                alert("الرجاء إدخال سبب الرفض");
                return;
            }

            statusBadge.className = "status-badge cancelled";
            statusBadge.textContent = "ملغي";
            rejectionBox.classList.add("hidden");
            alert(`تم إلغاء الحجز. سبب الرفض: ${reason}`);
        });
    }

    // Add note
    const addNoteBtn = document.querySelector(".btn-add-note");
    const notesList = document.querySelector(".notes-list");

    if (addNoteBtn && notesList) {
        addNoteBtn.addEventListener("click", () => {
            const noteText = document.querySelector(".add-note textarea").value;
            if (!noteText) {
                alert("الرجاء كتابة الملاحظة");
                return;
            }

            const now = new Date();
            const timeStr =
                now.toLocaleDateString("ar-EG") +
                " - " +
                now.toLocaleTimeString("ar-EG", {
                    hour: "2-digit",
                    minute: "2-digit",
                });

            const noteHTML = `
                <div class="note-item">
                    <div class="note-header">
                        <strong>محمد المدير</strong>
                        <span>${timeStr}</span>
                    </div>
                    <p>${noteText}</p>
                </div>
            `;

            notesList.insertAdjacentHTML("beforeend", noteHTML);
            document.querySelector(".add-note textarea").value = "";

            // Add to activity log
            const activityLog = document.querySelector(".activity-timeline");
            if (activityLog) {
                const activityHTML = `
                    <div class="activity-entry">
                        <div class="activity-dot"></div>
                        <div class="activity-content">
                            <p>تم إضافة ملاحظة بواسطة <strong>محمد المدير</strong></p>
                            <span>${timeStr}</span>
                        </div>
                    </div>
                `;
                activityLog.insertAdjacentHTML("beforeend", activityHTML);
            }
        });
    }

    // View documents
    const viewButtons = document.querySelectorAll(".doc-view");
    viewButtons.forEach((btn) => {
        btn.addEventListener("click", () => {
            alert("عرض المستند - سيتم فتح الملف في نافذة جديدة");
        });
    });

    // Download documents
    const downloadButtons = document.querySelectorAll(".doc-download");
    downloadButtons.forEach((btn) => {
        btn.addEventListener("click", () => {
            alert("جاري تحميل المستند");
        });
    });

    // View profile
    const viewProfile = document.querySelector(".btn-view-profile");
    if (viewProfile) {
        viewProfile.addEventListener("click", () => {
            alert("عرض الملف الشخصي للعميل");
        });
    }

    // View hall
    const viewHall = document.querySelector(".btn-view-hall");
    if (viewHall) {
        viewHall.addEventListener("click", () => {
            alert("عرض صفحة القاعة");
        });
    }
});

// Bookings Management JavaScript
document.addEventListener("DOMContentLoaded", () => {
    // Filter functionality
    const filterSelects = document.querySelectorAll(".filter-select");
    const filterDate = document.querySelectorAll(".filter-date");
    const filterSearch = document.querySelector(".filter-search");
    const resetBtn = document.querySelector(".reset-filters");

    if (resetBtn) {
        resetBtn.addEventListener("click", () => {
            filterSelects.forEach((select) => (select.value = "all"));
            filterDate.forEach((date) => (date.value = ""));
            if (filterSearch) filterSearch.value = "";

            alert("تم إعادة تعيين جميع الفلاتر");
        });
    }

    // Search functionality (simulated)
    if (filterSearch) {
        filterSearch.addEventListener("input", (e) => {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll(".bookings-table tbody tr");

            rows.forEach((row) => {
                const customerName = row
                    .querySelector(".customer-cell strong")
                    ?.textContent.toLowerCase();
                const bookingId = row
                    .querySelector(".booking-id")
                    ?.textContent.toLowerCase();

                if (
                    customerName?.includes(searchTerm) ||
                    bookingId?.includes(searchTerm)
                ) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        });
    }

    // Filter by status (simulated)
    const statusFilter = document.querySelector(".filter-select:first-child");
    if (statusFilter) {
        statusFilter.addEventListener("change", (e) => {
            const status = e.target.value;
            const rows = document.querySelectorAll(".bookings-table tbody tr");

            if (status === "all") {
                rows.forEach((row) => (row.style.display = ""));
            } else {
                rows.forEach((row) => {
                    const statusBadge = row.querySelector(".status-badge");
                    if (statusBadge) {
                        const rowStatus = statusBadge.classList.contains(status)
                            ? status
                            : status === "pending" &&
                                statusBadge.classList.contains("pending")
                              ? "pending"
                              : status === "confirmed" &&
                                  statusBadge.classList.contains("confirmed")
                                ? "confirmed"
                                : status === "completed" &&
                                    statusBadge.classList.contains("completed")
                                  ? "completed"
                                  : status === "cancelled" &&
                                      statusBadge.classList.contains(
                                          "cancelled",
                                      )
                                    ? "cancelled"
                                    : null;

                        if (rowStatus) {
                            row.style.display = "";
                        } else {
                            row.style.display = "none";
                        }
                    }
                });
            }
        });
    }

    // View booking details
    const viewButtons = document.querySelectorAll(".action-btn.view");
    viewButtons.forEach((btn) => {
        btn.addEventListener("click", () => {
            const row = btn.closest("tr");
            const bookingId = row.querySelector(".booking-id").textContent;
            const customerName = row.querySelector(
                ".customer-cell strong",
            ).textContent;

            alert(`عرض تفاصيل الحجز ${bookingId} للعميل ${customerName}`);
            // window.location.href = `booking-details.html?id=${bookingId}`;
        });
    });

    // Edit booking
    const editButtons = document.querySelectorAll(".action-btn.edit");
    editButtons.forEach((btn) => {
        btn.addEventListener("click", () => {
            const bookingId = btn
                .closest("tr")
                .querySelector(".booking-id").textContent;
            alert(`تعديل الحجز ${bookingId}`);
        });
    });

    // More actions
    const moreButtons = document.querySelectorAll(".action-btn.more");
    moreButtons.forEach((btn) => {
        btn.addEventListener("click", (e) => {
            const rect = btn.getBoundingClientRect();

            // Simple dropdown simulation
            const action = confirm(
                "خيارات إضافية:\n• تأكيد الحجز\n• إلغاء الحجز\n• إرسال تذكير",
            );

            if (action) {
                const row = btn.closest("tr");
                const bookingId = row.querySelector(".booking-id").textContent;
                const statusBadge = row.querySelector(".status-badge");

                if (statusBadge.classList.contains("pending")) {
                    statusBadge.className = "status-badge confirmed";
                    statusBadge.textContent = "مؤكد";
                    alert(`تم تأكيد الحجز ${bookingId}`);
                }
            }
        });
    });

    // Export button
    const exportBtn = document.querySelector(".export-btn");
    if (exportBtn) {
        exportBtn.addEventListener("click", () => {
            alert("جاري تصدير الحجوزات إلى Excel");
        });
    }

    // Pagination
    const pageBtns = document.querySelectorAll(".page-btn:not(:has(i))");
    pageBtns.forEach((btn) => {
        btn.addEventListener("click", () => {
            pageBtns.forEach((b) => b.classList.remove("active"));
            btn.classList.add("active");

            const pageNum = btn.textContent;
            alert(`جاري عرض الصفحة ${pageNum}`);
        });
    });

    // Previous/Next buttons
    const prevBtn = document.querySelector(".page-btn:first-child");
    const nextBtn = document.querySelector(".page-btn:last-child");

    if (prevBtn && nextBtn) {
        prevBtn.addEventListener("click", () => {
            const activePage = document.querySelector(".page-btn.active");
            const prevPage = activePage?.previousElementSibling;
            if (prevPage && !prevPage.querySelector("i")) {
                prevPage.click();
            }
        });

        nextBtn.addEventListener("click", () => {
            const activePage = document.querySelector(".page-btn.active");
            const nextPage = activePage?.nextElementSibling;
            if (nextPage && !nextPage.querySelector("i")) {
                nextPage.click();
            }
        });
    }

    // Date filters
    const dateInputs = document.querySelectorAll(".filter-date");
    dateInputs.forEach((input) => {
        input.addEventListener("change", () => {
            alert("تم تطبيق فلترة التاريخ");
        });
    });
});
