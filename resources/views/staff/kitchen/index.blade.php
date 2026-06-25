@extends('layouts.admin')

@section('content')

<!-- Page Header -->
<div class="mb-8 flex flex-col sm:flex-row sm:items-start justify-between gap-4">
    <div>
        <span class="section-label"><i class="fa-solid fa-fire-burner mr-1.5"></i>Dapur</span>
        <h1 class="font-semibold mt-1" style="font-size: 1.75rem; letter-spacing: -0.5px; color: var(--color-ink);">Monitor Pesanan</h1>
        <p style="color: var(--color-ink-muted); font-size: 0.9375rem; margin-top: 4px;">Kelola status pesanan dari diproses hingga siap disajikan.</p>
    </div>
    <button onclick="location.reload()" class="btn btn-ghost self-start sm:self-auto" style="font-size: 0.875rem; padding: 8px 14px;">
        <i class="fa-solid fa-rotate-right mr-1.5 text-xs"></i>Refresh
    </button>
</div>

<!-- Dashboard Content Container -->
<div id="dashboard-main-content">
    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="card" style="padding: 16px 20px;">
            <p class="t-mono text-[10px] mb-1" style="color: var(--color-ink-faint);">Total Antrian</p>
            <p class="font-bold text-2xl" style="letter-spacing: -0.5px; color: var(--color-ink);">{{ $orders->count() }}</p>
        </div>
        <div class="card" style="padding: 16px 20px; border-color: rgba(59,130,246,0.25);">
            <p class="t-mono text-[10px] mb-1" style="color: #2563eb;">Diproses</p>
            <p class="font-bold text-2xl" style="letter-spacing: -0.5px; color: var(--color-ink);">{{ $diprosesCount }}</p>
        </div>
        <div class="card" style="padding: 16px 20px; border-color: rgba(24,226,153,0.3);">
            <p class="t-mono text-[10px] mb-1" style="color: var(--color-brand-deep);">Sedang Dibuat</p>
            <p class="font-bold text-2xl" style="letter-spacing: -0.5px; color: var(--color-ink);">{{ $dibuatCount }}</p>
        </div>
    </div>

    <!-- Orders -->
    @if ($orders->isEmpty())
    <div class="card flex flex-col items-center justify-center py-16 text-center" style="border-style: dashed;">
        <i class="fa-solid fa-fire-burner text-3xl mb-4" style="color: var(--color-ink-faint);"></i>
        <p class="font-semibold mb-1" style="color: var(--color-ink);">Dapur Kosong</p>
        <p class="text-sm" style="color: var(--color-ink-muted);">Tidak ada pesanan masuk ke dapur saat ini.</p>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        @foreach ($orders as $order)
        @php $isCooking = $order->status === 'dibuat'; @endphp
        <div class="card flex flex-col" style="border-radius: 20px; padding: 0; overflow: hidden;
             border-left: 3px solid {{ $isCooking ? 'var(--color-brand)' : '#3b82f6' }};">

            <!-- Header -->
            <div class="flex justify-between items-center px-5 pt-4 pb-3" style="border-bottom: 1px solid var(--color-border-subtle);">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="t-mono text-[10px]" style="color: var(--color-ink-faint);">{{ $order->order_id }}</span>
                        <span class="t-mono text-[9px] px-1.5 py-0.5 rounded-md" style="background: rgba(24,226,153,0.1); color: var(--color-brand-deep);">
                            <i class="fa-regular fa-clock mr-1"></i>{{ $order->created_at->format('H:i') }}
                        </span>
                    </div>
                    <div class="flex items-center gap-2 mt-1.5">
                        <i class="fa-solid fa-table-cells text-xs" style="color: var(--color-ink-muted);"></i>
                        <span class="font-semibold" style="color: var(--color-ink);">Meja {{ $order->table_id }}</span>
                    </div>
                </div>
                @if ($isCooking)
                    <span class="badge badge-green"><i class="fa-solid fa-fire mr-1 text-[9px]"></i>Dibuat</span>
                @else
                    <span class="badge badge-blue"><i class="fa-solid fa-hourglass-half mr-1 text-[9px]"></i>Diproses</span>
                @endif
            </div>

            <!-- Items List -->
            <div class="flex-1 px-5 py-4">
                <p class="t-mono text-[10px] mb-3" style="color: var(--color-ink-faint);">Item Pesanan</p>
                <ul class="flex flex-col gap-2">
                    @foreach($order->items as $it)
                    <li class="flex items-center gap-2 text-sm" style="color: var(--color-ink-secondary);">
                        <span class="w-6 h-6 rounded-lg flex items-center justify-center text-xs font-bold flex-shrink-0 text-ink"
                              style="background: var(--color-brand-light);">{{ $it->quantity }}</span>
                        {{ $it->name }}
                    </li>
                    @endforeach
                </ul>

                @if (!empty($order->notes))
                <div class="mt-4 px-3 py-2.5 rounded-xl" style="background: #fef9c3; border: 1px solid #f59e0b30;">
                    <p class="t-mono text-[9px] mb-1" style="color: #92400e;">Catatan</p>
                    <p class="text-sm" style="color: #78350f;">{{ $order->notes }}</p>
                </div>
                @endif
            </div>

            <!-- Action -->
            <div class="px-5 pb-4">
                <form method="POST" action="{{ route('kitchen.action') }}">
                    @csrf
                    <input type="hidden" name="action" value="{{ $isCooking ? 'complete_order' : 'mark_cooking' }}">
                    <input type="hidden" name="order_id" value="{{ $order->order_id }}">
                    @if ($isCooking)
                    <button type="submit" class="btn btn-brand w-full">
                        <i class="fa-solid fa-circle-check mr-2"></i>Tandai Selesai
                    </button>
                    @else
                    <button type="submit" class="btn btn-primary w-full">
                        <i class="fa-solid fa-fire mr-2"></i>Mulai Membuat
                    </button>
                    @endif
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
