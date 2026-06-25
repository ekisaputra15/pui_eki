@extends('layouts.admin')

@section('content')
<!-- Page Header -->
<div class="mb-8 flex flex-col sm:flex-row sm:items-start justify-between gap-4">
    <div>
        <span class="section-label"><i class="fa-solid fa-clock-rotate-left mr-1.5"></i>Riwayat</span>
        <h1 class="font-semibold mt-1" style="font-size: 1.75rem; letter-spacing: -0.5px; color: var(--color-ink);">Riwayat Clear Up</h1>
        <p style="color: var(--color-ink-muted); font-size: 0.9375rem; margin-top: 4px;">Log aktivitas pembersihan meja oleh waitress.</p>
    </div>
    <a href="{{ route('waitress.index') }}" class="btn btn-brand self-start sm:self-auto" style="font-size: 0.875rem; padding: 8px 16px;">
        <i class="fa-solid fa-qrcode mr-1.5 text-xs"></i>Scan Meja
    </a>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
    <div class="card" style="padding: 16px 20px;">
        <p class="t-mono text-[10px] mb-1" style="color: var(--color-ink-faint);">Total Log</p>
        <p class="font-bold text-2xl" style="letter-spacing: -0.5px; color: var(--color-ink);">{{ $totalLogs }}</p>
    </div>
    <div class="card" style="padding: 16px 20px; border-color: rgba(24,226,153,0.3);">
        <p class="t-mono text-[10px] mb-1" style="color: var(--color-brand-deep);">Hasil Filter</p>
        <p class="font-bold text-2xl" style="letter-spacing: -0.5px; color: var(--color-ink);">{{ $filteredLogs }}</p>
    </div>
</div>

<!-- Filter -->
<div class="card mb-6" style="padding: 16px 20px;">
    <form method="GET" action="{{ route('waitress.history') }}" class="flex flex-col sm:flex-row gap-3 items-end">
        <div class="flex-1">
            <label class="block mb-1.5" style="font-size: 0.8125rem; font-weight: 500; color: var(--color-ink);">
                <i class="fa-solid fa-filter mr-1.5 text-xs" style="color: var(--color-brand-deep);"></i>Filter ID Meja
            </label>
            <input type="text" name="table" value="{{ $tableFilter }}"
                   class="input-base" placeholder="Contoh: A1 atau VIP 1">
        </div>
        <div class="flex gap-2">
            <button type="submit" class="btn btn-brand" style="padding: 9px 18px; font-size: 0.875rem;">
                <i class="fa-solid fa-magnifying-glass mr-1.5 text-xs"></i>Filter
            </button>
            <a href="{{ route('waitress.history') }}" class="btn btn-ghost" style="padding: 9px 14px; font-size: 0.875rem;">Reset</a>
        </div>
    </form>
</div>

<!-- Table -->
<div class="card overflow-hidden" style="padding: 0; border-radius: 16px;">
    @if ($history->isEmpty())
    <div class="flex flex-col items-center justify-center py-16 text-center px-6">
        <i class="fa-solid fa-clock-rotate-left text-3xl mb-4" style="color: var(--color-ink-faint);"></i>
        <p class="font-semibold mb-1" style="color: var(--color-ink);">Belum Ada Riwayat</p>
        <p class="text-sm" style="color: var(--color-ink-muted);">
            {{ $tableFilter ? "Tidak ada log untuk meja \"$tableFilter\"." : 'Mulai scan meja untuk membuat log pertama.' }}
        </p>
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="data-table" style="min-width: 520px;">
            <thead>
                <tr>
                    <th><i class="fa-solid fa-table-cells mr-1.5"></i>Meja</th>
                    <th><i class="fa-solid fa-user mr-1.5"></i>Waitress</th>
                    <th style="text-align: right;"><i class="fa-solid fa-clock mr-1.5"></i>Waktu</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($history as $log)
                <tr>
                    <td>
                        <span class="flex items-center gap-2">
                            <span class="badge badge-green">Meja {{ $log->table_id }}</span>
                        </span>
                    </td>
                    <td>
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-bold text-ink flex-shrink-0"
                                 style="background: var(--color-brand);">
                                {{ strtoupper(substr($log->waitress_name, 0, 1)) }}
                            </div>
                            <span style="font-size: 0.9375rem;">{{ $log->waitress_name }}</span>
                        </div>
                    </td>
                    <td style="text-align: right;">
                        <span class="t-mono text-xs" style="color: var(--color-ink-faint);">{{ $log->created_at->format('Y-m-d H:i:s') }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

<div class="mt-6">
    {{ $history->appends(request()->query())->links() }}
</div>
@endsection
