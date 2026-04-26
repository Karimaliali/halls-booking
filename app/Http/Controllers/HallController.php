<?php

namespace App\Http\Controllers;

use App\Models\Hall;
use Illuminate\Http\Request;

class HallController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/halls",
     *     summary="Get all halls",
     *     operationId="getAllHalls",
     *     tags={"Halls"},
     *     @OA\Response(response=200, description="List of all halls")
     * )
     */
    public function index() {
        return Hall::all();
    }

    /**
     * @OA\Post(
     *     path="/api/halls",
     *     summary="Create a new hall",
     *     operationId="createHall",
     *     tags={"Halls"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","price","location","capacity"},
     *             @OA\Property(property="name", type="string", example="Grand Hall"),
     *             @OA\Property(property="price", type="number", example=5000),
     *             @OA\Property(property="location", type="string", example="Cairo"),
     *             @OA\Property(property="capacity", type="integer", example=200),
     *             @OA\Property(property="main_image", type="string", nullable=true, example="image.jpg")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Hall created successfully"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'location' => 'required|string|max:255',
            'capacity' => 'required|numeric|min:1',
            'category' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0',
            'features' => 'nullable|array',
            'other_features' => 'nullable|string',
            'unavailable_dates' => 'nullable|string',
            'images' => 'nullable|array|max:10',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ], [
            'name.required' => 'اسم القاعة مطلوب',
            'price.required' => 'السعر مطلوب',
            'price.numeric' => 'السعر يجب أن يكون رقم',
            'main_image.image' => 'الملف يجب أن يكون صورة',
            'capacity.required' => 'السعة مطلوبة',
            'capacity.numeric' => 'السعة يجب أن تكون رقم',
            'location.required' => 'الموقع مطلوب'
        ]);

        try {
            // التحقق من أن المستخدم مصرح
            if (!auth()->check()) {
                return back()->with('error', 'يجب عليك تسجيل الدخول أولاً');
            }

            $user = auth()->user();
            
            // معالجة الصورة الرئيسية
            if ($request->hasFile('main_image')) {
                $file = $request->file('main_image');
                if ($file->isValid()) {
                    $mainImagePath = $file->store('halls', 'public');
                    if ($mainImagePath && \Illuminate\Support\Facades\Storage::disk('public')->exists(ltrim($mainImagePath, '/'))) {
                        $validated['main_image'] = $mainImagePath;
                    } else {
                        \Log::warning('Main hall image saved path not found on disk', ['path' => $mainImagePath]);
                        $validated['main_image'] = null;
                    }
                } else {
                    $validated['main_image'] = null;
                }
            }

            // معالجة الخصائص
            if (isset($validated['features']) && !empty($validated['other_features'])) {
                $other = array_filter(array_map('trim', explode(',', $validated['other_features'])));
                $validated['features'] = array_merge($validated['features'] ?? [], $other);
            } else if (!isset($validated['features'])) {
                $validated['features'] = [];
            }

            // تحويل السعات والأسعار إلى الصيغة الصحيحة
            $validated['capacity'] = (int)$validated['capacity'];
            $validated['price'] = (float)$validated['price'];
            
            // إزالة الحقول الزائدة غير الضرورية
            unset($validated['images']);
            unset($validated['other_features']);

            // إنشاء القاعة
            $hall = $user->halls()->create($validated);

            if (!$hall) {
                return back()->with('error', 'فشل إنشاء القاعة');
            }

            // معالجة الصور الإضافية
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    if ($image && $image->isValid()) {
                        $path = $image->store('halls/images', 'public');
                        if ($path && \Illuminate\Support\Facades\Storage::disk('public')->exists(ltrim($path, '/'))) {
                            $hall->gallery()->create(['path' => $path]);
                        } else {
                            \Log::warning('Hall gallery image saved path not found on disk', ['path' => $path, 'hall_id' => $hall->id]);
                        }
                    }
                }
            }

            return redirect()->route('owner.halls')->with('status', 'تمت إضافة القاعة بنجاح');
        } catch (\Exception $e) {
            \Log::error('Hall Creation Error: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified hall.
     */
    public function show($id)
    {
        try {
            $hall = Hall::with(['gallery', 'reviews'])->findOrFail($id);
            return view('owner.hall-show', compact('hall'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return back()->with('error', 'القاعة غير موجودة');
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified hall.
     */
    public function edit($id)
    {
        try {
            $hall = Hall::findOrFail($id);
            
            // التحقق من أن المستخدم هو مالك القاعة
            if ($hall->user_id !== auth()->id()) {
                return back()->with('error', 'غير مصرح: يمكنك فقط تعديل قاعاتك الخاصة');
            }
            
            return view('owner.edit-hall', compact('hall'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return back()->with('error', 'القاعة غير موجودة');
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }

    /**
     * @OA\Put(
     *     path="/api/halls/{id}",
     *     summary="Update a hall",
     *     operationId="updateHall",
     *     tags={"Halls"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Hall ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Grand Hall"),
     *             @OA\Property(property="price", type="number", example=5000),
     *             @OA\Property(property="location", type="string", example="Cairo"),
     *             @OA\Property(property="capacity", type="integer", example=200),
     *             @OA\Property(property="main_image", type="string", nullable=true, example="image.jpg")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Hall updated successfully"),
     *     @OA\Response(response=403, description="Unauthorized - you can only update your own halls"),
     *     @OA\Response(response=404, description="Hall not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(Request $request, $id) {
        try {
            $hall = Hall::findOrFail($id);

            // التحقق من أن المستخدم الحالي هو مالك القاعة
            if ($hall->user_id !== $request->user()->id) {
                return back()->with('error', 'غير مصرح: يمكنك فقط تعديل قاعاتك الخاصة');
            }

            $fields = $request->validate([
                'name' => 'sometimes|required|string',
                'price' => 'sometimes|required|numeric',
                'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'location' => 'sometimes|required|string',
                'capacity' => 'sometimes|required|integer',
                'category' => 'nullable|string',
                'status' => 'nullable|string',
                'min_price' => 'nullable|numeric',
                'max_price' => 'nullable|numeric',
                'features' => 'nullable|array',
                'other_features' => 'nullable|string',
                'unavailable_dates' => 'nullable|string',
                'images' => 'nullable|array',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            // معالجة الصورة الرئيسية الجديدة
            if ($request->hasFile('main_image')) {
                // حذف الصورة القديمة إذا كانت موجودة
                if ($hall->main_image) {
                    \Storage::disk('public')->delete($hall->main_image);
                }
                $mainImagePath = $request->file('main_image')->store('halls', 'public');
                $fields['main_image'] = $mainImagePath;
            }

            // معالجة الخصائص
            if (isset($fields['features']) && !empty($fields['other_features'])) {
                $other = array_filter(array_map('trim', explode(',', $fields['other_features'])));
                $fields['features'] = array_merge($fields['features'] ?? [], $other);
            }
            
            unset($fields['other_features']);

            $hall->update($fields);

            // معالجة الصور الإضافية الجديدة
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('halls/images', 'public');
                    $hall->gallery()->create(['path' => $path]);
                }
            }

            return redirect(route('halls.show', $hall))->with('status', 'تم تحديث القاعة بنجاح');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return back()->with('error', 'القاعة غير موجودة');
        } catch (\Exception $e) {
            \Log::error('Hall Update Error: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/halls/{id}",
     *     summary="Delete a hall",
     *     operationId="deleteHall",
     *     tags={"Halls"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Hall ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Hall deleted successfully"),
     *     @OA\Response(response=403, description="Unauthorized - you can only delete your own halls"),
     *     @OA\Response(response=404, description="Hall not found")
     * )
     */
    public function destroy(Request $request, $id) {
        try {
            $hall = Hall::findOrFail($id);

            // التحقق من أن المستخدم الحالي هو مالك القاعة
            if ($hall->user_id !== ($request->user()?->id ?? null)) {
                if ($request->wantsJson() || $request->is('api/*')) {
                    return response([
                        'status' => 'error',
                        'message' => 'غير مصرح: يمكنك فقط حذف قاعاتك الخاصة'
                    ], 403);
                }

                return back()->with('error', 'غير مصرح: يمكنك فقط حذف قاعاتك الخاصة');
            }

            $hall->delete();

            if ($request->wantsJson() || $request->is('api/*')) {
                return response([
                    'status' => 'success',
                    'message' => 'تم حذف القاعة بنجاح'
                ], 200);
            }

            return redirect()->route('owner.halls')->with('status', 'تم حذف القاعة بنجاح');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response([
                    'status' => 'error',
                    'message' => 'القاعة غير موجودة'
                ], 404);
            }

            return back()->with('error', 'القاعة غير موجودة');
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response(['error' => $e->getMessage()], 500);
            }

            return back()->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }

    /**
     * Display the public hall details page.
     */
    public function publicShow(Hall $hall)
    {
        $hall->load(['gallery', 'reviews.user']);
        $hall->loadCount('reviews');
        $hall->loadAvg('reviews', 'rating');

        $relatedHalls = Hall::where('id', '!=', $hall->id)
            ->when($hall->category, function ($query, $category) {
                return $query->where('category', $category);
            })
            ->latest()
            ->take(3)
            ->get();

        if ($relatedHalls->isEmpty()) {
            $relatedHalls = Hall::where('id', '!=', $hall->id)->latest()->take(3)->get();
        }

        $isOwner = auth()->check() && auth()->id() === $hall->user_id;

        return view('hall-details', compact('hall', 'relatedHalls', 'isOwner'));
    }

    /**
     * Build the hall search query and attach relevance scoring.
     */
    private function buildSearchQuery(Request $request)
    {
        $query = Hall::query()->select('halls.*');
        $bindings = [];
        $relevanceParts = [];

        if ($location = $request->query('location')) {
            $location = trim($location);
            $query->where(function($q) use ($location) {
                $q->where('location', 'like', '%' . $location . '%')
                  ->orWhere(function($subQ) use ($location) {
                      $words = array_filter(preg_split('/\s+/', $location), fn($word) => trim($word) !== '' && trim($word) !== '-');
                      foreach ($words as $word) {
                          $subQ->orWhere('location', 'like', '%' . trim($word) . '%');
                      }
                  });
            });

            $relevanceParts[] = '(CASE WHEN location LIKE ? THEN 100 ELSE 0 END)';
            $bindings[] = '%' . $location . '%';

            $words = array_filter(preg_split('/\s+/', $location), fn($word) => trim($word) !== '' && trim($word) !== '-');
            foreach ($words as $word) {
                $word = trim($word);
                if ($word !== '') {
                    $relevanceParts[] = '(CASE WHEN location LIKE ? THEN 15 ELSE 0 END)';
                    $bindings[] = '%' . $word . '%';
                }
            }
        }

        if ($category = $request->query('category')) {
            $query->where('category', $category);
            $relevanceParts[] = '(CASE WHEN category = ? THEN 20 ELSE 0 END)';
            $bindings[] = $category;
        }

        $feature = $request->query('feature') ?? $request->query('features');
        if ($feature) {
            if (is_array($feature)) {
                foreach ($feature as $f) {
                    $query->whereJsonContains('features', $f);
                    $relevanceParts[] = '(CASE WHEN features LIKE ? THEN 10 ELSE 0 END)';
                    $bindings[] = '%' . $f . '%';
                }
            } else {
                $query->whereJsonContains('features', $feature);
                $relevanceParts[] = '(CASE WHEN features LIKE ? THEN 10 ELSE 0 END)';
                $bindings[] = '%' . $feature . '%';
            }
        }

        if ($minPrice = $request->query('min_price')) {
            $query->where('price', '>=', floatval($minPrice));
        }

        if ($maxPrice = $request->query('max_price')) {
            $query->where('price', '<=', floatval($maxPrice));
        }

        if ($guests = $request->query('guests')) {
            if (str_contains($guests, '-')) {
                [$min, $max] = array_map('trim', explode('-', $guests, 2));
                $min = intval(preg_replace('/[^0-9]/', '', $min));
                $max = intval(preg_replace('/[^0-9]/', '', $max));
                if ($min) {
                    $query->where('capacity', '>=', $min);
                }
                if ($max) {
                    $query->where('capacity', '<=', $max);
                }
            } elseif (str_contains($guests, 'أقل') || str_contains($guests, 'اقل')) {
                $num = intval(preg_replace('/[^0-9]/', '', $guests));
                if ($num) {
                    $query->where('capacity', '<=', $num);
                }
            } elseif (str_contains($guests, 'أكثر') || str_contains($guests, 'اكثر')) {
                $num = intval(preg_replace('/[^0-9]/', '', $guests));
                if ($num) {
                    $query->where('capacity', '>=', $num);
                }
            }
        }

        if (!empty($relevanceParts)) {
            $query->selectRaw('halls.*, (' . implode(' + ', $relevanceParts) . ') as relevance', $bindings);

            if (!$request->query('sort')) {
                $query->orderByDesc('relevance');
            }
        } elseif (!$request->query('sort')) {
            $query->latest();
        }

        return $query;
    }

    /**
     * Search halls with filters.
     */
    public function search(Request $request)
    {
        $query = $this->buildSearchQuery($request);

        if ($sort = $request->query('sort')) {
            switch ($sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'latest':
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    $query->latest();
                    break;
            }
        }

        $halls = $query->paginate(12)->withQueryString();

        return view('search', compact('halls'));
    }

    /**
     * API Search halls with filters.
     */
    public function searchApi(Request $request)
    {
        $query = $this->buildSearchQuery($request);

        $halls = $query->paginate(12)->withQueryString();

        return response()->json($halls);
    }

    /**
     * Store a review for a hall.
     */
    public function storeReview(Request $request, Hall $hall)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review = $hall->reviews()->updateOrCreate(
            [
                'user_id' => $request->user()->id,
            ],
            [
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]
        );

        return back()->with('status', 'تم إرسال التقييم بنجاح.');
    }

    /**
     * Toggle favorite status for a hall.
     */
    public function toggleFavorite(Request $request, Hall $hall)
    {
        $user = $request->user();

        // Check if already favorited
        $isFavorited = $user->favoriteHalls()->where('hall_id', $hall->id)->exists();

        if ($isFavorited) {
            // Remove from favorites
            $user->favoriteHalls()->detach($hall->id);
            $favorited = false;
        } else {
            // Add to favorites
            $user->favoriteHalls()->attach($hall->id);
            $favorited = true;
        }

        return response()->json([
            'success' => true,
            'favorited' => $favorited,
            'message' => $favorited ? 'تم إضافة القاعة للمفضلة' : 'تم إزالة القاعة من المفضلة'
        ]);
    }

    /**
     * Check if hall is favorited by current user.
     */
    public function isFavorited(Request $request, Hall $hall)
    {
        $user = $request->user();
        $isFavorited = $user->favoriteHalls()->where('hall_id', $hall->id)->exists();

        return response()->json([
            'favorited' => $isFavorited
        ]);
    }
}
