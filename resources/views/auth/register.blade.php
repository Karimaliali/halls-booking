@extends('layouts.app')

@section('title', 'التسجيل')
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

    .navbar .nav-signup-btn:hover {
        box-shadow: 0 12px 28px rgba(212, 175, 55, 0.4) !important;
    }

    .navbar .nav-signup-btn {
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
        <div class="auth-card" id="registerCard">
            <div class="auth-header">
                <h2>إنشاء حساب جديد</h2>
                <p>انضم إلى قاعة وابدأ في حجز أو إدارة القاعات</p>
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

            <form class="auth-form" action="{{ route('register') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label><i class="fa-regular fa-user"></i> الاسم الكامل</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="أدخل اسمك" required />
                </div>

                <div class="form-group">
                    <label><i class="fa-regular fa-envelope"></i> البريد الإلكتروني</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="أدخل بريدك الإلكتروني" required />
                </div>

                <div class="form-group">
                    <label><i class="fa-regular fa-user"></i> الدور</label>
                    <select name="role" required>
                        <option value="" disabled {{ empty($role) ? 'selected' : '' }}>اختر الدور</option>
                        <option value="customer" {{ old('role', $role) === 'customer' ? 'selected' : '' }}>مستخدم (Customer)</option>
                        <option value="owner" {{ old('role', $role) === 'owner' ? 'selected' : '' }}>مالك (Owner)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label><i class="fa-regular fa-lock"></i> كلمة المرور</label>
                    <input type="password" name="password" placeholder="أدخل كلمة المرور" required />
                </div>

                <div class="form-group">
                    <label><i class="fa-regular fa-lock"></i> تأكيد كلمة المرور</label>
                    <input type="password" name="password_confirmation" placeholder="أعد كتابة كلمة المرور" required />
                </div>

                <button type="submit" class="auth-btn">
                    <i class="fa-regular fa-user-plus"></i>
                    إنشاء الحساب
                </button>
            </form>

            <div class="auth-footer">
                <p>
                    لديك حساب بالفعل؟
                    <a href="{{ route('login') }}">تسجيل الدخول</a>
                </p>
            </div>
        </div>
    </div>
@endsection
