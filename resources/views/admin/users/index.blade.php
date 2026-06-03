@extends('layouts.admin')

@section('content')
@php
    $roles = ['waitress', 'kasir', 'dapur', 'pengelola'];
    $usersAll = \App\Models\User::all();
    $roleCounts = $usersAll->groupBy('role')->map->count();
@endphp

<!-- Page Header -->
<div class="mb-8 flex items-start justify-between gap-4">
    <div>
        <span class="section-label"><i class="fa-solid fa-users-gear mr-1.5"></i>Pengelola</span>
        <h1 class="font-semibold mt-1" style="font-size: 1.75rem; letter-spacing: -0.5px; color: var(--color-ink);">Manajemen User</h1>
        <p style="color: var(--color-ink-muted); font-size: 0.9375rem; margin-top: 4px;">Daftar staf dan admin yang memiliki akses ke sistem.</p>
    </div>
    <button type="button" onclick="openModal('create-user-modal')" class="btn btn-brand" style="font-size: 0.875rem; padding: 8px 16px; flex-shrink: 0;">
        <i class="fa-solid fa-user-plus mr-1.5 text-xs"></i>Tambah User
    </button>
</div>

<!-- Stats -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    @foreach($roles as $r)
    <div class="card" style="padding: 14px 18px;">
        <p class="t-mono text-[10px] mb-1 capitalize" style="color: var(--color-ink-faint);">{{ $r }}</p>
        <p class="font-bold text-xl" style="letter-spacing: -0.3px; color: var(--color-ink);">{{ $roleCounts->get($r, 0) }}</p>
    </div>
    @endforeach
</div>

@if(session('success'))
<div class="flex items-center gap-3 px-4 py-3 rounded-xl mb-6" style="background: var(--color-brand-light); border: 1px solid rgba(24,226,153,0.3);">
    <i class="fa-solid fa-circle-check" style="color: var(--color-brand-deep);"></i>
    <p class="text-sm font-medium" style="color: var(--color-brand-deep);">{{ session('success') }}</p>
</div>
@endif

@if(session('error'))
<div class="flex items-center gap-3 px-4 py-3 rounded-xl mb-6" style="background: #fee2e2; border: 1px solid rgba(220,38,38,0.2);">
    <i class="fa-solid fa-triangle-exclamation" style="color: #dc2626;"></i>
    <p class="text-sm" style="color: #991b1b;">{{ session('error') }}</p>
</div>
@endif

<!-- Users Table -->
<div class="card overflow-hidden" style="padding: 0; border-radius: 16px;">
    <div class="overflow-x-auto">
        <table class="data-table" style="min-width: 600px;">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $u)
                @php
                    $roleBadge = ['kasir' => 'badge-blue', 'dapur' => 'badge-yellow', 'waitress' => 'badge-green', 'pengelola' => 'badge-gray'];
                @endphp
                <tr>
                    <td>
                        <div class="flex items-center gap-3 py-1">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm text-ink flex-shrink-0"
                                 style="background: linear-gradient(135deg, var(--color-brand) 0%, var(--color-brand-deep) 100%);">
                                {{ strtoupper(substr($u->name, 0, 1)) }}
                            </div>
                            <span class="font-semibold text-sm" style="color: var(--color-ink);">{{ $u->name }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="t-mono text-xs" style="color: var(--color-ink-muted);">@ {{ $u->username }}</span>
                    </td>
                    <td>
                        <span class="badge {{ $roleBadge[$u->role] ?? 'badge-gray' }}">{{ $u->role }}</span>
                    </td>
                    <td style="text-align: right;">
                        <div class="flex justify-end gap-2 pr-4">
                            <button type="button" class="btn btn-ghost" style="padding: 5px 12px; font-size: 0.8125rem;"
                                onclick='openEditUserModal(@json($u))'>
                                <i class="fa-solid fa-pen mr-1 text-xs"></i>Edit
                            </button>
                            <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn" style="padding: 5px 12px; font-size: 0.8125rem; background: #fee2e2; color: #dc2626; border: 1px solid rgba(220,38,38,0.2); border-radius: 9999px;" onclick="return confirm('Hapus user ini?')">
                                    <i class="fa-solid fa-trash mr-1 text-xs"></i>Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Create Modal -->
<div id="create-user-modal" class="hidden fixed inset-0 z-50 bg-black/40 items-center justify-center p-4 backdrop-blur-sm">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-modal p-6" style="border: 1px solid var(--color-border-subtle);">
        <div class="flex justify-between items-center mb-5">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: var(--color-brand-light);">
                    <i class="fa-solid fa-user-plus text-sm" style="color: var(--color-brand-deep);"></i>
                </div>
                <h3 class="font-semibold" style="color: var(--color-ink); letter-spacing: -0.2px;">Tambah User Baru</h3>
            </div>
            <button type="button" onclick="closeModal('create-user-modal')" class="w-8 h-8 flex items-center justify-center rounded-lg" style="color: var(--color-ink-muted); background: var(--color-surface);">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.users.store') }}" class="flex flex-col gap-3">
            @csrf
            <div>
                <label class="block mb-1.5" style="font-size: 0.8125rem; font-weight: 500; color: var(--color-ink);">Nama Lengkap</label>
                <input type="text" name="name" class="input-base" placeholder="Contoh: Budi Santoso" required>
            </div>
            <div>
                <label class="block mb-1.5" style="font-size: 0.8125rem; font-weight: 500; color: var(--color-ink);">Username</label>
                <input type="text" name="username" class="input-base" placeholder="Contoh: kasir1" required>
            </div>
            <div>
                <label class="block mb-1.5" style="font-size: 0.8125rem; font-weight: 500; color: var(--color-ink);">Email</label>
                <input type="email" name="email" class="input-base" placeholder="email@contoh.com" required>
            </div>
            <div>
                <label class="block mb-1.5" style="font-size: 0.8125rem; font-weight: 500; color: var(--color-ink);">Password</label>
                <input type="password" name="password" class="input-base" placeholder="Password" required>
            </div>
            <div>
                <label class="block mb-1.5" style="font-size: 0.8125rem; font-weight: 500; color: var(--color-ink);">Role</label>
                <select name="role" class="input-base" required>
                    <option value="kasir">Kasir</option>
                    <option value="dapur">Dapur</option>
                    <option value="waitress">Waitress</option>
                </select>
            </div>
            <div class="flex justify-end gap-2 mt-2">
                <button type="button" onclick="closeModal('create-user-modal')" class="btn btn-ghost" style="padding: 8px 16px; font-size: 0.875rem;">Batal</button>
                <button type="submit" class="btn btn-brand" style="padding: 8px 20px; font-size: 0.875rem;">
                    <i class="fa-solid fa-check mr-1.5 text-xs"></i>Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal Placeholder (Similar structure to Products) -->
<div id="edit-user-modal" class="hidden fixed inset-0 z-50 bg-black/40 items-center justify-center p-4 backdrop-blur-sm">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-modal p-6" style="border: 1px solid var(--color-border-subtle);">
        <div class="flex justify-between items-center mb-5">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: var(--color-brand-light);">
                    <i class="fa-solid fa-pen text-sm" style="color: var(--color-brand-deep);"></i>
                </div>
                <h3 class="font-semibold" style="color: var(--color-ink); letter-spacing: -0.2px;">Edit User</h3>
            </div>
            <button type="button" onclick="closeModal('edit-user-modal')" class="w-8 h-8 flex items-center justify-center rounded-lg" style="color: var(--color-ink-muted); background: var(--color-surface);">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
        <form id="edit-user-form" method="POST" class="flex flex-col gap-3">
            @csrf
            @method('PUT')
            <div>
                <label class="block mb-1.5" style="font-size: 0.8125rem; font-weight: 500; color: var(--color-ink);">Nama Lengkap</label>
                <input type="text" name="name" id="edit-user-name" class="input-base" required>
            </div>
            <div>
                <label class="block mb-1.5" style="font-size: 0.8125rem; font-weight: 500; color: var(--color-ink);">Username</label>
                <input type="text" name="username" id="edit-user-username" class="input-base" required disabled>
            </div>
            <div>
                <label class="block mb-1.5" style="font-size: 0.8125rem; font-weight: 500; color: var(--color-ink);">Role</label>
                <select name="role" id="edit-user-role" class="input-base" required>
                    <option value="kasir">Kasir</option>
                    <option value="dapur">Dapur</option>
                    <option value="waitress">Waitress</option>
                    <option value="pengelola">Pengelola</option>
                </select>
            </div>
            <div>
                <label class="block mb-1.5" style="font-size: 0.8125rem; font-weight: 500; color: var(--color-ink);">Password Baru (Opsional)</label>
                <input type="password" name="password" class="input-base" placeholder="Kosongkan jika tidak diubah">
            </div>
            <div class="flex justify-end gap-2 mt-2">
                <button type="button" onclick="closeModal('edit-user-modal')" class="btn btn-ghost" style="padding: 8px 16px; font-size: 0.875rem;">Batal</button>
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
function openEditUserModal(u) {
    const form = document.getElementById('edit-user-form');
    form.action = `/admin/users/${u.id}`;
    document.getElementById('edit-user-name').value = u.name;
    document.getElementById('edit-user-username').value = u.username;
    document.getElementById('edit-user-role').value = u.role;
    openModal('edit-user-modal');
}
</script>
@endsection
