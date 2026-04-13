@extends('layouts.app')

@section('title', 'تسجيل الدخول')
@section('body-class', 'page-auth')

@push('styles')
<style>
    .navbar {
        background: linear-gradient(135deg, #1b365d 0%, #152b4f 100%) !important;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3) !important;
        border-bottom: 1px solid rgba(212, 175, 55, 0.2) !important;
    }

    .navbar .logo {
        color: #d4af37 !important;
    }

    .navbar .logo i {
        color: #d4af37 !important;
    }

    .navbar .nav-links li a {
        color: rgba(255, 255, 255, 0.95) !important;
    }

    .navbar .nav-links li a:hover {
        background: rgba(212, 175, 55, 0.25) !important;
        color: #d4af37 !important;
    }

    .navbar .nav-signup-btn {
        background: linear-gradient(135deg, #d4af37 0%, #f59e0b 100%) !important;
        color: #1b365d !important;
    }

    .navbar .book-now-btn {
        display: none !important;
    }

    .navbar .nav-signup-btn:hover {
        box-shadow: 0 12px 28px rgba(212, 175, 55, 0.4) !important;
    }

    .navbar .nav-auth-btn {
        display: none !important;
    }

    .auth-container {
        padding-top: 140px;
        min-height: calc(100vh - 120px);
    }
</style>
@endpush

@section('content')
    <div class="auth-container">
        <div class="auth-card" id="loginCard">
            <div class="auth-header">
                <h2>تسجيل الدخول</h2>
                <p>مرحباً بعودتك! يرجى إدخال بياناتك للدخول</p>
            </div>

            @if($errors->any())
                <div class="auth-alert">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form class="auth-form" action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label><i class="fa-regular fa-envelope"></i> البريد الإلكتروني</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="أدخل بريدك الإلكتروني" required />
                </div>

                <div class="form-group">
                    <label><i class="fa-regular fa-lock"></i> كلمة المرور</label>
                    <input type="password" name="password" placeholder="أدخل كلمة المرور" required />
                </div>

                <div class="form-group">
                    <label><i class="fa-regular fa-user"></i> تذكرني</label>
                    <input type="checkbox" name="remember" />
                </div>

                <button type="submit" class="auth-btn">
                    <i class="fa-regular fa-arrow-left-to-bracket"></i>
                    تسجيل الدخول
                </button>
            </form>

            <div class="auth-footer">
                <p>
                    ليس لديك حساب؟
                    <a href="{{ route('register') }}">التسجيل الآن</a>
                </p>
            </div>
        </div>
    </div>
@endsection
