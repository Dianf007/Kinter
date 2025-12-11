<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Panel') | {{ config('app.name', 'Kinter') }}</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome.css') }}">
    <style>
        :root {
            --admin-primary: #4b6bfb;
            --admin-secondary: #7f63f4;
            --admin-bg: #f5f7fb;
            --admin-dark: #1c1f2e;
        }
        *, *::before, *::after {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
            background: radial-gradient(circle at top, rgba(75,107,251,0.15), transparent 45%),
                        radial-gradient(circle at bottom, rgba(127,99,244,0.2), transparent 50%),
                        var(--admin-bg);
            color: var(--admin-dark);
            display: flex;
            flex-direction: column;
        }
        .admin-shell {
            flex: 1;
            display: flex;
            flex-direction: row;
            min-height: 100vh;
        }
        .admin-sidebar {
            width: 240px;
            min-width: 200px;
            background: linear-gradient(135deg, var(--admin-primary) 60%, var(--admin-secondary) 100%);
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: stretch;
            padding: 32px 0 24px 0;
            position: relative;
            z-index: 100;
            transition: transform 0.3s cubic-bezier(.4,2,.6,1), box-shadow 0.2s;
            box-shadow: 0 0 32px 0 rgba(75,107,251,0.10);
        }
        .admin-sidebar__brand {
            font-weight: 700;
            font-size: 1.25rem;
            letter-spacing: 0.5px;
            padding: 0 32px 24px 32px;
            margin-bottom: 12px;
            color: #fff;
        }
        .admin-sidebar__menu {
            display: flex;
            flex-direction: column;
            gap: 12px;
            padding: 0 16px;
        }
        .admin-sidebar__section {
            margin-bottom: 18px;
        }
        .admin-sidebar__section-title {
            font-size: 0.95rem;
            font-weight: 600;
            color: rgba(255,255,255,0.7);
            margin-bottom: 6px;
            margin-left: 8px;
            letter-spacing: 0.5px;
        }
        .admin-sidebar__submenu {
            display: flex;
            flex-direction: column;
            gap: 4px;
            margin-left: 18px;
        }
        .admin-sidebar__link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 18px;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 500;
            color: #fff;
            text-decoration: none;
            opacity: 0.92;
            transition: background 0.2s, color 0.2s, opacity 0.2s;
        }
        .admin-sidebar__link.active, .admin-sidebar__link:hover {
            background: rgba(255,255,255,0.13);
            color: #fff;
            opacity: 1;
        }
        .admin-sidebar__bottom {
            margin-top: auto;
            padding: 0 16px;
        }
        .admin-sidebar__logout {
            width: 100%;
            margin-top: 18px;
        }
        .admin-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 32px 32px 32px 0;
            min-width: 0;
        }
        .admin-card {
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 25px 60px rgba(28,31,46,0.12);
            border: 1px solid rgba(75,107,251,0.08);
            overflow: hidden;
        }
        .admin-card__header {
            padding: 24px;
            background: linear-gradient(135deg, var(--admin-primary), var(--admin-secondary));
            color: #ffffff;
        }
        .admin-card__body {
            padding: 24px;
        }
        .admin-btn {
            border: none;
            border-radius: 999px;
            padding: 10px 18px;
            font-weight: 500;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .admin-btn--outline {
            background: transparent;
            color: var(--admin-primary);
            border: 1px solid rgba(75,107,251,0.4);
        }
        .admin-btn--solid {
            background: linear-gradient(135deg, var(--admin-primary), var(--admin-secondary));
            color: #fff;
            box-shadow: 0 12px 20px rgba(75,107,251,0.35);
        }
        .admin-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 18px 30px rgba(75,107,251,0.25);
        }
        .sidebar-toggle {
            display: none;
            position: absolute;
            top: 18px;
            right: -44px;
            background: #fff;
            border: none;
            border-radius: 50%;
            width: 38px;
            height: 38px;
            box-shadow: 0 2px 8px rgba(28,31,46,0.10);
            color: var(--admin-primary);
            font-size: 1.5rem;
            z-index: 200;
            cursor: pointer;
        }
        @media (max-width: 1024px) {
            .admin-shell {
                flex-direction: column;
            }
            .admin-sidebar {
                position: fixed;
                left: 0;
                top: 0;
                height: 100vh;
                transform: translateX(-100%);
                box-shadow: 0 0 0 rgba(0,0,0,0);
            }
            .admin-sidebar.open {
                transform: translateX(0);
                box-shadow: 0 0 32px 0 rgba(75,107,251,0.18);
            }
            .sidebar-toggle {
                display: block;
            }
            .admin-main {
                padding: 24px 8px 24px 8px;
            }
        }
        @media (max-width: 576px) {
            .admin-sidebar {
                width: 90vw;
                min-width: 0;
                padding: 18px 0 12px 0;
            }
            .admin-main {
                padding: 12px 2vw 12px 2vw;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="admin-shell">
        @php $adminLoggedIn = session('admin_id'); @endphp
        @if($adminLoggedIn)
            <nav class="admin-sidebar" id="adminSidebar">
                <div class="admin-sidebar__brand">{{ config('app.name', 'Kinter') }} Admin</div>
                <div class="admin-sidebar__menu">
                    <div class="admin-sidebar__section">
                        <a href="{{ route('admin.dashboard') }}" class="admin-sidebar__link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </div>
                    <div class="admin-sidebar__section">
                        <div class="admin-sidebar__section-title"><i class="fas fa-folder-open"></i> Project</div>
                        <div class="admin-sidebar__submenu">
                            <a href="{{ route('admin.kid-projects.index') }}" class="admin-sidebar__link {{ request()->routeIs('admin.kid-projects.*') ? 'active' : '' }}">
                                <i class="fas fa-cube"></i> Scratch
                            </a>
                        </div>
                    </div>
                </div>
                <div class="admin-sidebar__bottom">
                    @hasSection('admin-navbar')
                        @yield('admin-navbar')
                    @else
                        <a href="{{ route('admin.logout') }}" class="admin-btn admin-btn--outline admin-sidebar__logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    @endif
                </div>
                <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar"><i class="fas fa-bars"></i></button>
            </nav>
        @endif
        <main class="admin-main">
            @yield('content')
        </main>
    </div>
    <script>
        // Sidebar toggle for desktop and mobile
        document.addEventListener('DOMContentLoaded', function() {
            var sidebar = document.getElementById('adminSidebar');
            var toggle = document.getElementById('sidebarToggle');
            var main = document.querySelector('.admin-main');
            function toggleSidebar() {
                sidebar.classList.toggle('open');
                if (sidebar.classList.contains('open')) {
                    main.style.filter = 'blur(2px)';
                } else {
                    main.style.filter = '';
                }
            }
            if (sidebar && toggle) {
                toggle.addEventListener('click', toggleSidebar);
            }
            // Close sidebar when clicking outside (mobile)
            document.addEventListener('click', function(e) {
                if (sidebar.classList.contains('open') && !sidebar.contains(e.target) && !toggle.contains(e.target)) {
                    toggleSidebar();
                }
            });
        });
    </script>
    <script>
        // Sidebar toggle for mobile
        document.addEventListener('DOMContentLoaded', function() {
            var sidebar = document.getElementById('adminSidebar');
            var toggle = document.getElementById('sidebarToggle');
            if (sidebar && toggle) {
                toggle.addEventListener('click', function() {
                    sidebar.classList.toggle('open');
                });
            }
        });
    </script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    @stack('scripts')
</body>
</html>
