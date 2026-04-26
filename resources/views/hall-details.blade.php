@extends('layouts.app')

@section('title', ($hall->name ?? 'تفاصيل القاعة') . ' | قاعة')
@section('body-class', 'page-hall-details')

@section('content')
    @push('styles')
    <style>
        body.page-hall-details .navbar {
            background: linear-gradient(135deg, #1b365d 0%, #152b4f 100%) !important;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25) !important;
            border-bottom: 1px solid rgba(212, 175, 55, 0.2) !important;
            z-index: 10000 !important;
            backdrop-filter: blur(12px) !important;
        }

        body.page-hall-details .navbar .logo,
        body.page-hall-details .navbar .logo i {
            color: #d4af37 !important;
        }

        body.page-hall-details .navbar .nav-links li a,
        body.page-hall-details .navbar .nav-links li .nav-auth-btn {
            color: rgba(255, 255, 255, 0.92) !important;
        }

        body.page-hall-details .navbar .nav-links li a:hover,
        body.page-hall-details .navbar .nav-links li .nav-auth-btn:hover {
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

        .gallery-grid {
            display: grid;
            grid-template-columns: minmax(0, 2.5fr) minmax(0, 1fr);
            grid-template-rows: repeat(4, minmax(160px, 1fr));
            gap: 16px;
            align-items: stretch;
            min-height: 680px;
        }

        .main-image {
            grid-row: span 4;
            overflow: hidden;
            border-radius: 24px;
            min-width: 0;
            background: #f8fafc;
            min-height: 100%;
        }

        .image-side {
            overflow: hidden;
            border-radius: 24px;
            background: #f8fafc;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .gallery-grid img {
            width: 100%;
            height: 100%;
            display: block;
            cursor: pointer;
            border-radius: 24px;
            background: rgba(0, 0, 0, 0.05);
        }

        .main-image img {
            object-fit: cover;
            object-position: center;
        }

        .image-side img {
            object-fit: cover;
            object-position: center;
            width: 100%;
            height: 100%;
            padding: 0;
            background: transparent;
        }

        .details-section {
            padding: 60px 0 40px;
        }

        .details-wrapper {
            display: grid;
            grid-template-columns: 2.1fr 0.9fr;
            gap: 40px;
            align-items: start;
        }

        .hall-header h1 {
            font-size: clamp(2rem, 4vw, 3rem);
            margin-bottom: 12px;
            line-height: 1.1;
        }

        .hall-header .hall-rating {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        .hall-header .hall-rating span {
            color: var(--text-light);
            font-weight: 700;
        }

        .hall-header .location {
            color: var(--text-muted);
            font-size: 0.98rem;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .quick-actions {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            margin: 20px 0 30px;
        }

        .action-btn {
            padding: 12px 22px;
            border: 1px solid rgba(36, 45, 57, 0.12);
            background: #ffffff;
            border-radius: 50px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
            color: #152b4f;
            font-weight: 700;
            transition: all 0.2s ease;
        }

        .action-btn:hover {
            background: #1b365d;
            color: #ffffff;
            border-color: transparent;
            transform: translateY(-2px);
        }

        .action-btn.active {
            background: #e91e63;
            color: #ffffff;
            border-color: transparent;
        }

        .action-btn.active:hover {
            background: #c2185b;
        }

        .favorites-count {
            margin-left: 5px;
            font-size: 14px;
            color: #666;
        }

        .action-btn.active .favorites-count {
            color: #fff;
        }

        .booking-sidebar {
            position: sticky;
            top: 120px;
        }

        .booking-card {
            overflow: hidden;
        }

        .booking-card .form-group {
            margin-bottom: 18px;
        }

        .booking-card label {
            display: block;
            margin-bottom: 10px;
            font-weight: 700;
            color: #0f172a;
            font-size: 0.95rem;
            letter-spacing: 0.01em;
        }

        .booking-card .form-control {
            width: 100%;
            padding: 14px 16px;
            border-radius: 16px;
            border: 1px solid rgba(36, 45, 57, 0.12);
            background: #f9fafb;
            color: #152b4f;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
        }

        .booking-card .form-control:focus {
            border-color: rgba(59, 130, 246, 0.8);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.12);
            background: #ffffff;
        }

        .booking-card .date-input-wrap {
            position: relative;
        }

        .booking-card .booking-date-input {
            width: 100%;
            padding: 16px 56px 16px 18px;
            border-radius: 20px;
            border: 1px solid rgba(148, 163, 184, 0.3);
            background: #ffffff;
            color: #0f172a;
            font-size: 1rem;
            box-shadow: inset 0 1px 2px rgba(15, 23, 42, 0.06);
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: textfield;
        }

        .booking-card .booking-date-input:focus {
            border-color: rgba(59, 130, 246, 0.9);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.12);
        }

        .booking-card .date-input-icon {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #2563eb;
            pointer-events: none;
            font-size: 1.1rem;
        }

        .custom-calendar {
            margin-top: 18px;
            padding: 18px;
            border-radius: 24px;
            background: #ffffff;
            border: 1px solid rgba(36, 45, 57, 0.08);
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
        }

        .custom-calendar .calendar-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
            gap: 12px;
        }

        .custom-calendar .calendar-title {
            font-weight: 700;
            color: #111827;
            font-size: 0.98rem;
        }

        .custom-calendar .calendar-nav {
            display: flex;
            gap: 8px;
        }

        .custom-calendar .calendar-nav button {
            width: 36px;
            height: 36px;
            border-radius: 14px;
            border: 1px solid rgba(36, 45, 57, 0.12);
            background: #f8fafc;
            color: #1b365d;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .custom-calendar .calendar-nav button:hover:not(:disabled) {
            background: #e2e8f0;
        }

        .custom-calendar .calendar-nav button:disabled {
            background: #f1f5f9;
            color: #94a3b8;
            border-color: rgba(148, 163, 184, 0.4);
            cursor: not-allowed;
            opacity: 0.7;
        }

        .custom-calendar .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, minmax(0, 1fr));
            gap: 8px;
        }

        .custom-calendar .calendar-weekday {
            text-align: center;
            color: #64748b;
            font-size: 0.82rem;
            font-weight: 700;
        }

        .custom-calendar .calendar-day,
        .custom-calendar .calendar-empty {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            min-height: 44px;
            width: 100%;
            border-radius: 14px;
            border: 1px solid transparent;
            background: #f8fafc;
            color: #0f172a;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.15s ease, background-color 0.15s ease, border-color 0.15s ease;
        }

        .custom-calendar .calendar-day:hover:not(.disabled):not(.selected) {
            transform: translateY(-1px);
            background: #eef2ff;
        }

        .custom-calendar .calendar-day.selected {
            background: linear-gradient(135deg, #d4af37 0%, #f59e0b 100%);
            color: #1b365d;
            border-color: transparent;
        }

        .custom-calendar .calendar-day.disabled {
            background: #f1f5f9;
            color: #94a3b8;
            cursor: not-allowed;
            opacity: 0.72;
        }

        .custom-calendar .calendar-day.today {
            border-color: rgba(59, 130, 246, 0.4);
        }

        .custom-calendar .calendar-footer {
            margin-top: 14px;
            padding: 12px 16px;
            background: #f8fafc;
            border-radius: 16px;
            color: #475569;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .custom-calendar .calendar-footer span {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .booking-card .booking-date-input::-webkit-calendar-picker-indicator,
        .booking-card .booking-date-input::-webkit-inner-spin-button,
        .booking-card .booking-date-input::-webkit-clear-button {
            display: none;
            -webkit-appearance: none;
        }

        .booking-card .booking-date-input::-ms-clear,
        .booking-card .booking-date-input::-ms-expand {
            display: none;
        }

        .file-upload {
            display: grid;
            grid-template-columns: auto 1fr;
            align-items: center;
            gap: 14px;
            padding: 16px 18px;
            border-radius: 18px;
            border: 1px dashed rgba(36, 45, 57, 0.18);
            background: #f8fafc;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
        }

        .file-upload:hover {
            background: #ffffff;
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.08);
        }

        .file-upload input[type=file] {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
        }

        .file-upload i {
            font-size: 1.4rem;
            color: #1b365d;
        }

        .file-upload span {
            color: #152b4f;
            font-weight: 700;
        }

        .tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            border-radius: 999px;
            background: rgba(212, 175, 55, 0.12);
            color: #1b365d;
            font-weight: 700;
            font-size: 0.93rem;
        }

        .reviews-section {
            padding: 40px 0;
        }

        .reviews-summary {
            background: #ffffff;
            border-radius: 22px;
            padding: 28px 32px;
            box-shadow: 0 18px 50px rgba(15, 23, 42, 0.06);
            display: flex;
            align-items: center;
            gap: 18px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .rating-summary {
            display: flex;
            align-items: center;
            gap: 18px;
            flex-wrap: wrap;
        }

        .rating-number {
            font-size: 2.8rem;
            font-weight: 800;
            color: #152b4f;
        }

        .rating-number span {
            font-size: 0.95rem;
            color: #64748b;
            font-weight: 600;
        }

        .rating-stars {
            display: flex;
            flex-direction: row-reverse;
            gap: 6px;
            font-size: 1.2rem;
        }

        .rating-stars i {
            color: #f4c150;
        }

        .review-form {
            background: #ffffff;
            border-radius: 24px;
            padding: 32px;
            margin-bottom: 32px;
            box-shadow: 0 18px 50px rgba(15, 23, 42, 0.06);
        }

        .rating-selector {
            margin-bottom: 20px;
        }

        .rating-selector label {
            display: block;
            margin-bottom: 10px;
            font-weight: 700;
            color: #152b4f;
        }

        .rating-stars label {
            cursor: pointer;
        }

        .rating-stars input {
            display: none;
        }

        .rating-stars i {
            color: #cbd5e1;
            transition: color 0.2s ease;
        }

        .rating-stars input:checked + i,
        .rating-stars input:checked ~ i,
        .rating-stars label:hover ~ label i,
        .rating-stars label:hover i {
            color: #f4c150;
        }

        .review-form textarea {
            width: 100%;
            min-height: 120px;
            padding: 16px;
            border-radius: 18px;
            border: 1px solid rgba(36, 45, 57, 0.12);
            background: #f9fafb;
            color: #152b4f;
            resize: vertical;
        }

        .form-error {
            color: #de2a1e;
            font-size: 0.9rem;
            margin-top: 8px;
        }

        .review-form textarea {
            min-height: 110px;
            border-radius: 18px;
            resize: vertical;
            border: 1px solid rgba(36, 45, 57, 0.12);
            padding: 16px;
            background: #f9fafb;
            color: #152b4f;
        }

        .reviews-list {
            display: grid;
            gap: 18px;
        }

        .review-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 22px;
            padding: 24px;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.05);
        }

        .review-card strong {
            color: #152b4f;
        }

        .review-card p {
            margin: 12px 0 0;
            color: #475569;
            line-height: 1.75;
        }

        .hall-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 18px;
            color: #475569;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .hall-meta span {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            border-radius: 999px;
            background: #f8fafc;
        }

        .hall-meta i {
            color: #1b365d;
        }

        .date-picker-row .date-picker-wrap {
            display: grid;
            gap: 10px;
            grid-template-columns: 1fr auto;
            align-items: center;
        }

        .tags-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin: 14px 0;
        }

        .text-muted {
            color: #64748b;
        }

        .text-center {
            text-align: center;
        }

        .reviews-summary .star-rating {
            display: flex;
            gap: 4px;
        }

        .price-info {
            margin-bottom: 12px;
            font-weight: 700;
            color: #1b365d;
        }

        .related-halls .grid-3 {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 24px;
        }

        .related-card {
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 16px 40px rgba(15, 23, 42, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: block;
            text-decoration: none;
            color: inherit;
        }

        .related-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 24px 72px rgba(15, 23, 42, 0.12);
        }

        .related-card .card-img {
            min-height: 220px;
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .related-card .card-img .hall-status {
            position: absolute;
            top: 18px;
            left: 18px;
            padding: 8px 14px;
            border-radius: 999px;
            background: rgba(27, 54, 93, 0.9);
            color: white;
            font-weight: 700;
            font-size: 0.85rem;
        }

        .related-card .card-body {
            padding: 24px;
        }

        .related-card .card-body h3 {
            margin-bottom: 10px;
            font-size: 1.2rem;
        }

        .related-card .card-body .price-info {
            margin-bottom: 14px;
            color: #1b365d;
            font-weight: 700;
        }

        .related-card .btn-full {
            width: 100%;
            justify-content: center;
        }

        @media (max-width: 1100px) {
            .details-wrapper {
                grid-template-columns: 1fr;
            }

            .related-halls .grid-3 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 760px) {
            .related-halls .grid-3 {
                grid-template-columns: 1fr;
            }

            .details-wrapper {
                gap: 25px;
            }

            .booking-sidebar {
                position: static;
            }
        }
    </style>
    @endpush
    <!-- مسار التنقل (Breadcrumb) -->
    <div class="breadcrumb">
        <div class="container">
            <a href="{{ route('home') }}">الرئيسية</a>
            <i class="fa fa-chevron-left"></i>
            <a href="{{ route('home') }}#featured">قاعات</a>
            <i class="fa fa-chevron-left"></i>
            <span>{{ $hall->name ?? 'تفاصيل القاعة' }}</span>
        </div>
    </div>

    <!-- قسم معرض الصور (Gallery) -->
    <section class="gallery-section">
        <div class="container">
            <div class="gallery-grid">
                @php
                    // الصور من جدول hall_images (إذا موجودة)
                    $galleryImages = [];
                    if (isset($hall->gallery) && is_iterable($hall->gallery)) {
                        $galleryImages = $hall->gallery->pluck('path')->toArray();
                    }

                    // fallback: إذا لم توجد صور في hall_images فنجرب الحقول القديمة
                    if (empty($galleryImages)) {
                        $galleryImages = $hall->images ?? [];
                        if (!is_array($galleryImages) && !empty($galleryImages)) {
                            $galleryImages = json_decode($galleryImages, true) ?: [$galleryImages];
                        }
                    }

                    // إضافة الصورة الرئيسية إلى بداية المعرض دائمًا
                    $mainImageRaw = $hall->main_image ?? null;
                    if (is_array($mainImageRaw)) {
                        $mainImageRaw = $mainImageRaw[0] ?? null;
                    } elseif (is_string($mainImageRaw) && str_starts_with($mainImageRaw, '[')) {
                        $decoded = json_decode($mainImageRaw, true);
                        if (is_array($decoded)) {
                            $mainImageRaw = $decoded[0] ?? null;
                        }
                    }

                    if (!empty($mainImageRaw)) {
                        // إضافة الصورة الرئيسية في البداية إذا لم تكن موجودة بالفعل
                        if (!in_array($mainImageRaw, $galleryImages)) {
                            array_unshift($galleryImages, $mainImageRaw);
                        }
                    }

                    // fallback آخر للـ main_image إذا لم توجد أي صور
                    if (empty($galleryImages) && !empty($mainImageRaw)) {
                        $galleryImages = [$mainImageRaw];
                    }

                    $galleryImages = array_values($galleryImages);

                    $mainImage = $galleryImages[0] ?? $mainImageRaw ?? 'https://images.unsplash.com/photo-1519167758481-83f550bb49b3?auto=format&fit=crop&w=1200&q=80';
                    $sideImages = array_slice($galleryImages, 1, 4);

                    $imageUrl = function ($path) {
                        if (!is_string($path) || $path === '') {
                            return null;
                        }
                        // إذا كان رابط كامل
                        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
                            return $path;
                        }

                        // Normalize local storage paths
                        // - If already begins with /storage/ or storage/, use it directly.
                        // - If it begins with halls/ (as saved by the upload logic), prefix with storage/.
                        // - If it is just a filename, assume it's stored under storage/halls/.
                        if (str_starts_with($path, '/storage/') || str_starts_with($path, 'storage/')) {
                            $storagePath = ltrim($path, '/');
                            if (str_starts_with($storagePath, 'storage/')) {
                                $storagePath = substr($storagePath, strlen('storage/'));
                            }

                            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($storagePath)) {
                                return '/storage-file/' . $storagePath;
                            }
                            return null;
                        }

                        if (!str_contains($path, '/')) {
                            $path = 'halls/' . $path;
                        }

                        $storagePath = ltrim($path, '/');
                        if (str_starts_with($storagePath, 'storage/')) {
                            $storagePath = substr($storagePath, strlen('storage/'));
                        }

                        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($storagePath)) {
                            return '/storage-file/' . $storagePath;
                        }

                        return null;
                    };
                @endphp

                @php
                    $placeholder = 'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?auto=format&fit=crop&w=600&q=80';

                    // Build a stable array of image URLs for the lightbox.
                    $galleryUrls = array_map(function ($img) use ($imageUrl, $placeholder) {
                        $url = $imageUrl($img);
                        return $url ?: $placeholder;
                    }, $galleryImages);

                    if (empty($galleryUrls)) {
                        $galleryUrls = [$placeholder];
                    }

                    $mainImageUrl = $imageUrl($mainImage) ?: $placeholder;
                    $sideImageUrls = array_slice($galleryUrls, 1, 4);
                    while (count($sideImageUrls) < 4) {
                        $sideImageUrls[] = $placeholder;
                    }
                @endphp

                <div class="main-image">
                    <img src="{{ $mainImageUrl }}" alt="{{ $hall->name ?? 'قاعة' }}" data-src="{{ $mainImageUrl }}" onclick="window.openLightbox(this.getAttribute('data-src'))" style="cursor: pointer;" />
                </div>
                @foreach($sideImageUrls as $index => $sideUrl)
                    <div class="image-side">
                        <img src="{{ $sideUrl }}" alt="{{ $hall->name ?? 'قاعة' }} - صورة {{ $index + 1 }}" data-src="{{ $sideUrl }}" onclick="window.openLightbox(this.getAttribute('data-src'))" style="cursor: pointer;" />
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- محتوى التفاصيل -->
    <section class="details-section">
        <div class="container">
            @if(session('success'))
                <div style="margin-bottom: 18px; padding: 14px 18px; background: rgba(76, 175, 80, 0.16); border: 1px solid rgba(76, 175, 80, 0.35); border-radius: 12px; color: #fff; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-check-circle" style="color: #4caf50; font-size: 20px;"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div style="margin-bottom: 18px; padding: 14px 18px; background: rgba(220, 38, 38, 0.16); border: 1px solid rgba(220, 38, 38, 0.35); border-radius: 12px; color: #fff; display: flex; align-items: flex-start; gap: 10px;">
                    <i class="fas fa-exclamation-triangle" style="color: #dc2626; font-size: 20px; margin-top: 2px;"></i>
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            <div class="details-wrapper">
                <!-- العمود الأيمن - المعلومات الرئيسية -->
                <div class="details-main">
                    @php
                        $avgRating = $hall->reviews_avg_rating ? number_format($hall->reviews_avg_rating, 1) : '0.0';
                        $reviewsCount = $hall->reviews_count ?? 0;
                        $starRating = round($hall->reviews_avg_rating ?? 4.3);
                        $totalStars = 5;
                    @endphp

                    <div class="hall-header">
                        <h1>{{ $hall->name ?? 'اسم القاعة' }}</h1>
                        <div class="hall-rating">
                            @for($i = 1; $i <= $totalStars; $i++)
                                @if($i <= $starRating)
                                    <i class="fa fa-star"></i>
                                @else
                                    <i class="fa fa-star" style="opacity: 0.35;"></i>
                                @endif
                            @endfor
                            <span>{{ $avgRating }} · {{ $reviewsCount }} تقييم</span>
                        </div>
                        <p class="location">
                            <i class="fa fa-map-pin"></i> {{ $hall->location ?? 'الموقع غير محدد' }}
                        </p>
                        <div class="hall-meta">
                            <span><i class="fa fa-users"></i> {{ $hall->capacity ?? 'غير محددة' }} ضيف</span>
                            <span><i class="fa fa-tag"></i> {{ $hall->category ?? 'قاعة فاخرة' }}</span>
                            <span><i class="fa fa-calendar-check"></i> {{ $hall->status ?? 'متاحة' }}</span>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    @if(!auth()->check() || auth()->user()->role !== 'owner')
                    <div class="quick-actions">
                        <button class="action-btn" id="favoriteBtn" title="أضف للمفضلة">
                            <i class="fa fa-heart"></i> أضف للمفضلة
                            <span class="favorites-count">({{ $hall->favorites_count }})</span>
                        </button>
                        <button class="action-btn" onclick="shareHall()" title="مشاركة">
                            <i class="fa fa-share-alt"></i> مشاركة
                        </button>
                        <button class="action-btn" onclick="window.print()" title="طباعة">
                            <i class="fa fa-print"></i> طباعة
                        </button>
                    </div>
                    @endif

                    <!-- الوصف -->
                    <div class="info-card">
                        <h2>وصف القاعة</h2>
                        <p>
                            {{ $hall->description ?: 'لا يوجد وصف مفصّل لهذه القاعة بعد. يمكنك التواصل مع المالك للحصول على معلومات أكثر.' }}
                        </p>
                    </div>

                    <!-- الخدمات والمميزات -->
                    <div class="info-card">
                        <h2>الخدمات والمميزات</h2>
                        <div class="features-grid">
                            @if(!empty($hall->features) && is_array($hall->features) && count($hall->features))
                                @foreach($hall->features as $feature)
                                    <div class="feature-item">
                                        <i class="fa fa-check"></i>
                                        <span>{{ $feature }}</span>
                                    </div>
                                @endforeach
                            @else
                                <div class="feature-item">
                                    <i class="fa fa-wifi"></i>
                                    <span>واي فاي مجاني</span>
                                </div>
                                <div class="feature-item">
                                    <i class="fa fa-car"></i>
                                    <span>مواقف سيارات واسعة</span>
                                </div>
                                <div class="feature-item">
                                    <i class="fa fa-utensils"></i>
                                    <span>بوفيه مفتوح</span>
                                </div>
                                <div class="feature-item">
                                    <i class="fa fa-music"></i>
                                    <span>دي جي وموسيقى</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- العمود الأيسر - الحجز والأسعار -->
                <div class="booking-sidebar">
                    @if(empty($isOwner))
                        <div class="booking-card">
                            @php
                                $price = $hall->price ?? 0;
                                $minPrice = $hall->min_price ?? $price;
                                $maxPrice = $hall->max_price ?? $price;
                                $priceLabel = number_format((float)$price);
                                $avgPrice = number_format((float)((float)$minPrice + (float)$maxPrice) / 2);
                            @endphp

                            <div class="price-tag">
                                <span class="price">{{ $priceLabel }} ج.م</span>
                                <span class="price-note">للحجز (ليلة واحدة)</span>
                            </div>

                            <div class="price-breakdown">
                                <h4>تفاصيل الأسعار</h4>
                                <div class="price-row">
                                    <span>أقل سعر</span>
                                    <span>{{ number_format((float)$minPrice) }} ج.م</span>
                                </div>
                                <div class="price-row highlight">
                                    <span>أعلى سعر</span>
                                    <span>{{ number_format((float)$maxPrice) }} ج.م</span>
                                </div>
                                <div class="price-row">
                                    <span>متوسط السعر</span>
                                    <span>{{ $avgPrice }} ج.م</span>
                                </div>
                            </div>

                            <div class="hall-specs-vertical">
                                <div class="spec-item">
                                    <i class="fa fa-users"></i>
                                    <div>
                                        <strong>السعة</strong>
                                        <span>{{ $hall->capacity ?? 'غير محددة' }} ضيف</span>
                                    </div>
                                </div>
                                <div class="spec-item">
                                    <i class="fa fa-clock"></i>
                                    <div>
                                        <strong>مدة الحجز</strong>
                                        <span>6 ساعات (قابل للتمديد)</span>
                                    </div>
                                </div>
                                <div class="spec-item">
                                    <i class="fa fa-calendar-alt"></i>
                                    <div>
                                        <strong>التوفر</strong>
                                        <span class="available">{{ $hall->status ?? 'متاح اليوم' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group date-picker-group">
                                <label>تاريخ الحجز</label>
                                <div class="date-input-wrap">
                                    <input
                                        type="date"
                                        id="bookingDateInput"
                                        class="form-control booking-date-input"
                                        required
                                    />
                                    <span class="date-input-icon"><i class="fa fa-calendar-alt"></i></span>
                                </div>
                                <small class="text-muted">اختر التاريخ المناسب للحجز.</small>
                            </div>
                            <div class="custom-calendar" id="bookingCalendar"></div>

                            @auth
                                @if(Auth::user()->role === 'customer')
                                    <button type="button" id="bookNowButton" class="btn btn-primary btn-large book-now" onclick="openModal()">
                                        احجز الآن
                                    </button>
                                @else
                                    <button type="button" class="btn btn-secondary btn-large" disabled>
                                        غير مسموح للحجز لحساب المالك
                                    </button>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn btn-primary-navy btn-large">
                                    سجل دخول ثم احجز
                                </a>
                            @endauth

                            <div class="secure-badge">
                                <i class="fa fa-lock"></i>
                                <span>حجز آمن وموثق | الدفع عند التأكيد</span>
                            </div>
                        </div>
                    @else
                        <div class="booking-card" style="text-align: center; padding: 30px;">
                            <h3 style="margin-top: 0;">لوحة تحكم القاعة</h3>
                            <p style="margin: 16px 0 24px; color: #475569;">
                                يمكنك تعديل معلومات القاعة وإدارة التوافر والحجوزات.
                            </p>
                            <a href="{{ route('owner.halls.edit', $hall) }}" class="btn btn-primary-navy btn-large">
                                تعديل القاعة
                            </a>
                        </div>
                    @endif

                    @auth
                        @if(!empty($isOwner))
                            @php
                                $unavailableDates = $hall->unavailable_dates;
                                if (!is_array($unavailableDates)) {
                                    $decodedUnavailableDates = json_decode($unavailableDates, true);
                                    if (is_array($decodedUnavailableDates)) {
                                        $unavailableDates = $decodedUnavailableDates;
                                    } elseif (is_string($unavailableDates)) {
                                        $unavailableDates = array_filter(array_map('trim', explode(',', $unavailableDates)));
                                    } else {
                                        $unavailableDates = [];
                                    }
                                }
                                if (!is_array($unavailableDates)) {
                                    $unavailableDates = [];
                                }
                            @endphp

                            <div class="info-card">
                                <h3>
                                    <i class="fa fa-calendar-times"></i> إغلاق مواعيد
                                </h3>

                                <form id="unavailableDatesForm" method="POST" action="{{ route('owner.halls.update', $hall) }}">
                                    @csrf
                                    @method('PUT')
                                    <input
                                        type="hidden"
                                        name="unavailable_dates"
                                        id="unavailableDatesInput"
                                        value="{{ implode(',', $unavailableDates) }}"
                                    />

                                    <!-- <div class="form-group date-picker-row">
                                        <label>اختر تاريخاً غير متاح</label>
                                        <div class="date-picker-wrap">
                                            <input type="date" id="unavailableDatePicker" class="form-control" />
                                            <button type="button" class="btn btn-secondary" id="addUnavailableDateBtn">إضافة</button>
                                        </div>
                                    </div> -->

                                    <div id="unavailableDatesList" class="tags-list"></div>

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

                                    <button type="submit" class="btn btn-primary-navy btn-large">حفظ التواريخ</button>
                                </form>
                            </div>
                        @endif
                    @endauth

                    <div class="info-card map-card">
                        <h3>
                            <i class="fa fa-map-marked-alt"></i> موقع القاعة
                        </h3>
                        <div class="map-placeholder">
                            <img
                                src="https://maps.googleapis.com/maps/api/staticmap?center=31.0437,31.3747&zoom=14&size=300x200&markers=color:red%7C31.0437,31.3747&key=YOUR_API_KEY"
                                alt="خريطة الموقع"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </section>

        @php
            $reviews = $hall->reviews ?? collect();
            $reviewsCount = $reviews->count();
            $avgRating = $reviewsCount ? round($reviews->avg('rating'), 1) : null;
        @endphp

        <section class="section reviews-section">
            <div class="container">
                <div class="section-header">
                    <h2>تقييمات العملاء</h2>
                    <p>
                        @if($reviewsCount)
                            عدد التقييمات: {{ $reviewsCount }}
                        @else
                            لا توجد تقييمات بعد. كن أول من يقيم!
                        @endif
                    </p>
                </div>

                <div class="reviews-summary">
                    <div class="rating-summary">
                        <div class="rating-number">
                            {{ $avgRating ?? '--' }}
                            <span>/ 5</span>
                        </div>
                        <div class="star-rating">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fa fa-star" style="color: {{ $avgRating >= $i ? '#f4c150' : 'rgba(0,0,0,0.12)' }}"></i>
                            @endfor
                        </div>
                    </div>
                </div>

                @auth
                    @if(auth()->user()->role === 'customer')
                    <div class="review-form">
                        <form method="POST" action="{{ route('halls.reviews.store', $hall) }}">
                            @csrf
                            <div class="rating-selector">
                                <label>قيم القاعة</label>
                                <div class="rating-stars">
                                    @for($star = 5; $star >= 1; $star--)
                                        <label>
                                            <input type="radio" name="rating" value="{{ $star }}" {{ old('rating') == $star ? 'checked' : '' }} />
                                            <i class="fa fa-star" data-star="{{ $star }}"></i>
                                        </label>
                                    @endfor
                                </div>
                                @error('rating')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <textarea name="comment" placeholder="اكتب تعليقك (اختياري)"></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary btn-large">إرسال التقييم</button>
                        </form>
                    </div>
                    @else
                    <div style="margin: 32px 0; padding: 20px; background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.2); border-radius: 12px; color: #3b82f6; text-align: center;">
                        <i class="fas fa-info-circle"></i> كمالك قاعة، لا يمكنك تقييم قاعتك الخاصة.
                    </div>
                    @endif
                @endauth

                <div class="reviews-list">
                    @forelse($reviews->sortByDesc('created_at') as $review)
                        <div class="review-card" style="margin-bottom: 14px; padding: 14px; border-radius: 14px; background: rgba(255,255,255,0.08);">
                            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 6px;">
                                <strong>{{ $review->user->name ?? 'مستخدم' }}</strong>
                                <span style="color: #f4c150;">
                                    @for($i=1; $i<=5; $i++)
                                        <i class="fa fa-star{{ $review->rating >= $i ? '' : '-o' }}"></i>
                                    @endfor
                                </span>
                            </div>
                            @if($review->comment)
                                <p style="margin:0; color: #475569;">{{ $review->comment }}</p>
                            @else
                                <p style="margin:0; color: #64748b;">(بدون تعليق)</p>
                            @endif
                            <div style="margin-top: 8px; color: rgba(255,255,255,0.6); font-size: 0.85rem;">
                                {{ $review->created_at->diffForHumans() }}
                            </div>
                        </div>
                    @empty
                        <p style="color: rgba(255,255,255,0.8);">لا يوجد تقييمات حتى الآن.</p>
                    @endforelse
                </div>

            </div>
        </section>

        @unless($isOwner)
        <!-- قاعات مشابهة (Related Halls) -->
        <section class="section related-halls">
            <div class="container">
                <div class="section-header">
                    <h2>قاعات مشابهة قد تعجبك</h2>
                    <p>اختر من بين قاعات أخرى بنفس المستوى والجودة.</p>
                </div>

                @if($relatedHalls->isNotEmpty())
                    <div class="grid-3">
                        @foreach($relatedHalls as $related)
                            @php
                                $relatedImage = $related->main_image_url;
                                $relatedPrice = number_format($related->price ?? 0);
                            @endphp
                            <a href="{{ route('halls.show', $related) }}" class="related-card">
                                <div class="card-img" style="background-image:url('{{ $relatedImage }}');">
                                    <div class="hall-status">{{ $related->status ?: 'متاحة' }}</div>
                                </div>
                                <div class="card-body">
                                    <div class="price-info">تبدأ من <span>{{ $relatedPrice }} ج.م</span></div>
                                    <h3>{{ $related->name }}</h3>
                                    <p class="location"><i class="fa fa-map-pin"></i> {{ $related->location }}</p>
                                    <div class="btn btn-full btn-primary">عرض التفاصيل</div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-center">لا توجد قاعات مشابهة في الوقت الحالي. تصفح القاعات الأخرى للاطلاع على المزيد من العروض.</p>
                @endif
            </div>
        </section>
        @endunless

        <footer id="contact">
            <div class="container footer-content">
                <div class="footer-brand">
                    <div class="logo">قاعة</div>
                    <p>
                        الخيار الأول لحجز وتنظيم القاعات في الشرق الأوسط. نضمن
                        لك السهولة الشفافية والأمان في كل حجز.
                    </p>
                </div>

                <div class="footer-contact">
                    <h5>تواصل معنا</h5>
                    <p><i class="fa fa-phone"></i> 01552585217</p>
                    <p><i class="fa fa-envelope"></i> karim2elshazly@gmail.com</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>جميع الحقوق محفوظة &copy; 2026 Karim Elshazly</p>
            </div>
        </footer>

        <div id="bookingModal" class="modal booking-modal">
            <div class="modal-content booking-modal-content">
                <!-- Progress Steps -->
                <div class="booking-steps">
                    <div class="step active" data-step="1">
                        <div class="step-number">1</div>
                        <div class="step-title">البيانات الشخصية</div>
                    </div>
                    <div class="step-connector"></div>
                    <div class="step" data-step="2">
                        <div class="step-number">2</div>
                        <div class="step-title">الوثائق والتأكيد</div>
                    </div>
                </div>

                <div class="modal-header">
                    <h3 id="modalTitle">إتمام بيانات حجز القاعة</h3>
                    <span class="close-modal">&times;</span>
                </div>

                <form id="bookingForm" class="booking-form" action="{{ route('bookings.store') }}" method="POST" enctype="multipart/form-data" data-native-booking="true">
                    @csrf
                    <input type="hidden" id="hallId" name="hall_id" value="{{ $hall->id ?? 1 }}" />
                    <input type="hidden" id="bookingDate" name="booking_date" value="" />

                    <!-- Step 1: Personal Information -->
                    <div class="booking-step active" id="step1">
                        <div class="step-header">
                            <i class="fa fa-user-circle"></i>
                            <h4>البيانات الشخصية</h4>
                            <p>يرجى إدخال بياناتك الشخصية بدقة</p>
                        </div>

                        <div class="input-row">
                            <div class="form-group">
                                <label for="userName">
                                    <i class="fa fa-user"></i>
                                    الاسم الكامل (كما في البطاقة)
                                </label>
                                <input
                                    type="text"
                                    id="userName"
                                    name="userName"
                                    required
                                    placeholder="أدخل اسمك الرباعي"
                                    autocomplete="name"
                                />
                                <div class="input-hint">يجب أن يطابق الاسم في البطاقة الشخصية</div>
                            </div>
                            <div class="form-group">
                                <label for="userId">
                                    <i class="fa fa-id-card"></i>
                                    الرقم القومي (14 رقم)
                                </label>
                                <input
                                    type="text"
                                    id="userId"
                                    name="userId"
                                    maxlength="14"
                                    required
                                    placeholder="00000000000000"
                                    pattern="[0-9]{14}"
                                    autocomplete="off"
                                />
                                <div class="input-hint">14 رقم فقط، بدون مسافات</div>
                            </div>
                        </div>

                        <div class="input-row">
                            <div class="form-group">
                                <label for="userPhone">
                                    <i class="fa fa-phone"></i>
                                    رقم الهاتف
                                </label>
                                <input
                                    type="tel"
                                    id="userPhone"
                                    name="userPhone"
                                    required
                                    placeholder="01xxxxxxxxx"
                                    pattern="[0-9]{11}"
                                    autocomplete="tel"
                                />
                                <div class="input-hint">رقم هاتف محمول صالح</div>
                            </div>
                            <div class="form-group">
                                <label for="userEmail">
                                    <i class="fa fa-envelope"></i>
                                    البريد الإلكتروني
                                </label>
                                <input
                                    type="email"
                                    id="userEmail"
                                    name="userEmail"
                                    required
                                    placeholder="example@email.com"
                                    autocomplete="email"
                                />
                                <div class="input-hint">لتلقي تأكيد الحجز</div>
                            </div>
                        </div>

                        <div class="selected-date-info">
                            <div class="date-display">
                                <i class="fa fa-calendar-check"></i>
                                <div>
                                    <strong>التاريخ المحدد:</strong>
                                    <span id="selectedDateText">لم يتم الاختيار</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Documents -->
                    <div class="booking-step" id="step2">
                        <div class="step-header">
                            <i class="fa fa-file-upload"></i>
                            <h4>رفع الوثائق</h4>
                            <p>يرجى رفع الصور المطلوبة بوضوح</p>
                        </div>

                        <div class="documents-grid">
                            <div class="form-group">
                            <label for="idCardImage">
                                <i class="fa fa-id-card"></i>
                                صورة البطاقة الشخصية
                            </label>
                            <div class="file-upload enhanced">
                                <input
                                    type="file"
                                    id="idCardImage"
                                    name="idCardImage"
                                        required
                                    />
                                    <div class="upload-area">
                                        <i class="fa fa-cloud-upload-alt"></i>
                                        <span class="upload-text">اضغط لرفع صورة البطاقة</span>
                                        <span class="upload-subtext">وجه وظهر البطاقة بوضوح</span>
                                    </div>
                                    <div class="file-preview"></div>
                                </div>
                                <div class="file-requirements">
                                    <small>
                                        <i class="fa fa-info-circle"></i>
                                        الصورة يجب أن تكون واضحة وتشمل الوجه والظهر
                                    </small>
                                </div>
                            </div>

                        </div>

                        <div class="terms-agreement">
                            <label class="checkbox-container">
                                <input type="checkbox" id="termsAgreement" required />
                                <span class="checkmark"></span>
                                أوافق على <a href="#" target="_blank">الشروط والأحكام</a> و <a href="#" target="_blank">سياسة الخصوصية</a> و <a href="#" target="_blank">سياسة الاسترداد</a>
                            </label>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-prev" style="display: none;">
                            <i class="fa fa-arrow-right"></i>
                            السابق
                        </button>
                        <button type="button" class="btn btn-primary btn-next">
                            التالي
                            <i class="fa fa-arrow-left"></i>
                        </button>
                        <button type="submit" class="btn btn-success btn-book" style="display: none;">
                            <i class="fa fa-check-circle"></i>
                            احجز الآن
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </style>

    <script>
        console.log('🚀 Lightbox script loaded');

        // Initialize immediately
        let imageUrls = [];
        try {
            imageUrls = @json($galleryUrls);
            console.log('Image URLs loaded:', imageUrls);
        } catch (error) {
            console.error('❌ Error loading image URLs:', error);
            imageUrls = [];
        }
        window.imageUrls = imageUrls;

        // Simple Lightbox Handler - محسّن
        let currentLightboxIndex = 0;
        let currentLightboxZoom = 1;
        const minLightboxZoom = 1;
        const maxLightboxZoom = 3;
        const lightboxZoomStep = 0.25;
        const lightboxAutoHideDelay = 3000;
        let toolbarHideTimeout;

        // Drag variables
        let isDragging = false;
        let startX, startY, dragX = 0, dragY = 0;

        function resetLightboxAutoHide() {
            const toolbar = document.getElementById('lightboxToolbar');
            const header = document.querySelector('.lightbox-header');
            const navButtons = document.querySelectorAll('.lightbox-nav');

            if (toolbar) {
                toolbar.classList.remove('auto-hide');
                toolbar.classList.remove('hidden');
            }
            if (header) {
                header.classList.remove('auto-hide');
            }
            navButtons.forEach(btn => {
                btn.classList.remove('auto-hide');
            });

            clearTimeout(toolbarHideTimeout);
            toolbarHideTimeout = setTimeout(() => {
                if (toolbar) {
                    toolbar.classList.add('auto-hide');
                }
                if (header) {
                    header.classList.add('auto-hide');
                }
                navButtons.forEach(btn => {
                    btn.classList.add('auto-hide');
                });
            }, lightboxAutoHideDelay);
        }

                function updateLightboxImage() {
                    console.log('🔄 updateLightboxImage called');

                    const img = document.getElementById('lightboxImage');
                    console.log('Lightbox image element:', img);

                    if (!img) {
                        console.error('❌ Lightbox image element not found');
                        return;
                    }

                    if (!Array.isArray(window.imageUrls) || !window.imageUrls.length) {
                        console.warn('⚠️ updateLightboxImage: no imageUrls available');
                        console.log('window.imageUrls:', window.imageUrls);
                        img.src = '';
                    } else {
                        console.log('✅ Using imageUrls:', window.imageUrls);
                        img.src = window.imageUrls[currentLightboxIndex] || window.imageUrls[0] || '';
                        console.log('Setting image src to:', img.src);
                    }

                    // Reset drag position
                    dragX = 0;
                    dragY = 0;
                    img.style.transform = `translate(-50%, -50%) scale(${currentLightboxZoom})`;
                    
                    // Update cursor - always show grab hand for dragging
                    img.style.cursor = 'grab';
                    
                    // تحديث عرض رقم الصورة الحالية
                    const currentImageNum = document.getElementById('currentImageNum');
                    const zoomPercentage = document.getElementById('zoomPercentage');
                    const zoomLevel = document.getElementById('zoomLevel');

                    if (currentImageNum) {
                        currentImageNum.textContent = currentLightboxIndex + 1;
                    }
                    if (zoomPercentage) {
                        zoomPercentage.textContent = Math.round(currentLightboxZoom * 100) + '%';
                    }
                    if (zoomLevel) {
                        zoomLevel.textContent = Math.round(currentLightboxZoom * 100) + '%';
                    }
                    
                    // تحديث المعاينات النشطة
                    updateActiveThumbnail();
                }

                window.openLightbox = function openLightbox(src) {
                    console.log('🚀 openLightbox called with src:', src);

                    if (!src) {
                        console.error('❌ openLightbox called without a valid src');
                        return;
                    }

                    console.log('🔄 Opening Lightbox with src:', src);

                    // Scroll to top when opening lightbox
                    window.scrollTo(0, 0);

                    if (!window.imageUrls || !Array.isArray(window.imageUrls) || !window.imageUrls.length) {
                        console.warn('⚠️ imageUrls is empty or unavailable, using clicked image src as fallback');
                        window.imageUrls = src ? [src] : [];
                    }

                    const images = Array.isArray(window.imageUrls) && window.imageUrls.length ? window.imageUrls : (Array.isArray(imageUrls) ? imageUrls : []);
                    currentLightboxIndex = images.indexOf(src);
                    if (currentLightboxIndex < 0) {
                        currentLightboxIndex = 0;
                    }
                    currentLightboxZoom = 1;
                    
                    const modal = document.getElementById('lightboxModal');
                    if (!modal) {
                        console.error('❌ Modal element not found');
                        return;
                    }
                    
                    // تحديث عدد الصور الكلي
                    const totalImagesNum = document.getElementById('totalImagesNum');
                    if (totalImagesNum) {
                        totalImagesNum.textContent = window.imageUrls.length;
                    }
                    
                    // إنشاء المعاينات
                    createThumbnails();
                    
                    // إظهار التول بار (قد يكون في وضع الإخفاء من الفتح السابق)
                    const toolbar = document.getElementById('lightboxToolbar');
                    const header = document.querySelector('.lightbox-header');
                    const navButtons = document.querySelectorAll('.lightbox-nav');

                    // إزالة class auto-hide من كل العناصر عشان تظهر
                    if (toolbar) {
                        toolbar.classList.remove('auto-hide');
                        toolbar.classList.remove('hidden');
                    }
                    if (header) {
                        header.classList.remove('auto-hide');
                    }
                    navButtons.forEach(btn => {
                        btn.classList.remove('auto-hide');
                    });

                    // تفعيل مهلة الإخفاء التلقائي وإعادة ضبطها عند الحاجة
                    resetLightboxAutoHide();
                    
                    // إضافة منطق إخفاء/إظهار التولبار عند تحريك الماوس
                    if (modal && !modal.dataset.lightboxListenersAttached) {
                        modal.dataset.lightboxListenersAttached = 'true';

                        modal.addEventListener('mousemove', function(e) {
                            // عند تحريك الماوس، نعيد تفعيل مهلة الإخفاء
                            resetLightboxAutoHide();
                        });
                        
                        modal.addEventListener('click', function(e) {
                            if (e.target.closest('.lightbox-nav') ||
                                e.target.closest('.lightbox-toolbar') ||
                                e.target.closest('.lightbox-header') ||
                                e.target.closest('#thumbnailsPanel') ||
                                e.target.closest('#thumbnailsBtn') ||
                                e.target.closest('.toolbar-btn') ||
                                e.target.closest('.close-modal')) {
                                resetLightboxAutoHide();
                            }
                        });

                        modal.addEventListener('mouseenter', function() {
                            const toolbar = document.getElementById('lightboxToolbar');
                            if (toolbar) {
                                toolbar.classList.remove('hidden');
                            }
                        });
                        
                        modal.addEventListener('mouseleave', function() {
                            const toolbar = document.getElementById('lightboxToolbar');
                            if (toolbar) {
                                toolbar.classList.add('hidden');
                            }
                        });
                    }
                    
                    modal.classList.add('active');
                    modal.style.display = 'flex';
                    modal.style.visibility = 'visible';
                    modal.style.opacity = '1';
                    modal.style.background = 'rgba(0, 0, 0, 0.96)';
                    document.body.style.overflow = 'hidden';
                    document.documentElement.style.overflow = 'hidden';
                    document.body.classList.add('lightbox-open');

                    console.log('✅ Modal should be visible now');
                    updateLightboxImage();

                    // تطبيق event listeners للإغلاق عند النقر على المناطق الفارغة
                    setTimeout(() => {
                        const imageWrapper = modal.querySelector('.lightbox-image-wrapper');
                        const backdrop = modal.querySelector('.lightbox-backdrop');
                        const img = modal.querySelector('#lightboxImage');

                        if (imageWrapper) {
                            imageWrapper.addEventListener('click', function(e) {
                                if (e.target !== img && !e.target.closest('.lightbox-nav') && !e.target.closest('.lightbox-toolbar')) {
                                    closeLightbox();
                                }
                            });
                        }

                        if (backdrop) {
                            backdrop.addEventListener('click', closeLightbox);
                        }

                        if (img) {
                            img.addEventListener('click', function(e) {
                                e.stopPropagation();
                            });
                        }
                    }, 0);
                }

                function createThumbnails() {
                    const container = document.getElementById('fullThumbnailsContainer');
                    if (!container) {
                        return;
                    }
                    container.innerHTML = '';
                    
                    imageUrls.forEach((url, index) => {
                        const thumbnail = document.createElement('img');
                        thumbnail.src = url;
                        thumbnail.className = 'lightbox-thumbnail';
                        thumbnail.alt = `صورة ${index + 1}`;
                        thumbnail.style.cursor = 'pointer';
                        thumbnail.onclick = () => {
                            currentLightboxIndex = index;
                            currentLightboxZoom = 1;
                            updateLightboxImage();
                        scheduleThumbnailsAutoClose();
                        }
                        
                        container.appendChild(thumbnail);
                    });
                }

                function updateActiveThumbnail() {
                    const thumbnails = document.querySelectorAll('.lightbox-thumbnail');
                    thumbnails.forEach((thumb, index) => {
                        if (index === currentLightboxIndex) {
                            thumb.classList.add('active');
                        } else {
                            thumb.classList.remove('active');
                        }
                    });
                }

                function showPreviousLightbox() {
                    if (!window.imageUrls.length) return;
                    currentLightboxIndex = (currentLightboxIndex - 1 + window.imageUrls.length) % window.imageUrls.length;
                    currentLightboxZoom = 1;
                    updateLightboxImage();
                }

                function showNextLightbox() {
                    if (!window.imageUrls.length) return;
                    currentLightboxIndex = (currentLightboxIndex + 1) % window.imageUrls.length;
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
                    dragX = 0;
                    dragY = 0;
                    updateLightboxImage();
                }

                function getLightboxIcon(iconName) {
                    const icons = {
                        spinner: '<svg class="spinner-icon" width="20" height="20" viewBox="0 0 50 50" aria-hidden="true" focusable="false"><circle cx="25" cy="25" r="20" fill="none" stroke="currentColor" stroke-width="5" stroke-linecap="round" stroke-dasharray="90 150" transform="rotate(-90 25 25)"/></svg>',
                        download: '<svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M12 3v11" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M8 11l4 4 4-4" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/><path d="M6 19h12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>'
                    };
                    return icons[iconName] || '';
                }

                function downloadCurrentImage() {
                    const currentImageUrl = window.imageUrls[currentLightboxIndex];
                    if (!currentImageUrl) {
                        console.error('❌ No image URL available for download');
                        return;
                    }

                    const downloadBtn = document.getElementById('downloadBtn');
                    if (downloadBtn) {
                        downloadBtn.disabled = true;
                        downloadBtn.innerHTML = getLightboxIcon('spinner');
                    }

                    const urlWithoutQuery = currentImageUrl.split('?')[0];
                    const urlParts = urlWithoutQuery.split('/');
                    let filename = urlParts[urlParts.length - 1] || ('hall-image-' + (currentLightboxIndex + 1) + '.jpg');
                    if (!filename.includes('.')) {
                        filename += '.jpg';
                    }

                    // Use XMLHttpRequest instead of fetch for better compatibility
                    const xhr = new XMLHttpRequest();
                    xhr.open('GET', currentImageUrl, true);
                    xhr.responseType = 'blob';

                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            const blob = xhr.response;
                            const blobUrl = URL.createObjectURL(blob);
                            const link = document.createElement('a');
                            link.style.display = 'none';
                            link.href = blobUrl;
                            link.download = filename;
                            link.target = '_blank';
                            link.rel = 'noopener';
                            document.body.appendChild(link);
                            dispatchDownloadLink(link);

                            setTimeout(() => {
                                if (downloadBtn) {
                                    downloadBtn.disabled = false;
                                    downloadBtn.innerHTML = getLightboxIcon('download');
                                    console.log('✅ Image downloaded: ' + filename);
                                }
                                document.body.removeChild(link);
                                URL.revokeObjectURL(blobUrl);
                            }, 1500);
                        } else {
                            console.error('❌ Download failed with status:', xhr.status);
                            if (downloadBtn) {
                                downloadBtn.disabled = false;
                                downloadBtn.innerHTML = getLightboxIcon('download');
                            }
                        }
                    };

                    xhr.onerror = function() {
                        console.error('❌ XHR Download failed, falling back to direct download');
                        attemptSimpleDownload(currentImageUrl, filename, downloadBtn);
                    };

                    xhr.send();
                }

                function dispatchDownloadLink(link) {
                    try {
                        link.click();
                    } catch (error) {
                        if (typeof MouseEvent === 'function') {
                            const event = new MouseEvent('click', { view: window, bubbles: true, cancelable: true });
                            link.dispatchEvent(event);
                        } else if (typeof link.click === 'function') {
                            link.click();
                        }
                    }
                }

                function attemptSimpleDownload(url, filename, downloadBtn) {
                    const link = document.createElement('a');
                    link.href = url;
                    link.download = filename;
                    link.target = '_blank';
                    link.rel = 'noopener';
                    link.style.display = 'none';
                    document.body.appendChild(link);
                    dispatchDownloadLink(link);
                    setTimeout(() => {
                        document.body.removeChild(link);
                        if (downloadBtn) {
                            downloadBtn.disabled = false;
                            downloadBtn.innerHTML = getLightboxIcon('download');
                            console.log('✅ Download attempt: ' + filename);
                        }
                    }, 1500);
                }

                function closeLightbox() {
                    const modal = document.getElementById('lightboxModal');
                    if (modal) {
                        modal.classList.remove('active');
                        modal.style.display = '';
                        modal.style.visibility = '';
                        modal.style.opacity = '';
                        document.body.style.overflow = 'auto';
                        document.documentElement.style.overflow = '';
                        document.body.classList.remove('lightbox-open');
                    }
                    const thumbnailsPanel = document.getElementById('thumbnailsPanel');
                    if (thumbnailsPanel) {
                        thumbnailsPanel.style.display = 'none';
                    }
                }

                window.closeLightbox = closeLightbox;
                window.toggleThumbnails = toggleThumbnails;
                window.toggleThumbnailsBar = toggleThumbnailsBar;
                window.showPreviousLightbox = showPreviousLightbox;
                window.showNextLightbox = showNextLightbox;
                window.zoomInLightbox = zoomInLightbox;
                window.zoomOutLightbox = zoomOutLightbox;
                window.resetLightboxZoom = resetLightboxZoom;
                window.downloadCurrentImage = downloadCurrentImage;

                // Add click listeners to gallery images
                function setupLightboxListeners() {
                    console.log('🔄 Setting up lightbox event listeners...');

                    // Main image
                    const mainImage = document.querySelector('.main-image img');
                    console.log('Main image found:', mainImage);
                    if (mainImage) {
                        mainImage.addEventListener('click', function() {
                            console.log('Main image clicked, src:', mainImage.getAttribute('data-src'));
                            window.openLightbox(mainImage.getAttribute('data-src'));
                        });
                        console.log('✅ Main image listener added');
                    }

                    // Side images
                    const sideImages = document.querySelectorAll('.image-side img');
                    console.log('Side images found:', sideImages.length);
                    sideImages.forEach(function(img, index) {
                        img.addEventListener('click', function() {
                            console.log('Side image clicked:', index, 'src:', img.getAttribute('data-src'));
                            window.openLightbox(img.getAttribute('data-src'));
                        });
                    });
                    console.log('✅ Side images listeners added');
                }

                // Setup listeners immediately and on DOMContentLoaded
                setupLightboxListeners();
                document.addEventListener('DOMContentLoaded', setupLightboxListeners);

                // Also setup after a short delay in case images load later
                setTimeout(setupLightboxListeners, 1000);

                function toggleThumbnailsBar() {
                    const group = document.getElementById('thumbnailsGroup');
                    const btn = document.getElementById('thumbBarToggleBtn');
                    if (group) {
                        const hidden = group.classList.toggle('hidden-thumbnails');
                        if (btn) {
                            btn.innerHTML = hidden ? '<i class="fas fa-expand"></i>' : '<i class="fas fa-compress"></i>';
                            btn.title = hidden ? 'عرض المعاينات' : 'إخفاء المعاينات';
                        }
                    }
                }

                let thumbnailsAutoCloseTimeout;

                function scheduleThumbnailsAutoClose() {
                    const panel = document.getElementById('thumbnailsPanel');
                    if (!panel || panel.style.display !== 'block') {
                        return;
                    }
                    clearTimeout(thumbnailsAutoCloseTimeout);
                    thumbnailsAutoCloseTimeout = setTimeout(() => {
                        panel.style.display = 'none';
                    }, 2500);
                }

                function toggleThumbnails() {
                    const panel = document.getElementById('thumbnailsPanel');
                    if (!panel) return;
                    if (panel.style.display === 'block') {
                        panel.style.display = 'none';
                        clearTimeout(thumbnailsAutoCloseTimeout);
                    } else {
                        panel.style.display = 'block';
                        scheduleThumbnailsAutoClose();
                    }
                }

                // إغلاق المعاينات عند الضغط خارجها
                document.addEventListener('click', function(e) {
                    const panel = document.getElementById('thumbnailsPanel');
                    const thumbnailsBtn = document.getElementById('thumbnailsBtn');
                    if (!panel || panel.style.display !== 'block') {
                        return;
                    }
                    if (!panel.contains(e.target) &&
                        e.target !== thumbnailsBtn &&
                        (!thumbnailsBtn || !thumbnailsBtn.contains(e.target))) {
                        panel.style.display = 'none';
                        clearTimeout(thumbnailsAutoCloseTimeout);
                    }
                });

                const panel = document.getElementById('thumbnailsPanel');
                if (panel) {
                    panel.addEventListener('click', function(e) {
                        e.stopPropagation();
                        scheduleThumbnailsAutoClose();
                    });
                }

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
                    if (e.key === '+' || e.key === '=') {
                        zoomInLightbox();
                    }
                    if (e.key === '-') {
                        zoomOutLightbox();
                    }
                    if (e.key === '0') {
                        resetLightboxZoom();
                    }
                });

                // Close lightbox if clicking on backdrop (empty areas)
                const backdrop = document.querySelector('.lightbox-backdrop');
                if (backdrop) {
                    backdrop.addEventListener('click', function(e) {
                        if (e.target === this) {
                            closeLightbox();
                        }
                    });
                }

                // Close lightbox if clicking on image wrapper (empty areas around image)
                const imageWrapper = document.querySelector('.lightbox-image-wrapper');
                if (imageWrapper) {
                    imageWrapper.addEventListener('click', function(e) {
                        // Only close if clicking directly on the wrapper, not on the image
                        if (e.target === this) {
                            closeLightbox();
                        }
                    });
                }

                // Prevent closing when clicking on the image itself
                const lightboxImg = document.getElementById('lightboxImage');
                if (lightboxImg) {
                    lightboxImg.addEventListener('click', function(e) {
                        e.stopPropagation();
                    });
                }

            </script>

            <!-- Drag event listeners setup -->
            <script>
                // Setup drag functionality globally for lightbox image
                let dragStartX, dragStartY;

                document.addEventListener('mousedown', function(e) {
                    const lightboxImg = document.getElementById('lightboxImage');
                    const modal = document.getElementById('lightboxModal');

                    if (lightboxImg && modal && modal.classList.contains('active') && e.target === lightboxImg) {
                        isDragging = true;
                        dragStartX = e.clientX - dragX;
                        dragStartY = e.clientY - dragY;
                        lightboxImg.style.cursor = 'grabbing';
                        e.preventDefault();
                    }
                }, true);

                document.addEventListener('mousemove', function(e) {
                    const lightboxImg = document.getElementById('lightboxImage');
                    
                    if (isDragging && lightboxImg) {
                        dragX = e.clientX - dragStartX;
                        dragY = e.clientY - dragStartY;
                        lightboxImg.style.transform = `translate(calc(-50% + ${dragX}px), calc(-50% + ${dragY}px)) scale(${currentLightboxZoom})`;
                    }
                }, true);

                document.addEventListener('mouseup', function() {
                    const lightboxImg = document.getElementById('lightboxImage');
                    
                    if (isDragging && lightboxImg) {
                        isDragging = false;
                        lightboxImg.style.cursor = 'grab';
                    }
                }, true);
            </script>
            <script>
                console.log('hall-details booking script initialized');

                // ===== معاينة الصور عند رفع الملفات =====
                function setupImagePreview(inputId) {
                    const input = document.getElementById(inputId);
                    if (!input) {
                        console.log('Input not found:', inputId);
                        return;
                    }
                    const container = input.closest(".file-upload");
                    if (!container) {
                        console.log('Container not found for:', inputId);
                        return;
                    }

                    console.log('Setting up preview for:', inputId);

                    input.addEventListener("change", (event) => {
                        console.log('File input changed for:', inputId, 'Files:', event.target.files.length);
                        const file = input.files[0];
                        if (!file) {
                            console.log('No file selected');
                            return;
                        }

                        console.log('Processing file:', file.name, 'Type:', file.type);

                        const reader = new FileReader();
                        reader.onload = (e) => {
                            console.log('File loaded successfully');
                            // إزالة الصورة القديمة إن وجدت
                            let img = container.querySelector("img.preview");
                            if (!img) {
                                console.log('Creating new preview image');
                                img = document.createElement("img");
                                img.className = "preview";
                                img.style.maxWidth = "100%";
                                img.style.maxHeight = "180px";
                                img.style.display = "block";
                                img.style.marginTop = "12px";
                                img.style.borderRadius = "12px";
                                img.style.boxShadow = "0 6px 20px rgba(0, 0, 0, 0.15)";
                                img.style.objectFit = "contain";
                                img.style.border = "2px solid #f0f0f0";
                                img.style.transition = "all 0.3s ease";
                                container.appendChild(img);
                            }
                            img.src = e.target.result;

                            // إضافة تأثير تحميل
                            img.style.opacity = "0";
                            setTimeout(() => {
                                img.style.opacity = "1";
                            }, 100);
                        };

                        reader.onerror = (error) => {
                            console.error('Error reading file:', error);
                        };

                        reader.readAsDataURL(file);
                    });
                }

                // تفعيل معاينة الصور لحقول الرفع
                document.addEventListener('DOMContentLoaded', function() {
                    setupImagePreview("idCardImage");
                    setupImagePreview("receiptImage");
                });

                // Favorite button functionality
                const favoriteBtn = document.getElementById('favoriteBtn');
                if (favoriteBtn) {
                    const hallId = {{ $hall->id }};
                    
                    // تحقق من حالة المفضلة من الخادم
                    async function checkFavoriteStatus() {
                        try {
                            const response = await fetch(`/api/halls/${hallId}/favorite`);
                            const data = await response.json();
                            if (data.favorited) {
                                favoriteBtn.classList.add('active');
                                favoriteBtn.style.color = '#e91e63';
                            } else {
                                favoriteBtn.classList.remove('active');
                                favoriteBtn.style.color = 'inherit';
                            }
                        } catch (error) {
                            console.error('Error checking favorite status:', error);
                        }
                    }
                    
                    // تحقق عند التحميل
                    checkFavoriteStatus();
                    
                    favoriteBtn.addEventListener('click', async function() {
                        if (!@json(Auth::check())) {
                            window.location.href = "{{ route('login') }}";
                            return;
                        }
                        
                        const isActive = !this.classList.contains('active');
                        
                        try {
                            const response = await fetch(`/api/halls/${hallId}/favorite`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({ favorited: isActive })
                            });
                            
                            const data = await response.json();
                            if (data.favorited) {
                                this.classList.add('active');
                                this.style.color = '#e91e63';
                            } else {
                                this.classList.remove('active');
                                this.style.color = 'inherit';
                            }
                        } catch (error) {
                            console.error('Error toggling favorite:', error);
                        }
                    });
                }

                // Share functionality
                window.shareHall = function() {
                    const title = '{{ $hall->name }}';
                    const text = 'اقترح عليك هذه القاعة الرائعة في منصة QAA\'A';
                    const url = window.location.href;
                    
                    if (navigator.share) {
                        navigator.share({
                            title: title,
                            text: text,
                            url: url
                        }).catch(err => console.log('خطأ في المشاركة:', err));
                    } else {
                        // نسخ الرابط للحافظة
                        navigator.clipboard.writeText(url).then(() => {
                            alert('تم نسخ الرابط! يمكنك الآن مشاركته.');
                        });
                    }
                };

                // Star rating selector (for review form)
                const ratingStars = document.querySelectorAll('.review-form i[data-star]');
                ratingStars.forEach((star) => {
                    star.addEventListener('click', () => {
                        const value = star.getAttribute('data-star');
                        const radio = document.querySelector(`input[name="rating"][value="${value}"]`);
                        if (radio) {
                            radio.checked = true;
                        }
                        ratingStars.forEach((s) => {
                            const sValue = s.getAttribute('data-star');
                            s.style.color = sValue <= value ? '#f4c150' : '#ccc';
                        });
                    });
                });

                window.userIsAuthenticated = @json(Auth::check());
                const modal = document.getElementById('bookingModal');
                const bookNowPageBtn = document.getElementById('bookNowButton') || document.querySelector('.book-now');
                const closeBtn = modal?.querySelector('.close-modal');
                const bookingDateInput = document.getElementById('bookingDate');
                const bookingDatePicker = document.getElementById('bookingDateInput');
                const hallIdInput = document.getElementById('hallId');
                const depositAmountEl = document.getElementById('depositAmount');
                const remainingAmountEl = document.getElementById('remainingAmount');
                const termsAgreement = document.getElementById('termsAgreement');
                const bookModalBtn = document.querySelector('.btn-book');
                const steps = document.querySelectorAll('.booking-step');
                const stepIndicators = document.querySelectorAll('.booking-steps .step');
                const nextBtns = document.querySelectorAll('.btn-next');
                const prevBtns = document.querySelectorAll('.btn-prev');
                let currentStep = 0;
                const serverToday = "{{ \Carbon\Carbon::today()->toDateString() }}";

                const updateSteps = () => {
                    steps.forEach((step, index) => {
                        step.classList.toggle('active', index === currentStep);
                    });

                    stepIndicators.forEach((indicator, index) => {
                        indicator.classList.toggle('active', index <= currentStep);
                    });

                    prevBtns.forEach(btn => {
                        btn.style.display = currentStep > 0 ? 'inline-flex' : 'none';
                    });

                    nextBtns.forEach(btn => {
                        btn.style.display = currentStep < steps.length - 1 ? 'inline-flex' : 'none';
                    });

                    if (bookModalBtn) {
                        bookModalBtn.style.display = currentStep === steps.length - 1 ? 'inline-flex' : 'none';
                    }
                };

                const validateCurrentStep = () => {
                    const currentStepEl = steps[currentStep];
                    const requiredFields = currentStepEl.querySelectorAll('[required]');
                    let isValid = true;

                    requiredFields.forEach(field => {
                        if (!field.value.trim()) {
                            field.style.borderColor = '#ef4444';
                            isValid = false;
                        } else {
                            field.style.borderColor = '#d1d5db';
                        }
                    });

                    if (currentStep === 1) {
                        const idFile = document.getElementById('idCardImage')?.files.length || 0;
                        if (idFile === 0) {
                            alert('يرجى رفع صورة الهوية الشخصية');
                            isValid = false;
                        }
                    }

                    return isValid;
                };

                nextBtns.forEach(btn => {
                    btn.addEventListener('click', () => {
                        if (validateCurrentStep()) {
                            currentStep = Math.min(currentStep + 1, steps.length - 1);
                            updateSteps();
                        }
                    });
                });

                prevBtns.forEach(btn => {
                    btn.addEventListener('click', () => {
                        currentStep = Math.max(currentStep - 1, 0);
                        updateSteps();
                    });
                });

                window.resetBookingSteps = () => {
                    currentStep = 0;
                    updateSteps();
                };

                if (bookModalBtn) {
                    bookModalBtn.style.display = 'none';
                }

                const toLatinDigits = (str) => {
                    if (!str) return str;
                    const map = { '٠': '0', '١': '1', '٢': '2', '٣': '3', '٤': '4', '٥': '5', '٦': '6', '٧': '7', '٨': '8', '٩': '9' };
                    return String(str).replace(/[٠-٩]/g, (d) => map[d] ?? d);
                };

                const normalizeDateString = (raw) => {
                    if (!raw) return raw;
                    raw = toLatinDigits(String(raw)).trim();

                    // Support dd/mm/yyyy or dd-mm-yyyy
                    const dmy = raw.match(/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/);
                    if (dmy) {
                        const [, d, m, y] = dmy;
                        return `${y.padStart(4, '0')}-${m.padStart(2, '0')}-${d.padStart(2, '0')}`;
                    }

                    // Support yyyy/mm/dd or yyyy-mm-dd
                    const ymd = raw.match(/^(\d{4})[\/\-](\d{1,2})[\/\-](\d{1,2})$/);
                    if (ymd) {
                        const [, y, m, d] = ymd;
                        return `${y.padStart(4, '0')}-${m.padStart(2, '0')}-${d.padStart(2, '0')}`;
                    }

                    return raw;
                };

                let unavailableDates = @json($unavailableDates ?? []);
                if (!Array.isArray(unavailableDates)) {
                    unavailableDates = unavailableDates ? [unavailableDates] : [];
                }
                unavailableDates = unavailableDates.map(normalizeDateString).filter(Boolean);
                console.log('📅 Initial unavailable dates:', unavailableDates);

                const unavailableDatesInput = document.getElementById('unavailableDatesInput');
                const unavailableDatesPicker = document.getElementById('unavailableDatePicker');
                const addUnavailableDateBtn = document.getElementById('addUnavailableDateBtn');
                const unavailableDatesList = document.getElementById('unavailableDatesList');
                const calendarMonthLabelLight = document.getElementById('calendarMonthLabelLight');
                const calendarDaysGridLight = document.getElementById('calendarDaysGridLight');
                const calendarPrevBtnLight = document.getElementById('calendarPrevBtnLight');
                const calendarNextBtnLight = document.getElementById('calendarNextBtnLight');
                const calendarToday = new Date();
                const calendarMonthDateLight = new Date(calendarToday.getFullYear(), calendarToday.getMonth(), 1);
                const weekDaysLight = ['س', 'ح', 'ن', 'ث', 'ر', 'خ', 'ج'];
                const monthFormatterLight = new Intl.DateTimeFormat('ar-EG', { month: 'long', year: 'numeric' });

                const renderUnavailableDatesCalendarLight = () => {
                    if (!calendarMonthLabelLight || !calendarDaysGridLight) return;
                    calendarMonthLabelLight.textContent = monthFormatterLight.format(calendarMonthDateLight);
                    const firstDayOfWeek = new Date(calendarMonthDateLight.getFullYear(), calendarMonthDateLight.getMonth(), 1).getDay();
                    const daysInMonth = new Date(calendarMonthDateLight.getFullYear(), calendarMonthDateLight.getMonth() + 1, 0).getDate();
                    const firstAllowedMonth = new Date(calendarToday.getFullYear(), calendarToday.getMonth(), 1);
                    const canGoBack = calendarMonthDateLight.getTime() > firstAllowedMonth.getTime();

                    if (calendarPrevBtnLight) {
                        calendarPrevBtnLight.disabled = !canGoBack;
                        calendarPrevBtnLight.style.opacity = canGoBack ? '1' : '0.45';
                        calendarPrevBtnLight.style.cursor = canGoBack ? 'pointer' : 'not-allowed';
                    }

                    calendarDaysGridLight.innerHTML = '';
                    weekDaysLight.forEach((day) => {
                        const label = document.createElement('span');
                        label.textContent = day;
                        label.style.textAlign = 'center';
                        label.style.color = '#475569';
                        label.style.fontSize = '0.82rem';
                        label.style.fontWeight = '700';
                        calendarDaysGridLight.appendChild(label);
                    });

                    for (let emptyIndex = 0; emptyIndex < firstDayOfWeek; emptyIndex += 1) {
                        const emptyCell = document.createElement('div');
                        emptyCell.style.minHeight = '44px';
                        calendarDaysGridLight.appendChild(emptyCell);
                    }

                    for (let day = 1; day <= daysInMonth; day += 1) {
                        const dateValue = `${calendarMonthDateLight.getFullYear()}-${String(calendarMonthDateLight.getMonth() + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                        const cellDate = new Date(calendarMonthDateLight.getFullYear(), calendarMonthDateLight.getMonth(), day);
                        const isPastDate = cellDate < calendarToday;
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
                        dayBtn.style.border = isUnavailable ? '1px solid #cbd5e1' : '1px solid #e2e8f0';
                        dayBtn.style.padding = '0';
                        dayBtn.style.background = isPastDate ? '#f8fafc' : isUnavailable ? '#f59e0b' : '#ffffff';
                        dayBtn.style.color = isPastDate ? '#94a3b8' : isUnavailable ? '#111827' : '#0f172a';
                        dayBtn.style.fontWeight = isUnavailable ? '700' : '500';
                        dayBtn.style.cursor = isPastDate ? 'not-allowed' : 'pointer';
                        dayBtn.disabled = isPastDate;
                        dayBtn.addEventListener('click', () => {
                            if (isPastDate) return;
                            const hiddenInput = document.getElementById('unavailableDatesInput');
                            if (!hiddenInput) return;
                            const currentDates = hiddenInput.value.split(',').filter(d => d);
                            if (currentDates.includes(dateValue)) {
                                unavailableDates = unavailableDates.filter((d) => d !== dateValue);
                            } else {
                                unavailableDates = [...new Set([...currentDates, dateValue])];
                            }
                            unavailableDates.sort();
                            updateUnavailableDatesInput();
                            renderUnavailableDates();
                        });
                        calendarDaysGridLight.appendChild(dayBtn);
                    }
                };

                const renderUnavailableDates = () => {
                    if (!unavailableDatesList) return;
                    unavailableDatesList.innerHTML = '';
                    unavailableDates.forEach((date) => {
                        const tag = document.createElement('span');
                        tag.className = 'tag';
                        tag.style = 'background: rgba(255,255,255,0.1); padding: 6px 10px; border-radius: 14px; display: inline-flex; align-items: center; gap: 6px;';
                        tag.innerHTML = `${date} <button type="button" style="background:none;border:none;color:#fff;cursor:pointer;" aria-label="حذف" title="حذف">×</button>`;
                        tag.querySelector('button').addEventListener('click', () => {
                            unavailableDates = unavailableDates.filter((d) => d !== date);
                            updateUnavailableDatesInput();
                            renderUnavailableDates();
                        });
                        unavailableDatesList.appendChild(tag);
                    });
                    renderUnavailableDatesCalendarLight();
                };

                const updateUnavailableDatesInput = () => {
                    if (!unavailableDatesInput) return;
                    unavailableDatesInput.value = unavailableDates.join(',');
                    console.log('📅 Updated unavailable dates input:', unavailableDatesInput.value);
                };

                if (addUnavailableDateBtn && unavailableDatesPicker) {
                    addUnavailableDateBtn.addEventListener('click', () => {
                        const val = normalizeDateString(unavailableDatesPicker.value);
                        if (!val) return;
                        if (!unavailableDates.includes(val)) {
                            unavailableDates.push(val);
                            unavailableDates.sort();
                            updateUnavailableDatesInput();
                            renderUnavailableDates();
                        }
                    });
                }

                calendarPrevBtnLight?.addEventListener('click', () => {
                    calendarMonthDateLight.setMonth(calendarMonthDateLight.getMonth() - 1);
                    renderUnavailableDatesCalendarLight();
                });

                calendarNextBtnLight?.addEventListener('click', () => {
                    calendarMonthDateLight.setMonth(calendarMonthDateLight.getMonth() + 1);
                    renderUnavailableDatesCalendarLight();
                });

                renderUnavailableDates();

                const parsePrice = (priceText) => {
                    const digits = priceText.replace(/[^0-9]/g, '');
                    return Number(digits) || 0;
                };

                const formatNumber = (num) => {
                    return num.toLocaleString('ar-EG');
                };

                const setDepositAmount = () => {
                    const priceEl = document.querySelector('.price-tag .price');
                    if (!priceEl || !depositAmountEl) return;

                    const price = parsePrice(priceEl.textContent);
                    const deposit = Math.ceil(price * 0.1);
                    depositAmountEl.textContent = formatNumber(deposit);
                };

                const selectedDateText = document.getElementById('selectedDateText');

                const getToday = () => {
                    const today = new Date();
                    const year = today.getFullYear();
                    const month = String(today.getMonth() + 1).padStart(2, '0');
                    const day = String(today.getDate()).padStart(2, '0');
                    return `${year}-${month}-${day}`;
                };

                const formatDateReadable = (date) => {
                    try {
                        return new Date(date).toLocaleDateString('ar-EG', {
                            day: '2-digit',
                            month: 'long',
                            year: 'numeric',
                        });
                    } catch (e) {
                        return date;
                    }
                };

                const bookingCalendar = document.getElementById('bookingCalendar');
                let calendarMonthDate = new Date(getToday());

                const formatMonthHeader = (date) => {
                    return new Date(date).toLocaleDateString('ar-EG', {
                        month: 'long',
                        year: 'numeric',
                    });
                };

                const renderCalendar = (selected = null) => {
                    if (!bookingCalendar) return;

                    const today = getToday();
                    const activeDate = selected ? new Date(selected) : new Date(calendarMonthDate);
                    calendarMonthDate = new Date(activeDate);
                    calendarMonthDate.setDate(1);

                    const monthName = formatMonthHeader(calendarMonthDate);
                    const firstDayOfWeek = new Date(calendarMonthDate).getDay();
                    const daysInMonth = new Date(calendarMonthDate.getFullYear(), calendarMonthDate.getMonth() + 1, 0).getDate();
                    const selectedDateStr = normalizeDateString(selected || bookingDatePicker?.value || today);

                    const weekDays = ['س', 'ح', 'ن', 'ث', 'ر', 'خ', 'ج'];
                    const currentDate = new Date(today);
                    const firstAllowedMonth = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
                    const canGoBack = new Date(calendarMonthDate.getFullYear(), calendarMonthDate.getMonth(), 1) > firstAllowedMonth;
                    const header = `
                        <div class="calendar-header">
                            <div class="calendar-title">${monthName}</div>
                            <div class="calendar-nav">
                                <button type="button" data-calendar-action="prev" ${canGoBack ? '' : 'disabled'}>‹</button>
                                <button type="button" data-calendar-action="next">›</button>
                            </div>
                        </div>
                        <div class="calendar-grid">
                            ${weekDays.map((day) => `<div class="calendar-weekday">${day}</div>`).join('')}
                        </div>
                    `;

                    let dayCells = '';
                    for (let i = 0; i < firstDayOfWeek; i += 1) {
                        dayCells += '<div class="calendar-empty"></div>';
                    }

                    for (let day = 1; day <= daysInMonth; day += 1) {
                        const dateValue = `${calendarMonthDate.getFullYear()}-${String(calendarMonthDate.getMonth() + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                        const isUnavailableDate = isDateUnavailable(dateValue) || dateValue < today;
                        const isSelected = selectedDateStr === dateValue;
                        const classes = ['calendar-day'];
                        if (isUnavailableDate) classes.push('disabled');
                        if (isSelected) classes.push('selected');
                        if (dateValue === today) classes.push('today');

                        dayCells += `<button type="button" class="${classes.join(' ')}" data-calendar-date="${dateValue}" ${isUnavailableDate ? 'disabled' : ''}>${day}</button>`;
                    }

                    bookingCalendar.innerHTML = header + '<div class="calendar-grid">' + dayCells + '</div>';

                    bookingCalendar.querySelectorAll('[data-calendar-action]').forEach((btn) => {
                        btn.addEventListener('click', () => {
                            const action = btn.getAttribute('data-calendar-action');
                            if (action === 'prev') {
                                const currentDate = new Date(today);
                                const firstAllowedMonth = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
                                const monthToShow = new Date(calendarMonthDate.getFullYear(), calendarMonthDate.getMonth() - 1, 1);
                                if (monthToShow < firstAllowedMonth) {
                                    return;
                                }
                            }
                            calendarMonthDate.setMonth(calendarMonthDate.getMonth() + (action === 'next' ? 1 : -1));
                            renderCalendar();
                        });
                    });

                    bookingCalendar.querySelectorAll('[data-calendar-date]').forEach((btn) => {
                        btn.addEventListener('click', () => {
                            const dateValue = btn.getAttribute('data-calendar-date');
                            if (!dateValue || btn.disabled) return;
                            bookingDatePicker.value = dateValue;
                            onDatePicked(dateValue);
                            renderCalendar(dateValue);
                        });
                    });
                };

                const getNextAvailableDate = (startDate) => {
                    const today = getToday();
                    let current = normalizeDateString(startDate) || today;
                    if (current < today) {
                        current = today;
                    }

                    const date = new Date(current);
                    for (let i = 0; i < 365; i += 1) {
                        const candidate = date.toISOString().slice(0, 10);
                        if (!isDateUnavailable(candidate)) {
                            return candidate;
                        }
                        date.setDate(date.getDate() + 1);
                    }
                    return null;
                };

                // Fetch booked dates from server when modal opens or page loads
                const fetchBookedDates = async () => {
                    try {
                        const hallId = document.getElementById('hallId')?.value;
                        if (!hallId) return;

                        const response = await fetch(`/api/booked-dates?hall_id=${hallId}`);
                        const data = await response.json();

                        if (data.booked_dates && Array.isArray(data.booked_dates)) {
                            const bookedDates = data.booked_dates.map(normalizeDateString);
                            unavailableDates = Array.from(new Set([...unavailableDates, ...bookedDates])).sort();
                            console.log('📅 Booked dates loaded:', unavailableDates);
                        }
                    } catch (error) {
                        console.error('❌ Failed to fetch booked dates:', error);
                    }
                };

                // When user picks a date via the date input
                const isDateUnavailable = (dateStr) => {
                    if (!dateStr) return false;
                    const normalizedDate = normalizeDateString(dateStr);
                    return unavailableDates && unavailableDates.includes(normalizedDate);
                };

                const onDatePicked = (dateStr, showAlert = true) => {
                    if (!dateStr) return false;

                    const normalizedDate = normalizeDateString(dateStr);
                    const today = getToday();
                    let selectedDate = normalizedDate;

                    if (selectedDate < today || isDateUnavailable(selectedDate)) {
                        const nextAvailable = getNextAvailableDate(selectedDate);
                        if (!nextAvailable) {
                            if (showAlert) {
                                alert('لا يوجد تاريخ متاح للحجز في المستقبل القريب. الرجاء المحاولة لاحقاً.');
                            }
                            if (bookingDatePicker) bookingDatePicker.value = '';
                            if (bookingDateInput) bookingDateInput.value = '';
                            if (selectedDateText) selectedDateText.textContent = 'لم يتم الاختيار';
                            const summaryDateEl = document.getElementById('summaryDate');
                            if (summaryDateEl) {
                                summaryDateEl.textContent = 'لم يتم الاختيار';
                            }
                            return false;
                        }

                        if (showAlert) {
                            if (selectedDate < today) {
                                alert('لا يمكن اختيار تاريخ سابق. تم اختيار أول تاريخ متاح بعد اليوم.');
                            } else {
                                alert('هذا التاريخ غير متاح للحجز. تم اختيار أول تاريخ متاح بعده.');
                            }
                        }

                        selectedDate = nextAvailable;
                        if (bookingDatePicker) bookingDatePicker.value = selectedDate;
                    }

                    if (bookingDateInput) bookingDateInput.value = selectedDate;
                    if (selectedDateText) selectedDateText.textContent = formatDateReadable(selectedDate);
                    const summaryDateEl = document.getElementById('summaryDate');
                    if (summaryDateEl) {
                        summaryDateEl.textContent = formatDateReadable(selectedDate);
                    }
                    renderCalendar(selectedDate);
                    return true;
                };

                if (bookingDatePicker) {
                    bookingDatePicker.min = getToday();
                    bookingDatePicker.value = getToday();
                    fetchBookedDates().then(() => {
                        const initialDate = getNextAvailableDate(getToday());
                        if (initialDate) {
                            bookingDatePicker.value = initialDate;
                        }
                        onDatePicked(bookingDatePicker.value, false);
                        renderCalendar(bookingDatePicker.value);
                    });

                    bookingDatePicker.addEventListener('change', (event) => {
                        onDatePicked(event.target.value);
                    });

                    bookingDatePicker.addEventListener('input', (event) => {
                        onDatePicked(event.target.value);
                    });
                }

                let lastScrollPosition = 0;

                const lockPageScroll = () => {
                    lastScrollPosition = window.scrollY || document.documentElement.scrollTop || 0;
                    document.documentElement.classList.add('modal-open');
                    document.body.classList.add('modal-open');
                    document.body.style.overflow = 'hidden';
                    document.documentElement.style.overflow = 'hidden';
                };

                const unlockPageScroll = () => {
                    document.documentElement.classList.remove('modal-open');
                    document.body.classList.remove('modal-open');
                    document.body.style.overflow = '';
                    document.documentElement.style.overflow = '';
                };

                const openModal = async () => {
                    if (!window.userIsAuthenticated) {
                        window.location.href = "{{ route('login') }}";
                        return;
                    }
                    if (!modal) return;

                    await fetchBookedDates();

                    if (bookingDatePicker && bookingDatePicker.value === '') {
                        bookingDatePicker.value = getToday();
                    }
                    const selectedDateValue = bookingDatePicker?.value || getToday();
                    if (!onDatePicked(selectedDateValue)) {
                        return;
                    }

                    const finalSelectedDate = bookingDatePicker?.value || getToday();
                    if (isDateUnavailable(finalSelectedDate)) {
                        return;
                    }

                    if (bookingDateInput && bookingDateInput.value === '') {
                        bookingDateInput.value = finalSelectedDate;
                    }
                    if (selectedDateText) {
                        selectedDateText.textContent = formatDateReadable(bookingDateInput.value);
                    }

                    modal.classList.add('show');
                    lockPageScroll();
                    setDepositAmount();

                    if (typeof window.resetBookingSteps === 'function') {
                        window.resetBookingSteps();
                    }
                };

                window.openBookingModal = openModal;
                window.openModal = openModal;
                if (termsAgreement && bookModalBtn) {
                    bookModalBtn.disabled = !termsAgreement?.checked;
                }

                if (termsAgreement && bookModalBtn) {
                    termsAgreement.addEventListener('change', () => {
                        bookModalBtn.disabled = !termsAgreement.checked;
                    });
                }

                console.log('Booking modal ready', { modal, bookNowPageBtn, bookModalBtn });

                const closeModal = () => {
                    if (!modal) return;
                    modal.classList.remove('show');
                    unlockPageScroll();
                };

                if (bookNowPageBtn) {
                    console.log('Booking button found', bookNowPageBtn);
                    bookNowPageBtn.addEventListener('click', async (event) => {
                        event.preventDefault();
                        const selectedValue = bookingDatePicker?.value || getToday();
                        if (!onDatePicked(selectedValue)) {
                            return;
                        }
                        await openModal();
                    });
                } else {
                    console.warn('Booking button not found');
                }

                if (closeBtn) {
                    closeBtn.addEventListener('click', closeModal);
                }

                if (modal) {
                    modal.addEventListener('click', (event) => {
                        if (event.target === modal) {
                            closeModal();
                        }
                    });
                }

                const bookingForm = document.getElementById('bookingForm');
                if (bookingForm) {
                    bookingForm.noValidate = true;

                    bookingForm.addEventListener('submit', async (event) => {
                        if (!termsAgreement || !termsAgreement.checked) {
                            event.preventDefault();
                            alert('يرجى الموافقة على الشروط والأحكام');
                            return;
                        }

                        if (!bookingDateInput || !bookingDateInput.value) {
                            const todayStr = serverToday;
                            if (bookingDateInput) bookingDateInput.value = todayStr;
                            if (bookingDatePicker) bookingDatePicker.value = todayStr;
                            if (selectedDateText) selectedDateText.textContent = formatDateReadable(todayStr);
                        }

                        const bookingDate = normalizeDateString(bookingDateInput.value);
                        const hallId = hallIdInput?.value;

                        if (!hallId) {
                            event.preventDefault();
                            alert('حدث خطأ: رقم القاعة غير موجود.');
                            return;
                        }

                        if (!bookingDate) {
                            event.preventDefault();
                            alert('يرجى اختيار تاريخ الحجز الصحيح.');
                            return;
                        }

                        // Check availability before submitting
                        try {
                            const availabilityResponse = await fetch(`/api/check-availability?hall_id=${hallId}&booking_date=${bookingDate}`);
                            const availabilityData = await availabilityResponse.json();

                            if (!availabilityData.available) {
                                event.preventDefault();
                                alert('عذراً، القاعة محجوزة بالفعل في هذا التاريخ. يرجى اختيار تاريخ آخر.');
                                return;
                            }
                        } catch (error) {
                            console.error('خطأ في التحقق من التوفرية:', error);
                            event.preventDefault();
                            alert('حدث خطأ أثناء التحقق من توفرية القاعة. يرجى المحاولة لاحقاً.');
                            return;
                        }

                        // Validate only the visible step fields, because hidden step fields may still be present
                        const currentStepEl = steps[currentStep];
                        if (currentStepEl) {
                            const requiredFields = currentStepEl.querySelectorAll('[required]');
                            for (const field of requiredFields) {
                                if (field.type === 'file' && field.files.length === 0) {
                                    event.preventDefault();
                                    alert('يرجى رفع الملفات المطلوبة قبل تأكيد الحجز.');
                                    return;
                                }
                                if (field.type !== 'file' && !field.value.trim()) {
                                    event.preventDefault();
                                    field.focus();
                                    return;
                                }
                            }
                        }

                        // Allow the browser to submit the form normally after the final check
                    });

if (bookModalBtn) {
                    bookModalBtn.addEventListener('click', (event) => {
                            if (bookingForm) {
                                bookingForm.requestSubmit();
                            }
                        });
                    }

                    // Payment will be handled from the bookings page
                }
            </script>

            <style>
                /* Progress Steps Enhanced */
        .booking-steps {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 40px 20px;
            margin-bottom: 0;
            background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
            border-bottom: 1px solid #e5e7eb;
            position: relative;
        }

        .booking-steps .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
            opacity: 0.4;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .booking-steps .step.active {
            opacity: 1;
            transform: scale(1.05);
        }

        .booking-steps .step-number {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, #e5e7eb 0%, #d1d5db 100%);
            color: #6b7280;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 18px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 3px solid transparent;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .booking-steps .step.active .step-number {
            background: linear-gradient(135deg, #d4af37 0%, #f59e0b 100%);
            color: white;
            border-color: #d4af37;
            box-shadow: 0 6px 20px rgba(212, 175, 55, 0.4);
            transform: scale(1.1);
        }

        .booking-steps .step-title {
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            text-align: center;
            white-space: nowrap;
            transition: all 0.3s ease;
        }

        .booking-steps .step.active .step-title {
            color: #d4af37;
            font-weight: 700;
        }

        .step-connector {
            width: 80px;
            height: 3px;
            background: linear-gradient(90deg, #e5e7eb 0%, #d1d5db 100%);
            margin: 0 16px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 2px;
            position: relative;
            overflow: hidden;
        }

        .step-connector::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, #d4af37 0%, #f59e0b 100%);
            transition: left 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .booking-steps .step.active + .step-connector::before {
            left: 0;
        }

        /* Booking Steps Content */
        .booking-step {
            display: none;
            padding: 40px;
            animation: stepFadeIn 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            min-height: 500px;
        }

        .booking-step.active {
            display: block;
        }

        .step-header {
            text-align: center;
            margin-bottom: 40px;
            position: relative;
        }

        .step-header::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, #d4af37 0%, #f59e0b 100%);
            border-radius: 2px;
        }

        .step-header i {
            font-size: 56px;
            color: #d4af37;
            margin-bottom: 20px;
            display: block;
            text-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
        }

        .step-header h4 {
            margin: 0 0 12px 0;
            font-size: 24px;
            font-weight: 700;
            color: #1f2937;
            letter-spacing: -0.5px;
        }

        .step-header p {
            margin: 0;
            color: #6b7280;
            font-size: 16px;
            line-height: 1.5;
        }

                /* Form Styles */
                .input-row {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: 24px;
                    margin-bottom: 32px;
                }

                .form-group {
                    position: relative;
                }

                .form-group label {
                    display: block;
                    margin-bottom: 8px;
                    font-weight: 600;
                    color: #374151;
                    font-size: 14px;
                    display: flex;
                    align-items: center;
                    gap: 8px;
                }

                .form-group label i {
                    color: #d4af37;
                    font-size: 16px;
                    width: 18px;
                    text-align: center;
                }

                .form-group input,
                .form-group select {
                    width: 100%;
                    padding: 16px 20px;
                    border: 2px solid #e5e7eb;
                    border-radius: 12px;
                    font-size: 16px;
                    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                    background: #ffffff;
                    box-sizing: border-box;
                    font-family: inherit;
                }

                .form-group input:focus,
                .form-group select:focus {
                    outline: none;
                    border-color: #d4af37;
                    box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
                    transform: translateY(-1px);
                }

                .form-group input:hover,
                .form-group select:hover {
                    border-color: #f59e0b;
                }

                .input-hint {
                    margin-top: 6px;
                    font-size: 12px;
                    color: #6b7280;
                    font-style: italic;
                }

                /* Documents Grid */
                .documents-grid {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: 32px;
                    margin-bottom: 32px;
                }

                /* File Upload Enhanced */
                .file-upload.enhanced {
                    position: relative;
                    border: 2px dashed #d1d5db;
                    border-radius: 16px;
                    padding: 40px 24px;
                    text-align: center;
                    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                    cursor: pointer;
                    background: linear-gradient(135deg, #fafbfc 0%, #ffffff 100%);
                    margin-top: 8px;
                }

                .file-upload.enhanced:hover {
                    border-color: #d4af37;
                    background: linear-gradient(135deg, rgba(212, 175, 55, 0.02) 0%, rgba(245, 158, 11, 0.02) 100%);
                    transform: translateY(-2px);
                    box-shadow: 0 8px 25px rgba(212, 175, 55, 0.15);
                }

                .file-upload.enhanced.accent {
                    border-color: #d4af37;
                    background: linear-gradient(135deg, rgba(212, 175, 55, 0.05) 0%, rgba(245, 158, 11, 0.05) 100%);
                    box-shadow: 0 4px 20px rgba(212, 175, 55, 0.2);
                }

                .upload-area i {
                    font-size: 40px;
                    color: #d4af37;
                    margin-bottom: 16px;
                    display: block;
                    text-shadow: 0 2px 8px rgba(212, 175, 55, 0.3);
                }

                .upload-text {
                    font-weight: 600;
                    color: #374151;
                    font-size: 16px;
                    margin-bottom: 4px;
                }

                .upload-subtext {
                    color: #6b7280;
                    font-size: 14px;
                }

                .file-preview {
                    margin-top: 16px;
                    display: none;
                    text-align: center;
                }

                .file-preview img {
                    max-width: 100%;
                    max-height: 200px;
                    border-radius: 16px;
                    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
                    object-fit: contain;
                    border: 3px solid #f8f9fa;
                    background: white;
                    transition: all 0.3s ease;
                }

                .file-preview img:hover {
                    transform: scale(1.02);
                    box-shadow: 0 12px 35px rgba(0, 0, 0, 0.18);
                }

                .file-preview:not(:empty) {
                    display: block;
                }

                .file-requirements {
                    margin-top: 12px;
                    text-align: center;
                }

                .file-requirements small {
                    color: #6b7280;
                    font-size: 12px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 6px;
                }

                .file-requirements i {
                    color: #10b981;
                }

                /* Payment Summary Enhanced */
                .payment-summary {
                    margin-bottom: 40px;
                }

                .summary-card {
                    background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
                    border: 1px solid #e5e7eb;
                    border-radius: 20px;
                    padding: 32px;
                    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
                    position: relative;
                    overflow: hidden;
                }

                .summary-card::before {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    height: 4px;
                    background: linear-gradient(90deg, #d4af37 0%, #f59e0b 100%);
                }

                .summary-card h5 {
                    margin: 0 0 24px 0;
                    font-size: 20px;
                    font-weight: 700;
                    color: #1f2937;
                    display: flex;
                    align-items: center;
                    gap: 10px;
                }

                .summary-card h5 i {
                    color: #d4af37;
                    font-size: 24px;
                }

                .summary-row {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    padding: 12px 0;
                    border-bottom: 1px solid #f3f4f6;
                    font-size: 15px;
                }

                .summary-row:last-child {
                    border-bottom: none;
                }

                .summary-row span:first-child {
                    color: #6b7280;
                    font-weight: 500;
                }

                .summary-row strong {
                    color: #1f2937;
                    font-weight: 600;
                }

                .summary-row.total {
                    border-top: 2px solid #d4af37;
                    padding-top: 20px;
                    margin-top: 16px;
                    font-weight: 700;
                    font-size: 18px;
                    color: #d4af37;
                    background: linear-gradient(135deg, rgba(212, 175, 55, 0.05) 0%, rgba(245, 158, 11, 0.05) 100%);
                    padding: 20px;
                    border-radius: 12px;
                    margin: 20px -8px -8px -8px;
                }

                .summary-row.total strong {
                    color: #d4af37;
                    font-size: 20px;
                }

                /* Deposit Info Enhanced */
                .deposit-info.enhanced {
                    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
                    border: 1px solid #f59e0b;
                    border-radius: 20px;
                    padding: 24px;
                    margin-bottom: 32px;
                    position: relative;
                }

                .deposit-info.enhanced::before {
                    content: '⚠️';
                    position: absolute;
                    top: 20px;
                    right: 20px;
                    font-size: 20px;
                }

                .deposit-notice {
                    display: flex;
                    align-items: flex-start;
                    gap: 12px;
                }

                .deposit-notice i {
                    color: #d97706;
                    font-size: 24px;
                    margin-top: 2px;
                    flex-shrink: 0;
                }

                .deposit-notice div strong {
                    color: #92400e;
                    display: block;
                    margin-bottom: 4px;
                }

                .price-calc {
                    background: white;
                    border-radius: 16px;
                    padding: 20px;
                    border: 1px solid #e5e7eb;
                    margin-top: 16px;
                }

                .calc-row {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    padding: 8px 0;
                    font-size: 14px;
                }

                .calc-row .amount {
                    font-weight: 700;
                    color: #d4af37;
                    font-size: 16px;
                }

                /* Payment Instructions */
                .payment-instructions h5 {
                    margin: 0 0 16px 0;
                    font-size: 18px;
                    font-weight: 700;
                    color: #1f2937;
                    display: flex;
                    align-items: center;
                    gap: 10px;
                }

                .payment-instructions h5 i {
                    color: #d4af37;
                    font-size: 20px;
                }

                .instructions-list {
                    background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
                    border-radius: 16px;
                    padding: 20px;
                    border: 1px solid #e5e7eb;
                }

                .instruction-item {
                    display: flex;
                    align-items: flex-start;
                    gap: 12px;
                    margin-bottom: 12px;
                    font-size: 14px;
                    line-height: 1.5;
                }

                .instruction-item:last-child {
                    margin-bottom: 0;
                }

                .instruction-item i {
                    color: #10b981;
                    font-size: 18px;
                    margin-top: 2px;
                    flex-shrink: 0;
                }

                /* Terms Agreement */
                .terms-agreement {
                    margin-top: 32px;
                    padding: 20px;
                    background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
                    border-radius: 16px;
                    border: 1px solid #e5e7eb;
                }

                .checkbox-container {
                    display: flex;
                    align-items: flex-start;
                    gap: 12px;
                    cursor: pointer;
                    font-size: 14px;
                    line-height: 1.5;
                }

                .checkbox-container input[type="checkbox"] {
                    width: 20px;
                    height: 20px;
                    margin: 0;
                    flex-shrink: 0;
                    margin-top: 2px;
                }

                .checkbox-container .checkmark {
                    width: 20px;
                    height: 20px;
                    border: 2px solid #d1d5db;
                    border-radius: 4px;
                    background: white;
                    position: relative;
                    flex-shrink: 0;
                    margin-top: 2px;
                    transition: all 0.3s ease;
                }

                .checkbox-container input[type="checkbox"]:checked + .checkmark {
                    background: #d4af37;
                    border-color: #d4af37;
                    box-shadow: 0 0 0 2px rgba(212, 175, 55, 0.2);
                }

                .checkbox-container input[type="checkbox"]:checked + .checkmark::after {
                    content: '✓';
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    color: white;
                    font-weight: bold;
                    font-size: 12px;
                }

                .checkbox-container a {
                    color: #d4af37;
                    text-decoration: none;
                    font-weight: 600;
                }

                .checkbox-container a:hover {
                    text-decoration: underline;
                }

                .payment-section {
                    margin: 30px 0;
                }

                .payment-methods {
                    margin-bottom: 20px;
                }

                .payment-method {
                    display: flex;
                    align-items: center;
                    gap: 12px;
                    padding: 16px 20px;
                    border: 2px solid #e5e7eb;
                    border-radius: 12px;
                    background: #ffffff;
                    cursor: pointer;
                    transition: all 0.3s ease;
                }

                .payment-method.active {
                    border-color: #d4af37;
                    background: rgba(212, 175, 55, 0.05);
                }

                .payment-method i {
                    font-size: 1.5rem;
                    color: #d4af37;
                }

                .payment-method span {
                    font-weight: 600;
                    color: #152b4f;
                }

                .payment-method small {
                    color: #64748b;
                    font-size: 0.9rem;
                }

                .payment-actions {
                    text-align: center;
                    margin: 30px 0;
                }

                .btn-payment {
                    background: linear-gradient(135deg, #d4af37 0%, #f59e0b 100%);
                    color: white;
                    border: none;
                    padding: 16px 32px;
                    border-radius: 50px;
                    font-weight: 700;
                    font-size: 1.1rem;
                    cursor: pointer;
                    transition: all 0.3s ease;
                    box-shadow: 0 8px 25px rgba(212, 175, 55, 0.3);
                }

                .btn-payment:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 12px 35px rgba(212, 175, 55, 0.4);
                }

                .btn-payment:disabled {
                    opacity: 0.6;
                    cursor: not-allowed;
                    transform: none;
                }

                .payment-status {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 12px;
                    padding: 20px;
                    background: rgba(212, 175, 55, 0.1);
                    border-radius: 12px;
                    margin-top: 20px;
                }

                .loading-spinner {
                    width: 20px;
                    height: 20px;
                    border: 2px solid #d4af37;
                    border-top: 2px solid transparent;
                    border-radius: 50%;
                    animation: spin 1s linear infinite;
                }

                @keyframes spin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }

                /* Modal Footer Enhanced */
                .modal-footer {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    padding: 32px 40px;
                    border-top: 1px solid #e5e7eb;
                    background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
                    border-radius: 0 0 28px 28px;
                    gap: 20px;
                }

                .btn {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    gap: 8px;
                    padding: 14px 28px;
                    border-radius: 12px;
                    font-size: 16px;
                    font-weight: 600;
                    text-decoration: none;
                    border: none;
                    cursor: pointer;
                    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                    font-family: inherit;
                    min-width: 120px;
                }

                .btn-secondary {
                    background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
                    color: white;
                    box-shadow: 0 4px 12px rgba(107, 114, 128, 0.3);
                }

                .btn-secondary:hover {
                    background: linear-gradient(135deg, #4b5563 0%, #374151 100%);
                    transform: translateY(-2px);
                    box-shadow: 0 6px 20px rgba(107, 114, 128, 0.4);
                }

                .btn-primary {
                    background: linear-gradient(135deg, #d4af37 0%, #f59e0b 100%);
                    color: white;
                    box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
                }

                .btn-primary:hover {
                    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
                    transform: translateY(-2px);
                    box-shadow: 0 6px 20px rgba(212, 175, 55, 0.4);
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

                .date-picker-row .date-picker-wrap .form-control,
                .date-picker-row .date-picker-wrap input[type="date"] {
                    width: 100%;
                    padding: 16px 18px;
                    border-radius: 20px;
                    border: 1px solid rgba(148, 163, 184, 0.3);
                    background: #ffffff;
                    color: #0f172a;
                    font-size: 1rem;
                    box-shadow: inset 0 1px 2px rgba(15, 23, 42, 0.06);
                    appearance: none;
                    -webkit-appearance: none;
                    -moz-appearance: textfield;
                }

                .date-picker-row .date-picker-wrap .form-control:focus,
                .date-picker-row .date-picker-wrap input[type="date"]:focus {
                    border-color: rgba(59, 130, 246, 0.9);
                    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.12);
                    background: #ffffff;
                }

                .btn-confirm-final {
                    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                    color: white;
                    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
                    font-size: 18px;
                    padding: 16px 32px;
                    min-width: 160px;
                }

                .btn-confirm-final:hover {
                    background: linear-gradient(135deg, #059669 0%, #047857 100%);
                    transform: translateY(-2px);
                    box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
                }

                .btn-success {
                    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                    color: white;
                    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
                    font-size: 16px;
                    padding: 12px 28px;
                    min-width: 150px;
                }

                .btn-success:hover {
                    background: linear-gradient(135deg, #059669 0%, #047857 100%);
                    transform: translateY(-2px);
                    box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
                }

                .btn:disabled {
                    opacity: 0.6;
                    cursor: not-allowed;
                    transform: none !important;
                    box-shadow: none !important;
                }

                /* Selected Date Info Enhanced */
                .selected-date-info {
                    background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
                    border: 1px solid #0ea5e9;
                    border-radius: 16px;
                    padding: 20px;
                    margin-bottom: 32px;
                    position: relative;
                }

                .selected-date-info::before {
                    content: '📅';
                    position: absolute;
                    top: 20px;
                    right: 20px;
                    font-size: 20px;
                }

                .date-display {
                    display: flex;
                    align-items: center;
                    gap: 12px;
                }

                .date-display i {
                    color: #0ea5e9;
                    font-size: 24px;
                    flex-shrink: 0;
                }

                .date-display div strong {
                    color: #0c4a6e;
                    display: block;
                    font-size: 14px;
                    margin-bottom: 4px;
                }

                .date-display div span {
                    color: #0369a1;
                    font-weight: 600;
                    font-size: 16px;
                }

                /* Animations Enhanced */
                @keyframes stepFadeIn {
                    from {
                        opacity: 0;
                        transform: translateX(30px) scale(0.95);
                    }
                    to {
                        opacity: 1;
                        transform: translateX(0) scale(1);
                    }
                }

                /* Responsive Design */
                @media (max-width: 768px) {
                    #bookingModal .booking-modal-content {
                        width: 95%;
                        max-width: 95%;
                        margin: 20px;
                        max-height: calc(100vh - 40px);
                    }

                    .modal-header {
                        padding: 20px 24px;
                    }

                    .modal-header h3 {
                        font-size: 20px;
                    }

                    .booking-steps {
                        padding: 24px 20px 16px;
                    }

                    .booking-steps .step-number {
                        width: 40px;
                        height: 40px;
                        font-size: 16px;
                    }

                    .step-connector {
                        width: 40px;
                    }

                    .booking-step {
                        padding: 24px 20px;
                        min-height: 400px;
                    }

                    .step-header {
                        margin-bottom: 24px;
                    }

                    .step-header i {
                        font-size: 40px;
                    }

                    .step-header h4 {
                        font-size: 20px;
                    }

                    .input-row {
                        grid-template-columns: 1fr;
                        gap: 20px;
                    }

                    .documents-grid {
                        grid-template-columns: 1fr;
                        gap: 24px;
                    }

                    .modal-footer {
                        flex-direction: column;
                        gap: 16px;
                        padding: 24px 20px;
                    }

                    .btn {
                        width: 100%;
                        justify-content: center;
                        padding: 16px 24px;
                    }

                    .summary-card {
                        padding: 20px;
                    }

                    .deposit-info.enhanced,
                    .terms-agreement {
                        padding: 16px;
                    }
                }

                @media (max-width: 480px) {
                    .booking-steps {
                        padding: 20px 16px 12px;
                    }

                    .booking-step {
                        padding: 20px 16px;
                    }

                    .modal-header {
                        padding: 16px 20px;
                    }

                    .modal-header h3 {
                        font-size: 18px;
                    }

                    .step-header h4 {
                        font-size: 18px;
                    }

                    .summary-card h5 {
                        font-size: 18px;
                    }

                    .btn {
                        font-size: 15px;
                        padding: 14px 20px;
                    }
                }

                /* Lightbox responsive toolbar */
                @media (max-width: 768px) {
                    .lightbox-toolbar {
                        gap: 12px;
                        padding: 12px 16px;
                        bottom: 10px;
                    }

                    .toolbar-btn {
                        width: 36px;
                        height: 36px;
                        font-size: 14px;
                    }

                    .reset-btn {
                        font-size: 0.8rem;
                        padding: 0 10px;
                    }

                    .thumbnails-group {
                        display: none;
                    }

                    .lightbox-nav {
                        width: 48px;
                        height: 48px;
                    }
                }

                @media (max-width: 480px) {
                    .lightbox-toolbar {
                        flex-wrap: wrap;
                        gap: 8px;
                        padding: 10px 12px;
                        max-width: 90vw;
                    }

                    .toolbar-btn {
                        width: 34px;
                        height: 34px;
                        font-size: 13px;
                    }

                    .toolbar-group {
                        gap: 6px;
                    }

                    .lightbox-nav {
                        width: 44px;
                        height: 44px;
                        font-size: 20px;
                    }

                    .lightbox-prev {
                        left: 8px;
                    }

                    .lightbox-next {
                        right: 8px;
                    }
                }
            </style>
    </div>

    <!-- Lightbox Modal - محسّن -->
    <div id="lightboxModal" class="lightbox-container">
        <!-- خلفية داكنة -->
        <div class="lightbox-backdrop" onclick="closeLightbox()"></div>

        <!-- محتوى اللايتبوكس -->
        <div class="lightbox-content">
            <!-- رأس اللايتبوكس -->
            <div class="lightbox-header">
                <div class="lightbox-info">
                    <span class="image-counter"><span id="currentImageNum">1</span> / <span id="totalImagesNum">5</span></span>
                </div>
                <!-- <button class="lightbox-btn lightbox-close" onclick="closeLightbox()" title="إغلاق (ESC)">
                    <i class="fas fa-times"></i>
                </button> -->
            </div>

            <!-- منطقة الصورة -->
            <div class="lightbox-image-wrapper">
                <img id="lightboxImage" src="" alt="صورة القاعة" class="lightbox-image">
                
                <!-- أيقونات التحكم على الصورة -->
                <!-- <div class="lightbox-zoom-info">
                    <span id="zoomPercentage">100%</span>
                </div> -->
            </div>

            <!-- أزرار الملاحة -->
            <button class="lightbox-nav lightbox-prev" onclick="showPreviousLightbox()" title="السابق (←)">
                <svg width="24" height="24" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                    <path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
            <button class="lightbox-nav lightbox-next" onclick="showNextLightbox()" title="التالي (→)">
                <svg width="24" height="24" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                    <path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>

            <!-- شريط التحكم في الأسفل -->
            <div class="lightbox-toolbar" id="lightboxToolbar">
                <!-- مجموعة التكبير -->
                <div class="toolbar-group">
                    <button class="toolbar-btn" onclick="zoomOutLightbox()" title="تصغير (-)" id="zoomOutBtn">
                        <svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                            <path d="M6 12h12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </button>
                    <button class="toolbar-btn reset-btn" onclick="resetLightboxZoom()" title="إعادة تعيين (1x)">
                        <span id="zoomLevel">100%</span>
                    </button>
                    <button class="toolbar-btn" onclick="zoomInLightbox()" title="تكبير (+)" id="zoomInBtn">
                        <svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                            <path d="M12 5v14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <path d="M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </button>
                </div>

                <!-- معاينات الصور -->
                <div class="toolbar-group thumbnails-group" id="thumbnailsGroup">
                    <div id="thumbnailsContainer" class="thumbnails-container"></div>
                </div>

                <!-- أزرار إضافية -->
                <div class="toolbar-group">
                    <button class="toolbar-btn" onclick="downloadCurrentImage()" title="تنزيل الصورة" id="downloadBtn">
                        <svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                            <path d="M12 3v11" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <path d="M8 11l4 4 4-4" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M6 19h12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </button>
                    <button class="toolbar-btn" onclick="toggleThumbnails()" title="فتح المعاينات" id="thumbnailsBtn">
                        <svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                            <rect x="4" y="4" width="7" height="7" stroke="currentColor" stroke-width="2" fill="none" rx="1"/>
                            <rect x="13" y="4" width="7" height="7" stroke="currentColor" stroke-width="2" fill="none" rx="1"/>
                            <rect x="4" y="13" width="7" height="7" stroke="currentColor" stroke-width="2" fill="none" rx="1"/>
                            <rect x="13" y="13" width="7" height="7" stroke="currentColor" stroke-width="2" fill="none" rx="1"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- معاينات الصور الكاملة -->
        <div id="thumbnailsPanel" class="thumbnails-panel" style="display: none;">
            <div class="thumbnails-scroll">
                <div id="fullThumbnailsContainer" class="thumbnails-full-container"></div>
            </div>
        </div>
    </div>

    <style>
        /* نمط لايتبوكس محسّن */
        #bookingModal {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 20010;
            background: rgba(0, 0, 0, 0.92) !important;
            justify-content: center;
            align-items: flex-start;
            padding-top: 50px;
            overflow-y: auto;
            visibility: hidden;
            opacity: 0;
            transition: opacity 0.2s ease, visibility 0.2s ease;
            pointer-events: none;
        }

        #bookingModal.show {
            display: flex !important;
            visibility: visible;
            opacity: 1;
            pointer-events: auto;
        }

        #bookingModal .booking-modal-content {
            width: min(95%, 900px);
            max-width: 900px;
            background: linear-gradient(135deg, #ffffff 0%, #fafbfc 100%);
            border-radius: 28px;
            padding: 0;
            position: relative;
            max-height: calc(100vh - 60px);
            overflow-y: auto;
            box-sizing: border-box;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.15), 0 0 0 1px rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Modal Header Enhancement */
        .modal-header {
            background: linear-gradient(135deg, #1b365d 0%, #152b4f 100%);
            color: white;
            padding: 24px 32px;
            border-radius: 28px 28px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 20px rgba(27, 54, 93, 0.15);
        }

        .modal-header h3 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            color: white;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .close-modal {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .close-modal:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .lightbox-container {
            display: none;
            position: fixed;
            inset: 0;
            width: 100vw;
            height: 100vh;
            z-index: 99999;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.96);
            visibility: hidden;
            opacity: 0;
            transition: opacity 0.25s ease, visibility 0.25s ease;
            overflow: hidden;
            pointer-events: none;
        }

        .lightbox-container.active {
            display: flex;
            visibility: visible;
            opacity: 1;
            pointer-events: auto;
        }

        body.lightbox-open {
            overflow: hidden !important;
            position: fixed !important;
            width: 100% !important;
            height: 100% !important;
        }

        .lightbox-backdrop {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.98) 0%, rgba(20, 20, 40, 0.98) 100%);
            cursor: pointer;
            z-index: 1;
            pointer-events: all;
        }

        .lightbox-content {
            position: relative;
            width: min(98%, 1240px);
            max-height: calc(100vh - 80px);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px 40px 120px;
            box-sizing: border-box;
            z-index: 10;
            pointer-events: none;
            overflow: visible;
        }

        .lightbox-image-wrapper,
        .lightbox-nav,
        .lightbox-toolbar,
        .lightbox-header {
            pointer-events: all;
        }

        .lightbox-header {
            position: absolute;
            top: 20px;
            left: 20px;
            right: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 10001;
            transition: opacity 0.3s ease;
        }
        }

        .image-counter {
            background: rgba(255, 255, 255, 0.15);
            padding: 8px 16px;
            border-radius: 24px;
            color: white;
            font-size: 0.95rem;
            font-weight: 600;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* .lightbox-close {
            background: rgba(255, 69, 69, 0.9);
            color: white;
            border: none;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(255, 69, 69, 0.4);
        } */

        .lightbox-close:hover {
            background: rgba(255, 50, 50, 1);
            transform: scale(1.1);
            box-shadow: 0 6px 16px rgba(255, 69, 69, 0.6);
        }

        .lightbox-image-wrapper {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            max-width: 98vw;
            max-height: calc(100vh - 180px);
            margin: 120px 0 120px;
            cursor: default;
            overflow: visible;
            min-height: 240px;
        }

        .lightbox-image {
            position: absolute;
            max-width: 98vw;
            max-height: calc(100vh - 180px);
            width: auto;
            height: auto;
            object-fit: contain;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.6);
            transform: scale(1);
            transform-origin: center;
            transition: transform 0.2s ease;
            user-select: none;
            -webkit-user-drag: none;
            pointer-events: auto;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%) scale(1);
            cursor: grab;
        }

        .lightbox-image:hover {
            cursor: grab;
        }

        .lightbox-image:active {
            cursor: grabbing;
        }

        .lightbox-zoom-info {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(255, 255, 255, 0.15);
            color: white;
            padding: 6px 12px;
            border-radius: 16px;
            font-size: 0.85rem;
            font-weight: 600;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .lightbox-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 56px;
            height: 56px;
            border-radius: 50%;
            border: none;
            background: rgba(255, 255, 255, 0.15);
            color: white;
            font-size: 24px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease, opacity 0.3s ease;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            z-index: 10001;
        }

        .lightbox-prev {
            left: 40px;
        }

        .lightbox-next {
            right: 40px;
        }

        .lightbox-nav:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-50%) scale(1.1);
            box-shadow: 0 8px 24px rgba(255, 255, 255, 0.2);
        }

        .lightbox-nav:active {
            transform: translateY(-50%) scale(0.95);
        }

        .lightbox-toolbar {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 20px;
            align-items: center;
            background: rgba(20, 20, 30, 0.95);
            padding: 16px 28px;
            border-radius: 50px;
            backdrop-filter: blur(16px);
            border: 1px solid rgba(212, 175, 55, 0.3);
            box-shadow: 0 12px 48px rgba(0, 0, 0, 0.5);
            z-index: 10001;
            transition: all 0.3s ease, opacity 0.3s ease;
        }

        .lightbox-toolbar.auto-hide {
            opacity: 0;
            pointer-events: none;
        }

        .lightbox-toolbar:not(.auto-hide) {
            opacity: 1;
            pointer-events: all;
        }

        .lightbox-header.auto-hide {
            opacity: 0;
            pointer-events: none;
        }

        .lightbox-header:not(.auto-hide) {
            opacity: 1;
            pointer-events: all;
        }

        .lightbox-nav.auto-hide {
            opacity: 0;
            pointer-events: none;
        }

        .lightbox-nav:not(.auto-hide) {
            opacity: 1;
            pointer-events: all;
        }

        .lightbox-toolbar.compact {
            width: auto;
            min-width: 64px;
            padding: 12px;
            border-radius: 50%;
            gap: 0;
            justify-content: center;
        }

        .lightbox-toolbar.compact .toolbar-group:not(.compact-toggle-group) {
            display: none !important;
        }

        .toolbar-group {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .toolbar-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.1);
            color: white;
            cursor: pointer;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .toolbar-btn svg {
            width: 18px;
            height: 18px;
            display: block;
            fill: none;
            stroke: currentColor;
        }

        .spinner-icon {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

        .toolbar-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(255, 255, 255, 0.15);
        }

        .thumbnails-group.hidden-thumbnails {
            display: none !important;
        }

        .reset-btn {
            padding: 0 12px;
            border-radius: 20px;
            width: auto;
            background: rgba(212, 175, 55, 0.2);
            border-color: rgba(212, 175, 55, 0.5);
            color: #d4af37;
            font-weight: 700;
            font-size: 0.85rem;
        }

        .reset-btn:hover {
            background: rgba(212, 175, 55, 0.35);
            color: #ffd966;
        }

        .thumbnails-panel {
            position: absolute;
            bottom: 150px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            padding: 12px;
            max-width: 90vw;
            max-height: 150px;
            overflow-y: auto;
            z-index: 10001;
            animation: slideUp 0.3s ease;
        }

        .thumbnails-panel:active {
            pointer-events: all;
        }

        .thumbnails-header {
            display: flex;
            justify-content: flex-end;
            padding: 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin: -12px -12px 12px -12px;
        }

        .thumbnails-close-btn {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .thumbnails-close-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.1);
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateX(-50%) translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateX(-50%) translateY(0);
            }
        }

        .thumbnails-scroll {
            display: flex;
            gap: 10px;
        }

        .thumbnails-full-container {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .lightbox-thumbnail {
            width: 70px;
            height: 70px;
            border-radius: 12px;
            cursor: pointer;
            border: 2px solid rgba(255, 255, 255, 0.2);
            object-fit: contain;
            background: rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .lightbox-thumbnail:hover {
            border-color: rgba(255, 255, 255, 0.5);
            transform: scale(1.08);
        }

        .lightbox-thumbnail.active {
            border-color: #d4af37;
            box-shadow: 0 0 12px rgba(212, 175, 55, 0.6);
        }

        /* استجابة للأجهزة الصغيرة */
        @media (max-width: 768px) {
            .lightbox-content {
                padding: 10px;
            }

            .lightbox-header {
                top: 10px;
                left: 10px;
                right: 10px;
            }

            .lightbox-nav {
                width: 48px;
                height: 48px;
                font-size: 20px;
            }

            .lightbox-prev {
                left: 10px;
            }

            .lightbox-next {
                right: 10px;
            }

            .lightbox-toolbar {
                bottom: 10px;
                padding: 10px 16px;
                gap: 12px;
            }

            .toolbar-btn {
                width: 36px;
                height: 36px;
                font-size: 14px;
            }

            .image-counter {
                font-size: 0.85rem;
                padding: 6px 12px;
            }

            .lightbox-image-wrapper {
                margin-top: 80px;
                margin-bottom: 100px;
            }
        }

        .lightbox-actions {
            display: flex;
            gap: 10px;
        }

        .lightbox-btn {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .lightbox-btn:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: scale(1.05);
        }
    </style>

    </script>

@endsection
