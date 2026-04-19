<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Withdrawal;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalBookings = Booking::count();
        $totalRevenue = Booking::where('payment_status', 'completed')->sum('deposit_amount');
        $pendingWithdrawals = Withdrawal::where('status', 'pending')->sum('amount');
        $withdrawals = Withdrawal::with('user')
            ->whereIn('status', ['pending', 'approved'])
            ->latest()
            ->paginate(10);

        return view('admin.dashboard', compact(
            'totalBookings',
            'totalRevenue',
            'pendingWithdrawals',
            'withdrawals'
        ));
    }

    public function approveWithdrawal(Withdrawal $withdrawal)
    {
        $withdrawal->approve();
        return back()->with('success', 'تمت الموافقة على طلب السحب');
    }

    public function completeWithdrawal(Withdrawal $withdrawal)
    {
        $withdrawal->complete();
        return back()->with('success', 'تم تسجيل التحويل');
    }
}
