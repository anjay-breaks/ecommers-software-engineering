@extends('layouts.ceo')

@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="tf-section mb-30">
            <div class="wg-box">
                <div class="flex items-center justify-between">
                    <h5>Daftar Pengguna</h5>
                </div>
                <p class="mb-3">Manajemen akun pengguna dalam sistem.</p>


                {{-- Session Messages from the first snippet --}}
                @if(session('success') || session('error'))
                <div class="my-3">
                    @if(session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @elseif(session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                </div>
                @endif

                <div class="wg-table table-all-user">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    {{-- Headers updated --}}
                                    <th class="text-center">Nama</th>
                                    <th class="text-center">Email</th> {{-- New Email column header --}}
                                    <th class="text-center">Role</th>
                                    @if(Auth::user()->utype === 'CEO')
                                        <th class="text-center">Aksi Role</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                <tr>
                                    {{-- Nama column --}}
                                    <td class="text-center">
                                        <div>{{ $user->name }}</div>
                                    </td>

                                    {{-- New Email column --}}
                                    <td class="text-center">
                                        <div class="small">{{ $user->email }}</div>
                                    </td>

                                    {{-- Role column: Logic from the first snippet to determine role name --}}
                                    {{-- ... bagian kode lainnya ... --}}

                                    {{-- Role column: Logic from the first snippet to determine role name --}}
                                    <td class="text-center">
                                        @php
                                            $roleName = $user->utype; // Default ke utype
                                            // Gaya dasar untuk semua role badge
                                            $baseRoleStyle = 'padding: 0.3em 0.65em; font-size: 0.85em; font-weight: 600; line-height: 1; color: #fff; text-align: center; white-space: nowrap; vertical-align: baseline; border-radius: 0.375rem; display: inline-block;';
                                            $specificRoleStyle = ''; // Akan diisi berdasarkan role

                                            switch($user->utype) {
                                                case 'CEO':
                                                    $roleName = 'CEO';
                                                    // Biru tua yang kuat dan profesional, sedikit efek bayangan untuk "menyala"
                                                    $specificRoleStyle = 'background-color: #005cbA; text-shadow: 0px 0px 3px rgba(0,0,0,0.5);';
                                                    break;
                                                case 'ADM':
                                                    $roleName = 'Admin';
                                                    // Oranye yang cerah dan energik
                                                    $specificRoleStyle = 'background-color: #F08C00; color: #212529; text-shadow: 0px 0px 2px rgba(255,255,255,0.6);'; // Teks gelap agar kontras
                                                    break;
                                                case 'USR':
                                                    $roleName = 'User';
                                                    // Hijau yang segar dan jelas
                                                    $specificRoleStyle = 'background-color: #20c997;'; // Bootstrap teal
                                                    break;
                                                default:
                                                    // $roleName tetap $user->utype
                                                    // Abu-abu netral untuk default
                                                    $specificRoleStyle = 'background-color: #6c757d;'; // Bootstrap secondary
                                                    break;
                                            }
                                            $finalRoleStyle = $baseRoleStyle . ' ' . $specificRoleStyle;
                                        @endphp
                                        <span style="{{ $finalRoleStyle }}">{{ $roleName }}</span>
                                    </td>
                                    @if(Auth::user()->utype === 'CEO')
                                        <td class="text-center">
                                            @if(Auth::id() !== $user->id)
                                            <form action="{{ route('users.updateRole', $user->id) }}" method="POST" class="mb-0 d-inline-block">
                                                @csrf
                                                <select name="role" onchange="this.form.submit()" class="form-select form-select-sm">
                                                    <option value="ADM" {{ $user->utype === 'ADM' ? 'selected' : '' }}>Jadikan Admin</option>
                                                    <option value="CEO" {{ $user->utype === 'CEO' ? 'selected' : '' }}>Jadikan CEO</option>
                                                    <option value="USR" {{ $user->utype === 'USR' ? 'selected' : '' }}>Jadikan User</option>
                                                </select>
                                            </form>
                                            @else
                                            <span>Tidak dapat mengubah role sendiri</span>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                                @empty
                                {{-- Empty state from the first snippet, colspan updated --}}
                                <tr>
                                    <td colspan="{{ Auth::user()->utype === 'CEO' ? '4' : '3' }}" class="text-center py-5">
                                        <div>
                                            <p class="fs-5">Belum Ada Pengguna</p>
                                            <p class="small">Saat ini tidak ada data pengguna yang terdaftar.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Pagination from the first snippet --}}
                @if ($users instanceof \Illuminate\Pagination\LengthAwarePaginator && $users->hasPages())
                    <div class="mt-4 d-flex justify-content-center">
                        {{ $users->links('pagination::bootstrap-5') }}
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection
