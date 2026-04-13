// Bootstrap axios configuration
// Use global axios from CDN instead of importing it as a module in the browser.
if (typeof window !== "undefined" && window.axios) {
    const axios = window.axios;

    // Add token to headers if it exists
    let token = localStorage.getItem("token");
    if (token) {
        axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
    }
}

// Handle 401 responses (Unauthorized)
window.axios.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response?.status === 401) {
            localStorage.removeItem("token");
            localStorage.removeItem("user");
            window.location.href = "/admin/login.html";
        }
        return Promise.reject(error);
    },
);

// ===== عرض اسم المستخدم بعد تسجيل الدخول =====
document.addEventListener("DOMContentLoaded", () => {
    const user = localStorage.getItem("user");
    const token = localStorage.getItem("token");
    const loginBtn = document.querySelector(".login-btn");
    const signupBtn = document.querySelector(".nav-signup-btn");

    console.log(
        "bootstrap.js init - user:",
        user,
        "token:",
        token,
        "loginBtn:",
        loginBtn,
        "signupBtn:",
        signupBtn,
    );

    if (user && loginBtn && signupBtn) {
        try {
            const userData = JSON.parse(user);
            const userName = userData.name || "مستخدم";
            const userInitial = userName.charAt(0).toUpperCase();

            // إنشء عنصر عرض المستخدم
            const userDisplay = document.createElement("div");
            userDisplay.className = "user-display";
            userDisplay.innerHTML = `
        <span>${userName}</span>
        <div class="user-avatar">${userInitial}</div>
      `;

            // إنشء قائمة منسدلة
            const dropdown = document.createElement("div");
            dropdown.className = "user-dropdown";
            dropdown.innerHTML = `
        <a href="admin/index.html"><i class="fa fa-dashboard"></i> لوحة التحكم</a>
        <a href="admin/reses.html"><i class="fa fa-calendar"></i> حجوزاتي</a>
        <a href="#" onclick="event.preventDefault(); logout()"><i class="fa fa-sign-out"></i> تسجيل الخروج</a>
      `;

            // إرسال عنصر المستخدم
            loginBtn.parentElement.replaceChild(userDisplay, loginBtn);
            signupBtn.remove();

            // إضافة القائمة المنسدلة بعد عنصر المستخدم
            userDisplay.appendChild(dropdown);

            // فتح/إغلاق القائمة المنسدلة
            userDisplay.addEventListener("click", (e) => {
                e.stopPropagation();
                dropdown.classList.toggle("active");
            });

            // إغلاق القائمة عند النقر خارجها
            document.addEventListener("click", (e) => {
                if (!userDisplay.contains(e.target)) {
                    dropdown.classList.remove("active");
                }
            });
        } catch (err) {
            console.error("Error parsing user data:", err);
        }
    } else if (token && (!user || !loginBtn || !signupBtn)) {
        console.log(
            "token present but missing elements or user info",
            user,
            loginBtn,
            signupBtn,
        );
    }

    // دالة تسجيل الخروج
    window.logout = function () {
        if (confirm("هل تريد تسجيل الخروج؟")) {
            localStorage.removeItem("token");
            localStorage.removeItem("user");
            window.location.href = "/index.html";
        }
    };
});
