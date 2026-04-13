@extends('layouts.app')

@section('title', 'بحث عن قاعات | قاعة')
@section('body-class', 'page-search')

@section('content')
    <!-- Header البحث -->
    <div class="search-header">
        <div class="container">
            <h1>البحث عن القاعات</h1>
            <p>ابحث بين أكثر من 5000 قاعة في جميع أنحاء مصر</p>
        </div>
    </div>

    <!-- شريط البحث المتقدم -->
    <section class="advanced-search">
        <div class="container">
            <div class="search-wrapper">
                <form id="search-form" method="GET" action="{{ route('search') }}" class="search-filters">
                    <input type="hidden" name="feature" id="filter-feature" value="{{ request('feature', '') }}" />
                    <div class="filter-row">
                        <div class="filter-group">
                            <label><i class="fa fa-map-marker-alt"></i> المدينة أو المنطقة</label>
                            <input
                                name="location"
                                type="text"
                                placeholder="مثال: الرياض، جدة، المنصورة"
                                value="{{ request('location', '') }}"
                            />
                        </div>
                        <div class="filter-group">
                            <label><i class="fa fa-calendar"></i> تاريخ المناسبة</label>
                            <input name="date" type="date" value="{{ request('date', '') }}" />
                        </div>
                    </div>
                    <div class="filter-row">
                        <div class="filter-group">
                            <label><i class="fa fa-users"></i> عدد الضيوف</label>
                            <select name="guests">
                                <option value="">اختر عدد الضيوف</option>
                                <option value="أقل من 100" {{ request('guests') == 'أقل من 100' ? 'selected' : '' }}>أقل من 100</option>
                                <option value="100 - 300" {{ request('guests') == '100 - 300' ? 'selected' : '' }}>100 - 300</option>
                                <option value="300 - 600" {{ request('guests') == '300 - 600' ? 'selected' : '' }}>300 - 600</option>
                                <option value="أكثر من 600" {{ request('guests') == 'أكثر من 600' ? 'selected' : '' }}>أكثر من 600</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label><i class="fa fa-tag"></i> تصنيف القاعة</label>
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
            <div class="results-header">
                <div class="results-count">
                    <h3>{{ isset($halls) ? ($halls instanceof \Illuminate\Pagination\LengthAwarePaginator ? $halls->total() : $halls->count()) : 0 }} قاعات</h3>
                    <p>تم العثور على {{ isset($halls) ? ($halls instanceof \Illuminate\Pagination\LengthAwarePaginator ? $halls->total() : $halls->count()) : 0 }} قاعة تطابق معايير بحثك</p>
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
                        <div class="result-card">
                            <div class="result-img">
                                <img
                                    src="{{ $hall->main_image_url }}"
                                    alt="{{ $hall->name }}"
                                />
                                <span class="result-badge available">متاحة</span>
                                <button class="fav-btn">
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
                                        <span>4.8</span>
                                        <span class="reviews-count">(0)</span>
                                    </div>
                                </div>
                                <p class="result-location">
                                    <i class="fa fa-map-pin"></i> {{ $hall->location }}
                                </p>
                                <div class="result-features">
                                    <span><i class="fa fa-users"></i> {{ $hall->capacity }} ضيف</span>
                                    @if($hall->features)
                                        @foreach($hall->features as $feature)
                                            <span><i class="fa fa-check"></i> {{ $feature }}</span>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="result-footer">
                                    <div class="result-price">
                                        <span class="price">{{ number_format($hall->price) }} ج.م</span>
                                        <span class="price-note">/ ليلة</span>
                                    </div>
                                    <a href="{{ route('halls.show', $hall) }}" class="btn-view">عرض التفاصيل</a>
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

            @if(isset($halls) && $halls instanceof \Illuminate\Pagination\LengthAwarePaginator)
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
@endsection
