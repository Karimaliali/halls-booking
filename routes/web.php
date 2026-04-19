<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HallController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WebAuthController;
use App\Models\Hall;
use Illuminate\Http\Request;
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

    Route::post('/payments/initiate', [PaymentController::class, 'initiatePayment'])
        ->name('payments.initiate');

    Route::get('/payments/test-callback', function (Request $request) {
        $bookingId = $request->query('booking_id');
        $status = $request->query('status', 'success');

        if (!$bookingId) {
            return redirect('/')->with('error', 'Invalid booking ID');
        }

        $booking = \App\Models\Booking::find($bookingId);
        if (!$booking || $booking->user_id !== auth()->id()) {
            return redirect('/')->with('error', 'Booking not found or unauthorized');
        }

        if ($status === 'success') {
            $booking->update([
                'payment_status' => 'completed',
                'payment_date' => now(),
            ]);
            return redirect('/customer/bookings')->with('success', 'Payment completed successfully (Test Mode)');
        } else {
            return redirect('/customer/bookings')->with('error', 'Payment failed (Test Mode)');
        }
    })->name('payments.test-callback');

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
        })->with(['hall', 'user'])->latest()->get();
        return view('owner.bookings', compact('bookings'));
    })->name('owner.bookings');

    Route::post('/owner/bookings/{booking}/confirm', [BookingController::class, 'confirmBooking'])
        ->name('owner.bookings.confirm')
        ->middleware('role.web:owner');

    Route::post('/customer/bookings/{booking}/cancel', [BookingController::class, 'cancelBooking'])
        ->name('customer.bookings.cancel')
        ->middleware('role.web:customer');

    // Owner withdrawal routes
    Route::middleware('role.web:owner')->group(function () {
        Route::get('/owner/withdrawals', function () {
            $withdrawals = auth()->user()->withdrawals()->latest()->paginate(20);
            $totalEarnings = \App\Models\Booking::whereHas('hall', function($q) {
                $q->where('user_id', auth()->id());
            })->where('payment_status', 'completed')->sum('deposit_amount');
            $pendingWithdrawals = auth()->user()->withdrawals()->where('status', 'pending')->sum('amount');
            return view('owner.withdrawals', compact('withdrawals', 'totalEarnings', 'pendingWithdrawals'));
        })->name('owner.withdrawals');

        Route::post('/owner/withdrawals', function (Request $request) {
            $request->validate([
                'amount' => 'required|numeric|min:100',
                'bank_account' => 'required|string',
                'bank_name' => 'required|string',
                'account_holder' => 'required|string',
            ]);

            \App\Models\Withdrawal::create([
                'user_id' => auth()->id(),
                'amount' => $request->amount,
                'bank_account' => $request->bank_account,
                'bank_name' => $request->bank_name,
                'account_holder' => $request->account_holder,
                'requested_at' => now(),
            ]);

            return back()->with('success', 'تم طلب السحب بنجاح. سيتم معالجته قريباً.');
        })->name('owner.withdrawals.store');
    });

    // Admin routes
    Route::middleware('role.web:admin')->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

        Route::post('/admin/withdrawals/{withdrawal}/approve', [AdminController::class, 'approveWithdrawal'])->name('admin.withdrawals.approve');

        Route::post('/admin/withdrawals/{withdrawal}/complete', [AdminController::class, 'completeWithdrawal'])->name('admin.withdrawals.complete');
    });
});

