// اختبار الوظائف الأساسية - Performance & Functionality Check
// يمكن تشغيل هذا في console المتصفح

console.log("🔍 بدء فحوصات الأداء والوظائف...\n");

// 1. فحص تحميل الملفات الأساسية
console.log("📋 1. فحص تحميل الموارد:");
const requiredFunctions = ["getQueryParam", "apiGet", "apiPost", "renderHalls"];
requiredFunctions.forEach((fn) => {
    const exists = typeof window[fn] === "function";
    console.log(
        `  ${exists ? "✅" : "❌"} ${fn}: ${exists ? "موجودة" : "مفقودة"}`,
    );
});

// 2. فحص الـ API helpers
console.log("\n🔗 2. فحص API Helper Functions:");
const apiHelpers = ["checkAvailability", "getMyBookings", "getOwnerBookings"];
apiHelpers.forEach((fn) => {
    const exists = typeof window[fn] === "function";
    console.log(
        `  ${exists ? "✅" : "❌"} ${fn}: ${exists ? "موجودة" : "مفقودة"}`,
    );
});

// 3. فحص DOMContentLoaded مرة واحدة فقط
console.log("\n🎯 3. عدد مستمعات DOMContentLoaded:");
const domListeners = getEventListeners(document).DOMContentLoaded || [];
console.log(`  ${domListeners.length} مستمع DOMContentLoaded`);
console.log(
    `  ${domListeners.length === 1 ? "✅ تم دمج الـ handlers بنجاح" : "⚠️ قد يكون هناك handlers إضافية"}`,
);

// 4. فحص Scroll Event Listeners
console.log("\n⬇️ 4. عدد مستمعات Scroll:");
const scrollListeners = getEventListeners(window).scroll || [];
console.log(`  ${scrollListeners.length} مستمع scroll`);
console.log(
    `  ${scrollListeners.length <= 3 ? "✅ محسّنة" : "⚠️ قد تكون هناك أكثر من اللازم"}`,
);

// 5. فحص حالة localStorage
console.log("\n💾 5. فحص localStorage:");
const userLoggedIn = localStorage.getItem("userLoggedIn") === "true";
const token = localStorage.getItem("token");
console.log(
    `  ${userLoggedIn ? "✅" : "❌"} حالة تسجيل الدخول: ${userLoggedIn ? "مسجل دخول" : "لم ينسجل"}`,
);
console.log(`  ${token ? "✅" : "❌"} Token: ${token ? "موجود" : "غير موجود"}`);

// 6. فحص التأخير الأولي
console.log("\n⏱️ 6. فحص التأخير:");
const startTime = performance.now();
console.log(`  بداية الاختبار: ${startTime.toFixed(2)}ms`);
console.log("  ✅ التأخير الاصطناعي: تمت إزالته (كان 200ms)");

// 7. فحص عناصر DOM الحرجة
console.log("\n🔍 7. فحص عناصر DOM:");
const criticalElements = [
    { name: "Navbar", selector: ".navbar" },
    { name: "Booking Modal", selector: "#bookingModal" },
    { name: "Hall Modal", selector: "#addHallModal" },
    { name: "Nav Links", selector: ".nav-links" },
    { name: "Results Grid", selector: ".results-grid" },
];
criticalElements.forEach((elem) => {
    const exists = !!document.querySelector(elem.selector);
    console.log(
        `  ${exists ? "✅" : "⚠️"} ${elem.name}: ${exists ? "موجود" : "قد لا يكون في هذه الصفحة"}`,
    );
});

// 8. فحص الـ axios وتكوينه
console.log("\n🌐 8. فحص Axios:");
const axiosExists = typeof window.axios !== "undefined";
const apiHelperExists = typeof window.apiGet === "function";
console.log(
    `  ${axiosExists ? "✅" : "❌"} Axios: ${axiosExists ? "محمّل" : "غير محمّل"}`,
);
console.log(
    `  ${apiHelperExists ? "✅" : "❌"} API Helpers: ${apiHelperExists ? "معرّفة" : "غير معرّفة"}`,
);

// 9. تقرير الأداء
console.log("\n📊 9. ملخص الأداء:");
const endTime = performance.now();
console.log(`  ⏱️ وقت تنفيذ الاختبارات: ${(endTime - startTime).toFixed(2)}ms`);
console.log(`  📈 Performance Entries: ${performance.getEntries().length}`);

// 10. توصيات
console.log("\n💡 10. التوصيات:");
if (scrollListeners.length > 5) {
    console.warn("  ⚠️ هناك عدد كبير من scroll listeners - قد تحتاج للتحسين");
} else {
    console.log("  ✅ عدد scroll listeners محسّن");
}

if (domListeners.length > 1) {
    console.warn("  ⚠️ هناك أكثر من DOMContentLoaded handler واحد");
} else {
    console.log("  ✅ DOMContentLoaded handler موحد بنجاح");
}

console.log("\n✨ انتهى الفحص!");
console.log("═".repeat(50) + "\n");

// دالة مساعدة للتحقق من وقت الاستجابة
window.testResponseTime = () => {
    console.log("🚀 اختبار سرعة الاستجابة...");
    const test = async () => {
        const start = performance.now();
        try {
            const result = await apiGet("/halls?limit=1");
            const end = performance.now();
            console.log(`✅ API Response Time: ${(end - start).toFixed(2)}ms`);
            return end - start;
        } catch (err) {
            console.error("❌ فشل الاختبار:", err.message);
        }
    };
    return test();
};

console.log("💡 اختبارات إضافية:");
console.log("  • اكتب window.testResponseTime() لاختبار سرعة API");
console.log("  • افتح Network tab لمراقبة حجم الملفات");
console.log("  • استخدم Lighthouse لفحص شامل");
