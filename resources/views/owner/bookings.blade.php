@extends('layouts.app')

@section('title', 'حجوزات قاعاتي')
@section('body-class', 'page-owner-bookings')

@section('content')
    <div class="container" style="padding: 40px 20px; margin-top: 90px;">
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap; margin-bottom: 30px;">
            <div>
                <h2 style="margin: 0 0 10px; font-weight: 900; font-size: 2rem;">حجوزات قاعاتي</h2>
                <p style="margin: 0; color: rgba(255,255,255,0.75);">إدارة جميع الحجوزات الواردة لقاعاتك مع تفاصيل مفصلة لكل حجز.</p>
            </div>
        </div>

        @if($bookings->isEmpty())
            <div style="margin-top: 30px; padding: 40px 30px; background: rgba(255,255,255,0.08); border: 2px dashed rgba(255,255,255,0.25); border-radius: 18px; text-align: center;">
                <i class="fa fa-inbox" style="font-size: 3rem; color: rgba(255,255,255,0.4); display: block; margin-bottom: 16px;"></i>
                <p style="margin: 0; color: rgba(255,255,255,0.75); font-size: 1.1rem; font-weight: 600;">لا توجد حجوزات حتى الآن</p>
                <p style="margin: 8px 0 0; color: rgba(255,255,255,0.6);">سيظهر هنا جميع حجوزات قاعاتك فور استقبال طلبات حجز جديدة</p>
            </div>
        @else
            <!-- عرض الحجوزات كـ Cards منظمة -->
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 24px;">
                @foreach($bookings as $booking)
                    <div style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); border-radius: 18px; overflow: hidden; cursor: pointer;">
                        
                        <!-- رأس البطاقة -->
                        <div style="padding: 20px; border-bottom: 2px solid rgba(255,255,255,0.1); background: rgba(27, 54, 93, 0.3);">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 12px;">
                                <div style="flex: 1;">
                                    <h3 style="margin: 0 0 8px; font-weight: 900; font-size: 1.15rem;">
                                        <a href="{{ route('halls.show', $booking->hall) }}" style="color: #d4af37; text-decoration: none;">
                                            {{ $booking->hall->name }}
                                        </a>
                                    </h3>
                                    <p style="margin: 0; color: rgba(255,255,255,0.6); font-size: 0.85rem;">
                                        <i class="fa fa-map-pin"></i> {{ $booking->hall->location }}
                                    </p>
                                </div>
                                <span style="padding: 8px 14px; border-radius: 8px; display: inline-block; font-weight: 900; font-size: 0.85rem; white-space: nowrap;
                                    {{ $booking->status === 'confirmed' ? 'background: rgba(16, 185, 129, 0.25); color: #10b981;' : '' }}
                                    {{ $booking->status === 'pending' ? 'background: rgba(245, 158, 11, 0.25); color: #f59e0b;' : '' }}
                                    {{ $booking->status === 'cancelled' ? 'background: rgba(239, 68, 68, 0.25); color: #ef4444;' : '' }}
                                ">
                                    @if($booking->status === 'confirmed')
                                        <i class="fa fa-check-circle"></i> مؤكد
                                    @elseif($booking->status === 'pending')
                                        <i class="fa fa-clock"></i> قيد الانتظار
                                    @elseif($booking->status === 'cancelled')
                                        <i class="fa fa-times-circle"></i> ملغى
                                    @else
                                        {{ $booking->status }}
                                    @endif
                                </span>
                            </div>
                        </div>

                        <!-- محتوى البطاقة -->
                        <div style="padding: 20px;">
                            <!-- تفاصيل العميل -->
                            <div style="margin-bottom: 18px;">
                                <strong style="color: rgba(255,255,255,0.5); display: block; margin-bottom: 8px; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">بيانات العميل</strong>
                                <div style="display: flex; gap: 12px; align-items: flex-start;">
                                    <div style="flex-shrink: 0;">
                                        <div style="width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), var(--accent)); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 900; font-size: 1.2rem;">
                                            {{ substr($booking->user->name, 0, 1) }}
                                        </div>
                                    </div>
                                    <div style="flex: 1;">
                                        <p style="margin: 0 0 4px; font-weight: 700; color: #fff;">{{ $booking->user_name ?? $booking->user->name }}</p>
                                        <p style="margin: 0; color: rgba(255,255,255,0.65); font-size: 0.85rem;">{{ $booking->user->email }}</p>
                                    </div>
                                </div>
                            </div>

                            @if($booking->payment_status === 'completed')
                                <div style="margin-bottom: 18px; padding: 16px; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.12); border-radius: 14px;">
                                    <strong style="color: rgba(255,255,255,0.6); display: block; margin-bottom: 10px; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">بيانات العميل بعد الدفع</strong>
                                    <div style="display: grid; gap: 10px;">
                                        <p style="margin: 0; color: rgba(255,255,255,0.8); font-size: 0.95rem;"><strong>الهاتف:</strong> {{ optional($booking->user)->phone ?? 'غير متوفر' }}</p>
                                        <p style="margin: 0; color: rgba(255,255,255,0.8); font-size: 0.95rem;"><strong>الرقم القومي:</strong> {{ $booking->user_id_number ?? 'غير متوفر' }}</p>

                                        @if($booking->id_card_image)
                                            <div>
                                                <p style="margin: 0 0 6px; color: rgba(255,255,255,0.7); font-size: 0.85rem;">صورة البطاقة الشخصية</p>
                                                <img src="{{ route('storage.file', $booking->id_card_image) }}" alt="ID Card Image" style="max-width: 100%; border-radius: 12px; border: 1px solid rgba(255,255,255,0.1);"/>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div style="margin-bottom: 18px; padding: 16px; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.12); border-radius: 14px;">
                                    <p style="margin: 0; color: rgba(255,255,255,0.75); font-size: 0.95rem;">
                                        سيتم عرض بيانات العميل الشخصية كاملة بعد اكتمال دفع العربون.
                                    </p>
                                </div>
                            @endif

                            <!-- تفاصيل الحجز -->
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                                <!-- تاريخ الحجز -->
                                <div style="padding: 12px; background: rgba(255,255,255,0.05); border-radius: 12px; border-right: 3px solid var(--primary);">
                                    <p style="margin: 0 0 4px; color: rgba(255,255,255,0.5); font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">تاريخ الحجز</p>
                                    <p style="margin: 0; font-weight: 900; color: #fff; font-size: 1rem;">
                                        {{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}
                                    </p>
                                    <p style="margin: 4px 0 0; font-size: 0.8rem; color: rgba(255,255,255,0.6);">
                                        {{ \Carbon\Carbon::parse($booking->booking_date)->format('l') }}
                                    </p>
                                </div>

                                <!-- تاريخ التسجيل -->
                                <div style="padding: 12px; background: rgba(255,255,255,0.05); border-radius: 12px; border-right: 3px solid var(--accent);">
                                    <p style="margin: 0 0 4px; color: rgba(255,255,255,0.5); font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">تم الطلب</p>
                                    <p style="margin: 0; font-weight: 900; color: #fff; font-size: 1rem;">
                                        {{ $booking->created_at->diffForHumans() }}
                                    </p>
                                    <p style="margin: 4px 0 0; font-size: 0.8rem; color: rgba(255,255,255,0.6);">
                                        {{ $booking->created_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- زر الإجراء -->
                        <div style="padding: 16px 20px; background: rgba(255,255,255,0.05); border-top: 1px solid rgba(255,255,255,0.1); display: flex; gap: 10px; flex-wrap: wrap; align-items: center; justify-content: space-between;">
                            <a href="{{ route('halls.show', $booking->hall) }}" class="btn btn-primary" style="text-decoration: none; padding: 10px 16px; background: var(--primary); color: #fff; border-radius: 8px; font-weight: 700; display: inline-flex; align-items: center; gap: 8px;">
                                <i class="fa fa-eye"></i> عرض تفاصيل القاعة
                            </a>

                            @if($booking->status === 'pending')
                                <form method="POST" action="{{ route('owner.bookings.confirm', $booking) }}" style="margin: 0;">
                                    @csrf
                                    <button type="submit" class="btn btn-success" style="padding: 10px 16px; background: #10b981; color: #fff; border-radius: 8px; border: none; font-weight: 700; cursor: pointer;">
                                        <i class="fa fa-check"></i> تأكيد الحجز
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
