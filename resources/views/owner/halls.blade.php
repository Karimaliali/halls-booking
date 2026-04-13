@extends('layouts.app')

@section('title', 'قاعاتي')
@section('body-class', 'page-owner-halls')

@section('content')
    @push('styles')
    <style>
        .owner-halls-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 32px;
        }

        .owner-halls-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 22px;
        }

        .hall-card {
            background: linear-gradient(135deg, rgba(27, 54, 93, 0.85) 0%, rgba(21, 43, 79, 0.75) 100%);
            border: 1px solid rgba(212, 175, 55, 0.25);
            border-radius: 24px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: none;
        }

        .hall-card:hover {
            transform: none;
            box-shadow: 0 20px 60px rgba(212, 175, 55, 0.15), 0 8px 32px rgba(0, 0, 0, 0.18);
            border-color: rgba(212, 175, 55, 0.3);
        }

        .hall-card img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            display: block;
        }

        .hall-card-body {
            padding: 22px;
            display: flex;
            flex-direction: column;
            gap: 16px;
            flex: 1;
        }

        .hall-card-body h3 {
            margin: 0;
            color: #fff;
            font-weight: 800;
        }

        .hall-status {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            font-weight: 700;
            font-size: 0.88rem;
            white-space: nowrap;
        }

        .hall-status.active {
            background: rgba(16, 185, 129, 0.16);
            color: #10b981;
        }

        .hall-status.inactive {
            background: rgba(239, 68, 68, 0.16);
            color: #ef4444;
        }

        .hall-card-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .hall-badges {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .hall-badge {
            background: rgba(212, 175, 55, 0.15);
            border-radius: 999px;
            padding: 8px 12px;
            font-size: 0.85rem;
            color: #ffd54f;
            display: inline-flex;
            gap: 8px;
            align-items: center;
            font-weight: 600;
        }

        .hall-badge i {
            color: #ffd54f;
        }

        .hall-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
            margin-top: 8px;
            padding-top: 12px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .hall-actions a,
        .hall-actions button {
            border-radius: 999px;
            padding: 10px 16px;
            border: none;
            text-decoration: none;
            font-weight: 700;
            cursor: pointer;
            transition: none;
            font-size: 0.9rem;
        }

        .hall-actions a:hover,
        .hall-actions button:hover {
            transform: none;
        }

        .btn-action-primary {
            background: rgba(212, 175, 55, 0.95);
            color: #1b365d;
        }

        .btn-action-primary:hover {
            background: rgba(255, 215, 85, 0.95);
        }

        .btn-action-secondary {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .btn-action-secondary:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .hall-card-meta {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            color: rgba(255, 255, 255, 0.72);
            font-size: 0.95rem;
        }
    </style>
    @endpush

    <div class="container" style="padding: 40px 20px;">
        <div class="owner-halls-header">
            <div>
                <h2 style="margin: 0 0 10px;">قاعاتي</h2>
                <p style="margin: 0; color: rgba(255,255,255,0.75);">هنا تجد جميع القاعات التي قمت بإضافتها، ويمكنك تعديلها أو حذفها أو عرض تفاصيل الحجز الخاصة بها.</p>
            </div>
            <a href="{{ route('owner.halls.create') }}" class="nav-auth-btn" style="background: #facc15; color: #222;">+ إضافة قاعة جديدة</a>
        </div>

        @if($halls->isEmpty())
            <div style="margin-top: 30px; padding: 24px; background: rgba(255,255,255,0.08); border: 1px dashed rgba(255,255,255,0.25); border-radius: 16px;">
                <p style="margin: 0;">ليس لديك قاعات بعد. اضغط على "إضافة قاعة جديدة" لبدء إضافة أول قاعة.</p>
            </div>
        @else
            <div class="owner-halls-grid">
                @foreach($halls as $hall)
                    <div class="hall-card">
                        <a href="{{ route('owner.halls.show', $hall) }}">
                            <img src="{{ $hall->first_image_url }}" alt="{{ $hall->name }}" />
                        </a>

                        <div class="hall-card-body">
                            <div class="hall-card-row">
                                <h3 style="margin: 0;">{{ $hall->name }}</h3>
                                <span class="hall-status {{ trim($hall->status) === 'متاح' || trim($hall->status) === 'active' ? 'active' : 'inactive' }}">
                                    {{ trim($hall->status) === 'متاح' || trim($hall->status) === 'active' ? 'مفعلة' : 'معطلة' }}
                                </span>
                            </div>

                            <div class="hall-badges">
                                <span class="hall-badge"><i class="fa fa-map-pin"></i> {{ $hall->location }}</span>
                                <span class="hall-badge"><i class="fa fa-users"></i> {{ $hall->capacity }} ضيف</span>
                                <span class="hall-badge"><i class="fa fa-money-bill"></i> {{ number_format($hall->price) }} ج.م / ليلة</span>
                            </div>

                            @if(!empty($hall->category) || ($hall->features && count($hall->features)))
                                <div class="hall-card-meta">
                                    @if(!empty($hall->category))
                                        <span><strong>الفئة:</strong> {{ $hall->category }}</span>
                                    @endif
                                    @if($hall->features)
                                        <span><strong>مزايا:</strong> {{ implode('، ', array_slice($hall->features, 0, 3)) }}{{ count($hall->features) > 3 ? '...' : '' }}</span>
                                    @endif
                                </div>
                            @endif

                            <div class="hall-actions">
                                <a href="{{ route('owner.halls.show', $hall) }}" class="btn-action-primary">عرض التفاصيل</a>
                                <a href="{{ route('owner.halls.edit', $hall) }}" class="btn-action-secondary">تعديل</a>
                                <form method="POST" action="{{ route('owner.halls.destroy', $hall) }}" onsubmit="return confirm('هل أنت متأكد من حذف هذه القاعة؟')" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action-secondary" style="background: rgba(239,68,68,0.12); color: #ef4444;">حذف</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
