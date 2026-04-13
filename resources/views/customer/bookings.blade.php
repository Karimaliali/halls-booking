@extends('layouts.app')

@section('title', 'حجوزاتي')
@section('body-class', 'page-customer-bookings')

@section('content')
    <div class="container" style="padding: 40px 20px; margin-top: 90px;">
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap; margin-bottom: 24px;">
            <div>
                <h2 style="margin: 0 0 10px; font-weight: 900;">حجوزاتي</h2>
                <p style="margin: 0; color: rgba(255,255,255,0.75);">تابع جميع حجوزاتك، ويمكنك إلغاء الحجز لأي موعد غير مطلوب.</p>
            </div>
        </div>

        @if($bookings->count() > 0)
            <div style="display: grid; gap: 22px;">
                @foreach($bookings as $booking)
                    <div style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.12); border-radius: 22px; overflow: hidden; display: grid; grid-template-columns: 1fr 220px; gap: 0; min-height: 170px;">
                        <div style="padding: 22px; display: flex; flex-direction: column; justify-content: space-between; gap: 16px;">
                            <div>
                                <h3 style="margin: 0 0 8px;">{{ $booking->hall->name ?? 'قاعة غير محددة' }}</h3>
                                <p style="margin: 0; color: rgba(255,255,255,0.72);">{{ $booking->hall->location ?? 'الموقع غير محدد' }}</p>
                            </div>

                            <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px;">
                                <div style="background: rgba(255,255,255,0.05); padding: 14px; border-radius: 16px;">
                                    <div style="font-size: 0.8rem; color: rgba(255,255,255,0.55); margin-bottom: 6px;">تاريخ الحجز</div>
                                    <strong style="color: #fff;">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}</strong>
                                </div>
                                <div style="background: rgba(255,255,255,0.05); padding: 14px; border-radius: 16px;">
                                    <div style="font-size: 0.8rem; color: rgba(255,255,255,0.55); margin-bottom: 6px;">الحالة</div>
                                    <strong style="color: #fff;">
                                        @if($booking->status == 'pending')
                                            قيد المراجعة
                                        @elseif($booking->status == 'confirmed')
                                            مؤكد
                                        @elseif($booking->status == 'cancelled')
                                            ملغى
                                        @else
                                            {{ $booking->status }}
                                        @endif
                                    </strong>
                                </div>
                            </div>

                            <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
                                @if($booking->status !== 'cancelled')
                                    <form method="POST" action="{{ route('customer.bookings.cancel', $booking) }}" style="margin: 0;">
                                        @csrf
                                        <button type="submit" style="padding: 10px 16px; border-radius: 999px; border: none; background: rgba(239,68,68,0.9); color: #fff; font-weight: 700; cursor: pointer;">
                                            <i class="fa fa-times-circle"></i> إلغاء الحجز
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('halls.show', $booking->hall) }}" style="padding: 10px 16px; border-radius: 999px; background: rgba(212,175,55,0.95); color: #1b365d; font-weight: 700; text-decoration: none;">
                                    عرض القاعة
                                </a>
                            </div>
                        </div>
                        <div style="background: url('{{ $booking->hall->main_image_url ?? 'https://via.placeholder.com/400x300' }}') center center / cover no-repeat; min-height: 170px;"></div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="margin-top: 24px; padding: 30px; background: rgba(255,255,255,0.08); border: 1px dashed rgba(255,255,255,0.25); border-radius: 20px; text-align: center; color: rgba(255,255,255,0.75);">
                <p style="margin: 0; font-size: 1.05rem;">لا توجد حجوزات حتى الآن.</p>
            </div>
        @endif
    </div>
@endsection
