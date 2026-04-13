<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\HallController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WebAuthController;
use App\Models\Hall;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

// Serve storage files without symlink (for USB/FAT32)
Route::get('storage-file/{path}', function ($path) {
    $storagePath = base_path('storage/app/public/' . $path);
    
    if (!is_file($storagePath)) {
        return response('Not Found', 404);
    }
    
    $ext = strtolower(pathinfo($storagePath, PATHINFO_EXTENSION));
    $mimes = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg', 
        'png' => 'image/png',
        'gif' => 'image/gif',
        'webp' => 'image/webp'
    ];
    
    $mime = $mimes[$ext] ?? 'application/octet-stream';
    
    // Use BinaryFileResponse to handle FAT32
    return new \Symfony\Component\HttpFoundation\BinaryFileResponse($storagePath, 200, [
        'Content-Type' => $mime,
        'Content-Disposition' => 'inline'
    ]);
})->where('path', '.+')->name('storage.file');

Route::get('/debug-search', function () {
    $halls = Hall::where('location', 'like', '%طلخا - الدقهليه%')->get();
    return response()->json([
        'search_term' => 'طلخا - الدقهليه',
        'results_count' => $halls->count(),
        'results' => $halls->map(function($hall) {
            return [
                'name' => $hall->name,
                'location' => $hall->location,
                'price' => $hall->price
            ];
        })
    ]);
});

Route::get('/', function () {
    $featuredHalls = Hall::withAvg('reviews', 'rating')
        ->withCount('reviews')
        ->latest()
        ->take(3)
        ->get();

    return view('home', compact('featuredHalls'));
})->name('home');

Route::get('/search', [HallController::class, 'search'])->name('search');
Route::get('/halls/{hall}', [HallController::class, 'publicShow'])->name('halls.show');

Route::post('/halls/{hall}/reviews', [HallController::class, 'storeReview'])
    ->name('halls.reviews.store')
    ->middleware('auth');

Route::middleware('guest')->group(function () {
    Route::get('/login', [WebAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [WebAuthController::class, 'login']);

    Route::get('/register', [WebAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [WebAuthController::class, 'register']);
});

Route::match(['get', 'post'], '/logout', [WebAuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/profile/edit', function () {
        return view('profile.edit');
    })->name('profile.edit');

    Route::post('/profile/update', [UserController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [UserController::class, 'destroy'])
        ->name('profile.destroy');

    Route::post('/bookings', [BookingController::class, 'store'])
        ->name('bookings.store');

    Route::get('/customer/bookings', function () {
        $bookings = auth()->user()->bookings()->with('hall')->latest()->get();
        return view('customer.bookings', compact('bookings'));
    })->name('customer.bookings')->middleware('role:customer');

    Route::get('/owner/halls', function () {
        $halls = auth()->user()->halls()->latest()->get();
        return view('owner.halls', compact('halls'));
    })->name('owner.halls')->middleware('role:owner');

    Route::get('/owner/halls/create', function () {
        return view('owner.add-hall');
    })->name('owner.halls.create')->middleware('role:owner');

    Route::post('/owner/halls', [HallController::class, 'store'])
        ->name('owner.halls.store')->middleware('role:owner');

    Route::get('/owner/halls/{hall}', [HallController::class, 'show'])
        ->name('owner.halls.show')->middleware('role:owner');

    Route::get('/owner/halls/{hall}/edit', [HallController::class, 'edit'])
        ->name('owner.halls.edit')->middleware('role:owner');

    Route::put('/owner/halls/{hall}', [HallController::class, 'update'])
        ->name('owner.halls.update')->middleware('role:owner');

    Route::delete('/owner/halls/{hall}', [HallController::class, 'destroy'])
        ->name('owner.halls.destroy')->middleware('role:owner');

    Route::get('/owner/bookings', function () {
        $bookings = \App\Models\Booking::whereHas('hall', function($q) {
            $q->where('user_id', auth()->id());
        })->with(['hall', 'user'])->get();
        return view('owner.bookings', compact('bookings'));
    })->name('owner.bookings');

    Route::post('/owner/bookings/{booking}/confirm', [BookingController::class, 'confirmBooking'])
        ->name('owner.bookings.confirm')
        ->middleware('role:owner');

    Route::post('/customer/bookings/{booking}/cancel', [BookingController::class, 'cancelBooking'])
        ->name('customer.bookings.cancel')
        ->middleware('role:customer');
});
