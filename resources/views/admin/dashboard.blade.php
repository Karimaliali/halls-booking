@extends('layouts.app')

@section('title', 'لوحة التحكم')

@section('content')
<section class="section">
    <div class="container">
        <div class="section-header">
            <h2>لوحة تحكم الإدارة</h2>
        </div>

        <div class="stats-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 40px;">
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 12px;">
                <div style="font-size: 14px; opacity: 0.9;">إجمالي الحجوزات</div>
                <div style="font-size: 28px; font-weight: bold; margin-top: 10px;">{{ $totalBookings }}</div>
            </div>
            <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 20px; border-radius: 12px;">
                <div style="font-size: 14px; opacity: 0.9;">إجمالي الإيرادات</div>
                <div style="font-size: 28px; font-weight: bold; margin-top: 10px;">{{ number_format($totalRevenue) }} ج.م</div>
            </div>
            <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 20px; border-radius: 12px;">
                <div style="font-size: 14px; opacity: 0.9;">طلبات سحب قيد الانتظار</div>
                <div style="font-size: 28px; font-weight: bold; margin-top: 10px;">{{ number_format($pendingWithdrawals) }} ج.م</div>
            </div>
        </div>

        <div style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; overflow: hidden;">
            <div style="padding: 20px; border-bottom: 1px solid rgba(255,255,255,0.1);">
                <h3 style="margin: 0; color: #fff;">طلبات السحب المعلقة</h3>
            </div>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: rgba(0,0,0,0.3);">
                        <th style="padding: 15px; text-align: right; color: #ccc; border-bottom: 1px solid rgba(255,255,255,0.1);">المالك</th>
                        <th style="padding: 15px; text-align: right; color: #ccc; border-bottom: 1px solid rgba(255,255,255,0.1);">المبلغ</th>
                        <th style="padding: 15px; text-align: right; color: #ccc; border-bottom: 1px solid rgba(255,255,255,0.1);">البنك</th>
                        <th style="padding: 15px; text-align: right; color: #ccc; border-bottom: 1px solid rgba(255,255,255,0.1);">الحالة</th>
                        <th style="padding: 15px; text-align: right; color: #ccc; border-bottom: 1px solid rgba(255,255,255,0.1);">الإجراء</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($withdrawals as $w)
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <td style="padding: 15px;">{{ $w->user->name }}</td>
                            <td style="padding: 15px;">{{ number_format($w->amount) }} ج.م</td>
                            <td style="padding: 15px;">{{ $w->bank_name }}</td>
                            <td style="padding: 15px;">
                                @if($w->status === 'pending')
                                    <span style="background: rgba(255,193,7,0.2); color: #ffc107; padding: 6px 12px; border-radius: 6px; font-size: 12px;">قيد الانتظار</span>
                                @elseif($w->status === 'approved')
                                    <span style="background: rgba(33,150,243,0.2); color: #2196f3; padding: 6px 12px; border-radius: 6px; font-size: 12px;">موافق عليه</span>
                                @elseif($w->status === 'completed')
                                    <span style="background: rgba(76,175,80,0.2); color: #4caf50; padding: 6px 12px; border-radius: 6px; font-size: 12px;">مكتمل</span>
                                @else
                                    <span style="background: rgba(244,67,54,0.2); color: #f44336; padding: 6px 12px; border-radius: 6px; font-size: 12px;">مرفوض</span>
                                @endif
                            </td>
                            <td style="padding: 15px;">
                                @if($w->status === 'pending')
                                    <form method="POST" action="{{ route('admin.withdrawals.approve', $w) }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" style="padding: 6px 12px; background: #2196f3; color: white; border: none; border-radius: 6px; cursor: pointer; margin-left: 5px;">موافقة</button>
                                    </form>
                                @elseif($w->status === 'approved')
                                    <form method="POST" action="{{ route('admin.withdrawals.complete', $w) }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" style="padding: 6px 12px; background: #4caf50; color: white; border: none; border-radius: 6px; cursor: pointer;">تم التحويل</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding: 30px; text-align: center; color: #999;">لا توجد طلبات معلقة</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 20px;">
            {{ $withdrawals->links() }}
        </div>
    </div>
</section>
@endsection
