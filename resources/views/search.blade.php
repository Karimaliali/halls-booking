@extends('layouts.app')

@section('title', 'بحث عن قاعات | قاعة')
@section('body-class', 'page-search page-hall-details')

@section('content')
    @push('styles')
    <style>
        body.page-hall-details .navbar {
            background: linear-gradient(135deg, #1b365d 0%, #152b4f 100%) !important;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3) !important;
            border-bottom: 1px solid rgba(212, 175, 55, 0.2) !important;
            z-index: 10000 !important;
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

        .results-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 22px;
            margin-top: 24px;
        }

        .result-card {
            background: linear-gradient(135deg, rgba(27, 54, 93, 0.85) 0%, rgba(21, 43, 79, 0.75) 100%);
            border: 1px solid rgba(212, 175, 55, 0.25);
            border-radius: 24px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: none;
            cursor: pointer;
        }

        .result-card:hover {
            transform: none;
            box-shadow: none;
            border-color: rgba(212, 175, 55, 0.3);
        }

        .result-img {
            position: relative;
            min-height: 220px;
            overflow: hidden;
        }

        .result-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .result-badge {
            position: absolute;
            top: 16px;
            right: 16px;
            padding: 8px 14px;
            border-radius: 999px;
            font-size: 0.85rem;
            font-weight: 700;
            color: #fff;
            backdrop-filter: blur(10px);
        }

        .result-badge.available {
            background: rgba(16, 185, 129, 0.92);
        }

        .result-badge.unavailable {
            background: rgba(239, 68, 68, 0.92);
        }

        .fav-btn {
            position: absolute;
            top: 16px;
            left: 16px;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            border: none;
            background: rgba(0, 0, 0, 0.28);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: none;
        }

        .fav-btn:hover {
            background: rgba(0, 0, 0, 0.42);
        }

        .result-content {
            padding: 20px 22px 24px;
            display: flex;
            flex-direction: column;
            gap: 14px;
            flex: 1;
        }

        .result-content h4 {
            margin: 0;
            font-size: 1.15rem;
            color: #fff;
            font-weight: 800;
        }

        .result-header {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: flex-start;
        }

        .result-header > div:first-child {
            flex: 1;
        }

        .result-tag {
            display: inline-block;
            background: rgba(212, 175, 55, 0.25);
            color: #ffd54f;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 700;
            white-space: nowrap;
            margin-top: 6px;
        }

        .rating {
            display: flex !important;
            align-items: center;
            gap: 6px;
            color: #ffd54f !important;
            font-weight: 700;
            flex-wrap: wrap;
        }

        .rating i {
            color: #ffd54f;
        }

        .reviews-count {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.8);
        }

        .result-location {
            color: #fff;
            font-size: 0.95rem;
            line-height: 1.5;
            font-weight: 500;
        }

        .result-location i {
            color: #ffd54f;
            margin-right: 6px;
        }

        .result-features {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .result-features span {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(212, 175, 55, 0.15);
            color: #ffd54f;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .result-features span i {
            color: #10b981;
        }

        .result-features span:first-child i {
            color: #ffd54f;
        }

        .result-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 8px;
            padding-top: 14px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .result-price {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .result-price .price {
            font-size: 1.35rem;
            font-weight: 900;
            color: #ffd54f;
        }

        .result-price .price-note {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.8);
        }

        .btn-view {
            display: inline-flex;
            gap: 8px;
            align-items: center;
            justify-content: center;
            padding: 10px 18px;
            border-radius: 999px;
            background: rgba(212, 175, 55, 0.95);
            color: #1b365d;
            font-weight: 700;
            text-decoration: none;
            transition: none;
        }

        .btn-view:hover {
            background: rgba(255, 215, 85, 0.95);
            transform: none;
        }

        .result-excerpt {
            color: rgba(255, 255, 255, 0.85);
            font-size: 0.95rem;
            line-height: 1.6;
            min-height: 3rem;
        }
    </style>
    @endpush
    <!-- Header البحث -->
    <div class="search-header">
        <div class="container">
            <h1><i class="fas fa-search"></i> البحث عن القاعات</h1>
            <p>ابحث بين أكثر من 5000 قاعة في جميع أنحاء مصر واختر الأفضل لك</p>
        </div>
    </div>

    <!-- شريط البحث المتقدم -->
    <section class="advanced-search">
        <div class="container">
            <div class="search-wrapper">
                <form id="search-form" method="GET" action="{{ route('search') }}" class="search-filters">
                    <input type="hidden" name="feature" id="filter-feature" value="{{ request('feature', '') }}" />
                    <div class="filter-row">
                        <div class="filter-group" style="position: relative;">
                            <label><i class="fa fa-map-marker-alt"></i> المدينة أو المنطقة</label>
                            <input
                                id="searchLocationInput"
                                name="location"
                                type="text"
                                placeholder="ابحث عن مدينة..."
                                value="{{ request('location', '') }}"
                                autocomplete="off"
                                style="width: 100%;"
                            />
                            <ul id="searchLocationDropdown" style="position: absolute; top: 100%; left: 0; right: 0; background: rgba(0,0,0,0.8); border: 1px solid rgba(255,255,255,0.2); border-top: none; border-radius: 0 0 12px 12px; max-height: 300px; overflow-y: auto; list-style: none; margin: 0; padding: 0; z-index: 1000; display: none;">
                            </ul>
                        </div>
                        <div class="filter-group">
                            <label><i class="fa fa-calendar"></i> تاريخ المناسبة</label>
                            <input name="date" type="date" value="{{ request('date', '') }}" />
                        </div>
                    </div>
                    <div class="filter-row">
                        <div class="filter-group">
                            <label
                                ><i class="fa fa-users"></i> عدد
                                الضيوف</label
                            >
                            <select name="guests">
                                <option value="">اختر عدد الضيوف</option>
                                <option value="أقل من 100" {{ request('guests') == 'أقل من 100' ? 'selected' : '' }}>أقل من 100</option>
                                <option value="100 - 300" {{ request('guests') == '100 - 300' ? 'selected' : '' }}>100 - 300</option>
                                <option value="300 - 600" {{ request('guests') == '300 - 600' ? 'selected' : '' }}>300 - 600</option>
                                <option value="أكثر من 600" {{ request('guests') == 'أكثر من 600' ? 'selected' : '' }}>أكثر من 600</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label
                                ><i class="fa fa-tag"></i> تصنيف
                                القاعة</label
                            >
                            <select name="category">
                                <option value="">جميع التصنيفات</option>
                                <option value="قاعات أفراح" {{ request('category') == 'قاعات أفراح' ? 'selected' : '' }}>قاعات أفراح</option>
                                <option value="قاعات مؤتمرات" {{ request('category') == 'قاعات مؤتمرات' ? 'selected' : '' }}>قاعات مؤتمرات</option>
                                <option value="قاعات مناسبات" {{ request('category') == 'قاعات مناسبات' ? 'selected' : '' }}>قاعات مناسبات</option>
                                <option value="قاعات اجتماعات" {{ request('category') == 'قاعات اجتماعات' ? 'selected' : '' }}>قاعات اجتماعات</option>
                            </select>
                        </div>
                    </div>
                    <div class="filter-row price-range-row">
                        <div class="filter-group price-range">
                            <label><i class="fa fa-money-bill"></i> نطاق السعر (بالجنيه)</label>
                            <div class="price-inputs">
                                <input
                                    name="min_price"
                                    type="number"
                                    placeholder="الحد الأدنى"
                                    value="{{ request('min_price', '') }}"
                                />
                                <span>إلى</span>
                                <input
                                    name="max_price"
                                    type="number"
                                    placeholder="الحد الأقصى"
                                    value="{{ request('max_price', '') }}"
                                />
                            </div>
                        </div>
                        <div class="search-actions">
                            <button type="submit" class="btn-search-primary">
                                <i class="fa fa-search"></i> بحث
                            </button>
                            <button
                                class="btn-reset-filters"
                                type="button"
                                onclick="window.location='{{ route('search') }}'"
                            >
                                <i class="fa fa-rotate-left"></i> إعادة تعيين الفلاتر
                            </button>
                        </div>
                    </div>
                </form>

                <!-- الفلاتر السريعة (Tags) -->
                <div class="quick-filters">
                    <span class="filter-tag {{ request('feature') === '' ? 'active' : '' }}" data-feature="">الكل</span>
                    <span class="filter-tag {{ request('feature') === 'مكيفة' ? 'active' : '' }}" data-feature="مكيفة">مكيفة</span>
                    <span class="filter-tag {{ request('feature') === 'مواقف سيارات' ? 'active' : '' }}" data-feature="مواقف سيارات">مواقف سيارات</span>
                    <span class="filter-tag {{ request('feature') === 'واي فاي' ? 'active' : '' }}" data-feature="واي فاي">واي فاي</span>
                    <span class="filter-tag {{ request('feature') === 'بوفيه مفتوح' ? 'active' : '' }}" data-feature="بوفيه مفتوح">بوفيه مفتوح</span>
                    <span class="filter-tag {{ request('feature') === 'قاعة نساء' ? 'active' : '' }}" data-feature="قاعة نساء">قاعة نساء</span>
                    <span class="filter-tag {{ request('feature') === 'قاعة رجال' ? 'active' : '' }}" data-feature="قاعة رجال">قاعة رجال</span>
                    <span class="filter-tag {{ request('feature') === 'غرفة عروس' ? 'active' : '' }}" data-feature="غرفة عروس">غرفة عروس</span>
                    <span class="filter-tag {{ request('feature') === 'شاشات عرض' ? 'active' : '' }}" data-feature="شاشات عرض">شاشات عرض</span>
                </div>
            </div>
        </div>
    </section>

    <!-- نتائج البحث -->
    <section class="search-results">
        <div class="container">
            <!-- عنوان النتائج وترتيبها -->
            <div class="results-header">
                <div class="results-count">
                    <h3>{{ isset($halls) ? $halls->count() : 0 }} قاعة متاحة</h3>
                    <p>تم العثور على {{ isset($halls) ? $halls->count() : 0 }} قاعة تطابق معايير بحثك</p>
                </div>
                <div class="sort-options">
                    <label>ترتيب حسب:</label>
                    <select name="sort" form="search-form">
                        <option value="" {{ request('sort') === '' ? 'selected' : '' }}>الأكثر طلباً</option>
                        <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>الأقل سعراً</option>
                        <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>الأعلى سعراً</option>
                        <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>الأحدث</option>
                    </select>
                </div>
            </div>

            <div class="results-grid">
                @if(isset($halls) && $halls->count())
                    @foreach($halls as $hall)
                        <div class="result-card" onclick="window.location.href='{{ route('halls.show', $hall) }}'">
                            <div class="result-img">
                                <img
                                    src="{{ $hall->main_image_url }}"
                                    alt="{{ $hall->name }}"
                                />
                                <span class="result-badge {{ trim($hall->status) === 'متاح' || trim($hall->status) === 'active' ? 'available' : 'unavailable' }}">
                                    {{ trim($hall->status) === 'متاح' || trim($hall->status) === 'active' ? 'متاحة' : 'غير متاحة' }}
                                </span>
                                <button class="fav-btn" type="button" onclick="event.stopPropagation();">
                                    <i class="fa fa-heart"></i>
                                </button>
                            </div>
                            <div class="result-content">
                                <div class="result-header">
                                    <div>
                                        <h4>{{ $hall->name }}</h4>
                                        <span class="result-tag">{{ $hall->category ?: 'قاعة فاخرة' }}</span>
                                    </div>
                                    <div class="rating">
                                        <i class="fa fa-star"></i>
                                        <span>{{ number_format($hall->reviews_avg_rating ?? 4.6, 1) }}</span>
                                        <span class="reviews-count">({{ $hall->reviews_count ?? 0 }})</span>
                                    </div>
                                </div>
                                <p class="result-excerpt">{{ \Illuminate\Support\Str::limit($hall->description ?? 'قاعة مميزة مجهزة بكل الخدمات المطلوبة.', 100) }}</p>
                                <p class="result-location">
                                    <i class="fa fa-map-pin"></i> {{ $hall->location }}
                                </p>
                                <div class="result-features">
                                    <span><i class="fa fa-users"></i> {{ $hall->capacity }} ضيف</span>
                                    @if($hall->features)
                                        @foreach(array_slice($hall->features, 0, 3) as $feature)
                                            <span><i class="fa fa-check"></i> {{ $feature }}</span>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="result-footer">
                                    <div class="result-price">
                                        <span class="price">{{ number_format($hall->price) }} ج.م</span>
                                        <span class="price-note">/ ليلة</span>
                                    </div>
                                    <a href="{{ route('halls.show', $hall) }}" class="btn-view" onclick="event.stopPropagation()">عرض التفاصيل</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="no-results-card">
                        <p>لا توجد نتائج للبحث الحالي.</p>
                    </div>
                @endif
            </div>

            @if(isset($halls) && $halls instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
                <div class="pagination-wrapper" style="margin-top: 40px; text-align: center;">
                    {{ $halls->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </section>

    <!-- خريطة النتائج (اختياري) -->
    <section class="map-section">
        <div class="container">
            <div class="map-toggle">
                <button class="btn-toggle-map">
                    <i class="fa fa-map"></i> عرض القاعات على الخريطة
                </button>
            </div>
            <div class="map-container hidden">
                <img
                    src="https://via.placeholder.com/1200x300.png?text=خريطة+القاعات"
                    alt="خريطة القاعات"
                />
            </div>
        </div>
    </section>

    <footer id="contact">
        <div class="container footer-content">
            <div class="footer-brand">
                <div class="logo">قاعة</div>
                <p>
                    الخيار الأول لحجز وتنظيم القاعات في الشرق الأوسط. نضمن
                    لك السهولة، الشفافية، والأمان في كل حجز.
                </p>
            </div>

            <div class="footer-contact">
                <h5>تواصل معنا</h5>
                <p><i class="fa fa-phone"></i> 01552585217 </p>
                <p><i class="fa fa-envelope"></i> karim2elshazly@gmail.com</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>جميع الحقوق محفوظة &copy; 2026 Karim Elshazly</p>
        </div>
    </footer>

    <script>
        const americanCities = [
            // محافظة الدقهلية (في الأول)
            'المنصورة - الدقهلية',
            'ميت غمر - الدقهلية',
            'منية النصر - الدقهلية',
            'ديرب نجم - الدقهلية',
            'الجمالية - الدقهلية',
            'دكرنس - الدقهلية',
            'سنبارة - الدقهلية',
            'ومن - الدقهلية',
            'طلخا - الدقهلية',
            'بلقاس - الدقهلية',
            
            // باقي المحافظات
            'القاهرة',
            'الجيزة',
            'الإسكندرية',
            'بورسعيد',
            'السويس',
            'الأقصر',
            'أسوان',
            'الغردقة',
            'شرم الشيخ',
            'الفيوم',
            'بني سويف',
            'المنيا',
            'طنطا',
            'كفر الشيخ',
            'دمياط',
            'المحلة الكبرى',
            'الإسماعيلية',
            'العريش',
            'رفح',
            'الوادي الجديد',
            'مطروح',
            'السلوم',
            'سيوة',
            'الفاشر',
            'الخارجة',
            'إدفو',
            'إسنا',
            'قنا',
            'سوهاج',
            'أسيوط',
            'ملوي'
        ];

        const searchLocationInput = document.getElementById('searchLocationInput');
        const searchLocationDropdown = document.getElementById('searchLocationDropdown');

        if (searchLocationInput) {
            searchLocationInput.addEventListener('input', function() {
                const value = this.value.toLowerCase();
                const filtered = americanCities.filter(city => city.includes(value) && city !== this.value);
                
                if (value.length > 0 && filtered.length > 0) {
                    searchLocationDropdown.innerHTML = filtered.map(city => 
                        `<li style="padding: 10px 14px; cursor: pointer; border-bottom: 1px solid rgba(255,255,255,0.1); color: #fff; transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='transparent'" onclick="selectSearchLocation('${city}')">${city}</li>`
                    ).join('');
                    searchLocationDropdown.style.display = 'block';
                } else {
                    searchLocationDropdown.style.display = 'none';
                }
            });

            searchLocationInput.addEventListener('focus', function() {
                if (this.value.length === 0) {
                    searchLocationDropdown.innerHTML = americanCities.map(city => 
                        `<li style="padding: 10px 14px; cursor: pointer; border-bottom: 1px solid rgba(255,255,255,0.1); color: #fff; transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='transparent'" onclick="selectSearchLocation('${city}')">${city}</li>`
                    ).join('');
                    searchLocationDropdown.style.display = 'block';
                }
            });

            document.addEventListener('click', function(e) {
                if (e.target !== searchLocationInput) {
                    searchLocationDropdown.style.display = 'none';
                }
            });
        }

        function selectSearchLocation(city) {
            searchLocationInput.value = city;
            searchLocationDropdown.style.display = 'none';
        }
    </script>
@endsection

