<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard — E-MENUGO</title>
    <!-- Google Fonts: Inter + Geist Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Source+Code+Pro:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS v4 Browser CDN -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    
    <!-- QR Scanner Library -->
    <script src="https://unpkg.com/html5-qrcode"></script>
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- Design System: Mintlify-inspired, Green Dominant -->
    <style type="text/tailwindcss">
        @theme {
            /* Brand Palette */
            --color-brand:          #18E299;
            --color-brand-light:    #d4fae8;
            --color-brand-deep:     #0fa76e;
            --color-brand-darker:   #0d8a5a;
            
            /* Neutral Scale */
            --color-ink:            #0d0d0d;
            --color-ink-secondary:  #333333;
            --color-ink-muted:      #666666;
            --color-ink-faint:      #888888;
            
            --color-canvas:         #ffffff;
            --color-surface:        #fafafa;
            --color-surface-hover:  #f5f5f5;
            
            --color-border:         rgba(0,0,0,0.08);
            --color-border-subtle:  rgba(0,0,0,0.05);
            
            /* Typography */
            --font-sans: "Inter", "system-ui", "-apple-system", sans-serif;
            --font-mono: "Source Code Pro", "ui-monospace", monospace;

            /* Shadows */
            --shadow-card:   rgba(0,0,0,0.03) 0px 2px 4px;
            --shadow-button: rgba(0,0,0,0.06) 0px 1px 2px;
            --shadow-modal:  rgba(0,0,0,0.12) 0px 8px 32px, rgba(0,0,0,0.06) 0px 2px 8px;
        }

        /* ─── Base ──────────────────────────────────── */
        body {
            @apply font-sans bg-canvas text-ink antialiased;
            font-feature-settings: "cv02", "cv03", "cv04", "cv11";
        }

        /* ─── Typography ────────────────────────────── */
        .t-display {
            font-size: 4rem; font-weight: 600; line-height: 1.1;
            letter-spacing: -1.28px; color: var(--color-ink);
        }
        .t-heading {
            font-size: 2.5rem; font-weight: 600; line-height: 1.1;
            letter-spacing: -0.8px; color: var(--color-ink);
        }
        .t-subheading {
            font-size: 1.5rem; font-weight: 500; line-height: 1.3;
            letter-spacing: -0.24px; color: var(--color-ink);
        }
        .t-card-title {
            font-size: 1.25rem; font-weight: 600; line-height: 1.3;
            letter-spacing: -0.2px; color: var(--color-ink);
        }
        .t-body-lg {
            font-size: 1.125rem; font-weight: 400; line-height: 1.6;
            color: var(--color-ink-muted);
        }
        .t-body {
            font-size: 1rem; font-weight: 400; line-height: 1.6;
            color: var(--color-ink-secondary);
        }
        .t-label {
            font-size: 0.8125rem; font-weight: 500; line-height: 1.5;
            letter-spacing: 0.65px; text-transform: uppercase;
            color: var(--color-ink-faint);
        }
        .t-mono {
            font-family: var(--font-mono);
            font-size: 0.75rem; font-weight: 500; line-height: 1.5;
            letter-spacing: 0.6px; text-transform: uppercase;
        }

        /* ─── Buttons ───────────────────────────────── */
        .btn {
            @apply inline-flex items-center justify-center font-medium transition-all duration-150 cursor-pointer select-none;
            font-size: 0.9375rem;
        }
        .btn-brand {
            background: var(--color-brand);
            color: var(--color-ink);
            padding: 8px 24px;
            border-radius: 9999px;
            font-weight: 600;
            box-shadow: var(--shadow-button);
            border: 1px solid transparent;
        }
        .btn-brand:hover { opacity: 0.88; transform: translateY(-1px); }

        .btn-primary {
            background: var(--color-ink);
            color: var(--color-canvas);
            padding: 8px 24px;
            border-radius: 9999px;
            font-weight: 500;
            box-shadow: var(--shadow-button);
            border: 1px solid transparent;
        }
        .btn-primary:hover { opacity: 0.88; transform: translateY(-1px); }

        .btn-ghost {
            background: var(--color-canvas);
            color: var(--color-ink);
            padding: 6px 16px;
            border-radius: 9999px;
            font-weight: 500;
            border: 1px solid var(--color-border);
            box-shadow: var(--shadow-button);
        }
        .btn-ghost:hover { background: var(--color-surface-hover); }

        .btn-outline-brand {
            background: var(--color-brand-light);
            color: var(--color-brand-deep);
            padding: 6px 16px;
            border-radius: 9999px;
            font-weight: 500;
            border: 1px solid rgba(24,226,153,0.25);
        }
        .btn-outline-brand:hover { background: rgba(24,226,153,0.2); }

        /* ─── Cards ─────────────────────────────────── */
        .card {
            background: var(--color-canvas);
            border: 1px solid var(--color-border-subtle);
            border-radius: 16px;
            padding: 24px;
            box-shadow: var(--shadow-card);
        }
        .card-featured {
            background: var(--color-canvas);
            border: 1px solid var(--color-border-subtle);
            border-radius: 24px;
            padding: 32px;
            box-shadow: var(--shadow-card);
        }
        .card-green {
            background: linear-gradient(135deg, #18E299 0%, #0fa76e 100%);
            border-radius: 16px;
            padding: 24px;
            color: var(--color-ink);
        }

        /* ─── Inputs ────────────────────────────────── */
        .input-base {
            background: var(--color-canvas);
            color: var(--color-ink);
            border: 1px solid var(--color-border);
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.9375rem;
            font-family: var(--font-sans);
            transition: border-color 0.15s, outline 0.15s;
            width: 100%;
            outline: none;
        }
        .input-base::placeholder { color: var(--color-ink-faint); }
        .input-base:focus {
            border-color: var(--color-brand);
            outline: 2px solid rgba(24,226,153,0.25);
            outline-offset: 0px;
        }
        .input-pill {
            border-radius: 9999px;
            padding: 10px 20px;
        }

        /* ─── Badges & Pills ────────────────────────── */
        .badge {
            display: inline-flex; align-items: center;
            padding: 3px 10px;
            border-radius: 9999px;
            font-family: var(--font-mono);
            font-size: 0.6875rem; font-weight: 600;
            letter-spacing: 0.6px; text-transform: uppercase;
        }
        .badge-green  { background: var(--color-brand-light); color: var(--color-brand-deep); }
        .badge-yellow { background: #fef9c3; color: #a16207; }
        .badge-red    { background: #fee2e2; color: #991b1b; }
        .badge-blue   { background: #dbeafe; color: #1d4ed8; }
        .badge-gray   { background: var(--color-surface-hover); color: var(--color-ink-muted); }

        /* ─── Dashboard Sidebar ─────────────────────── */
        .sidebar-nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 14px;
            border-radius: 10px;
            font-size: 0.9375rem; font-weight: 500;
            color: var(--color-ink-muted);
            transition: all 0.15s ease;
            text-decoration: none;
        }
        .sidebar-nav-item i {
            color: var(--color-ink-faint);
            transition: color 0.15s ease;
        }
        .sidebar-nav-item:hover {
            background: rgba(0,0,0,0.035);
            color: var(--color-ink);
        }
        .sidebar-nav-item:hover i {
            color: var(--color-ink-secondary);
        }
        .sidebar-nav-item.active {
            background: var(--color-brand-light);
            color: var(--color-brand-deep);
            font-weight: 600;
        }
        .sidebar-nav-item.active i {
            color: var(--color-brand-deep);
        }

        /* ─── Section Labels ────────────────────────── */
        .section-label {
            font-family: var(--font-mono);
            font-size: 0.75rem; font-weight: 500;
            letter-spacing: 0.9px; text-transform: uppercase;
            color: var(--color-brand-deeper, var(--color-brand-deep));
            display: inline-block; margin-bottom: 8px;
        }

        /* ─── Tables ────────────────────────────────── */
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th {
            padding: 12px 16px;
            font-family: var(--font-mono);
            font-size: 0.6875rem; font-weight: 600;
            letter-spacing: 0.6px; text-transform: uppercase;
            color: var(--color-ink-faint);
            background: var(--color-surface);
            border-bottom: 1px solid var(--color-border-subtle);
            text-align: left;
        }
        .data-table td {
            padding: 14px 16px;
            font-size: 0.9375rem;
            color: var(--color-ink-secondary);
            border-bottom: 1px solid var(--color-border-subtle);
        }
        .data-table tr:last-child td { border-bottom: none; }
        .data-table tbody tr:hover { background: var(--color-surface); }
    </style>
</head>
<body class="flex h-screen overflow-hidden">

    <div id="dashboard-overlay" class="hidden fixed inset-0 bg-black/30 z-30 md:hidden" onclick="closeDashboardSidebar()"></div>

    <!-- Sidebar -->
    <aside id="dashboard-sidebar"
           class="flex flex-col bg-surface border-r border-[rgba(0,0,0,0.06)] flex-shrink-0 h-screen overflow-y-auto"
           style="width: 240px; position: fixed; top: 0; left: 0; bottom: 0; z-index: 40; transform: translateX(-100%); transition: transform 0.25s cubic-bezier(.4,0,.2,1);">
        
        <!-- Brand -->
        <div class="flex items-center gap-3 px-5 pt-5 pb-4" style="border-bottom: 1px solid var(--color-border-subtle);">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0" 
                 style="background: linear-gradient(135deg, #18E299 0%, #0fa76e 100%); box-shadow: rgba(24,226,153,0.3) 0px 2px 8px;">
                <i class="fa-solid fa-utensils text-sm" style="color: #0d0d0d;"></i>
            </div>
            <div>
                <span class="font-semibold text-base" style="letter-spacing: -0.4px; color: var(--color-ink);">E-MENUGO</span>
                <p class="t-mono text-[9px]" style="color: var(--color-brand-deep); margin-top: 1px;">Dashboard</p>
            </div>
        </div>

        <!-- Nav -->
        <nav class="flex-1 px-3 py-4 flex flex-col gap-0.5 overflow-y-auto">
            @php
            $role = auth()->user()->role;
            $current_route = request()->route()->getName();
            
            function navItemHtml($href, $label, $isActive, $faIcon = 'fa-circle') {
                $cls = $isActive ? 'sidebar-nav-item active' : 'sidebar-nav-item';
                $icon = "<i class=\"fa-solid $faIcon w-4 text-center text-[13px]\"></i>";
                return "<a href=\"$href\" class=\"$cls\">$icon <span>$label</span></a>";
            }

            function navSectionHtml($label) {
                return "<p style=\"font-family: var(--font-mono); font-size: 0.6rem; font-weight: 600; letter-spacing: 0.8px; text-transform: uppercase; color: var(--color-ink-faint); padding: 14px 12px 6px;\">$label</p>";
            }
            @endphp
            
            @if ($role === 'kasir')
                {!! navSectionHtml('Kasir') !!}
                {!! navItemHtml(route('cashier.index'), 'Monitor Pesanan', str_starts_with($current_route, 'cashier.index'), 'fa-receipt') !!}
                {!! navItemHtml(route('cashier.tables'), 'Manajemen Meja', str_starts_with($current_route, 'cashier.tables'), 'fa-table-cells-large') !!}
            @elseif ($role === 'dapur')
                {!! navSectionHtml('Dapur') !!}
                {!! navItemHtml(route('kitchen.index'), 'Pesanan Masuk', str_starts_with($current_route, 'kitchen.index'), 'fa-fire-burner') !!}
            @elseif ($role === 'waitress')
                {!! navSectionHtml('Waitress') !!}
                {!! navItemHtml(route('waitress.index'), 'Clear Up Meja', str_starts_with($current_route, 'waitress.index'), 'fa-broom') !!}
                {!! navItemHtml(route('waitress.history'), 'Riwayat Scan', str_starts_with($current_route, 'waitress.history'), 'fa-clock-rotate-left') !!}
            @elseif ($role === 'pengelola')
                {!! navSectionHtml('Pengelola') !!}
                {!! navItemHtml(route('admin.index'), 'Semua Pesanan', str_replace('admin.index', 'active', $current_route) == 'active', 'fa-list-check') !!}
                {!! navItemHtml(route('admin.products.index'), 'Produk & Menu', str_starts_with($current_route, 'admin.products'), 'fa-utensils') !!}
                {!! navItemHtml(route('admin.tables.index'), 'Status Meja', str_starts_with($current_route, 'admin.tables'), 'fa-table-cells') !!}
                {!! navSectionHtml('Pengguna') !!}
                {!! navItemHtml(route('admin.users.index'), 'Manage User', str_starts_with($current_route, 'admin.users'), 'fa-users-gear') !!}
            @endif
        </nav>

        <!-- User Footer -->
        <div class="px-3 pb-4 pt-3 border-t border-[rgba(0,0,0,0.05)]">
            <div class="flex items-center gap-3 px-3 py-2.5 mb-1 rounded-xl" style="background: var(--color-surface); border: 1px solid var(--color-border-subtle);">
                <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm text-ink flex-shrink-0"
                     style="background: linear-gradient(135deg, var(--color-brand) 0%, var(--color-brand-deep) 100%);">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold truncate" style="color: var(--color-ink); letter-spacing: -0.1px;">{{ auth()->user()->name }}</p>
                    <p class="t-mono text-[9px] truncate" style="color: var(--color-brand-deep);">{{ strtoupper(auth()->user()->role) }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="w-full mt-1">
                @csrf
                <button type="submit" class="sidebar-nav-item w-full" style="color: #dc2626; border: none; background: transparent; cursor: pointer; text-align: left;">
                    <i class="fa-solid fa-right-from-bracket w-4 text-center text-[13px]"></i>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content wrapper: takes remaining width, scrolls independently -->
    <main class="flex-1 min-w-0 flex flex-col overflow-hidden lg:ml-[240px]" style="background: #f7f8fa;">
        <!-- Top Dashboard Header -->
        <div class="flex-shrink-0 flex items-center justify-between px-6 py-3"
             style="background: rgba(255,255,255,0.92); border-bottom: 1px solid var(--color-border-subtle); backdrop-filter: blur(12px); position: sticky; top: 0; z-index: 10;">
            <div class="flex items-center gap-3">
                <!-- Hamburger -->
                <button type="button" onclick="toggleDashboardSidebar()" id="sidebar-toggle-btn"
                        class="w-8 h-8 flex items-center justify-center rounded-lg transition-colors hover:bg-surface-hover lg:hidden"
                        style="color: var(--color-ink-muted); cursor: pointer;">
                    <i class="fa-solid fa-bars text-sm"></i>
                </button>
                <div>
                    <p class="font-semibold text-sm" style="color: var(--color-ink); letter-spacing: -0.1px;">
                        Dashboard {{ ucfirst(auth()->user()->role) }}
                    </p>
                    <p class="t-mono text-[10px]" style="color: var(--color-ink-faint);">E-MENUGO System</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-full" style="background: var(--color-brand-light); border: 1px solid rgba(24,226,153,0.2);">
                    <div class="w-1.5 h-1.5 rounded-full animate-pulse" style="background: var(--color-brand);"></div>
                    <span class="t-mono text-[10px]" style="color: var(--color-brand-deep);">Online</span>
                </div>
                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs text-ink flex-shrink-0"
                     style="background: linear-gradient(135deg, var(--color-brand) 0%, var(--color-brand-deep) 100%);">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            </div>
        </div>
        <!-- Scrollable page content -->
        <div class="flex-1 overflow-y-auto" id="main-scroll-area">
            <div class="max-w-[1200px] mx-auto p-6 lg:p-10">
                @yield('content')
            </div>
            
            <!-- Footer bar -->
            <div class="py-5 px-6 mt-10 text-center border-t border-[rgba(0,0,0,0.05)]">
                <p class="t-mono text-[10px]" style="color: var(--color-ink-faint);">E-MENUGO — Digital Restaurant Ordering System</p>
            </div>
        </div>
    </main>

    <!-- Notification UI Components -->
    <div id="toast-container" class="fixed bottom-6 right-6 z-[9999] flex flex-col gap-3 w-full max-w-[360px] pointer-events-none *:pointer-events-auto"></div>
    <audio id="notification-sound" preload="auto" class="hidden">
        <source src="https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3" type="audio/mpeg">
    </audio>

    <script type="module">
        import { initFirebaseNotifications } from "{{ asset('assets/js/firebase_notifications.js') }}";
        
        const config = {
            apiKey: "{{ config('services.firebase.api_key') }}",
            authDomain: "{{ config('services.firebase.auth_domain') }}",
            databaseURL: "{{ config('services.firebase.database_url') }}",
            projectId: "{{ config('services.firebase.project_id') }}",
            storageBucket: "{{ config('services.firebase.storage_bucket') }}",
            messagingSenderId: "{{ config('services.firebase.messaging_sender_id') }}",
            appId: "{{ config('services.firebase.app_id') }}"
        };

        const userRole = "{{ auth()->user()->role ?? 'customer' }}";
        const latestOrderId = "{{ session('latest_order_tracking_id') }}";
        const currentStatus = "{{ $currentStatus ?? '' }}";

        if (config.apiKey && config.databaseURL) {
            initFirebaseNotifications(config, userRole, latestOrderId, currentStatus);
        }
    </script>

<script>
(function() {
    const sidebar = document.getElementById('dashboard-sidebar');
    const overlay = document.getElementById('dashboard-overlay');
    
    let sidebarOpen = false;

    function openDashboardSidebar() {
        sidebar.style.transform = 'translateX(0)';
        overlay.classList.remove('hidden');
        sidebarOpen = true;
    }
    function closeDashboardSidebar() {
        sidebar.style.transform = 'translateX(-100%)';
        overlay.classList.add('hidden');
        sidebarOpen = false;
    }
    
    window.toggleDashboardSidebar = function() {
        sidebarOpen ? closeDashboardSidebar() : openDashboardSidebar();
    };
    
    window.closeDashboardSidebar = closeDashboardSidebar;

    function applyLayout() {
        if (window.innerWidth >= 1024) {
            sidebar.style.transform = 'translateX(0)';
            overlay.classList.add('hidden');
            sidebarOpen = true;
        } else {
            if (!sidebarOpen) {
                sidebar.style.transform = 'translateX(-100%)';
                overlay.classList.add('hidden');
            }
        }
    }

    applyLayout();
    window.addEventListener('resize', applyLayout);
})();
</script>

</body>
</html>
