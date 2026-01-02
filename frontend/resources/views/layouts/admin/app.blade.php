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
            --admin-primary: #6366f1;
            --admin-secondary: #8b5cf6;
            --admin-success: #10b981;
            --admin-warning: #f59e0b;
            --admin-danger: #ef4444;
            --admin-info: #3b82f6;
            
            --admin-bg: #f8fafc;
            --admin-card-bg: #ffffff;
            --admin-dark: #1e293b;
            --admin-text: #334155;
            --admin-text-light: #64748b;
            --admin-border: #e2e8f0;
            --admin-shadow: rgba(0, 0, 0, 0.05);
        }
        
        [data-theme="dark"] {
            --admin-bg: #0f172a;
            --admin-card-bg: #1e293b;
            --admin-dark: #f1f5f9;
            --admin-text: #e2e8f0;
            --admin-text-light: #94a3b8;
            --admin-border: #334155;
            --admin-shadow: rgba(0, 0, 0, 0.3);
        }
        *, *::before, *::after {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
            background: var(--admin-bg);
            color: var(--admin-text);
            display: flex;
            flex-direction: column;
            transition: background 0.3s ease, color 0.3s ease;
        }
        .admin-shell {
            flex: 1;
            display: flex;
            flex-direction: row;
            min-height: 100vh;
        }
        .admin-sidebar {
            width: 260px;
            min-width: 260px;
            background: var(--admin-card-bg);
            border-right: 1px solid var(--admin-border);
            color: var(--admin-text);
            display: flex;
            flex-direction: column;
            align-items: stretch;
            padding: 24px 0;
            position: relative;
            z-index: 100;
            transition: all 0.3s ease;
            box-shadow: 0 0 20px var(--admin-shadow);
        }
        .admin-sidebar__brand {
            font-weight: 700;
            font-size: 1.375rem;
            letter-spacing: -0.5px;
            padding: 0 24px 20px 24px;
            margin-bottom: 16px;
            color: var(--admin-text);
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid var(--admin-border);
        }
        .admin-sidebar__brand span {
            font-size: 0.75rem;
            font-weight: 500;
            padding: 4px 10px;
            background: linear-gradient(135deg, var(--admin-primary), var(--admin-secondary));
            color: white;
            border-radius: 6px;
            opacity: 0.9;
        }
        .admin-sidebar__menu {
            display: flex;
            flex-direction: column;
            gap: 4px;
            padding: 0 12px;
            flex: 1;
            overflow-y: auto;
        }
        .admin-sidebar__section {
            margin-bottom: 20px;
        }
        .admin-sidebar__section-title {
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--admin-text-light);
            margin-bottom: 8px;
            padding: 8px 12px 4px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .admin-sidebar__submenu {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .admin-sidebar__link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--admin-text);
            text-decoration: none;
            transition: all 0.2s ease;
            position: relative;
        }
        .admin-sidebar__link i {
            width: 20px;
            text-align: center;
            color: var(--admin-text-light);
            transition: color 0.2s ease;
        }
        .admin-sidebar__link:hover {
            background: var(--admin-bg);
            color: var(--admin-primary);
        }
        .admin-sidebar__link:hover i {
            color: var(--admin-primary);
        }
        .admin-sidebar__link.active {
            background: linear-gradient(135deg, var(--admin-primary), var(--admin-secondary));
            color: white;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(99, 102, 241, 0.3);
        }
        .admin-sidebar__link.active i {
            color: white;
        }
        .admin-sidebar__bottom {
            margin-top: auto;
            padding: 16px 12px 0;
            border-top: 1px solid var(--admin-border);
        }
        .admin-sidebar__logout {
            width: 100%;
            margin-top: 0;
            justify-content: center;
            gap: 8px;
        }
        .admin-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 32px 32px 32px 32px;
            min-width: 0;
        }
        .admin-topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
            padding: 12px 18px;
            border-radius: 16px;
            background: var(--admin-card-bg);
            border: 1px solid var(--admin-border);
            box-shadow: 0 1px 3px var(--admin-shadow);
            transition: all 0.3s ease;
        }
        .admin-topbar__label {
            font-weight: 600;
            color: var(--admin-dark);
        }
        .admin-topbar select {
            max-width: 340px;
        }
        .admin-card {
            background: var(--admin-card-bg);
            border-radius: 16px;
            box-shadow: 0 1px 3px var(--admin-shadow);
            border: 1px solid var(--admin-border);
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .admin-card__header {
            padding: 20px 24px;
            background: linear-gradient(135deg, var(--admin-primary), var(--admin-secondary));
            color: #ffffff;
            border-bottom: 1px solid var(--admin-border);
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
            border: 1px solid var(--admin-border);
            transition: all 0.2s ease;
        }
        .admin-btn--outline:hover {
            background: var(--admin-primary);
            color: #fff;
            border-color: var(--admin-primary);
        }
        .admin-btn--solid {
            background: linear-gradient(135deg, var(--admin-primary), var(--admin-secondary));
            color: #fff;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }
        .admin-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 18px 30px rgba(75,107,251,0.25);
        }
        .sidebar-toggle {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            background: var(--admin-card-bg);
            border: 1px solid var(--admin-border);
            border-radius: 8px;
            width: 40px;
            height: 40px;
            box-shadow: 0 2px 8px var(--admin-shadow);
            color: var(--admin-text);
            font-size: 1.2rem;
            z-index: 999;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .sidebar-toggle:hover {
            background: var(--admin-primary);
            color: white;
            border-color: var(--admin-primary);
        }
        .admin-sidebar__school-selector {
            display: flex;
            flex-direction: column;
            gap: 6px;
            font-size: 0.75rem;
            color: var(--admin-text-light);
            margin-bottom: 4px;
        }
        .admin-sidebar__school-select {
            width: 100%;
            padding: 6px 8px;
            font-size: 0.85rem;
            border: 1px solid var(--admin-border);
            border-radius: 6px;
            background: var(--admin-bg);
            color: var(--admin-text);
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .admin-sidebar__school-select:hover {
            border-color: var(--admin-primary);
            background: var(--admin-card-bg);
        }
        .admin-sidebar__school-select:disabled {
            cursor: not-allowed;
            opacity: 0.7;
        }
        .admin-sidebar__school-text {
            padding: 4px 0;
            color: var(--admin-text);
            font-weight: 500;
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
                z-index: 1000;
            }
            .admin-sidebar.open {
                transform: translateX(0);
                box-shadow: 4px 0 20px var(--admin-shadow);
            }
            .sidebar-toggle {
                display: block;
            }
            .admin-main {
                padding: 20px 12px;
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
                @php
                    $adminRole = session('admin_role', 'admin');
                    $roleLabel = match($adminRole) {
                        'ultraadmin' => 'Ultra Admin',
                        'superadmin' => 'Super Admin',
                        'admin' => 'Admin',
                        default => 'Admin',
                    };
                @endphp
                <div class="admin-sidebar__brand" style="flex-direction: column; align-items: flex-start; gap: 0;">
                    <div style="display: flex; align-items: center; gap: 10px; width: 100%; margin-bottom: 12px;">
                        <i class="fas fa-graduation-cap" style="color: var(--admin-primary); font-size: 1.5rem;"></i>
                        <span style="font-size: 0.75rem; font-weight: 500; padding: 4px 10px; background: linear-gradient(135deg, var(--admin-primary), var(--admin-secondary)); color: white; border-radius: 6px; opacity: 0.9;">{{ $roleLabel }}</span>
                    </div>
                    @php
                        $canSwitchSchool = in_array($adminRole, ['superadmin', 'ultraadmin'], true);
                        $schoolCount = count($availableSchools ?? []);
                        $currentSchoolName = $currentSchoolName ?? ($currentSchool->name ?? '-');
                    @endphp
                    <div class="admin-sidebar__school-selector" style="width: 100%;">
                        <span>Sekolah</span>
                        @if($schoolCount > 1 && $canSwitchSchool)
                            <form method="POST" action="{{ route('admin.school.switch') }}" style="width: 100%;">
                                @csrf
                                <select name="school_id" class="admin-sidebar__school-select" onchange="this.form.submit()" aria-label="Switch school">
                                    @foreach($availableSchools as $school)
                                        <option value="{{ $school->id }}" {{ (int) session('admin_school_id') === (int) $school->id ? 'selected' : '' }}>
                                            {{ $school->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        @else
                            <div class="admin-sidebar__school-text">{{ $currentSchoolName }}</div>
                        @endif
                    </div>
                </div>
                <div class="admin-sidebar__menu">
                    <div class="admin-sidebar__section">
                        <a href="{{ route('admin.dashboard') }}" class="admin-sidebar__link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-home"></i> <span>Dashboard</span>
                        </a>
                    </div>
                    @if(in_array($adminRole, ['admin','superadmin','ultraadmin'], true))
                        <div class="admin-sidebar__section">
                            <div class="admin-sidebar__section-title">Project</div>
                            <div class="admin-sidebar__submenu">
                                <a href="{{ route('admin.kid-projects.scratch.index') }}" class="admin-sidebar__link {{ request()->routeIs('admin.kid-projects.scratch.*') ? 'active' : '' }}">
                                    <i class="fas fa-cube"></i> <span>Scratch</span>
                                </a>
                            </div>
                        </div>
                    @endif
                    @if(in_array($adminRole, ['admin','superadmin','ultraadmin'], true))
                        <div class="admin-sidebar__section">
                            <div class="admin-sidebar__section-title">Management</div>
                            <div class="admin-sidebar__submenu">
                                <a href="{{ route('admin.classrooms.index') }}" class="admin-sidebar__link {{ request()->routeIs('admin.classrooms.*') ? 'active' : '' }}">
                                    <i class="fas fa-chalkboard"></i> <span>Kelas</span>
                                </a>
                                    <a href="{{ route('admin.students.index') }}" class="admin-sidebar__link {{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
                                        <i class="fas fa-user-graduate"></i> <span>Siswa</span>
                                    </a>
                                    <a href="{{ route('admin.teachers.index') }}" class="admin-sidebar__link {{ request()->routeIs('admin.teachers.*') ? 'active' : '' }}">
                                        <i class="fas fa-user-tie"></i> <span>Guru</span>
                                    </a>
                                    <a href="{{ route('admin.schedules.index') }}" class="admin-sidebar__link {{ request()->routeIs('admin.schedules.*') ? 'active' : '' }}">
                                        <i class="fas fa-calendar-week"></i> <span>Jadwal</span>
                                    </a>
                            </div>
                        </div>
                    @endif

                    @if(in_array($adminRole, ['superadmin','ultraadmin'], true))
                        <div class="admin-sidebar__section">
                            <div class="admin-sidebar__section-title">Settings</div>
                            <div class="admin-sidebar__submenu">
                                <a href="{{ route('admin.admins.index') }}" class="admin-sidebar__link {{ request()->routeIs('admin.admins.*') ? 'active' : '' }}">
                                    <i class="fas fa-user-shield"></i> <span>Admin Users</span>
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="admin-sidebar__bottom">
                    <a href="{{ route('admin.logout') }}" class="admin-sidebar__link" style="width: 100%;">
                        <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
                    </a>
                </div>
                <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar"><i class="fas fa-bars"></i></button>
            </nav>
        @endif
        <main class="admin-main">
            @if($adminLoggedIn)
                @php
                    $role = session('admin_role', 'admin');
                    $canSwitchSchool = in_array($role, ['superadmin', 'ultraadmin'], true);
                    $currentSchool = $currentSchool ?? null;
                    $availableSchools = $availableSchools ?? collect();
                    $currentSchoolName = $currentSchool->name ?? '-';
                @endphp

                <div class="admin-topbar">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-school" style="color: var(--admin-primary);"></i>
                            <div>
                                <div class="text-muted small">Sekolah</div>
                                <strong style="color: var(--admin-text);">{{ $currentSchoolName }}</strong>
                            </div>
                        </div>

                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <button id="themeToggle" class="admin-btn admin-btn--outline" style="padding: 8px 12px;" title="Toggle Dark Mode">
                            <i class="fas fa-moon" id="themeIcon"></i>
                        </button>
                    </div>
                </div>
            @endif
            @yield('content')
        </main>
    </div>
    <script>
        // Theme Toggle
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggle = document.getElementById('themeToggle');
            const themeIcon = document.getElementById('themeIcon');
            const htmlElement = document.documentElement;
            
            // Load saved theme
            const savedTheme = localStorage.getItem('adminTheme') || 'light';
            htmlElement.setAttribute('data-theme', savedTheme);
            updateThemeIcon(savedTheme);
            
            if (themeToggle) {
                themeToggle.addEventListener('click', function() {
                    const currentTheme = htmlElement.getAttribute('data-theme');
                    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                    
                    htmlElement.setAttribute('data-theme', newTheme);
                    localStorage.setItem('adminTheme', newTheme);
                    updateThemeIcon(newTheme);
                });
            }
            
            function updateThemeIcon(theme) {
                if (themeIcon) {
                    themeIcon.className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
                }
            }
        });

        // Sidebar toggle for desktop and mobile
        document.addEventListener('DOMContentLoaded', function() {
            var sidebar = document.getElementById('adminSidebar');
            var toggle = document.getElementById('sidebarToggle');
            var main = document.querySelector('.admin-main');
            function toggleSidebar() {
                if (!sidebar) return;
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
                if (sidebar && sidebar.classList.contains('open') && !sidebar.contains(e.target) && !toggle.contains(e.target)) {
                    toggleSidebar();
                }
            });
        });
    </script>
    <script src="{{ asset('assets/js/jquery-3.5.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    @stack('scripts')
</body>
</html>
