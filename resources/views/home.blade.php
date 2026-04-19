@extends('layouts.app')

@section('title', 'QAA\'A | منصة حجز القاعات الكبرى')
@section('body-class', 'page-home')

@section('content')
    <header class="hero" id="home">
        <div class="hero-overlay"></div>
        <div class="hero-particles">
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
        </div>
        <div class="hero-content">
            <div class="hero-badge">
                <span class="badge-icon"><i class="fas fa-crown"></i></span>
                <span class="badge">منصة QAA'A - أفضل منصة حجز قاعات</span>
            </div>
            <h1 class="hero-title">
                احجز القاعة المثالية<br />
                <span class="highlight">لمناسبتك القادمة</span><br />
                بكل سهولة وأمان
            </h1>
            <p class="hero-subtitle">
                نوفر لك الوصول لأكثر من 500 قاعة فاخرة في جميع أنحاء مصر 
                مجهزة بالكامل وبأسعار تنافسية لتلبي كافة تطلعاتك
            </p>

            <div class="search-panel" id="search-section">
                <div class="search-header">
                    <h3><i class="fas fa-filter"></i> ابحث عن قاعتك الآن</h3>
                </div>
                <form class="search-body" method="GET" action="{{ route('search') }}">
                    <div class="input-group" style="position: relative; z-index: 10002;">
                        <label><i class="fas fa-map-marker-alt"></i> المدينة أو المنطقة</label>
                        <input 
                            id="homeLocationInput" 
                            name="location" 
                            type="text" 
                            placeholder="ابدأ بالكتابة للبحث..." 
                            autocomplete="off"
                            style="width: 100%; padding: 14px 16px; font-size: 14px; border: 2px solid rgba(27, 54, 93, 0.15); border-radius: 12px; min-height: 54px; box-sizing: border-box;"
                        />
                        <ul id="homeLocationDropdown" style="
                            position: absolute;
                            top: 100%;
                            left: 0;
                            right: 0;
                            background: white;
                            border: 1px solid #ddd;
                            border-top: none;
                            max-height: 240px;
                            overflow-y: auto;
                            list-style: none;
                            margin: 0;
                            padding: 0;
                            display: none;
                            z-index: 10003;
                            border-radius: 0 0 12px 12px;
                            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.16);
                        ">
                        </ul>
                    </div>
                    <div class="input-group" style="position: relative; z-index: 1;">
                        <label><i class="fas fa-calendar-alt"></i> موعد المناسبة</label>
                        <input name="date" type="date" min="{{ date('Y-m-d') }}" style="position: relative; z-index: 2;" />
                    </div>
                    <div class="input-group">
                        <label><i class="fas fa-users"></i> عدد الضيوف</label>
                        <select name="guests">
                            <option value="">اختر عدد الضيوف</option>
                            <option value="أقل من 100 ضيف">أقل من 100 ضيف</option>
                            <option value="100 - 300 ضيف">100 - 300 ضيف</option>
                            <option value="300 - 600 ضيف">300 - 600 ضيف</option>
                            <option value="أكثر من 600 ضيف">أكثر من 600 ضيف</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-search" style="z-index: 1; position: relative;">
                        <i class="fas fa-search"></i> بحث
                    </button>
                </form>
            </div>
        </div>
    </header>

    <script>
        const cities = [
            // مدن محافظة الدقهليه - الأولى
            'المنصورة - الدقهليه',
            'طلخا - الدقهليه',
            'بلقاس - الدقهليه',
            'ميت غمر - الدقهليه',
            'السنبلاوين - الدقهليه',
            'الجمالية - الدقهليه',
            'منية النصر - الدقهليه',
            'أجا - الدقهليه',
            'بني عبيد - الدقهليه',
            'شرقية - الدقهليه',
            
            // باقي المدن المصرية
            'الإسكندرية',
            'أسوان',
            'أسيوط',
            'الإسماعيلية',
            'الأقصر',
            'البحيرة',
            'بني سويف',
            'القاهرة',
            'الفيوم',
            'الغربية',
            'جنوب سيناء',
            'الجيزة',
            'حلوان',
            'القليوبية',
            'كفر الشيخ',
            'المنيا',
            'مرسى مطروح',
            'الوادي الجديد',
            'شمال سيناء',
            'بورسعيد',
            'ردفان',
            'السويس',
            'طنطا',
            'الشرقية',
            'سوهاج',
            'أبو قير',
            'العريش',
            'الزقازيق',
            'بنها',
            'المحلة الكبرى',
            'كفر الدوار',
            'شبين الكوم',
            'ديرب نجم',
            'الحامول',
            'فاقوس',
            'قنا',
            'دمياط',
            'رشيد',
            'الغردقة',
            'الفرما',
            'مرسى علم',
            'رأس غارب',
            'سفاجا',
            'مطروح',
            'نويبع',
            'طابا'
        ];

        const homeLocationInput = document.getElementById('homeLocationInput');
        const homeLocationDropdown = document.getElementById('homeLocationDropdown');

        function filterCities(val) {
            homeLocationDropdown.innerHTML = '';
            const searchValue = val.trim();

            const filtered = searchValue.length === 0
                ? cities
                : cities.filter(city => city.includes(searchValue));

            if (filtered.length === 0) {
                homeLocationDropdown.style.display = 'none';
                return;
            }

            filtered.forEach(city => {
                const li = document.createElement('li');
                li.textContent = city;
                li.style.cssText = `
                    padding: 12px 14px;
                    cursor: pointer;
                    border-bottom: 1px solid #f0f0f0;
                    transition: background-color 0.2s, color 0.2s;
                    background: white;
                    color: #102a43;
                    font-size: 0.95rem;
                `;
                li.onmouseover = () => li.style.backgroundColor = '#f2f6fb';
                li.onmouseout = () => li.style.backgroundColor = 'white';
                li.onclick = () => selectHomeLocation(city);
                homeLocationDropdown.appendChild(li);
            });
            
            homeLocationDropdown.style.display = 'block';
        }

        function selectHomeLocation(city) {
            homeLocationInput.value = city;
            homeLocationDropdown.style.display = 'none';
        }

        homeLocationInput.addEventListener('input', (e) => {
            filterCities(e.target.value);
        });

        homeLocationInput.addEventListener('focus', () => {
            filterCities(homeLocationInput.value);
        });

        document.addEventListener('click', (e) => {
            if (e.target !== homeLocationInput && !homeLocationDropdown.contains(e.target)) {
                homeLocationDropdown.style.display = 'none';
            }
        });
    </script>

    <section class="stats">
        <div class="container stats-wrapper">
            <div class="stat-box">
                <h3>+500</h3>
                <p>قاعة مجهزة</p>
            </div>
            <div class="stat-box">
                <h3>+50</h3>
                <p>مدينة مغطاة</p>
            </div>
            <div class="stat-box">
                <h3>H24</h3>
                <p>دعم فني للحجوزات</p>
            </div>
            <div class="stat-box">
                <h3>100%</h3>
                <p>حجز مؤكد وآمن</p>
            </div>
        </div>
    </section>

    <section class="section halls" id="featured">
        <div class="container">
            <div class="section-header">
                <h2>أفخم القاعات المتاحة حالياً</h2>
                <p>
                    اختر من بين مجموعة مختارة من القاعات التي نالت أعلى
                    تقييم من عملائنا. كل قاعة تضمن تجربة لا تُنسى.
                </p>
            </div>

            <div class="grid-3">
                @forelse($featuredHalls as $hall)
                    @php
                        $imageUrl = $hall->main_image_url;
                        $rating = $hall->reviews_avg_rating ? number_format($hall->reviews_avg_rating, 1) : '0.0';
                        $reviewCount = $hall->reviews_count ?? 0;
                        $statusLabel = $hall->status ?: 'متاحة اليوم';
                        $statusClass = 'status-available';
                        if (str_contains(strtolower($statusLabel), 'خصم')) {
                            $statusClass = 'status-discount';
                        } elseif (str_contains(strtolower($statusLabel), 'طلب') || str_contains(strtolower($statusLabel), 'مطلوب') || str_contains(strtolower($statusLabel), 'شعبية') || str_contains(strtolower($statusLabel), 'أكثر')) {
                            $statusClass = 'status-popular';
                        }
                        $features = is_array($hall->features) ? array_filter($hall->features) : [];
                    @endphp
                    <div class="card card-hover {{ $statusClass === 'status-popular' ? 'card-premium' : '' }}" onclick="window.location.href='{{ route('halls.show', $hall) }}'" style="cursor: pointer;">
                        <div class="card-img-wrapper">
                            <div class="card-img" style="background-image: url('{{ $imageUrl }}');">
                                <div class="hall-status {{ $statusClass }}">{{ $statusLabel }}</div>
                                <div class="hall-badge">
                                    <i class="fas fa-heart"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h3>{{ $hall->name }}</h3>
                            <p class="location">
                                <i class="fa fa-map-pin"></i> {{ $hall->location }}
                            </p>
                            <div class="card-header-info">
                                <div class="price-info">
                                    تبدأ من <span>{{ number_format($hall->price, 0, '.', ',') }} ج.م</span>
                                </div>
                                <div class="rating">
                                    <i class="fas fa-star"></i>
                                    <span>{{ $rating }}</span>
                                    <p>({{ $reviewCount }} تقييم)</p>
                                </div>
                            </div>
                            <div class="hall-specs">
                                <span><i class="fa fa-users"></i> {{ $hall->capacity ?? 'غير محدد' }} ضيف</span>
                                <span><i class="fa fa-cogs"></i> {{ $hall->category ?: 'قاعة فاخرة' }}</span>
                            </div>
                            <div class="card-features">
                                @forelse(array_slice($features, 0, 2) as $feature)
                                    <span class="badge-feature">{{ $feature }}</span>
                                @empty
                                    <span class="badge-feature">خدمة كاملة</span>
                                    <span class="badge-feature">مساحة واسعة</span>
                                @endforelse
                            </div>
                            <a class="btn btn-full btn-hover" href="{{ route('halls.show', $hall) }}" onclick="event.stopPropagation()">
                                <i class="fas fa-calendar-check"></i> عرض وتأكيد الحجز
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="card card-hover">
                        <div class="card-body text-center" style="padding: 3rem;">
                            <h3>لا توجد قاعات مميزة حالياً</h3>
                            <p>سيتم عرض أفضل القاعات هنا عند توفر بيانات جديدة.</p>
                            <a href="{{ route('search') }}" class="btn btn-full btn-hover">
                                <i class="fas fa-search"></i> تصفح جميع القاعات
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
            <div class="text-center mt-4">
                <a href="{{ route('search') }}" class="btn btn-outline btn-lg">
                    عرض جميع القاعات المتاحة
                </a>
            </div>
        </div>
    </section>

    <section class="section steps-section" id="how-to-book">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2>كيفية الحجز في 3 خطوات سهلة</h2>
                <p>عملية حجز بسيطة وآمنة تنهي حفلتك المثالية</p>
            </div>
            <div class="steps-container">
                <div class="step-item step-card">
                    <div class="step-connector"></div>
                    <div class="step-icon-wrapper">
                        <div class="step-icon">
                            <i class="fas fa-search"></i>
                        </div>
                    </div>
                    <div class="step-content">
                        <h4>ابحث عن قاعة</h4>
                        <p>
                            استخدم محرك البحث المتقدم للعثور على القاعة الأنسب
                            بناءً على الموقع، التاريخ، وعدد ضيوفك.
                        </p>
                        <ul class="step-features">
                            <li><i class="fas fa-check-circle"></i> فلترة متقدمة</li>
                            <li><i class="fas fa-check-circle"></i> خدمات مخصصة</li>
                        </ul>
                    </div>
                </div>

                <div class="step-arrow">
                    <i class="fas fa-arrow-left"></i>
                </div>

                <div class="step-item step-card">
                    <div class="step-connector"></div>
                    <div class="step-icon-wrapper">
                        <div class="step-icon">
                            <i class="fas fa-compare"></i>
                        </div>
                    </div>
                    <div class="step-content">
                        <h4>قارن الأسعار والمزايا</h4>
                        <p>
                            تصفح الصور الحقيقية، المزايا، والخدمات الإضافية لكل
                            قاعة وقارن بين العروض المختلفة.
                        </p>
                        <ul class="step-features">
                            <li><i class="fas fa-check-circle"></i> مقارنة شاملة</li>
                            <li><i class="fas fa-check-circle"></i> عروض خاصة</li>
                        </ul>
                    </div>
                </div>

                <div class="step-arrow">
                    <i class="fas fa-arrow-left"></i>
                </div>

                <div class="step-item step-card">
                    <div class="step-connector"></div>
                    <div class="step-icon-wrapper">
                        <div class="step-icon">
                            <i class="fas fa-lock"></i>
                        </div>
                    </div>
                    <div class="step-content">
                        <h4>احجز وادفع بأمان</h4>
                        <p>
                            أكد حجزك من خلال بوابات دفع آمنة ومشفرة واحصل على عقد
                            إلكتروني فوري وتأكيد فوري.
                        </p>
                        <ul class="step-features">
                            <li><i class="fas fa-check-circle"></i> دفع آمن مشفر</li>
                            <li><i class="fas fa-check-circle"></i> ضمان الحجز</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="steps-cta">
                <a href="{{ route('search') }}" class="btn btn-lg btn-primary">
                    <i class="fas fa-play-circle"></i> ابدأ البحث الآن
                </a>
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
                <p><i class="fa fa-phone"></i> 01552585217</p>
                <p><i class="fa fa-envelope"></i> karim2elshazly@gmail.com</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>جميع الحقوق محفوظة &copy;2026 Karim Elshazly</p>
        </div>
    </footer>
@endsection
