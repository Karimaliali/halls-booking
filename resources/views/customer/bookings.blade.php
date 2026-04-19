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
                                <h3 style="margin: 0 0 8px; color: #d4af37;">{{ $booking->hall->name ?? 'قاعة غير محددة' }}</h3>
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
                                        @if($booking->status == 'pending_payment')
                                            في انتظار الدفع
                                        @elseif($booking->status == 'pending')
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
                                @if($booking->status !== 'cancelled' && $booking->payment_status === 'pending')
                                    <button type="button" class="pay-now-btn" data-booking-id="{{ $booking->id }}" data-hall-name="{{ $booking->hall->name }}" data-booking-date="{{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}" data-price="{{ $booking->hall->price }}" style="padding: 10px 16px; border-radius: 999px; border: none; background: rgba(212,175,55,0.95); color: #1b365d; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                                        <i class="fa fa-credit-card"></i> ادفع الآن
                                    </button>
                                @endif
                                @if($booking->status !== 'cancelled')
                                    <form method="POST" action="{{ route('customer.bookings.cancel', $booking) }}" style="margin: 0;">
                                        @csrf
                                        <button type="submit" style="padding: 10px 16px; border-radius: 999px; border: none; background: rgba(239,68,68,0.9); color: #fff; font-weight: 700; cursor: pointer;">
                                            <i class="fa fa-times-circle"></i> إلغاء الحجز
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('halls.show', $booking->hall) }}" style="padding: 10px 16px; border-radius: 999px; background: rgba(100,116,139,0.9); color: #fff; font-weight: 700; text-decoration: none;">
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

    <!-- Payment Modal -->
    <div id="paymentModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.7); z-index: 9999; align-items: center; justify-content: center;">
        <div style="background: #fff; border-radius: 20px; padding: 30px; max-width: 500px; width: 90%; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="margin: 0; color: #1f2937;">بيانات الدفع</h3>
                <button type="button" onclick="closePaymentModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #6b7280;">&times;</button>
            </div>

            <div id="paymentInfo" style="background: rgba(212,175,55,0.1); border: 1px solid #d4af37; border-radius: 12px; padding: 16px; margin-bottom: 20px;">
                <div style="display: grid; gap: 12px;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                        <div>
                            <div style="font-size: 0.85rem; color: rgba(0,0,0,0.6); margin-bottom: 4px;">اسم القاعة</div>
                            <strong id="paymentHallName" style="color: #1f2937;">-</strong>
                        </div>
                        <div>
                            <div style="font-size: 0.85rem; color: rgba(0,0,0,0.6); margin-bottom: 4px;">تاريخ الحجز</div>
                            <strong id="paymentBookingDate" style="color: #1f2937;">-</strong>
                        </div>
                    </div>
                    <div style="border-top: 1px solid rgba(212,175,55,0.3); padding-top: 12px;">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                            <div>
                                <div style="font-size: 0.85rem; color: rgba(0,0,0,0.6); margin-bottom: 4px;">السعر (الليلة)</div>
                                <strong id="paymentPrice" style="color: #d4af37; font-size: 1.2rem;">-</strong>
                            </div>
                            <div style="text-align: left;">
                                <div style="font-size: 0.85rem; color: rgba(0,0,0,0.6); margin-bottom: 4px;">العربون المطلوب (25%)</div>
                                <strong id="paymentDeposit" style="color: #d4af37; font-size: 1.2rem;">-</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div style="background: rgba(59,130,246,0.1); border: 1px solid #3b82f6; border-radius: 12px; padding: 12px; margin-bottom: 20px; color: #1e40af; font-size: 0.9rem;">
                <i class="fa fa-info-circle" style="margin-left: 6px;"></i>
                سيتم دفع عربون 25% فقط الآن. المبلغ المتبقي يُستحق عند تأكيد الحجز.
            </div>

            <button type="button" id="proceedPaymentBtn" onclick="proceedWithPayment()" style="width: 100%; padding: 14px; background: linear-gradient(135deg, #d4af37 0%, #f59e0b 100%); color: #1b365d; font-weight: 700; border: none; border-radius: 10px; cursor: pointer; font-size: 1rem; display: flex; align-items: center; justify-content: center; gap: 10px;">
                <i class="fa fa-lock"></i> ادفع الآن عبر Paymob
            </button>

            <div id="paymentLoadingSpinner" style="display: none; text-align: center; padding: 20px;">
                <div style="display: inline-block; width: 40px; height: 40px; border: 4px solid rgba(212,175,55,0.2); border-top-color: #d4af37; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                <p style="margin-top: 12px; color: #6b7280;">جاري معالجة الدفع...</p>
            </div>
        </div>
    </div>

    <script>
        let currentPaymentBookingId = null;

        function openPaymentModal(bookingId, hallName, bookingDate, price) {
            currentPaymentBookingId = bookingId;
            const priceNum = parseInt(price.toString().replace(/\D/g, '')) || 0;
            const deposit = Math.ceil(priceNum * 0.25);

            document.getElementById('paymentHallName').textContent = hallName;
            document.getElementById('paymentBookingDate').textContent = bookingDate;
            document.getElementById('paymentPrice').textContent = `${priceNum} ج.م`;
            document.getElementById('paymentDeposit').textContent = `${deposit} ج.م`;

            document.getElementById('paymentModal').style.display = 'flex';
        }

        function closePaymentModal() {
            document.getElementById('paymentModal').style.display = 'none';
            currentPaymentBookingId = null;
        }

        async function proceedWithPayment() {
            if (!currentPaymentBookingId) {
                alert('حدث خطأ، يرجى المحاولة مجدداً');
                return;
            }

            const btn = document.getElementById('proceedPaymentBtn');
            const spinner = document.getElementById('paymentLoadingSpinner');
            const info = document.getElementById('paymentInfo');

            try {
                btn.style.display = 'none';
                info.style.display = 'none';
                spinner.style.display = 'block';

                const response = await fetch('/api/payments/initiate', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        booking_id: currentPaymentBookingId,
                        deposit_percentage: 25
                    })
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.error || data.details || 'فشل في بدء عملية الدفع');
                }

                if (data.iframe_url) {
                    window.location.href = data.iframe_url;
                } else {
                    throw new Error('لم يتم الحصول على رابط الدفع');
                }
            } catch (error) {
                console.error('Payment error:', error);
                alert('حدث خطأ: ' + error.message);
                
                btn.style.display = 'flex';
                info.style.display = 'block';
                spinner.style.display = 'none';
            }
        }

        // Add event listeners to pay now buttons
        document.querySelectorAll('.pay-now-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                openPaymentModal(
                    this.dataset.bookingId,
                    this.dataset.hallName,
                    this.dataset.bookingDate,
                    this.dataset.price
                );
            });
        });

        // Close modal when clicking outside
        document.getElementById('paymentModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePaymentModal();
            }
        });

        // Add CSS animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes spin {
                to { transform: rotate(360deg); }
            }
        `;
        document.head.appendChild(style);
    </script>
@endsection
