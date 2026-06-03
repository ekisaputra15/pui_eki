<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — E-MENUGO</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Source+Code+Pro:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <style type="text/tailwindcss">
        @theme {
            --color-brand:         #18E299;
            --color-brand-light:   #d4fae8;
            --color-brand-deep:    #0fa76e;
            --color-ink:           #0d0d0d;
            --color-ink-secondary: #333333;
            --color-ink-muted:     #666666;
            --color-ink-faint:     #888888;
            --color-canvas:        #ffffff;
            --color-border:        rgba(0,0,0,0.08);
            --font-sans: "Inter", system-ui, sans-serif;
            --font-mono: "Source Code Pro", ui-monospace, monospace;
        }
        body { @apply font-sans bg-[#fafafa] antialiased; }
        .input-base {
            background: white;
            color: #0d0d0d;
            border: 1px solid rgba(0,0,0,0.10);
            border-radius: 10px;
            padding: 11px 14px;
            font-size: 0.9375rem;
            font-family: "Inter", system-ui, sans-serif;
            width: 100%;
            outline: none;
            transition: border-color 0.15s;
        }
        .input-base::placeholder { color: #888888; }
        .input-base:focus {
            border-color: #18E299;
            box-shadow: 0 0 0 3px rgba(24,226,153,0.15);
        }
    </style>
</head>
<body>
<div class="min-h-screen flex">
    <!-- Left Panel - Branding -->
    <div class="hidden md:flex flex-col justify-between w-2/5 p-10 relative overflow-hidden" style="background: #0d0d0d;">
        <!-- Atmospheric gradient -->
        <div class="absolute inset-0 pointer-events-none" style="
            background: radial-gradient(ellipse 80% 60% at 50% 80%, rgba(24,226,153,0.2) 0%, transparent 65%);
        "></div>
        
        <div class="relative z-10">
            <span class="font-semibold text-xl text-white" style="letter-spacing: -0.4px;">
                <span style="color: #18E299;">E-</span>MENUGO
            </span>
        </div>
        
        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full mb-6" style="background: rgba(24,226,153,0.12); border: 1px solid rgba(24,226,153,0.2);">
                <div class="w-2 h-2 rounded-full" style="background: #18E299;"></div>
                <span style="font-family: 'Source Code Pro', monospace; font-size: 0.6875rem; font-weight: 500; letter-spacing: 0.6px; text-transform: uppercase; color: #18E299;">Dashboard Staff</span>
            </div>
            <h2 class="text-white text-3xl font-semibold mb-3" style="letter-spacing: -0.6px; line-height: 1.2;">Selamat datang kembali</h2>
            <p style="color: rgba(255,255,255,0.5); font-size: 0.9375rem; line-height: 1.6;">Masuk untuk mengelola pesanan, meja, dan operasional restoran Anda.</p>
        </div>

        <div class="relative z-10">
            <p style="font-family: 'Source Code Pro', monospace; font-size: 0.6875rem; font-weight: 500; letter-spacing: 0.6px; text-transform: uppercase; color: rgba(255,255,255,0.25);">E-MENUGO System</p>
        </div>
    </div>

    <!-- Right Panel - Form -->
    <div class="flex-1 flex items-center justify-center p-6 md:p-12">
        <div class="w-full max-w-sm">
            <!-- Mobile logo -->
            <div class="mb-8 md:hidden text-center">
                <span class="font-semibold text-2xl" style="letter-spacing: -0.5px; color: #0d0d0d;">
                    <span style="color: #0fa76e;">E-</span>MENUGO
                </span>
            </div>

            <h1 class="text-2xl font-semibold mb-1" style="letter-spacing: -0.4px; color: #0d0d0d;">Masuk ke Dashboard</h1>
            <p class="mb-8" style="color: #666666; font-size: 0.9375rem;">Gunakan akun yang telah diberikan.</p>

            @if ($errors->any())
                <div class="flex items-center gap-3 px-4 py-3 rounded-xl mb-6" style="background: #fee2e2; border: 1px solid rgba(220,38,38,0.2);">
                    <svg class="w-4 h-4 flex-shrink-0" style="color: #dc2626;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span style="color: #991b1b; font-size: 0.875rem; font-weight: 500;">Username atau password salah.</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-4">
                @csrf
                <div>
                    <label class="block mb-1.5" style="font-size: 0.8125rem; font-weight: 500; color: #333333;">Username</label>
                    <input type="text" name="username" value="{{ old('username') }}" required class="input-base" placeholder="Contoh: kasir">
                </div>
                <div>
                    <label class="block mb-1.5" style="font-size: 0.8125rem; font-weight: 500; color: #333333;">Password</label>
                    <input type="password" name="password" required class="input-base" placeholder="••••••••">
                </div>
                <button type="submit" class="w-full font-semibold transition-all duration-150 mt-2" style="
                    background: #0d0d0d; color: white;
                    padding: 11px 24px;
                    border-radius: 9999px;
                    font-size: 0.9375rem;
                    font-family: 'Inter', sans-serif;
                    border: none;
                    cursor: pointer;
                    box-shadow: rgba(0,0,0,0.06) 0px 1px 2px;
                " onmouseover="this.style.opacity='0.88'" onmouseout="this.style.opacity='1'">
                    Sign In →
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-[rgba(0,0,0,0.06)]">
                <a href="/" style="font-size: 0.875rem; color: #888888; display: flex; align-items: center; gap: 5px; text-decoration: none; transition: color 0.15s;"
                   onmouseover="this.style.color='#0fa76e'" onmouseout="this.style.color='#888888'">
                    ← Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
