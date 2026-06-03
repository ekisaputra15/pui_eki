<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>E-MENUGO</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Source+Code+Pro:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script src="https://unpkg.com/html5-qrcode"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style type="text/tailwindcss">
        @theme {
            --color-brand:          #18E299;
            --color-brand-light:    #d4fae8;
            --color-brand-deep:     #0fa76e;
            --color-brand-darker:   #0d8a5a;
            --color-ink:            #0d0d0d;
            --color-ink-secondary:  #333333;
            --color-ink-muted:      #666666;
            --color-ink-faint:      #888888;
            --color-canvas:         #ffffff;
            --color-surface:        #fafafa;
            --color-surface-hover:  #f5f5f5;
            --color-border:         rgba(0,0,0,0.08);
            --color-border-subtle:  rgba(0,0,0,0.05);
            --font-sans: "Inter", "system-ui", "-apple-system", sans-serif;
            --font-mono: "Source Code Pro", "ui-monospace", monospace;
            --shadow-card:   rgba(0,0,0,0.03) 0px 2px 4px;
            --shadow-button: rgba(0,0,0,0.06) 0px 1px 2px;
            --shadow-modal:  rgba(0,0,0,0.12) 0px 8px 32px, rgba(0,0,0,0.06) 0px 2px 8px;
        }

        body {
            @apply font-sans bg-canvas text-ink antialiased;
            font-feature-settings: "cv02", "cv03", "cv04", "cv11";
        }
        .t-display { font-size: 4rem; font-weight: 600; line-height: 1.1; letter-spacing: -1.28px; color: var(--color-ink); }
        .t-heading { font-size: 2.5rem; font-weight: 600; line-height: 1.1; letter-spacing: -0.8px; color: var(--color-ink); }
        .t-subheading { font-size: 1.5rem; font-weight: 500; line-height: 1.3; letter-spacing: -0.24px; color: var(--color-ink); }
        .t-card-title { font-size: 1.25rem; font-weight: 600; line-height: 1.3; letter-spacing: -0.2px; color: var(--color-ink); }
        .t-body-lg { font-size: 1.125rem; font-weight: 400; line-height: 1.6; color: var(--color-ink-muted); }
        .t-body { font-size: 1rem; font-weight: 400; line-height: 1.6; color: var(--color-ink-secondary); }
        .t-mono { font-family: var(--font-mono); font-size: 0.75rem; font-weight: 500; line-height: 1.5; letter-spacing: 0.6px; text-transform: uppercase; }

        .btn { @apply inline-flex items-center justify-center font-medium transition-all duration-150 cursor-pointer select-none; font-size: 0.9375rem; }
        .btn-brand { background: var(--color-brand); color: var(--color-ink); padding: 8px 24px; border-radius: 9999px; font-weight: 600; box-shadow: var(--shadow-button); border: 1px solid transparent; }
        .btn-brand:hover { opacity: 0.88; transform: translateY(-1px); }
        .btn-primary { background: var(--color-ink); color: var(--color-canvas); padding: 8px 24px; border-radius: 9999px; font-weight: 500; box-shadow: var(--shadow-button); }
        .btn-primary:hover { opacity: 0.88; transform: translateY(-1px); }
        .btn-ghost { background: var(--color-canvas); color: var(--color-ink); padding: 6px 16px; border-radius: 9999px; font-weight: 500; border: 1px solid var(--color-border); box-shadow: var(--shadow-button); }
        .btn-ghost:hover { background: var(--color-surface-hover); }

        .card { background: var(--color-canvas); border: 1px solid var(--color-border-subtle); border-radius: 16px; padding: 24px; box-shadow: var(--shadow-card); }
        .badge { display: inline-flex; align-items: center; padding: 3px 10px; border-radius: 9999px; font-family: var(--font-mono); font-size: 0.6875rem; font-weight: 600; letter-spacing: 0.6px; text-transform: uppercase; }
        .badge-green  { background: var(--color-brand-light); color: var(--color-brand-deep); }
        .section-label { font-family: var(--font-mono); font-size: 0.75rem; font-weight: 500; letter-spacing: 0.9px; text-transform: uppercase; color: var(--color-brand-deep); display: inline-block; margin-bottom: 8px; }
    </style>
</head>
<body class="bg-canvas">

    @php
        $isCustomerRoute = str_starts_with(request()->route()->getName(), 'customer.');
        $cartItems = session('cart', []);
        $cartCount = 0;
        foreach ($cartItems as $it) { $cartCount += $it['quantity']; }
    @endphp

    @if(!request()->is('/'))
        <!-- Mobile Native Header Parity -->
        <header class="sticky top-0 z-50 flex justify-between items-center px-5 py-3 bg-canvas border-b border-[rgba(0,0,0,0.06)] backdrop-blur-md">
            <a href="/" class="font-sans font-semibold text-xl tracking-tight" style="letter-spacing: -0.4px; text-decoration: none;">
                <span style="color: var(--color-brand-deep);">E-</span><span style="color: var(--color-ink);">MENUGO</span>
            </a>
            @if(session('table_id'))
                <span class="badge badge-green">Meja {{ session('table_id') }}</span>
            @endif
        </header>
    @endif

    <div class="min-h-screen">
        @yield('content')
    </div>

    @if($isCustomerRoute && !request()->routeIs('customer.scan'))
        <div class="h-24"></div>
        <!-- Native Bottom Navigation Parity -->
        <nav class="fixed bottom-0 left-0 right-0 z-50 bg-canvas/90 backdrop-blur-lg border-t border-[rgba(0,0,0,0.07)]">
            <div class="flex justify-around items-center px-2 py-2">
                <a href="{{ route('customer.menu') }}" class="flex flex-col items-center gap-1 px-4 py-2 rounded-xl transition-colors hover:bg-[rgba(24,226,153,0.1)] group {{ request()->routeIs('customer.menu') ? 'bg-[rgba(24,226,153,0.1)]' : '' }}">
                    <svg class="w-5 h-5 transition-colors {{ request()->routeIs('customer.menu') ? 'text-brand-deep' : 'text-ink-faint group-hover:text-brand-deep' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                    <span class="t-mono text-[9px] transition-colors {{ request()->routeIs('customer.menu') ? 'text-brand-deep font-bold' : 'text-ink-faint group-hover:text-brand-deep' }}">Menu</span>
                </a>
                <a href="{{ route('customer.cart') }}" class="flex flex-col items-center gap-1 px-4 py-2 rounded-xl transition-colors hover:bg-[rgba(24,226,153,0.1)] relative group {{ request()->routeIs('customer.cart') ? 'bg-[rgba(24,226,153,0.1)]' : '' }}">
                    <svg class="w-5 h-5 transition-colors {{ request()->routeIs('customer.cart') ? 'text-brand-deep' : 'text-ink-faint group-hover:text-brand-deep' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span class="t-mono text-[9px] transition-colors {{ request()->routeIs('customer.cart') ? 'text-brand-deep font-bold' : 'text-ink-faint group-hover:text-brand-deep' }}">Pesanan</span>
                    @if($cartCount > 0)
                        <span class="absolute top-1 right-2 w-4 h-4 rounded-full text-[9px] font-bold flex items-center justify-center text-ink" style="background: var(--color-brand);">{{ $cartCount }}</span>
                    @endif
                </a>
                <a href="{{ route('customer.status') }}" class="flex flex-col items-center gap-1 px-4 py-2 rounded-xl transition-colors hover:bg-[rgba(24,226,153,0.1)] group {{ request()->routeIs('customer.status') ? 'bg-[rgba(24,226,153,0.1)]' : '' }}">
                    <svg class="w-5 h-5 transition-colors {{ request()->routeIs('customer.status') ? 'text-brand-deep' : 'text-ink-faint group-hover:text-brand-deep' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    <span class="t-mono text-[9px] transition-colors {{ request()->routeIs('customer.status') ? 'text-brand-deep font-bold' : 'text-ink-faint group-hover:text-brand-deep' }}">Status</span>
                </a>
            </div>
        </nav>
    @endif

    <!-- Notification Toast Container -->
    <div id="toast-container" class="fixed bottom-24 right-6 z-[9999] flex flex-col gap-3 w-full max-w-[360px] pointer-events-none *:pointer-events-auto"></div>
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

        const userRole = "customer";
        const latestOrderId = "{{ session('latest_order_tracking_id') }}";
        const currentStatus = "{{ $currentStatus ?? '' }}";

        if (config.apiKey && config.databaseURL) {
            initFirebaseNotifications(config, userRole, latestOrderId, currentStatus);
        }
    </script>
</body>
</html>
