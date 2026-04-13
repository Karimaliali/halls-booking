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

                <div class="form-group" style="flex: 1; min-width: 280px;">
                    <label style="display: block; margin-bottom: 6px; font-weight: 700;">تواريخ غير متاحة (افصل بفواصل)</label>
                    <input type="text" name="unavailable_dates" value="{{ old('unavailable_dates', is_array($hall->unavailable_dates) ? implode(', ', $hall->unavailable_dates) : '') }}" placeholder="مثال: 2026-03-20, 2026-03-21" style="width: 100%; padding: 12px 14px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.2); background: rgba(0,0,0,0.4); color: #fff;" />
                </div>
            </div>

            <div class="form-row" style="margin-top: 16px; display: flex; gap: 16px; flex-wrap: wrap;">
                <div class="form-group" style="flex: 1; min-width: 280px;">
                    <label style="display: block; margin-bottom: 6px; font-weight: 700;">صور إضافية (حتى 10 صور)</label>
                    <div id="galleryInputs" style="display: flex; flex-direction: column; gap: 8px;">
                        <div class="gallery-input-row" style="display: flex; gap: 8px; align-items: center;">
                            <input type="file" name="images[]" accept="image/*" style="flex:1; padding: 8px 10px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.2); background: rgba(0,0,0,0.4); color: #fff;" />
                            <button type="button" class="btn-remove-image" style="background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.2); border-radius: 12px; padding: 0 12px; color: #fff; cursor: pointer;">×</button>
                        </div>
                    </div>
                    <div id="galleryPreview" style="display: flex; flex-wrap: wrap; gap: 10px; margin-top: 12px;"></div>
                    <button type="button" id="addGalleryImage" style="margin-top: 10px; background: rgba(250, 204, 21, 0.8); border: none; border-radius: 12px; padding: 10px 14px; cursor: pointer;">إضافة صورة أخرى</button>
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
                const galleryInputsContainer = document.getElementById('galleryInputs');
                const addGalleryBtn = document.getElementById('addGalleryImage');
                const countLabel = document.getElementById('imagesCount');
                const previewContainer = document.getElementById('galleryPreview');
                const maxImages = 10;

                if (!galleryInputsContainer || !addGalleryBtn || !countLabel || !previewContainer) return;

                const getAllFileInputs = () => Array.from(galleryInputsContainer.querySelectorAll('input[type=file]'));

                const updateCount = () => {
                    const total = getAllFileInputs().reduce((sum, input) => sum + input.files.length, 0);
                    countLabel.textContent = total
                        ? `${total} ${total === 1 ? 'صورة' : 'صور'} مختارة`
                        : 'لم يتم اختيار أي صور';
                };

                const createPreviewThumb = (file, rowId) => {
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

                    removeBtn.addEventListener('click', () => {
                        const row = document.querySelector(`[data-row-id="${rowId}"]`);
                        if (row) row.remove();
                        refreshPreviews();
                        updateCount();
                    });

                    wrapper.appendChild(img);
                    wrapper.appendChild(removeBtn);
                    return wrapper;
                };

                const refreshPreviews = () => {
                    previewContainer.innerHTML = '';
                    const allRows = Array.from(galleryInputsContainer.querySelectorAll('[data-row-id]'));
                    allRows.forEach((row) => {
                        const input = row.querySelector('input[type=file]');
                        if (!input || !input.files.length) return;
                        const file = input.files[0];
                        const rowId = row.dataset.rowId;
                        previewContainer.appendChild(createPreviewThumb(file, rowId));
                    });
                };

                const createRow = () => {
                    const row = document.createElement('div');
                    row.className = 'gallery-input-row';
                    row.style.display = 'flex';
                    row.style.gap = '8px';
                    row.style.alignItems = 'center';
                    const rowId = `galleryRow_${Date.now()}_${Math.random().toString(16).slice(2)}`;
                    row.dataset.rowId = rowId;

                    const input = document.createElement('input');
                    input.type = 'file';
                    input.name = 'images[]';
                    input.accept = 'image/*';
                    input.style.flex = '1';
                    input.style.padding = '8px 10px';
                    input.style.borderRadius = '12px';
                    input.style.border = '1px solid rgba(255,255,255,0.2)';
                    input.style.background = 'rgba(0,0,0,0.4)';
                    input.style.color = '#fff';

                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.textContent = '×';
                    removeBtn.style.background = 'rgba(255,255,255,0.12)';
                    removeBtn.style.border = '1px solid rgba(255,255,255,0.2)';
                    removeBtn.style.borderRadius = '12px';
                    removeBtn.style.padding = '0 12px';
                    removeBtn.style.color = '#fff';
                    removeBtn.style.cursor = 'pointer';

                    removeBtn.addEventListener('click', () => {
                        if (getAllFileInputs().length <= 1) {
                            input.value = '';
                        } else {
                            row.remove();
                        }
                        refreshPreviews();
                        updateCount();
                    });

                    input.addEventListener('change', () => {
                        const totalFiles = getAllFileInputs().reduce((sum, fileInput) => sum + fileInput.files.length, 0);
                        if (totalFiles > maxImages) {
                            alert(`يمكنك رفع حتى ${maxImages} صور فقط.`);
                            input.value = '';
                        }
                        refreshPreviews();
                        updateCount();
                    });

                    row.appendChild(input);
                    row.appendChild(removeBtn);
                    return row;
                };

                addGalleryBtn.addEventListener('click', () => {
                    const currentCount = getAllFileInputs().reduce((sum, input) => sum + input.files.length, 0);
                    if (currentCount >= maxImages) {
                        alert(`يمكنك رفع حتى ${maxImages} صور فقط.`);
                        return;
                    }

                    galleryInputsContainer.appendChild(createRow());
                });

                // Start with a single row
                galleryInputsContainer.innerHTML = '';
                galleryInputsContainer.appendChild(createRow());
                updateCount();
            });
        </script>
    @endpush
@endsection
