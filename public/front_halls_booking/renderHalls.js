// دالة عرض القاعات في صفحة البحث
function renderHalls(response) {
    const grid = document.querySelector(".results-grid");
    if (!grid) return;

    // Handle both array and {data: array} response formats
    let halls = Array.isArray(response) ? response : response?.data || [];
    const total = response?.total || halls.length;
    const displayCount = halls.length;

    // Update results count
    const resultsCount = document.querySelector(".results-count");
    if (resultsCount) {
        const h3 = resultsCount.querySelector("h3");
        const p = resultsCount.querySelector("p");
        if (displayCount === 0) {
            if (h3) h3.textContent = "لا توجد نتائج";
            if (p) p.textContent = "لم يتم العثور على قاعات مطابقة لبحثك";
        } else if (total > displayCount) {
            if (h3) h3.textContent = `${displayCount} من ${total} قاعة متاحة`;
            if (p)
                p.textContent = `تم العثور على ${total} قاعة تطابق معايير بحثك`;
        } else {
            if (h3) h3.textContent = `${displayCount} قاعة متاحة`;
            if (p)
                p.textContent = `تم العثور على ${displayCount} قاعة تطابق معايير بحثك`;
        }
    }

    // If no halls, show friendly message
    if (!halls || halls.length === 0) {
        grid.innerHTML = `<div style="text-align: center; padding: 60px 0; color: #888; font-size: 1.2em;">
          <i class='fa fa-circle-exclamation' style='font-size:2.5em; color:#bbb; margin-bottom:10px;'></i><br>
          لم يتم العثور على قاعات مطابقة لبحثك<br>
          <span style='font-size:0.9em;color:#aaa;'>جرب تغيير الفلاتر أو إعادة تعيينها</span>
        </div>`;
        return;
    }

    grid.innerHTML = ""; // تنظيف
    halls.forEach((hall) => {
        const card = document.createElement("div");
        card.className = "result-card";
        card.dataset.id = hall.id;
        card.onclick = () => (window.location.href = `/halls/${hall.id}`);
        card.innerHTML = `
            <div class="result-img">
              <img src="${hall.main_image || ""}" alt="${hall.name}" onerror="this.src='https://via.placeholder.com/300x200'">
              <span class="result-badge available">${hall.status || "متاح"}</span>
              <button class="fav-btn"><i class="fa-regular fa-heart"></i></button>
            </div>
            <div class="result-content">
              <div class="result-header">
                <h4>${hall.name}</h4>
                <div class="rating">
                  <i class="fa fa-star"></i>
                  <span>${hall.rating || "0.0"}</span>
                  <span class="reviews-count">(${hall.reviews_count || 0})</span>
                </div>
              </div>
              <p class="result-location"><i class="fa fa-map-pin"></i> ${hall.location || "لم يتم تحديد الموقع"}</p>
              <div class="result-features">
                <span><i class="fa fa-users"></i> ${hall.capacity || "غير محدد"} ضيف</span>
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
