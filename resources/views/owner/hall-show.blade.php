@extends('layouts.app')

@section('title', $hall->name)
@section('body-class', 'page-owner-hall-details page-hall-details')

@section('content')
    @push('styles')
    <style>
        body.page-hall-details .navbar {
            background: linear-gradient(135deg, #1b365d 0%, #152b4f 100%) !important;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3) !important;
            border-bottom: 1px solid rgba(212, 175, 55, 0.2) !important;
        }

        body.page-hall-details .navbar .logo {
            color: #d4af37 !important;
        }

        body.page-hall-details .navbar .logo i {
            color: #d4af37 !important;
        }

        body.page-hall-details .navbar .nav-links li a {
            color: rgba(255, 255, 255, 0.95) !important;
        }

        body.page-hall-details .navbar .nav-links li a:hover {
            background: rgba(212, 175, 55, 0.25) !important;
            color: #d4af37 !important;
        }

        body.page-hall-details .navbar .nav-signup-btn {
            background: linear-gradient(135deg, #d4af37 0%, #f59e0b 100%) !important;
            color: #1b365d !important;
        }

        body.page-hall-details .navbar .nav-signup-btn:hover {
            box-shadow: 0 12px 28px rgba(212, 175, 55, 0.4) !important;
        }

        .btn-primary-navy {
            background: linear-gradient(135deg, #0f1f35 0%, #1b365d 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(27, 54, 93, 0.3);
        }

        .btn-primary-navy:hover {
            background: linear-gradient(135deg, #152b4f 0%, #1b365d 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(27, 54, 93, 0.4);
        }

        .form-control[type="date"],
        input[type="date"].form-control {
            padding: 16px 18px !important;
            border-radius: 20px !important;
            border: 1px solid rgba(148, 163, 184, 0.3) !important;
            background: #ffffff !important;
            color: #0f172a !important;
            box-shadow: inset 0 1px 2px rgba(15, 23, 42, 0.06) !important;
            appearance: none !important;
            -webkit-appearance: none !important;
            -moz-appearance: textfield !important;
        }

        .form-control[type="date"]:focus,
        input[type="date"].form-control:focus {
            border-color: rgba(59, 130, 246, 0.9) !important;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.12) !important;
            background: #ffffff !important;
        }

        .gallery-grid img {
            cursor: pointer;
        }
        .lightbox-container {
            display: none;
            position: fixed;
            z-index: 10000;
            top: 0;
            left: 0;
            width: 100%;
            min-height: 100vh;
            background: rgba(0,0,0,0.95);
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .lightbox-container.active {
            display: flex !important;
        }
        .lightbox-backdrop {
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.95);
            cursor: pointer;
            z-index: 1;
        }
        .lightbox-content {
            position: relative;
            z-index: 10002;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            box-sizing: border-box;
            pointer-events: none;
        }
        .lightbox-content * {
            pointer-events: auto;
        }
        .lightbox-image-wrapper {
            width: 100%;
            max-width: 96vw;
            max-height: calc(100vh - 220px);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 18px;
        }
        .lightbox-image {
            max-width: 96vw;
            max-height: calc(100vh - 260px);
            width: auto;
            height: auto;
            object-fit: contain;
            border-radius: 18px;
            transform: scale(1);
            transform-origin: center center;
            transition: transform 0.2s ease;
            box-shadow: 0 22px 80px rgba(0,0,0,0.55);
        }
        .lightbox-thumbnails {
            width: 100%;
            max-width: 96vw;
            display: flex;
            gap: 10px;
            overflow-x: auto;
            justify-content: center;
            padding: 8px 0;
            margin-bottom: 10px;
            z-index: 10003;
        }
        .lightbox-thumbnail {
            flex: 0 0 auto;
            width: 90px;
            height: 60px;
            border-radius: 14px;
            object-fit: cover;
            cursor: pointer;
            opacity: 0.75;
            border: 1px solid rgba(255,255,255,0.2);
            transition: transform 0.2s ease, opacity 0.2s ease, border-color 0.2s ease;
        }
        .lightbox-thumbnail:hover,
        .lightbox-thumbnail.active {
            opacity: 1;
            transform: scale(1.03);
            border-color: rgba(255,255,255,0.55);
        }
        .lightbox-nav,
        .toolbar-btn {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            opacity: 1 !important;
            visibility: visible !important;
            user-select: none !important;
            text-shadow: 0 0 6px rgba(0,0,0,0.7) !important;
            pointer-events: auto !important;
            border: none !important;
            cursor: pointer !important;
        }
        .lightbox-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: rgba(255,255,255,0.12);
            color: white;
            font-size: 34px;
            z-index: 10003 !important;
            border: 1px solid rgba(255,255,255,0.35) !important;
        }
        .lightbox-prev {
            left: 20px;
        }
        .lightbox-next {
            right: 20px;
        }
        .lightbox-toolbar {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 12px;
            z-index: 10003 !important;
        }
        .toolbar-btn {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: rgba(255,255,255,0.18) !important;
            color: white !important;
            border: 1px solid rgba(255,255,255,0.35) !important;
            font-size: 22px;
        }
        .toolbar-btn.reset-btn {
            width: 65px;
            border-radius: 24px;
            font-size: 14px;
        }
        body.page-hall-details,
        body.page-hall-details .gallery-section,
        body.page-hall-details .gallery-grid,
        body.page-hall-details .main-image,
        body.page-hall-details .main-image img,
        body.page-hall-details .image-side,
        body.page-hall-details .image-side img {
            background: #ffffff !important;
            filter: none !important;
            opacity: 1 !important;
        }
        
        /* جعل الـ sidebar ثابتة بدون حركة */
        .owner-hall-details .booking-sidebar {
            position: relative;
            align-self: start;
        }
        
        @media (max-width: 1280px) {
            .owner-hall-details .booking-sidebar {
                position: static;
                top: auto;
            }
        }
    </style>
    @endpush

    @if(session('status'))
        <div style="position: fixed; top: 100px; left: 50%; transform: translateX(-50%); z-index: 10001; padding: 14px 18px; background: rgba(76, 175, 80, 0.95); border: 1px solid rgba(76, 175, 80, 0.35); border-radius: 12px; color: #fff; display: flex; align-items: center; gap: 10px; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3); backdrop-filter: blur(10px);">
            <i class="fas fa-check-circle" style="color: #4caf50; font-size: 20px;"></i>
            {{ session('status') }}
        </div>
    @endif

    <!-- Lightbox Modal -->
    <div id="lightboxModal" class="lightbox-container">
        <div class="lightbox-backdrop" onclick="closeLightbox()"></div>
        <div class="lightbox-content">
            <div class="lightbox-image-wrapper">
                <img id="lightboxImage" src="" alt="صورة" class="lightbox-image" />
            </div>
            <div id="lightboxThumbnails" class="lightbox-thumbnails"></div>
        </div>
        <button class="lightbox-nav lightbox-prev" onclick="showPreviousLightbox()" title="السابق (←)">‹</button>
        <button class="lightbox-nav lightbox-next" onclick="showNextLightbox()" title="التالي (→)">›</button>
        <div class="lightbox-toolbar">
            <button class="toolbar-btn" onclick="zoomOutLightbox()" title="تصغير (-)">-</button>
            <button class="toolbar-btn reset-btn" onclick="resetLightboxZoom()" title="إعادة تعيين (1x)">1x</button>
            <button class="toolbar-btn" onclick="zoomInLightbox()" title="تكبير (+)">+</button>
            <button class="toolbar-btn" onclick="closeLightbox()" title="إغلاق (ESC)">×</button>
        </div>
    </div>

    @php
        $lightboxUrls = [];
        if (!empty($hall->main_image) && \Illuminate\Support\Facades\Storage::disk('public')->exists(ltrim($hall->main_image, '/'))) {
            $lightboxUrls[] = '/storage-file/' . ltrim($hall->main_image, '/');
        }
        if ($hall->gallery && $hall->gallery->count()) {
            foreach ($hall->gallery as $img) {
                $path = ltrim($img->path ?? '', '/');
                if ($path && \Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
                    $lightboxUrls[] = '/storage-file/' . $path;
                }
            }
        }
        if (empty($lightboxUrls)) {
            $lightboxUrls[] = 'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?auto=format&fit=crop&w=1500&q=80';
        }
        $lightboxUrls = array_values(array_unique($lightboxUrls));
    @endphp
    <script>
        const imageUrls = @json($lightboxUrls);
    </script>
    <!-- Gallery Section -->
    <section class="gallery-section">
        <div class="container">
            @php
                $galleryImages = [];
                if ($hall->gallery && $hall->gallery->count()) {
                    foreach ($hall->gallery as $img) {
                        $path = ltrim($img->path ?? '', '/');
                        if ($path && \Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
                            $galleryImages[] = $path;
                        }
                    }
                }

                if (!empty($hall->main_image) && \Illuminate\Support\Facades\Storage::disk('public')->exists(ltrim($hall->main_image, '/'))) {
                    $mainImageUrl = '/storage-file/' . ltrim($hall->main_image, '/');
                } elseif (!empty($galleryImages)) {
                    $mainImageUrl = '/storage-file/' . ltrim($galleryImages[0], '/');
                } else {
                    $mainImageUrl = 'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?auto=format&fit=crop&w=1500&q=80';
                }
            @endphp
            @if(!empty($galleryImages))
                <div class="gallery-grid">
                    <div class="main-image">
                        <img
                            src="{{ $mainImageUrl }}"
                            data-lightbox-url="{{ $mainImageUrl }}"
                            alt="{{ $hall->name }}"
                            onclick="openLightbox(this.dataset.lightboxUrl)"
                        />
                    </div>
                    @foreach(array_slice($galleryImages, 0, 4) as $path)
                        @php $imageUrl = '/storage-file/' . ltrim($path, '/'); @endphp
                        <div class="image-side">
                            <img
                                src="{{ $imageUrl }}"
                                data-lightbox-url="{{ $imageUrl }}"
                                alt=""
                                onclick="openLightbox(this.dataset.lightboxUrl)"
                            />
                        </div>
                    @endforeach
                </div>
            @else
                <div style="height: 400px; border-radius: 24px; overflow: hidden;">
                    <img
                        src="https://images.unsplash.com/photo-1511795409834-ef04bbd61622?auto=format&fit=crop&w=1500&q=80"
                        alt="{{ $hall->name }}"
                        style="width: 100%; height: 100%; object-fit: cover;"
                    />
                </div>
            @endif
        </div>
    </section>

    <!-- Details Section -->
    <section class="details-section owner-hall-details">
        <div class="container">
            <div class="details-wrapper">
                <!-- العمود الأيمن - المعلومات الرئيسية -->
                <div class="details-main">
                    <div class="hall-header">
                        <h1>{{ $hall->name ?? 'اسم القاعة' }}</h1>
                        <p class="location">
                            <i class="fa fa-map-pin"></i> {{ $hall->location ?? 'الموقع غير محدد' }}
                        </p>
                    </div>

                    <!-- Owner Actions -->
                    <div class="quick-actions">
                        <a href="{{ route('owner.halls.edit', $hall) }}" class="action-btn">
                            <i class="fa fa-edit"></i> تعديل البيانات
                        </a>
                        <a href="{{ route('owner.halls') }}" class="action-btn">
                            <i class="fa fa-arrow-right"></i> العودة للقائمة
                        </a>
                        <form method="POST" action="{{ route('owner.halls.destroy', $hall) }}" style="display: inline;" onsubmit="return confirm('هل أنت متأكد من حذف هذه القاعة؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn" style="color: #ef4444;">
                                <i class="fa fa-trash"></i> حذف
                            </button>
                        </form>
                    </div>

                    <!-- معلومات أساسية -->
                    <div class="info-card">
                        <h2>معلومات أساسية</h2>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div>
                                <strong style="display: block; margin-bottom: 8px;">السعة</strong>
                                <span>{{ $hall->capacity }} ضيف</span>
                            </div>
                            <div>
                                <strong style="display: block; margin-bottom: 8px;">السعر</strong>
                                <span>{{ number_format($hall->price) }} ج.م / ليلة</span>
                            </div>
                            @if($hall->category)
                            <div>
                                <strong style="display: block; margin-bottom: 8px;">الفئة</strong>
                                <span>{{ $hall->category }}</span>
                            </div>
                            @endif
                            <div>
                                <strong style="display: block; margin-bottom: 8px;">الحالة</strong>
                                <span style="color: {{ $hall->status === 'active' ? '#10b981' : '#ef4444' }};">
                                    {{ $hall->status === 'active' ? 'مفعلة' : 'معطلة' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- الوصف -->
                    <div class="info-card">
                        <h2>وصف القاعة</h2>
                        <p>
                            {{ $hall->description ?? 'لا يوجد وصف مفصّل لهذه القاعة بعد. يمكنك تعديل البيانات لإضافة وصف.' }}
                        </p>
                    </div>

                    <!-- الخدمات والمميزات -->
                    <div class="info-card">
                        <h2>الخدمات والمميزات</h2>
                        <div class="features-grid">
                            @if(!empty($hall->features) && is_array($hall->features))
                                @foreach($hall->features as $feature)
                                    <div class="feature-item">
                                        <i class="fa fa-check"></i>
                                        <span>{{ $feature }}</span>
                                    </div>
                                @endforeach
                            @else
                                <p style="color: rgba(255,255,255,0.7);">لا توجد مميزات محددة. قم بتعديل البيانات لإضافتها.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- العمود الأيسر - معلومات إدارية -->
                <div class="booking-sidebar">
                    @auth
                        @if(auth()->user()->id === $hall->user_id)
                            <div class="booking-card">
                                <h3 style="margin-top: 0;">إحصائيات القاعة</h3>

                                <div class="price-row">
                                    <span>عدد الحجوزات</span>
                                    <strong>{{ $hall->bookings_count ?? 0 }}</strong>
                                </div>

                                <div class="price-row">
                                    <span>عدد التقييمات</span>
                                    <strong>{{ $hall->reviews_count ?? 0 }}</strong>
                                </div>

                                <div class="price-row">
                                    <span>متوسط التقييم</span>
                                    <strong>{{ $hall->avg_rating ?? '--' }} / 5</strong>
                                </div>

                                <hr style="border: none; border-top: 1px solid rgba(255,255,255,0.1); margin: 16px 0;">

                                <a href="{{ route('owner.halls.edit', $hall) }}" class="btn btn-primary-navy btn-large" style="display: block; text-align: center; margin-bottom: 10px;">
                                    تعديل البيانات
                                </a>

                                <a href="{{ route('owner.halls') }}" class="btn btn-secondary btn-large" style="display: block; text-align: center; margin-bottom: 10px;">
                                    العودة للقائمة
                                </a>

                                <form method="POST" action="{{ route('owner.halls.destroy', $hall) }}" onsubmit="return confirm('هل أنت متأكد؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-secondary btn-large" style="display: block; width: 100%; background: rgba(239, 68, 68, 0.2); color: #ef4444; text-align: center;">
                                        حذف القاعة
                                    </button>
                                </form>
                            </div>

                            <div class="info-card">
                                <h3>
                                    <i class="fa fa-calendar-times"></i> إغلاق مواعيد
                                </h3>

                                <form id="unavailableDatesForm" method="POST" action="{{ route('owner.halls.update', $hall) }}">
                                    @csrf
                                    @method('PUT')
                                    @php
                                        $unavailableDates = [];
                                        if ($hall->unavailable_dates) {
                                            $unavailableDates = is_array($hall->unavailable_dates) 
                                                ? $hall->unavailable_dates 
                                                : json_decode($hall->unavailable_dates, true) ?? [];
                                        }
                                    @endphp
                                    <input
                                        type="hidden"
                                        name="unavailable_dates"
                                        id="unavailableDatesInput"
                                        value="{{ implode(',', $unavailableDates) }}"
                                    />

                                    <div class="form-group" style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center;">
                                        <label style="flex: 1 1 100%; margin-bottom: 8px;">اختر تاريخاً لوضعه غير متاح</label>
                                        <input type="date" id="unavailableDatePicker" class="form-control" style="flex: 1 1 auto; padding: 16px 18px; border-radius: 20px; border: 1px solid rgba(148, 163, 184, 0.3); background: #ffffff; color: #0f172a;" />
                                        <button type="button" class="btn btn-secondary" id="addUnavailableDateBtn" style="flex: 0 0 auto;">إضافة</button>
                                    </div>

                                    <div id="unavailableDatesList" style="display: flex; flex-wrap: wrap; gap: 8px; margin: 12px 0;"></div>

                                    <div id="ownerUnavailableCalendarCardLight" style="background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 24px; padding: 18px; margin-top: 18px; color: #0f172a;">
                                        <div style="display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-bottom: 16px;">
                                            <div style="font-weight: 700; color: #0f172a; font-size: 0.95rem;">تقويم الإغلاق</div>
                                            <div style="display: flex; gap: 10px;">
                                                <button type="button" id="calendarPrevBtnLight" style="width: 36px; height: 36px; border-radius: 14px; border: 1px solid #cbd5e1; background: #ffffff; color: #0f172a; cursor: pointer;">‹</button>
                                                <button type="button" id="calendarNextBtnLight" style="width: 36px; height: 36px; border-radius: 14px; border: 1px solid #cbd5e1; background: #ffffff; color: #0f172a; cursor: pointer;">›</button>
                                            </div>
                                        </div>
                                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; gap: 10px;">
                                            <div id="calendarMonthLabelLight" style="font-weight: 700; color: #0f172a; font-size: 0.95rem;"></div>
                                            <div style="font-size: 0.85rem; color: #475569;">اضغط على اليوم لتغييره.</div>
                                        </div>
                                        <div id="calendarDaysGridLight" style="display: grid; grid-template-columns: repeat(7, minmax(0, 1fr)); gap: 8px; background: #ffffff; padding: 12px; border-radius: 18px; border: 1px solid #e2e8f0;"></div>
                                    </div>

                                    <button type="submit" class="btn btn-primary-navy btn-large" style="display: block; width: 100%; text-align: center; margin-top: 18px;">حفظ التواريخ</button>
                                </form>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
        </section>

    @push('scripts')
    <script>
        let currentLightboxIndex = 0;
        let currentLightboxZoom = 1;
        const minLightboxZoom = 1;
        const maxLightboxZoom = 3;
        const lightboxZoomStep = 0.25;

        function updateLightboxImage() {
            const img = document.getElementById('lightboxImage');
            if (!img) {
                return;
            }
            img.src = imageUrls[currentLightboxIndex] || imageUrls[0] || '';
            img.style.transform = `scale(${currentLightboxZoom})`;
            highlightActiveThumbnail();
        }

        function renderLightboxThumbnails() {
            const thumbnailsContainer = document.getElementById('lightboxThumbnails');
            if (!thumbnailsContainer || !imageUrls.length) {
                return;
            }
            thumbnailsContainer.innerHTML = imageUrls.map((url, index) => `
                <img class="lightbox-thumbnail${index === currentLightboxIndex ? ' active' : ''}" src="${url}" data-index="${index}" alt="صورة مصغرة ${index + 1}" />
            `).join('');
            thumbnailsContainer.querySelectorAll('.lightbox-thumbnail').forEach((thumb) => {
                thumb.addEventListener('click', function () {
                    const index = parseInt(this.dataset.index, 10);
                    if (!Number.isNaN(index)) {
                        currentLightboxIndex = index;
                        currentLightboxZoom = 1;
                        updateLightboxImage();
                    }
                });
            });
        }

        function highlightActiveThumbnail() {
            const thumbnails = document.querySelectorAll('.lightbox-thumbnail');
            thumbnails.forEach((thumb, index) => {
                thumb.classList.toggle('active', index === currentLightboxIndex);
            });
        }

        function bindGalleryImagesToLightbox() {
            document.querySelectorAll('.gallery-grid img[data-lightbox-url]').forEach((img) => {
                img.addEventListener('click', function () {
                    openLightbox(this.dataset.lightboxUrl);
                });
            });
        }

        function openLightbox(src) {
            console.log('🔄 Opening Lightbox with src:', src);
            currentLightboxIndex = imageUrls.indexOf(src);
            if (currentLightboxIndex < 0) {
                currentLightboxIndex = 0;
            }
            currentLightboxZoom = 1;
            updateLightboxImage();
            renderLightboxThumbnails();

            const modal = document.getElementById('lightboxModal');
            if (!modal) {
                console.error('❌ Modal element not found');
                return;
            }
            modal.classList.add('active');
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            console.log('✅ Lightbox displayed with image:', src);
        }

        function showPreviousLightbox() {
            if (!imageUrls.length) return;
            currentLightboxIndex = (currentLightboxIndex - 1 + imageUrls.length) % imageUrls.length;
            currentLightboxZoom = 1;
            updateLightboxImage();
        }

        function showNextLightbox() {
            if (!imageUrls.length) return;
            currentLightboxIndex = (currentLightboxIndex + 1) % imageUrls.length;
            currentLightboxZoom = 1;
            updateLightboxImage();
        }

        function zoomInLightbox() {
            currentLightboxZoom = Math.min(maxLightboxZoom, currentLightboxZoom + lightboxZoomStep);
            updateLightboxImage();
        }

        function zoomOutLightbox() {
            currentLightboxZoom = Math.max(minLightboxZoom, currentLightboxZoom - lightboxZoomStep);
            updateLightboxImage();
        }

        function resetLightboxZoom() {
            currentLightboxZoom = 1;
            updateLightboxImage();
        }

        function closeLightbox() {
            const modal = document.getElementById('lightboxModal');
            if (modal) {
                modal.classList.remove('active');
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            bindGalleryImagesToLightbox();
        });

        // Close lightbox on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeLightbox();
            }
            if (e.key === 'ArrowLeft') {
                showPreviousLightbox();
            }
            if (e.key === 'ArrowRight') {
                showNextLightbox();
            }
        });

        // Close lightbox if clicking outside image  
        document.getElementById('lightboxModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeLightbox();
            }
        });  

        const todayDate = new Date();
        const currentDate = new Date(todayDate.getFullYear(), todayDate.getMonth(), todayDate.getDate());
        let calendarMonthDateLight = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
        const weekDaysLight = ['س', 'ح', 'ن', 'ث', 'ر', 'خ', 'ج'];
        const monthFormatterLight = new Intl.DateTimeFormat('ar-EG', { month: 'long', year: 'numeric' });

        document.getElementById('addUnavailableDateBtn')?.addEventListener('click', function() {
            const dateInput = document.getElementById('unavailableDatePicker');
            if (dateInput && dateInput.value) {
                const existingDates = document.getElementById('unavailableDatesInput').value;
                const newDates = existingDates ? existingDates + ',' + dateInput.value : dateInput.value;
                document.getElementById('unavailableDatesInput').value = newDates;
                updateUnavailableDatesList();
                dateInput.value = '';
            }
        });

        function updateUnavailableDatesList() {
            const hiddenInput = document.getElementById('unavailableDatesInput');
            if (!hiddenInput) return;
            const dates = hiddenInput.value.split(',').filter(d => d);
            const listDiv = document.getElementById('unavailableDatesList');
            if (listDiv) {
                listDiv.innerHTML = dates.map(date => `
                    <span style="background: rgba(239, 68, 68, 0.2); padding: 8px 12px; border-radius: 8px; color: #ef4444; cursor: pointer;" onclick="removeDate('${date}')">${date} <i class="fa fa-times"></i></span>
                `).join('');
            }
            renderUnavailableDatesCalendarLight(dates);
        }

        function removeDate(date) {
            const hiddenInput = document.getElementById('unavailableDatesInput');
            if (!hiddenInput) return;
            let dates = hiddenInput.value.split(',').filter(d => d);
            dates = dates.filter(d => d !== date);
            hiddenInput.value = dates.join(',');
            updateUnavailableDatesList();
        }

        function renderUnavailableDatesCalendarLight(dates) {
            const calendarMonthLabel = document.getElementById('calendarMonthLabelLight');
            const calendarDaysGrid = document.getElementById('calendarDaysGridLight');
            const calendarPrevBtn = document.getElementById('calendarPrevBtnLight');
            const calendarNextBtn = document.getElementById('calendarNextBtnLight');
            if (!calendarMonthLabel || !calendarDaysGrid) return;

            const unavailableDates = dates.map(d => d.trim()).filter(Boolean);
            calendarMonthLabel.textContent = monthFormatterLight.format(calendarMonthDateLight);
            const firstDayOfWeek = new Date(calendarMonthDateLight.getFullYear(), calendarMonthDateLight.getMonth(), 1).getDay();
            const daysInMonth = new Date(calendarMonthDateLight.getFullYear(), calendarMonthDateLight.getMonth() + 1, 0).getDate();
            const firstAllowedMonth = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
            const canGoBack = calendarMonthDateLight.getTime() > firstAllowedMonth.getTime();

            if (calendarPrevBtn) {
                calendarPrevBtn.disabled = !canGoBack;
                calendarPrevBtn.style.opacity = canGoBack ? '1' : '0.45';
                calendarPrevBtn.style.cursor = canGoBack ? 'pointer' : 'not-allowed';
            }

            calendarDaysGrid.innerHTML = '';

            weekDaysLight.forEach((day) => {
                const label = document.createElement('span');
                label.textContent = day;
                label.style.textAlign = 'center';
                label.style.color = '#475569';
                label.style.fontSize = '0.82rem';
                label.style.fontWeight = '700';
                calendarDaysGrid.appendChild(label);
            });

            for (let emptyIndex = 0; emptyIndex < firstDayOfWeek; emptyIndex += 1) {
                const emptyCell = document.createElement('div');
                emptyCell.style.minHeight = '44px';
                calendarDaysGrid.appendChild(emptyCell);
            }

            for (let day = 1; day <= daysInMonth; day += 1) {
                const dateValue = `${calendarMonthDateLight.getFullYear()}-${String(calendarMonthDateLight.getMonth() + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                const cellDate = new Date(calendarMonthDateLight.getFullYear(), calendarMonthDateLight.getMonth(), day);
                const isPastDate = cellDate < currentDate;
                const isUnavailable = unavailableDates.includes(dateValue);
                const dayBtn = document.createElement('button');
                dayBtn.type = 'button';
                dayBtn.textContent = day;
                dayBtn.dataset.date = dateValue;
                dayBtn.style.display = 'inline-flex';
                dayBtn.style.justifyContent = 'center';
                dayBtn.style.alignItems = 'center';
                dayBtn.style.minHeight = '44px';
                dayBtn.style.borderRadius = '14px';
                dayBtn.style.border = '1px solid transparent';
                dayBtn.style.padding = '0';
                dayBtn.style.background = isPastDate ? '#f1f5f9' : isUnavailable ? '#f59e0b' : '#ffffff';
                dayBtn.style.color = isPastDate ? '#94a3b8' : isUnavailable ? '#111827' : '#0f172a';
                dayBtn.style.fontWeight = isUnavailable ? '700' : '500';
                dayBtn.style.boxShadow = isUnavailable ? '0 0 0 1px rgba(245, 158, 11, 0.2)' : 'inset 0 0 0 1px rgba(148, 163, 184, 0.1)';
                dayBtn.style.cursor = isPastDate ? 'not-allowed' : 'pointer';
                dayBtn.disabled = isPastDate;
                dayBtn.addEventListener('click', function () {
                    if (isPastDate) return;
                    const hiddenInput = document.getElementById('unavailableDatesInput');
                    if (!hiddenInput) return;
                    const currentDates = hiddenInput.value.split(',').filter(d => d);
                    if (currentDates.includes(dateValue)) {
                        hiddenInput.value = currentDates.filter(d => d !== dateValue).join(',');
                    } else {
                        hiddenInput.value = [...new Set([...currentDates, dateValue])].join(',');
                    }
                    updateUnavailableDatesList();
                });
                calendarDaysGrid.appendChild(dayBtn);
            }
        }

        document.getElementById('calendarPrevBtnLight')?.addEventListener('click', function() {
            calendarMonthDateLight.setMonth(calendarMonthDateLight.getMonth() - 1);
            updateUnavailableDatesList();
        });

        document.getElementById('calendarNextBtnLight')?.addEventListener('click', function() {
            calendarMonthDateLight.setMonth(calendarMonthDateLight.getMonth() + 1);
            updateUnavailableDatesList();
        });

        // Initialize
        updateUnavailableDatesList();
    </script>
    @endpush
@endsection
