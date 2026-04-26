@extends('layouts.app')

@section('title', 'تعديل القاعة')

@section('body-class', 'page-owner-edit-hall')

@section('content')
    <div class="container" style="padding: 110px 20px 40px;">
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;">
            <div>
                <h2 style="margin: 0 0 10px;">تعديل القاعة</h2>
                <p style="margin: 0; color: rgba(255,255,255,0.75);">قم بتحديث بيانات القاعة.</p>
            </div>
            <a href="{{ route('owner.halls') }}" class="nav-auth-btn" style="background: rgba(255,255,255,0.12); color: #fff;">رجوع إلى قاعاتي</a>
        </div>

        @if(session('status'))
            <div style="margin-top: 18px; padding: 14px 18px; background: rgba(76, 175, 80, 0.16); border: 1px solid rgba(76, 175, 80, 0.35); border-radius: 12px; color: #fff; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-check-circle" style="color: #4caf50; font-size: 20px;"></i>
                {{ session('status') }}
            </div>
        @endif

        @if($errors->any())
            <div style="margin-top: 18px; padding: 14px 18px; background: rgba(220, 38, 38, 0.16); border: 1px solid rgba(220, 38, 38, 0.35); border-radius: 12px; color: #fff; display: flex; align-items: flex-start; gap: 10px;">
                <i class="fas fa-exclamation-triangle" style="color: #dc2626; font-size: 20px; margin-top: 2px;"></i>
                <div>
                    <strong>هناك أخطاء يجب تصحيحها:</strong>
                    <ul style="margin: 8px 0 0 0; padding-left: 18px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('owner.halls.update', $hall) }}" enctype="multipart/form-data" style="margin-top: 26px; background: rgba(255,255,255,0.06); padding: 30px; border-radius: 18px; border: 1px solid rgba(255,255,255,0.12);">
            @csrf
            @method('PUT')
            <div class="form-row" style="display: flex; gap: 16px; flex-wrap: wrap;">
                <div class="form-group" style="flex: 1; min-width: 280px;">
                    <label style="display: block; margin-bottom: 6px; font-weight: 700;">اسم القاعة</label>
                    <input type="text" name="name" value="{{ old('name', $hall->name) }}" placeholder="مثال: قصر الفرح" required style="width: 100%; padding: 12px 14px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.2); background: rgba(0,0,0,0.4); color: #fff;" />
                </div>

                <div class="form-group" style="flex: 1; min-width: 280px; position: relative;">
                    <label style="display: block; margin-bottom: 6px; font-weight: 700;">الموقع / المحافظة</label>
                    <input 
                        type="text" 
                        id="locationInput" 
                        name="location" 
                        value="{{ old('location', $hall->location) }}" 
                        placeholder="ابحث عن مدينة..." 
                        required 
                        autocomplete="off"
                        style="width: 100%; padding: 12px 14px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.2); background: rgba(0,0,0,0.4); color: #fff;" 
                    />
                    <ul id="locationDropdown" style="position: absolute; top: 100%; left: 0; right: 0; background: rgba(0,0,0,0.8); border: 1px solid rgba(255,255,255,0.2); border-top: none; border-radius: 0 0 12px 12px; max-height: 300px; overflow-y: auto; list-style: none; margin: 0; padding: 0; z-index: 1000; display: none;">
                    </ul>
                </div>
            </div>

            <div class="form-row" style="display: flex; gap: 16px; flex-wrap: wrap; margin-top: 16px;">
                <div class="form-group" style="flex: 1; min-width: 280px;">
                    <label style="display: block; margin-bottom: 6px; font-weight: 700;">السعة (عدد الضيوف)</label>
                    <input type="number" name="capacity" value="{{ old('capacity', $hall->capacity) }}" min="1" placeholder="مثال: 200" required style="width: 100%; padding: 12px 14px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.2); background: rgba(0,0,0,0.4); color: #fff;" />
                </div>

                <div class="form-group" style="flex: 1; min-width: 280px;">
                    <label style="display: block; margin-bottom: 6px; font-weight: 700;">السعر (جنيه)</label>
                    <input type="number" name="price" value="{{ old('price', $hall->price) }}" min="1" placeholder="مثال: 50000" required style="width: 100%; padding: 12px 14px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.2); background: rgba(0,0,0,0.4); color: #fff;" />
                </div>
            </div>

            <script>
                const egyptCities = [
                    // محافظة الدقهلية (في الأول)
                    'المنصورة - الدقهلية',
                    'طلخا - الدقهلية',
                    'ميت غمر - الدقهلية',
                    'منية النصر - الدقهلية',
                    'ديرب نجم - الدقهلية',
                    'الجمالية - الدقهلية',
                    'دكرنس - الدقهلية',
                    'سنبارة - الدقهلية',
                    'ومن - الدقهلية',
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
                    'ملوي',
                    'المنيا',
                    'بنى حسن',
                    'المنصورة',
                    'طام',
                    'ههيا',
                    'دلاص',
                    'البحيرة',
                    'كفر الدوار',
                    'إيتاي البارود',
                    'الحامول',
                    'دسوق',
                    'كفر الشيخ',
                    'بيلا',
                    'سيدي سالم',
                    'المحلة الكبرى',
                    'سماسطة',
                    'زفتى',
                    'بسيون',
                    'الدقهلية',
                    'شنطا'
                ];

                const locationInput = document.getElementById('locationInput');
                const locationDropdown = document.getElementById('locationDropdown');

                // عرض القائمة عند التركيز أو الكتابة
                locationInput.addEventListener('input', function() {
                    const value = this.value.toLowerCase();
                    const filtered = egyptCities.filter(city => city.includes(value) && city !== this.value);
                    
                    if (value.length > 0 && filtered.length > 0) {
                        locationDropdown.innerHTML = filtered.map(city => 
                            `<li style="padding: 10px 14px; cursor: pointer; border-bottom: 1px solid rgba(255,255,255,0.1); color: #fff; transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='transparent'" onclick="selectLocation('${city}')">${city}</li>`
                        ).join('');
                        locationDropdown.style.display = 'block';
                    } else {
                        locationDropdown.style.display = 'none';
                    }
                });

                // إظهار كل المدن عند التركيز
                locationInput.addEventListener('focus', function() {
                    if (this.value.length === 0) {
                        locationDropdown.innerHTML = egyptCities.map(city => 
                            `<li style="padding: 10px 14px; cursor: pointer; border-bottom: 1px solid rgba(255,255,255,0.1); color: #fff; transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='transparent'" onclick="selectLocation('${city}')">${city}</li>`
                        ).join('');
                        locationDropdown.style.display = 'block';
                    }
                });

                // إغلاق القائمة عند النقر في مكان آخر
                document.addEventListener('click', function(e) {
                    if (e.target !== locationInput) {
                        locationDropdown.style.display = 'none';
                    }
                });

                function selectLocation(city) {
                    locationInput.value = city;
                    locationDropdown.style.display = 'none';
                }
            </script>

            <div class="form-row" style="margin-top: 16px; display: flex; gap: 16px; flex-wrap: wrap;">
                <div class="form-group" style="flex: 1; min-width: 280px;">
                    <label style="display: block; margin-bottom: 6px; font-weight: 700;">الحد الأدنى للسعر (اختياري)</label>
                    <input type="number" name="min_price" value="{{ old('min_price', $hall->min_price ?? '') }}" min="0" placeholder="مثال: 40000" style="width: 100%; padding: 12px 14px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.2); background: rgba(0,0,0,0.4); color: #fff;" />
                </div>

                <div class="form-group" style="flex: 1; min-width: 280px;">
                    <label style="display: block; margin-bottom: 6px; font-weight: 700;">الحد الأقصى للسعر (اختياري)</label>
                    <input type="number" name="max_price" value="{{ old('max_price', $hall->max_price ?? '') }}" min="0" placeholder="مثال: 60000" style="width: 100%; padding: 12px 14px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.2); background: rgba(0,0,0,0.4); color: #fff;" />
                </div>
            </div>

            <div class="form-row" style="margin-top: 16px; display: flex; gap: 16px; flex-wrap: wrap;">
                <div class="form-group" style="flex: 1; min-width: 280px;">
                    <label style="display: block; margin-bottom: 6px; font-weight: 700;">الفئة</label>
                    <input type="text" name="category" value="{{ old('category', $hall->category) }}" placeholder="مثال: قاعات أفراح مؤتمرات" style="width: 100%; padding: 12px 14px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.2); background: rgba(0,0,0,0.4); color: #fff;" />
                </div>

                <div class="form-group" style="flex: 1; min-width: 280px;">
                    <label style="display: block; margin-bottom: 6px; font-weight: 700;">الحالة</label>
                    <select name="status" style="width: 100%; padding: 12px 14px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.2); background: rgba(0,0,0,0.4); color: #fff;">
                        <option value="متاح" {{ old('status', $hall->status) === 'متاح' ? 'selected' : '' }}>متاح</option>
                        <option value="غير متاح" {{ old('status', $hall->status) === 'غير متاح' ? 'selected' : '' }}>غير متاح</option>
                    </select>
                </div>
            </div>

            <div class="form-row" style="margin-top: 16px; display: flex; gap: 16px; flex-wrap: wrap;">
                <div class="form-group" style="flex: 1; min-width: 280px;">
                    <label style="display: block; margin-bottom: 6px; font-weight: 700;">الصورة الرئيسية (رفع ملف)</label>
                    <input type="file" name="main_image" accept="image/*" style="width: 100%; padding: 8px 10px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.2); background: rgba(0,0,0,0.4); color: #fff;" />
                </div>
            </div>

            <div class="form-row" style="margin-top: 16px; display: flex; gap: 16px; flex-wrap: wrap;">
                <div class="form-group" style="flex: 1; min-width: 280px;">
                    <label style="display: block; margin-bottom: 6px; font-weight: 700;">الخدمات المتاحة</label>
                    <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px;">
                        @php
                            $defaultFeatures = ['واي فاي مجاني', 'مواقف سيارات', 'بوفيه مفتوح', 'شاشات عرض', 'تكييف', 'إنترنت عالي السرعة'];
                            $selectedFeatures = old('features', is_array($hall->features) ? $hall->features : []);
                        @endphp
                        @foreach($defaultFeatures as $feature)
                            <label style="display: flex; align-items: center; gap: 8px; font-weight: 600;">
                                <input type="checkbox" name="features[]" value="{{ $feature }}" style="width: 22px; height: 22px; min-width: 22px; accent-color: #5d2fb6;" {{ in_array($feature, (array)$selectedFeatures) ? 'checked' : '' }} />
                                {{ $feature }}
                            </label>
                        @endforeach
                    </div>
                    <p style="margin: 12px 0 6px 0; font-size: 0.85rem; color: rgba(255,255,255,0.75);">يمكنك اختيار الخدمات المتاحة في القاعة أو كتابة خدمة إضافية.</p>
                    <input type="text" name="other_features" value="{{ old('other_features', $hall->other_features ?? '') }}" placeholder="اكتب خدمة إضافية (مفصول بفاصلة)" style="width: 100%; padding: 12px 14px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.2); background: rgba(0,0,0,0.4); color: #fff;" />
                </div>

                @php
                    $oldUnavailableDates = old('unavailable_dates');
                    if (!$oldUnavailableDates) {
                        if (is_array($hall->unavailable_dates)) {
                            $oldUnavailableDates = implode(', ', $hall->unavailable_dates);
                        } elseif (is_string($hall->unavailable_dates)) {
                            // إذا كان string، حاول تحويله إلى array ثم إلى string مرة أخرى للتأكد من التنسيق
                            $decoded = json_decode($hall->unavailable_dates, true);
                            if (is_array($decoded)) {
                                $oldUnavailableDates = implode(', ', $decoded);
                            } else {
                                $oldUnavailableDates = $hall->unavailable_dates;
                            }
                        } else {
                            $oldUnavailableDates = '';
                        }
                    }
                @endphp
                <div class="form-group" style="flex: 1 1 420px; min-width: 280px; max-width: 420px;">
                    <label style="display: block; margin-bottom: 6px; font-weight: 700;">تواريخ إغلاق الحجز</label>
                    <div style="width: 100%; max-width: 420px; padding: 18px; border-radius: 24px; background: rgba(15, 23, 42, 0.96); border: 1px solid rgba(255,255,255,0.14);">
                        <!-- <div style="margin-bottom: 16px;">
                            <div style="position: relative; border-radius: 24px; background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12);">
                                <input type="date" id="unavailableDatePicker" class="form-control booking-date-input" style="width: 100%; padding: 15px 18px; border-radius: 24px; border: none; background: transparent; color: #fff; font-size: 1rem;" />
                                <span class="date-input-icon" style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); color: #fbbf24; pointer-events: none; font-size: 1.1rem;"><i class="fa fa-calendar-alt"></i></span>
                            </div>
                            <small style="display: block; margin-top: 10px; color: rgba(255,255,255,0.65);">اختر التاريخ المناسب للحجز.</small>
                        </div> -->
                        <div id="ownerUnavailableCalendarCard" style="padding: 18px; border-radius: 24px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.12);">
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; gap: 10px;">
                                <div style="font-weight: 700; color: #fff; font-size: 0.95rem;">تقويم الإغلاق</div>
                                <button type="button" id="calendarCloseBtn" style="width: 34px; height: 34px; border-radius: 50%; border: 1px solid rgba(255,255,255,0.1); background: rgba(255,255,255,0.08); color: rgba(255,255,255,0.66); display: inline-flex; align-items: center; justify-content: center; cursor: pointer;">×</button>
                            </div>
                            <div style="padding: 18px; border-radius: 24px; background: rgba(15, 23, 42, 0.96); border: 1px solid rgba(255,255,255,0.14);">
                                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; gap: 10px;">
                                    <div id="calendarMonthLabel" style="font-weight: 700; color: #fff; font-size: 0.95rem;">أبريل 2026</div>
                                    <div style="display: flex; gap: 10px;">
                                        <button type="button" id="calendarPrevBtn" style="width: 36px; height: 36px; border-radius: 14px; border: 1px solid rgba(255,255,255,0.1); background: rgba(255,255,255,0.08); color: rgba(255,255,255,0.66); cursor: pointer;">‹</button>
                                        <button type="button" id="calendarNextBtn" style="width: 36px; height: 36px; border-radius: 14px; border: 1px solid rgba(255,255,255,0.1); background: rgba(255,255,255,0.08); color: rgba(255,255,255,0.88); cursor: pointer;">›</button>
                                    </div>
                                </div>
                                <div id="calendarDaysGrid" style="display: grid; grid-template-columns: repeat(7, minmax(0, 1fr)); gap: 8px;"></div>
                            </div>
                        </div>
                        <input type="hidden" name="unavailable_dates" id="unavailableDatesInput" value="{{ $oldUnavailableDates }}" />
                        <p style="margin: 10px 0 0 0; color: rgba(255,255,255,0.65); font-size: 0.87rem;">اضغط على اليوم لإغلاقه وسيظهر مغلقًا داخل التقويم.</p>
                    </div>
                </div>
            </div>

            <div class="form-row" style="margin-top: 16px; display: flex; gap: 16px; flex-wrap: wrap;">
                <div class="form-group" style="flex: 1; min-width: 280px;">
                    <label style="display: block; margin-bottom: 6px; font-weight: 700;">صور إضافية (حتى 10 صور)</label>
                    <input type="file" id="multiImageInput" name="images[]" accept="image/*" multiple style="width: 100%; padding: 8px 10px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.2); background: rgba(0,0,0,0.4); color: #fff;" />
                    <p style="margin: 8px 0 0 0; color: rgba(255,255,255,0.65); font-size: 0.87rem;">يمكنك اختيار عدة صور دفعة واحدة (اضغط Ctrl+اختر أو Cmd+اختر على الأجهزة الأخرى)</p>
                    <div id="galleryPreview" style="display: flex; flex-wrap: wrap; gap: 10px; margin-top: 12px;"></div>
                    <div id="imagesCount" style="margin-top: 8px; font-size: 0.9rem; color: rgba(255,255,255,0.7);">لم يتم اختيار أي صور</div>
                </div>
            </div>

            <div style="margin-top: 20px; display: flex; gap: 10px; flex-wrap: wrap;">
                <button type="submit" class="nav-auth-btn btn-primary">حفظ التغييرات</button>
                <a href="{{ route('owner.halls') }}" class="nav-auth-btn btn-secondary">إلغاء</a>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const multiImageInput = document.getElementById('multiImageInput');
                const previewContainer = document.getElementById('galleryPreview');
                const countLabel = document.getElementById('imagesCount');
                const maxImages = 10;

                if (!multiImageInput || !previewContainer || !countLabel) return;

                const updatePreview = () => {
                    previewContainer.innerHTML = '';
                    const files = multiImageInput.files;
                    
                    if (files.length > maxImages) {
                        alert(`يمكنك رفع حتى ${maxImages} صور فقط. تم حذف الصور الزائدة.`);
                        const dt = new DataTransfer();
                        for (let i = 0; i < maxImages; i++) {
                            dt.items.add(files[i]);
                        }
                        multiImageInput.files = dt.files;
                        updatePreview();
                        return;
                    }

                    for (let i = 0; i < files.length; i++) {
                        const file = files[i];
                        const wrapper = document.createElement('div');
                        wrapper.style.width = '80px';
                        wrapper.style.height = '80px';
                        wrapper.style.position = 'relative';
                        wrapper.style.borderRadius = '12px';
                        wrapper.style.overflow = 'hidden';
                        wrapper.style.border = '1px solid rgba(255,255,255,0.2)';
                        wrapper.style.background = 'rgba(0,0,0,0.2)';

                        const img = document.createElement('img');
                        img.style.width = '100%';
                        img.style.height = '100%';
                        img.style.objectFit = 'cover';

                        const removeBtn = document.createElement('button');
                        removeBtn.type = 'button';
                        removeBtn.textContent = '×';
                        removeBtn.style.position = 'absolute';
                        removeBtn.style.top = '2px';
                        removeBtn.style.right = '2px';
                        removeBtn.style.width = '22px';
                        removeBtn.style.height = '22px';
                        removeBtn.style.border = 'none';
                        removeBtn.style.borderRadius = '50%';
                        removeBtn.style.background = 'rgba(0,0,0,0.5)';
                        removeBtn.style.color = '#fff';
                        removeBtn.style.cursor = 'pointer';

                        const url = URL.createObjectURL(file);
                        img.src = url;

                        removeBtn.addEventListener('click', (e) => {
                            e.preventDefault();
                            const dt = new DataTransfer();
                            for (let j = 0; j < multiImageInput.files.length; j++) {
                                if (j !== i) {
                                    dt.items.add(multiImageInput.files[j]);
                                }
                            }
                            multiImageInput.files = dt.files;
                            updatePreview();
                        });

                        wrapper.appendChild(img);
                        wrapper.appendChild(removeBtn);
                        previewContainer.appendChild(wrapper);
                    }

                    const total = files.length;
                    countLabel.textContent = total
                        ? `${total} ${total === 1 ? 'صورة' : 'صور'} مختارة`
                        : 'لم يتم اختيار أي صور';
                };

                multiImageInput.addEventListener('change', updatePreview);

                const unavailableDatesInput = document.getElementById('unavailableDatesInput');
                const unavailableDatePicker = document.getElementById('unavailableDatePicker');
                const unavailableDatesList = document.getElementById('unavailableDatesList');
                const addUnavailableDateBtn = document.getElementById('addUnavailableDateBtn');
                let unavailableDates = [];

                const normalizeUnavailableDate = (value) => {
                    const date = value.trim();
                    if (!date) return null;
                    const parsed = new Date(date);
                    if (Number.isNaN(parsed.getTime())) return null;
                    const year = parsed.getFullYear();
                    const month = String(parsed.getMonth() + 1).padStart(2, '0');
                    const day = String(parsed.getDate()).padStart(2, '0');
                    return `${year}-${month}-${day}`;
                };

                const renderUnavailableDates = () => {
                    if (!unavailableDatesList) return;
                    unavailableDatesList.innerHTML = '';
                    unavailableDates.forEach((date) => {
                        const tag = document.createElement('div');
                        tag.style.display = 'inline-flex';
                        tag.style.alignItems = 'center';
                        tag.style.gap = '8px';
                        tag.style.padding = '8px 12px';
                        tag.style.borderRadius = '999px';
                        tag.style.background = 'rgba(255,255,255,0.08)';
                        tag.style.color = '#fff';
                        tag.style.border = '1px solid rgba(255,255,255,0.1)';
                        tag.style.fontSize = '0.9rem';
                        tag.textContent = date;

                        const removeBtn = document.createElement('button');
                        removeBtn.type = 'button';
                        removeBtn.textContent = '×';
                        removeBtn.style.border = 'none';
                        removeBtn.style.background = 'rgba(255,255,255,0.12)';
                        removeBtn.style.color = '#fff';
                        removeBtn.style.width = '24px';
                        removeBtn.style.height = '24px';
                        removeBtn.style.borderRadius = '50%';
                        removeBtn.style.cursor = 'pointer';
                        removeBtn.addEventListener('click', () => {
                            unavailableDates = unavailableDates.filter((d) => d !== date);
                            updateUnavailableDatesInput();
                            renderUnavailableDates();
                        });
                        tag.appendChild(removeBtn);
                        unavailableDatesList.appendChild(tag);
                    });
                };

                const updateUnavailableDatesInput = () => {
                    if (!unavailableDatesInput) return;
                    unavailableDatesInput.value = unavailableDates.join(', ');
                };

                const addUnavailableDate = (dateValue) => {
                    const normalized = normalizeUnavailableDate(dateValue);
                    if (!normalized) return;
                    if (unavailableDates.includes(normalized)) return;
                    unavailableDates.push(normalized);
                    unavailableDates.sort();
                    updateUnavailableDatesInput();
                    renderUnavailableDates();
                };

                if (unavailableDatesInput) {
                    unavailableDates = unavailableDatesInput.value
                        .split(',')
                        .map((d) => normalizeUnavailableDate(d))
                        .filter(Boolean);
                    unavailableDates = [...new Set(unavailableDates)];
                    updateUnavailableDatesInput();
                    renderUnavailableDates();
                }

                if (addUnavailableDateBtn && unavailableDatePicker) {
                    addUnavailableDateBtn.addEventListener('click', () => {
                        if (!unavailableDatePicker.value) return;
                        addUnavailableDate(unavailableDatePicker.value);
                        unavailableDatePicker.value = '';
                    });
                }

                const calendarMonthLabel = document.getElementById('calendarMonthLabel');
                const calendarDaysGrid = document.getElementById('calendarDaysGrid');
                const calendarPrevBtn = document.getElementById('calendarPrevBtn');
                const calendarNextBtn = document.getElementById('calendarNextBtn');
                const calendarCloseBtn = document.getElementById('calendarCloseBtn');
                const calendarCard = document.getElementById('ownerUnavailableCalendarCard');
                const currentDate = new Date();
                const todayDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), currentDate.getDate());
                let calendarMonthDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
                const weekDays = ['س', 'ح', 'ن', 'ث', 'ر', 'خ', 'ج'];
                const monthFormatter = new Intl.DateTimeFormat('ar-EG', { month: 'long', year: 'numeric' });

                function renderCalendar() {
                    if (!calendarMonthLabel || !calendarDaysGrid) return;

                    calendarMonthLabel.textContent = monthFormatter.format(calendarMonthDate);
                    const firstDayOfWeek = new Date(calendarMonthDate.getFullYear(), calendarMonthDate.getMonth(), 1).getDay();
                    const daysInMonth = new Date(calendarMonthDate.getFullYear(), calendarMonthDate.getMonth() + 1, 0).getDate();
                    const firstAllowedMonth = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
                    const canGoBack = calendarMonthDate.getTime() > firstAllowedMonth.getTime();

                    if (calendarPrevBtn) {
                        calendarPrevBtn.disabled = !canGoBack;
                        calendarPrevBtn.style.opacity = canGoBack ? '1' : '0.55';
                    }

                    calendarDaysGrid.innerHTML = '';

                    weekDays.forEach((day) => {
                        const label = document.createElement('span');
                        label.textContent = day;
                        label.style.textAlign = 'center';
                        label.style.color = 'rgba(255,255,255,0.55)';
                        label.style.fontSize = '0.78rem';
                        calendarDaysGrid.appendChild(label);
                    });

                    for (let emptyIndex = 0; emptyIndex < firstDayOfWeek; emptyIndex += 1) {
                        const emptyCell = document.createElement('div');
                        emptyCell.style.minHeight = '44px';
                        calendarDaysGrid.appendChild(emptyCell);
                    }

                    for (let day = 1; day <= daysInMonth; day += 1) {
                        const dateValue = `${calendarMonthDate.getFullYear()}-${String(calendarMonthDate.getMonth() + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                        const cellDate = new Date(calendarMonthDate.getFullYear(), calendarMonthDate.getMonth(), day);
                        const isPastDate = cellDate < todayDate;
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
                        dayBtn.style.border = isPastDate ? '1px solid rgba(255,255,255,0.08)' : isUnavailable ? '1px solid rgba(255,255,255,0.18)' : '1px solid transparent';
                        dayBtn.style.padding = '0';
                        dayBtn.style.background = isPastDate ? 'rgba(255,255,255,0.04)' : isUnavailable ? '#fbbf24' : 'rgba(255,255,255,0.08)';
                        dayBtn.style.color = isPastDate ? 'rgba(255,255,255,0.42)' : isUnavailable ? '#111827' : 'rgba(255,255,255,0.88)';
                        dayBtn.style.fontWeight = isPastDate ? '400' : isUnavailable ? '700' : '400';
                        dayBtn.style.boxShadow = isUnavailable ? '0 0 0 2px rgba(251, 191, 36, 0.18)' : 'none';
                        dayBtn.style.cursor = isPastDate ? 'not-allowed' : 'pointer';
                        dayBtn.disabled = isPastDate;
                        if (!isPastDate) {
                            dayBtn.addEventListener('click', () => {
                                if (unavailableDates.includes(dateValue)) {
                                    unavailableDates = unavailableDates.filter((d) => d !== dateValue);
                                } else {
                                    unavailableDates.push(dateValue);
                                    unavailableDates.sort();
                                }
                                updateUnavailableDatesInput();
                                renderCalendar();
                            });
                        }
                        calendarDaysGrid.appendChild(dayBtn);
                    }
                };

                if (calendarPrevBtn) {
                    calendarPrevBtn.addEventListener('click', () => {
                        calendarMonthDate.setMonth(calendarMonthDate.getMonth() - 1);
                        renderCalendar();
                    });
                }

                if (calendarNextBtn) {
                    calendarNextBtn.addEventListener('click', () => {
                        calendarMonthDate.setMonth(calendarMonthDate.getMonth() + 1);
                        renderCalendar();
                    });
                }

                if (calendarCloseBtn && calendarCard) {
                    calendarCloseBtn.addEventListener('click', () => {
                        calendarCard.style.display = 'none';
                    });
                }

                renderCalendar();
            });
        </script>
    @endpush
@endsection
