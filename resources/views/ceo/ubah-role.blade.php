@extends('layouts.ceo')

@section('content')
<div class="container-fluid py-4 px-xl-5">
    <div class="card shadow-sm border-0" style="border-radius: 1rem; overflow: hidden;">
        <div class="card-header border-0 py-4 px-4 px-md-5"> {{-- Removed bg-light --}}
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center">
                <div>
                    <h1 class="h3 mb-1 fw-bolder"> {{-- Removed text-dark-emphasis, will be handled by CSS --}}
                        <i class="bi bi-people-fill me-2 header-icon-accent"></i>Daftar Pengguna
                    </h1>
                    <p class="text-muted-custom mb-0">Manajemen akun pengguna dalam sistem.</p>
                </div>
                {{-- Optional: Add a button here like "Tambah Pengguna Baru" if applicable --}}
                {{-- <a href="#" class="btn btn-primary btn-sm mt-3 mt-sm-0">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Pengguna
                </a> --}}
            </div>
        </div>

        <div class="card-body p-0">
            @if(session('success') || session('error'))
            <div class="px-4 px-md-5 pt-4">
                @if(session('success'))
                    <div class="alert alert-success d-flex align-items-center rounded-3" role="alert">
                        <i class="bi bi-check-circle-fill fs-5 me-2"></i>
                        <div>{{ session('success') }}</div>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @elseif(session('error'))
                    <div class="alert alert-danger d-flex align-items-center rounded-3" role="alert">
                        <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
                        <div>{{ session('error') }}</div>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>
            @endif

            <div class="table-responsive">
                <table class="table table-borderless table-hover align-middle mb-0 user-table">
                    <thead class="thead-custom">
                        <tr>
                            <th scope="col" class="py-3 px-4 px-md-5 text-uppercase small fw-semibold">Pengguna</th>
                            <th scope="col" class="py-3 px-4 px-md-5 text-uppercase small fw-semibold">Role</th>
                            @if(Auth::user()->utype === 'CEO')
                                <th scope="col" class="py-3 px-4 px-md-5 text-uppercase small fw-semibold text-center">Aksi Role</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr class="user-row">
                            <td class="py-3 px-4 px-md-5">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-3" data-initials="{{ strtoupper(substr($user->name, 0, 1)) . (strpos($user->name, ' ') ? strtoupper(substr($user->name, strpos($user->name, ' ') + 1, 1)) : '') }}">
                                    </div>
                                    <div>
                                        <div class="fw-bold user-name">{{ $user->name }}</div> {{-- Removed text-dark-emphasis --}}
                                        <div class="text-muted-custom small">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4 px-md-5">
                                @php
                                    $roleBadgeClass = '';
                                    $roleName = $user->utype;
                                    switch($user->utype) {
                                        case 'CEO':
                                            $roleBadgeClass = 'badge-ceo';
                                            $roleName = 'CEO';
                                            break;
                                        case 'ADM':
                                            $roleBadgeClass = 'badge-admin';
                                            $roleName = 'Admin';
                                            break;
                                        case 'USR':
                                            $roleBadgeClass = 'badge-user';
                                            $roleName = 'User';
                                            break;
                                        default:
                                            $roleBadgeClass = 'badge-default';
                                            break;
                                    }
                                @endphp
                                <span class="badge rounded-pill px-3 py-2 {{ $roleBadgeClass }}">{{ $roleName }}</span>
                            </td>
                            @if(Auth::user()->utype === 'CEO')
                            <td class="py-3 px-4 px-md-5 text-center">
                                @if(Auth::id() !== $user->id)
                                <form action="{{ route('users.updateRole', $user->id) }}" method="POST" class="mb-0 d-inline-block change-role-form" style="min-width: 150px;">
                                    @csrf
                                    <select name="role" onchange="this.form.submit()" class="form-select form-select-sm custom-role-select">
                                        <option value="ADM" {{ $user->utype === 'ADM' ? 'selected' : '' }}>Jadikan Admin</option>
                                        <option value="CEO" {{ $user->utype === 'CEO' ? 'selected' : '' }}>Jadikan CEO</option>
                                        <option value="USR" {{ $user->utype === 'USR' ? 'selected' : '' }}>Jadikan User</option>
                                    </select>
                                </form>
                                @else
                                <span class="text-muted-custom fst-italic small d-flex align-items-center justify-content-center">
                                    <i class="bi bi-shield-lock-fill me-1"></i>Tidak dapat diubah
                                </span>
                                @endif
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ Auth::user()->utype === 'CEO' ? '3' : '2' }}" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center justify-content-center text-muted-custom">
                                    <i class="bi bi-person-x-fill" style="font-size: 3.5rem; opacity: 0.7;"></i>
                                    <p class="mt-3 mb-0 fs-5">Belum Ada Pengguna</p>
                                    <p class="small">Saat ini tidak ada data pengguna yang terdaftar.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($users instanceof \Illuminate\Pagination\LengthAwarePaginator && $users->hasPages())
            <div class="card-footer border-0 py-3 px-4 px-md-5 d-flex justify-content-center"> {{-- Removed bg-light --}}
                {{ $users->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Import Inter font - ensure it's loaded in your main layout or here */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

    :root {
        --futuristic-bg: #0a0f1a;
        --futuristic-bg-lighter: #141b2d;
        --futuristic-card-bg: rgba(20, 27, 45, 0.6);
        --futuristic-border-color: rgba(0, 191, 255, 0.2);
        --futuristic-text-primary: #e0e6f0;
        --futuristic-text-secondary: #8a99b3;
        --futuristic-accent: #00bfff;
        --futuristic-accent-glow: rgba(0, 191, 255, 0.3);
        --futuristic-primary-gradient: linear-gradient(135deg, #007bff 0%, #00bfff 100%);
    }

    body {
        background-color: var(--futuristic-bg) !important;
        font-family: 'Inter', sans-serif !important;
        color: var(--futuristic-text-primary) !important;
    }

    .card {
        background-color: var(--futuristic-card-bg) !important;
        border: 1px solid var(--futuristic-border-color) !important;
        backdrop-filter: blur(12px) saturate(150%) !important;
        -webkit-backdrop-filter: blur(12px) saturate(150%) !important; /* Safari */
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37) !important;
        border-radius: 0.75rem !important;
    }

    .card-header, .card-footer {
        background-color: transparent !important;
        border-bottom: 1px solid var(--futuristic-border-color) !important;
    }
    /* .card-header selector sudah ada di atas, jadi ini hanya untuk menegaskan */
    /* .card-footer selector sudah ada di atas, ini untuk menegaskan */
    .card-footer {
        border-top: 1px solid var(--futuristic-border-color) !important;
        border-bottom: none !important;
    }

    .card-header .h3, .card-header .h1 {
        font-weight: 700 !important;
        color: var(--futuristic-text-primary) !important;
        text-shadow: 0 0 8px var(--futuristic-accent-glow) !important;
    }
    .header-icon-accent {
        color: var(--futuristic-accent) !important;
        filter: drop-shadow(0 0 5px var(--futuristic-accent)) !important;
    }
    .text-muted-custom { /* Perlu lebih spesifik jika .text-muted dari Bootstrap menimpa */
        color: var(--futuristic-text-secondary) !important;
    }
    /* Untuk memastikan text-muted-custom pada elemen small juga tertimpa */
    .text-muted-custom.small {
        color: var(--futuristic-text-secondary) !important;
    }


    .user-table {
        font-size: 0.9rem !important;
    }
    .user-table .user-name {
        color: var(--futuristic-text-primary) !important;
        font-weight: 600 !important;
    }

    .thead-custom th {
        background-color: rgba(0,0,0,0.15) !important;
        color: var(--futuristic-text-secondary) !important;
        border-bottom: 1px solid var(--futuristic-border-color) !important;
        letter-spacing: 0.07em !important;
        font-weight: 600 !important;
    }
    /* Jika ada text-transform atau font-size di global th, tambahkan !important juga */
    .thead-custom th.text-uppercase { text-transform: uppercase !important; }
    .thead-custom th.small { font-size: 0.875em !important; } /* Sesuaikan dengan nilai small jika perlu */
    .thead-custom th.fw-semibold { font-weight: 600 !important; }
    .thead-custom th.text-center { text-align: center !important; }


    .user-row {
        border-bottom: 1px solid rgba(0, 191, 255, 0.1) !important;
    }
    .user-row:last-child {
        border-bottom: none !important;
    }
    .user-row:hover {
        background-color: rgba(0, 191, 255, 0.05) !important;
    }

    .avatar-circle {
        width: 44px !important;
        height: 44px !important;
        background: var(--futuristic-primary-gradient) !important;
        color: white !important;
        border-radius: 50% !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-weight: 700 !important;
        font-size: 1rem !important;
        text-transform: uppercase !important;
        box-shadow: 0 0 10px var(--futuristic-accent-glow) !important;
    }
    /* .avatar-circle::before tidak bisa diberi !important, tapi properti di dalamnya bisa jika diperlukan */

    /* Custom Badge Styles */
    .badge { /* Hati-hati jika .badge global dari Bootstrap sangat kuat */
        font-weight: 600 !important;
        letter-spacing: 0.03em !important;
        font-size: 0.8rem !important;
        padding: 0.6em 1em !important;
        border: 1px solid transparent !important; /* Bisa jadi masalah jika badge lain butuh border spesifik */
        box-shadow: 0 2px 5px rgba(0,0,0,0.2) !important;
    }
    .badge.rounded-pill { border-radius: 50rem !important; } /* Menegaskan rounded-pill jika ada override */

    .badge-ceo {
        background: linear-gradient(135deg, #007bff, #0056b3) !important;
        color: white !important;
        border-color: var(--futuristic-accent) !important;
        box-shadow: 0 0 10px rgba(0, 123, 255, 0.6) !important;
    }
    .badge-admin {
        background: linear-gradient(135deg, #ffc107, #e0a800) !important;
        color: #1a1a2e !important;
        border-color: #ffd700 !important;
        box-shadow: 0 0 10px rgba(255, 193, 7, 0.6) !important;
    }
    .badge-user {
        background: linear-gradient(135deg, #4b5563, #374151) !important;
        color: white !important;
        border-color: #6b7280 !important;
        box-shadow: 0 0 10px rgba(108, 117, 125, 0.5) !important;
    }
    .badge-default {
        background-color: #374151 !important;
        color: var(--futuristic-text-secondary) !important;
        border-color: #4b5563 !important;
    }

    .custom-role-select {
        background-color: rgba(0,0,0,0.25) !important;
        color: var(--futuristic-text-primary) !important;
        border: 1px solid var(--futuristic-border-color) !important;
        border-radius: 0.375rem !important; /* Bootstrap .form-select-sm mungkin punya ini */
        box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.15) !important;
        /* transition tidak memerlukan !important */
    }
    /* .custom-role-select option tidak bisa diberi !important pada properti option itu sendiri dari parent, tapi properti internalnya bisa */
    .custom-role-select option {
        background-color: var(--futuristic-bg-lighter); /* Ini mungkin tidak bisa di-override dengan !important dari parent */
        color: var(--futuristic-text-primary);
    }
    .custom-role-select:focus {
        border-color: var(--futuristic-accent) !important;
        box-shadow: 0 0 0 0.25rem var(--futuristic-accent-glow) !important;
        background-color: rgba(0,0,0,0.3) !important;
    }
     /* Jika .form-select dan .form-select-sm dari Bootstrap menimpa padding/font-size */
    .form-select.custom-role-select {
        padding-top: .25rem !important; /* Contoh nilai dari .form-select-sm */
        padding-bottom: .25rem !important;
        padding-left: .5rem !important;
        font-size: .875rem !important;
    }


    /* Alert Styling */
    .alert { /* Kelas .alert dari Bootstrap sangat umum, jadi !important sangat mungkin diperlukan */
        background-color: rgba(20, 27, 45, 0.8) !important;
        border: 1px solid !important; /* Akan menggunakan border-color dari .alert-success/danger */
        border-left-width: 4px !important;
        color: var(--futuristic-text-primary) !important;
        backdrop-filter: blur(5px) !important;
        border-radius: .375rem !important; /* Bootstrap .rounded-3 */
    }
    .alert-success {
        border-color: #28a745 !important;
        box-shadow: 0 0 10px rgba(40, 167, 69, 0.3) !important;
        /* Jika Bootstrap .alert-success punya background-color & color sendiri, timpa juga */
        background-color: rgba(20, 27, 45, 0.8) !important; /* Ulangi jika perlu */
        color: var(--futuristic-text-primary) !important;
    }
    .alert-danger {
        border-color: #dc3545 !important;
        box-shadow: 0 0 10px rgba(220, 53, 69, 0.3) !important;
        background-color: rgba(20, 27, 45, 0.8) !important; /* Ulangi jika perlu */
        color: var(--futuristic-text-primary) !important;
    }
    .alert .btn-close {
       filter: invert(80%) sepia(30%) saturate(150%) hue-rotate(180deg) brightness(150%) contrast(90%) !important;
    }
    .alert .bi {
        filter: drop-shadow(0 0 3px rgba(255,255,255,0.3)) !important;
    }


    /* Pagination Styling */
    /* Kelas .pagination, .page-item, .page-link sangat umum di Bootstrap */
    .pagination .page-item .page-link {
        background-color: rgba(0, 0, 0, 0.2) !important;
        border: 1px solid var(--futuristic-border-color) !important;
        color: var(--futuristic-accent) !important;
        border-radius: 0.3rem !important;
        margin: 0 3px !important;
        font-weight: 500 !important;
    }
    .pagination .page-item.active .page-link {
        background: var(--futuristic-primary-gradient) !important;
        border-color: var(--futuristic-accent) !important;
        color: white !important;
        font-weight: 700 !important;
        box-shadow: 0 0 10px var(--futuristic-accent-glow) !important;
    }
    .pagination .page-item.disabled .page-link {
        color: var(--futuristic-text-secondary) !important;
        opacity: 0.6 !important;
        background-color: rgba(0,0,0,0.1) !important;
    }
    .pagination .page-item .page-link:hover:not(.disabled) { /* :not(.disabled) tidak bisa diberi !important */
        background-color: var(--futuristic-accent-glow) !important;
        color: white !important;
        border-color: var(--futuristic-accent) !important;
    }
    .pagination .page-item.active .page-link:hover {
         filter: brightness(1.1) !important;
    }

    /* For "Tidak dapat diubah" text to fit the theme */
    .text-muted-custom.fst-italic.small .bi {
        color: var(--futuristic-text-secondary) !important;
        opacity: 0.8 !important;
    }
    .text-muted-custom.fst-italic.small { /* Targetkan parent span jika perlu */
        color: var(--futuristic-text-secondary) !important;
    }

    /* Untuk teks di dalam empty state jika tertimpa */
    .text-muted-custom .fs-5 {
        color: var(--futuristic-text-primary) !important; /* Jika fs-5 dari Bootstrap punya warna sendiri */
    }
    .text-muted-custom .small {
        color: var(--futuristic-text-secondary) !important; /* Jika small dari Bootstrap punya warna sendiri */
    }

</style>
@endpush

@push('scripts')
<script>
    // Script for avatar initials (no change needed from original, already robust)
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.avatar-circle').forEach(avatar => {
            const initials = avatar.getAttribute('data-initials');
            // The ::before pseudo-element handles displaying 'data-initials'.
            // This JS is a fallback if 'data-initials' attribute itself was missing,
            // but Blade PHP should always provide it.
            if (!initials) {
                const nameElement = avatar.closest('tr').querySelector('.user-name');
                if (nameElement) {
                    const name = nameElement.textContent.trim();
                    const nameParts = name.split(' ');
                    let calculatedInitials = nameParts[0] ? nameParts[0][0] : '';
                    if (nameParts.length > 1 && nameParts[1]) {
                        calculatedInitials += nameParts[1][0];
                    }
                    // If you were setting textContent directly instead of using ::before:
                    // avatar.textContent = calculatedInitials.toUpperCase();
                    // But since ::before is used, we'd need to set data-initials if it was missing:
                    // avatar.setAttribute('data-initials', calculatedInitials.toUpperCase());
                }
            }
        });
    });
</script>
@endpush
