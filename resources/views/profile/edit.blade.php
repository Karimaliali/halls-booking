@extends('layouts.app')

@section('title', 'تعديل الحساب')

@section('body-class', 'page-profile-edit')

@section('content')
    <div class="container" style="padding: 40px 20px;">
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;">
            <div>
                <h2 style="margin: 0 0 10px;">تعديل الحساب</h2>
                <p style="margin: 0; color: rgba(255,255,255,0.75);">قم بتحديث بيانات حسابك.</p>
            </div>
        </div>

        @if(session('status'))
            <div style="margin-top: 18px; padding: 14px 18px; background: rgba(76, 175, 80, 0.16); border: 1px solid rgba(76, 175, 80, 0.35); border-radius: 12px; color: #fff; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-check-circle" style="color: #4caf50; font-size: 20px;"></i>
                {{ session('status') }}
            </div>
        @endif

        @if($errors->any())
            <div style="margin-top: 18px; padding: 14px 18px; background: rgba(220, 38, 38, 0.16); border: 1px solid rgba(220, 38, 38, 0.35); border-radius: 12px; color: #fff; display: flex; align-items: flex-start; gap: 10px;">
                <i class="fas fa-exclamation-triangle" style="color: #dc2626; font-size: 20px; margin-top: 2px;"></i>
                <div>
                    <strong>هناك أخطاء:</strong>
                    <ul style="margin: 8px 0 0 0; padding-left: 18px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" style="margin-top: 26px; background: rgba(255,255,255,0.06); padding: 30px; border-radius: 18px; border: 1px solid rgba(255,255,255,0.12);">
            @csrf
            <div class="form-row" style="display: flex; gap: 16px; flex-wrap: wrap;">
                <div class="form-group" style="flex: 1; min-width: 280px;">
                    <label style="display: block; margin-bottom: 6px; font-weight: 700;">الاسم</label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required style="width: 100%; padding: 12px 14px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.2); background: rgba(0,0,0,0.4); color: #fff;" />
                </div>

                <div class="form-group" style="flex: 1; min-width: 280px;">
                    <label style="display: block; margin-bottom: 6px; font-weight: 700;">البريد الإلكتروني</label>
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required style="width: 100%; padding: 12px 14px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.2); background: rgba(0,0,0,0.4); color: #fff;" />
                </div>

                <div class="form-group" style="flex: 1; min-width: 280px;">
                    <label style="display: block; margin-bottom: 6px; font-weight: 700;">رقم الهاتف</label>
                    <input type="tel" name="phone" value="{{ old('phone', auth()->user()->phone) }}" required style="width: 100%; padding: 12px 14px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.2); background: rgba(0,0,0,0.4); color: #fff;" />
                </div>
            </div>

            <div style="margin-top: 20px; display: flex; gap: 10px; flex-wrap: wrap;">
                <button type="submit" class="nav-auth-btn btn-primary">حفظ التغييرات</button>
                <a href="{{ url()->previous() }}" class="nav-auth-btn btn-secondary">إلغاء</a>
            </div>
        </form>

        <hr style="margin: 40px 0; border-color: rgba(255,255,255,0.15);" />

        <div style="padding: 20px; background: rgba(255,255,255,0.05); border-radius: 16px;">
            <h3 style="margin: 0 0 12px; font-size: 24px;">حذف الحساب</h3>
            <p style="margin: 0 0 16px; color: rgba(255,255,255,0.75);">بحذف حسابك سيتم إزالة بياناتك وجميع القاعات المرتبطة بك.</p>
            <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('هل أنت متأكد أنك تريد حذف الحساب؟ هذا الإجراء لا يمكن التراجع عنه.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="nav-auth-btn" style="background: #dc2626; color: #fff; padding: 14px 32px; font-size: 16px; font-weight: 700; border-radius: 8px;">حذف الحساب</button>
            </form>
        </div>
    </div>
@endsection
