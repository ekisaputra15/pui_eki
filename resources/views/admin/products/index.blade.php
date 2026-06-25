@extends('layouts.admin')

@section('content')
@php
    $catCounts = $products->groupBy('category')->map->count();
    $totalProducts = $products->count();
@endphp

<!-- Page Header -->
<div class="mb-8 flex flex-col sm:flex-row sm:items-start justify-between gap-4">
    <div>
        <span class="section-label"><i class="fa-solid fa-utensils mr-1.5"></i>Pengelola</span>
        <h1 class="font-semibold mt-1" style="font-size: 1.75rem; letter-spacing: -0.5px; color: var(--color-ink);">Produk & Menu</h1>
        <p style="color: var(--color-ink-muted); font-size: 0.9375rem; margin-top: 4px;">Kelola daftar menu makanan dan minuman restoran Anda.</p>
    </div>
    <button type="button" onclick="openModal('create-product-modal')" class="btn btn-brand self-start sm:self-auto" style="font-size: 0.875rem; padding: 8px 16px;">
        <i class="fa-solid fa-plus mr-1.5 text-xs"></i>Tambah Produk
    </button>
</div>

<!-- Stats Card -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
    <div class="card" style="padding: 16px 20px;">
        <p class="t-mono text-[10px] mb-1" style="color: var(--color-ink-faint);">Total Produk</p>
        <p class="font-bold text-2xl" style="letter-spacing: -0.5px; color: var(--color-ink);">{{ $totalProducts }}</p>
    </div>
    <div class="card" style="padding: 16px 20px;">
        <p class="t-mono text-[10px] mb-1" style="color: var(--color-ink-faint);">Kategori</p>
        <p class="font-bold text-2xl" style="letter-spacing: -0.5px; color: var(--color-ink);">{{ $catCounts->count() }}</p>
    </div>
    <div class="card md:col-span-2" style="padding: 16px 20px; border-color: rgba(24,226,153,0.3);">
        <p class="t-mono text-[10px] mb-2" style="color: var(--color-brand-deep);">Kategori Aktif</p>
        <div class="flex flex-wrap gap-2">
            @foreach($catCounts as $cat => $count)
                <span class="badge badge-gray" style="font-size: 10px; padding: 2px 8px;">{{ $cat }} ({{ $count }})</span>
            @endforeach
        </div>
    </div>
</div>

@if(session('success'))
<div class="flex items-center gap-3 px-4 py-3 rounded-xl mb-6" style="background: var(--color-brand-light); border: 1px solid rgba(24,226,153,0.3);">
    <i class="fa-solid fa-circle-check" style="color: var(--color-brand-deep);"></i>
    <p class="text-sm font-medium" style="color: var(--color-brand-deep);">{{ session('success') }}</p>
</div>
@endif

<!-- Products List -->
<div class="card overflow-hidden" style="padding: 0; border-radius: 16px;">
    <div class="overflow-x-auto">
        <table class="data-table" style="min-width: 800px;">
            <thead>
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th style="width: 100px;">Produk</th>
                    <th>Nama & Kategori</th>
                    <th>Harga</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $p)
                <tr class="align-middle">
                    <td><span class="t-mono text-xs" style="color: var(--color-ink-faint);">{{ $p->id }}</span></td>
                    <td class="py-3">
                        <div class="w-16 h-16 rounded-xl overflow-hidden border border-[rgba(0,0,0,0.05)] bg-surface">
                            @if($p->image)
                                <img src="{{ $p->image }}" alt="produk" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-canvas">
                                    <i class="fa-solid fa-image text-ink-faint"></i>
                                </div>
                            @endif
                        </div>
                    </td>
                    <td>
                        <p class="font-semibold" style="color: var(--color-ink);">{{ $p->name }}</p>
                        <span class="badge badge-gray mt-1" style="font-size: 9px;">{{ $p->category }}</span>
                    </td>
                    <td>
                        <span class="font-bold" style="color: var(--color-brand-deep);">Rp{{ number_format($p->price, 0, ',', '.') }}</span>
                    </td>
                    <td style="text-align: right;">
                        <div class="flex justify-end gap-2 pr-4">
                            <button type="button" class="btn btn-ghost" style="padding: 6px 12px; font-size: 0.8125rem;"
                                onclick='openEditProductModal(@json($p))'>
                                <i class="fa-solid fa-pen mr-1 text-xs"></i>Edit
                            </button>
                            <form action="{{ route('admin.products.destroy', $p->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn" style="padding: 6px 12px; font-size: 0.8125rem; background: #fee2e2; color: #dc2626; border: 1px solid rgba(220,38,38,0.2); border-radius: 9999px;" onclick="return confirm('Hapus produk ini?')">
                                    <i class="fa-solid fa-trash mr-1 text-xs"></i>Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-12 text-center text-gray-500 italic">Belum ada produk yang ditambahkan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Create Modal -->
<div id="create-product-modal" class="hidden fixed inset-0 z-50 bg-black/40 items-center justify-center p-4 backdrop-blur-sm">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-modal p-6" style="border: 1px solid var(--color-border-subtle);">
        <div class="flex justify-between items-center mb-5">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: var(--color-brand-light);">
                    <i class="fa-solid fa-plus text-sm" style="color: var(--color-brand-deep);"></i>
                </div>
                <h3 class="font-semibold" style="color: var(--color-ink); letter-spacing: -0.2px;">Tambah Produk</h3>
            </div>
            <button type="button" onclick="closeModal('create-product-modal')" class="w-8 h-8 flex items-center justify-center rounded-lg" style="color: var(--color-ink-muted); background: var(--color-surface);">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="flex flex-col gap-4">
            @csrf
            <div>
                <label class="block mb-1.5" style="font-size: 0.8125rem; font-weight: 500; color: var(--color-ink);">Nama Produk</label>
                <input type="text" name="name" class="input-base" placeholder="Contoh: Espresso" required>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block mb-1.5" style="font-size: 0.8125rem; font-weight: 500; color: var(--color-ink);">Harga (Rp)</label>
                    <input type="number" min="0" name="price" class="input-base" placeholder="25000" required>
                </div>
                <div>
                    <label class="block mb-1.5" style="font-size: 0.8125rem; font-weight: 500; color: var(--color-ink);">Kategori</label>
                    <input type="text" name="category" class="input-base" placeholder="Minuman" required>
                </div>
            </div>
            <div>
                <label class="block mb-1.5" style="font-size: 0.8125rem; font-weight: 500; color: var(--color-ink);">Gambar Produk</label>
                <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp" class="input-base" style="padding: 6px;">
                <p class="mt-1.5" style="font-size: 0.75rem; color: var(--color-ink-faint);">Format: JPG, PNG, WEBP. Maks 2MB.</p>
            </div>
            <div class="flex justify-end gap-2 mt-2">
                <button type="button" onclick="closeModal('create-product-modal')" class="btn btn-ghost" style="padding: 8px 16px; font-size: 0.875rem;">Batal</button>
                <button type="submit" class="btn btn-brand" style="padding: 8px 20px; font-size: 0.875rem;">
                    <i class="fa-solid fa-check mr-1.5 text-xs"></i>Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="edit-product-modal" class="hidden fixed inset-0 z-50 bg-black/40 items-center justify-center p-4 backdrop-blur-sm">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-modal p-6" style="border: 1px solid var(--color-border-subtle);">
        <div class="flex justify-between items-center mb-5">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: var(--color-brand-light);">
                    <i class="fa-solid fa-pen text-sm" style="color: var(--color-brand-deep);"></i>
                </div>
                <h3 class="font-semibold" style="color: var(--color-ink); letter-spacing: -0.2px;">Update Produk</h3>
            </div>
            <button type="button" onclick="closeModal('edit-product-modal')" class="w-8 h-8 flex items-center justify-center rounded-lg" style="color: var(--color-ink-muted); background: var(--color-surface);">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
        <form id="edit-product-form" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block mb-1.5" style="font-size: 0.8125rem; font-weight: 500; color: var(--color-ink);">Nama Produk</label>
                <input type="text" name="name" id="edit-product-name" class="input-base" required>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block mb-1.5" style="font-size: 0.8125rem; font-weight: 500; color: var(--color-ink);">Harga (Rp)</label>
                    <input type="number" min="0" name="price" id="edit-product-price" class="input-base" required>
                </div>
                <div>
                    <label class="block mb-1.5" style="font-size: 0.8125rem; font-weight: 500; color: var(--color-ink);">Kategori</label>
                    <input type="text" name="category" id="edit-product-category" class="input-base" required>
                </div>
            </div>
            <div>
                <label class="block mb-1.5" style="font-size: 0.8125rem; font-weight: 500; color: var(--color-ink);">Ganti Gambar (Opsional)</label>
                <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp" class="input-base" style="padding: 6px;">
            </div>
            <div class="flex justify-end gap-2 mt-2">
                <button type="button" onclick="closeModal('edit-product-modal')" class="btn btn-ghost" style="padding: 8px 16px; font-size: 0.875rem;">Batal</button>
                <button type="submit" class="btn btn-brand" style="padding: 8px 20px; font-size: 0.875rem;">
                    <i class="fa-solid fa-check mr-1.5 text-xs"></i>Update
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id) { const el = document.getElementById(id); el.classList.remove('hidden'); el.classList.add('flex'); }
function closeModal(id) { const el = document.getElementById(id); el.classList.add('hidden'); el.classList.remove('flex'); }
function openEditProductModal(p) {
    const form = document.getElementById('edit-product-form');
    form.action = `/admin/products/${p.id}`;
    document.getElementById('edit-product-name').value = p.name;
    document.getElementById('edit-product-price').value = parseInt(p.price);
    document.getElementById('edit-product-category').value = p.category;
    openModal('edit-product-modal');
}
</script>
@endsection
