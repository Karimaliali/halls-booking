<!doctype html>
<html lang="ar" dir="rtl">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=1280" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>@yield('title', 'قاعة')</title>

        <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
            media="print"
            onload="this.media='all'"
        />
        <link rel="stylesheet" href="{{ asset('front_halls_booking/style.css') }}?v={{ md5_file(public_path('front_halls_booking/style.css')) }}" />
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
        @push('styles')
        <style>
            html,
            body {
                margin: 0 !important;
                padding: 0 !important;
                min-height: 100% !important;
                background: #f6f7fa !important;
                color: #242d39 !important;
            }

            body.page-search {
                padding-top: 90px !important;
                background: linear-gradient(135deg, #0f1f35 0%, #1b365d 100%) !important;
                color: #fff !important;
            }

            body.page-owner-halls,
            body.page-owner-hall-details,
            body.page-owner-add-hall,
            body.page-owner-edit-hall,
            body.page-owner-bookings,
            body.page-customer-bookings,
            body.page-profile-edit {
                background: linear-gradient(135deg, #0f1f35 0%, #1b365d 100%) !important;
                color: #fff !important;
            }

            body.page-home .navbar {
                background: transparent !important;
                box-shadow: none !important;
                border-bottom: none !important;
            }

            body.page-home .navbar.scrolled {
                background: rgba(27, 54, 93, 0.95) !important;
                box-shadow: 0 16px 48px rgba(15, 23, 42, 0.25) !important;
                border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
            }

            body.page-owner-halls,
            body.page-owner-hall-details,
            body.page-owner-add-hall,
            body.page-owner-bookings,
            body.page-customer-bookings,
            body.page-profile-edit {
                padding-top: 90px !important;
            }

            body.page-owner-halls .navbar,
            body.page-owner-hall-details .navbar,
            body.page-owner-add-hall .navbar,
            body.page-owner-bookings .navbar,
            body.page-customer-bookings .navbar,
            body.page-profile-edit .navbar {
                background: rgba(27, 54, 93, 0.95) !important;
                box-shadow: 0 12px 40px rgba(0, 0, 0, 0.35) !important;
                backdrop-filter: blur(20px) !important;
            }

            html.modal-open,
            body.modal-open {
                overflow: hidden !important;
                touch-action: none !important;
                position: fixed !important;
                width: 100% !important;
                height: 100% !important;
            }

            body.modal-open {
                overflow: hidden !important;
                height: 100vh !important;
            }

            /* تحسينات الناف بار لصفحات المصادقة */
            body.page-auth {
                background: linear-gradient(135deg, #f6f7fa 0%, #e8ecf1 100%) !important;
            }

            body.page-auth .navbar,
            .page-auth .navbar {
                background: linear-gradient(135deg, #1b365d 0%, #152b4f 100%) !important;
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3) !important;
                border-bottom: 1px solid rgba(212, 175, 55, 0.2) !important;
                backdrop-filter: blur(16px) !important;
            }

            body.page-auth .navbar.scrolled,
            .page-auth .navbar.scrolled {
                background: linear-gradient(135deg, #152b4f 0%, #0f1f35 100%) !important;
                box-shadow: 0 12px 40px rgba(0, 0, 0, 0.4) !important;
            }

            body.page-auth .logo,
            .page-auth .logo {
                color: #d4af37 !important;
            }

            body.page-auth .logo i,
            .page-auth .logo i {
                color: #d4af37 !important;
            }

            body.page-auth .nav-links li a,
            body.page-auth .nav-links li .nav-auth-btn,
            .page-auth .nav-links li a,
            .page-auth .nav-links li .nav-auth-btn {
                color: rgba(255, 255, 255, 0.95) !important;
            }

            body.page-auth .nav-links li a:hover,
            body.page-auth .nav-links li .nav-auth-btn:hover,
            .page-auth .nav-links li a:hover,
            .page-auth .nav-links li .nav-auth-btn:hover {
                background: rgba(212, 175, 55, 0.25) !important;
                color: #d4af37 !important;
                text-shadow: 0 0 8px rgba(212, 175, 55, 0.3) !important;
            }

            body.page-auth .nav-links li a.active,
            .page-auth .nav-links li a.active {
                background: rgba(212, 175, 55, 0.3) !important;
                color: #d4af37 !important;
                border-bottom: 2px solid #d4af37 !important;
            }

            body.page-auth .nav-auth-btn,
            .page-auth .nav-auth-btn {
                border-color: rgba(255, 255, 255, 0.7) !important;
                color: rgba(255, 255, 255, 0.95) !important;
            }

            body.page-auth .nav-signup-btn,
            .page-auth .nav-signup-btn {
                background: linear-gradient(135deg, #d4af37 0%, #f59e0b 100%) !important;
                color: #1b365d !important;
                border: none !important;
                font-weight: 800 !important;
                box-shadow: 0 8px 20px rgba(212, 175, 55, 0.3) !important;
            }

            body.page-auth .nav-signup-btn:hover,
            .page-auth .nav-signup-btn:hover {
                background: linear-gradient(135deg, #f59e0b 0%, #d4af37 100%) !important;
                box-shadow: 0 12px 28px rgba(212, 175, 55, 0.4) !important;
                transform: translateY(-3px) !important;
            }

            body.page-auth .nav-divider,
            .page-auth .nav-divider {
                background: rgba(212, 175, 55, 0.3) !important;
            }

            .nav-user-dropdown {
                position: relative;
            }

            .nav-user-btn {
                background: none;
                border: none;
                color: rgba(255, 255, 255, 0.95);
                cursor: pointer;
                padding: 10px 15px;
                border-radius: 8px;
                display: flex;
                align-items: center;
                gap: 8px;
                font-size: 14px;
                transition: background 0.3s ease;
            }

            .nav-user-btn:hover {
                background: rgba(212, 175, 55, 0.25);
                color: #d4af37;
            }

            .user-dropdown-menu {
                position: absolute;
                top: 100%;
                right: 0;
                background: rgba(27, 54, 93, 0.98);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.1);
                border-radius: 12px;
                min-width: 180px;
                opacity: 0;
                visibility: hidden;
                transform: translateY(-10px);
                transition: all 0.3s ease;
                z-index: 1001;
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            }

            .user-dropdown-menu.show {
                opacity: 1;
                visibility: visible;
                transform: translateY(0);
            }

            .user-dropdown-menu a {
                display: block;
                padding: 12px 16px;
                color: rgba(255, 255, 255, 0.9);
                text-decoration: none;
                transition: background 0.3s ease;
                border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            }

            .user-dropdown-menu a:last-child {
                border-bottom: none;
            }

            .user-dropdown-menu a:hover {
                background: rgba(212, 175, 55, 0.2);
                color: #d4af37;
            }

            .user-dropdown-menu a i {
                margin-left: 8px;
                width: 16px;
            }
            section[id],
            header[id],
            footer[id] {
                scroll-margin-top: 100px;
            }
        </style>
        @endpush
        @stack('styles')
    </head>
    <body class="@yield('body-class')">
        <nav class="navbar">
            <div class="container nav-container">
                <div class="logo">
                    <i class="fas fa-door-open"></i>
                    <span>QAA'A</span>
                </div>
                <ul class="nav-links" id="mainNavLinks" data-server-managed="true">
                    <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}"><i class="fas fa-home"></i> <span>الرئيسية</span></a></li>
                    <li><a href="{{ route('search') }}" class="{{ request()->routeIs('search') ? 'active' : '' }}"><i class="fas fa-search"></i> <span>القاعات</span></a></li>
                    <li><a href="{{ request()->routeIs('home') ? '#contact' : route('home') . '#contact' }}"><i class="fas fa-info-circle"></i> <span>عن الموقع</span></a></li>
                    <li class="nav-divider"></li>
                    @guest
                        <li><a href="{{ route('register', ['role' => 'owner']) }}" class="nav-auth-btn"><i class="fas fa-user-tie"></i> <span>كن مالك قاعة</span></a></li>
                        <li><a href="{{ route('login') }}" class="nav-auth-btn login-btn"><i class="fas fa-sign-in-alt"></i> <span>دخول</span></a></li>
                        <li><a href="{{ route('register') }}" class="nav-signup-btn"><i class="fas fa-user-plus"></i> <span>تسجيل</span></a></li>
                    @endguest
                    @auth
                        <li class="nav-user-dropdown">
                            <button class="nav-user-btn" onclick="toggleUserDropdown()">
                                <i class="fas fa-user"></i> {{ auth()->user()->name }} <i class="fas fa-chevron-down"></i>
                            </button>
                            <div class="user-dropdown-menu" id="userDropdown">
                                @if(auth()->user()->role === 'customer')
                                    <a href="{{ route('customer.bookings') }}"><i class="fas fa-calendar-check"></i> حجوزاتي</a>
                                @elseif(auth()->user()->role === 'owner')
                                    <a href="{{ route('owner.halls') }}"><i class="fas fa-home"></i> قاعاتي</a>
                                    <a href="{{ route('owner.bookings') }}"><i class="fas fa-list"></i> الحجوزات</a>
                                @endif
                                <a href="{{ route('profile.edit') }}"><i class="fas fa-cog"></i> الحساب</a>
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i> خروج</a>
                            </div>
                        </li>
                    @endauth
                    @if(!request()->routeIs('search') && (!auth()->check() || auth()->user()->role === 'customer'))
                    <li>
                        <a href="{{ route('search') }}" class="nav-signup-btn book-now-btn"><i class="fas fa-calendar-plus"></i> <span>احجز الآن</span></a>
                    </li>
                    @endif
                </ul>
                <div class="hamburger" id="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </nav>

        @auth
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        @endauth

        <main style="margin-top: 0;">@yield('content')</main>

        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script type="module" src="{{ asset('front_halls_booking/bootstrap.js') }}?v={{ filemtime(public_path('front_halls_booking/bootstrap.js')) }}"></script>
        <script src="{{ asset('front_halls_booking/api.js') }}?v={{ filemtime(public_path('front_halls_booking/api.js')) }}"></script>
        <script src="{{ asset('front_halls_booking/script.js') }}?v={{ filemtime(public_path('front_halls_booking/script.js')) }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const dropdown = document.querySelector('.dropdown');
                if (!dropdown) return;

                const toggle = dropdown.querySelector('.dropdown-toggle');
                const menu = dropdown.querySelector('.dropdown-menu');
                const icon = toggle.querySelector('i');

                const closeMenu = () => {
                    dropdown.classList.remove('open');
                    toggle.setAttribute('aria-expanded', 'false');
                };

                const openMenu = () => {
                    dropdown.classList.add('open');
                    toggle.setAttribute('aria-expanded', 'true');
                };

                toggle.addEventListener('click', function (event) {
                    event.preventDefault();
                    event.stopPropagation();

                    if (!menu) return;

                    const isOpen = dropdown.classList.contains('open');
                    if (isOpen) {
                        closeMenu();
                    } else {
                        openMenu();
                    }
                });

                document.addEventListener('click', function () {
                    if (!menu) return;
                    closeMenu();
                });

                // prevent click inside menu from closing it
                if (menu) {
                    menu.addEventListener('click', function (event) {
                        event.stopPropagation();
                    });
                }
            });
        </script>
        <script>
            function toggleMenu() {
                const navLinks = document.querySelector('.nav-links');
                navLinks.classList.toggle('active');
            }

            function toggleUserDropdown() {
                const dropdown = document.getElementById('userDropdown');
                dropdown.classList.toggle('show');
            }

            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                const dropdown = document.getElementById('userDropdown');
                const button = document.querySelector('.nav-user-btn');
                if (dropdown && button && !button.contains(event.target) && !dropdown.contains(event.target)) {
                    dropdown.classList.remove('show');
                }
            });
        </script>
        <div class="loader" id="loader">
            <div class="spinner"></div>
        </div>
        @stack('scripts')
    </body>
</html>
