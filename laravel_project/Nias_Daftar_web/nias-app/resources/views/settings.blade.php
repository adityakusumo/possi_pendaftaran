@extends('layouts.app')
@section('title', 'Pengaturan Sistem — Admin')

@section('content')
    <div class="card page-card">
        <div class="card-header d-flex justify-content-between align-items-center bg-white">
            <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-gear-fill me-2 text-primary"></i>Pengaturan Sistem</h5>
            <a href="{{ route('welcome') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i>Kembali ke Portal
            </a>
        </div>

        <div class="card-body p-0">
            {{-- Navigasi Tab --}}
            <ul class="nav nav-tabs px-3 pt-3 bg-light" id="settingTab">
                <li class="nav-item">
                    <a class="nav-link {{ !request('tab') || request('tab') === 'nias' ? 'active fw-bold border-bottom-0' : '' }}"
                        href="{{ route('settings', ['tab' => 'nias']) }}">
                        <i class="bi bi-person-vcard me-2"></i>Setting NIAS
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('tab') === 'lomba' ? 'active fw-bold border-bottom-0' : '' }}"
                        href="{{ route('settings', ['tab' => 'lomba']) }}">
                        <i class="bi bi-trophy me-2"></i>Setting Lomba (User)
                    </a>
                </li>
            </ul>

            <div class="p-4">
                {{-- KONTEN TAB 1: NIAS --}}
                {{-- KONTEN TAB 1: NIAS --}}
                @if(!request('tab') || request('tab') === 'nias')
                    <div class="tab-content-area">
                        <form action="{{ route('settings.nias.save') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="card border shadow-sm">
                                        <div class="card-header bg-light fw-bold">
                                            <i class="bi bi-calendar-range me-2"></i>Jadwal Pendaftaran
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">Tanggal Dibuka</label>
                                                <input type="date" name="nias_open_date" class="form-control"
                                                    value="{{ $niasOpenDate }}">
                                            </div>
                                            <div class="mb-0">
                                                <label class="form-label small fw-bold">Tanggal Ditutup</label>
                                                <input type="date" name="nias_close_date" class="form-control"
                                                    value="{{ $niasCloseDate }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <div
                                        class="alert {{ \App\Models\AppSetting::isNiasOpen() ? 'alert-success' : 'alert-warning' }} h-100 d-flex align-items-center">
                                        <div>
                                            <h6 class="fw-bold"><i class="bi bi-info-circle-fill me-2"></i>Status Saat Ini:</h6>
                                            <p class="mb-0">
                                                @if(\App\Models\AppSetting::isNiasOpen())
                                                    Pendaftaran sedang <strong>TERBUKA</strong>.
                                                @else
                                                    Pendaftaran sedang <strong>TERTUTUP</strong>.
                                                @endif
                                            </p>
                                            <hr>
                                            <button type="button" onclick="confirmResetJadwal()" class="btn btn-sm btn-danger">
                                                <i class="bi bi-x-circle me-1"></i>Reset & Tutup Pendaftaran
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card border shadow-sm mb-4">
                                <div class="card-header bg-light fw-bold d-flex justify-content-between align-items-center">
                                    <span><i class="bi bi-shield-lock me-2"></i>Batas Akun Pelatih per Club</span>
                                    <span class="badge bg-dark">Default: 2 Akun</span>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                        <table class="table table-sm table-hover mb-0">
                                            <thead class="table-light sticky-top">
                                                <tr>
                                                    <th class="ps-3">Nama Club</th>
                                                    <th class="text-center">Akun Terdaftar</th>
                                                    <th style="width: 150px;">Batas Maksimal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($clubStats as $clubName => $stat)
                                                    <tr>
                                                        <td class="ps-3 align-middle">{{ $clubName }}</td>
                                                        <td class="text-center align-middle">
                                                            <span
                                                                class="badge {{ $stat['count'] >= $stat['max'] ? 'bg-danger' : 'bg-info' }}">
                                                                {{ $stat['count'] }} / {{ $stat['max'] }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <input type="number" name="max_accounts[{{ $clubName }}]"
                                                                class="form-control form-control-sm text-center"
                                                                value="{{ $stat['max'] }}" min="1">
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-save me-2"></i>Simpan Semua Perubahan NIAS
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Form tersembunyi untuk Reset Jadwal --}}
                    <form id="form_reset_nias" action="{{ route('settings.nias.reset') }}" method="POST" style="display:none;">
                        @csrf
                    </form>
                @endif

                {{-- KONTEN TAB 2: LOMBA (Manajemen User) --}}
                @if(request('tab') === 'lomba')
                    <div class="tab-content-area">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0">Manajemen Akun Pelatih / Lomba</h6>
                            <form action="{{ route('settings') }}" method="GET" class="d-flex">
                                <input type="hidden" name="tab" value="lomba">
                                <input type="text" name="cari" class="form-control form-control-sm me-2"
                                    placeholder="Cari nama/email..." value="{{ request('cari') }}">
                                <button type="submit" class="btn btn-sm btn-dark">Cari</button>
                            </form>
                        </div>

                        {{-- Tabel daftar user/atlet yang sudah ada di file lama Anda --}}
                        <div class="table-responsive">
                            <table class="table table-sm table-hover border">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama</th>
                                        <th>Club</th>
                                        <th>Role</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>{{ $user->nama }}</td>
                                            <td>{{ $user->namaclub }}</td>
                                            <td><span class="badge bg-secondary">{{ $user->role }}</span></td>
                                            <td>
                                                {{-- Tombol Reset Password & Delete --}}
                                                <button onclick="confirmReset('{{ $user->id }}')"
                                                    class="btn btn-xs btn-warning">Reset</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $users->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .nav-tabs .nav-link {
            border: 1px solid transparent;
            color: #6c757d;
        }

        .nav-tabs .nav-link.active {
            background-color: #fff !important;
            border-color: #dee2e6 #dee2e6 #fff !important;
            color: #0d6efd !important;
        }

        .btn-xs {
            padding: 0.1rem 0.4rem;
            font-size: 0.75rem;
        }
    </style>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmResetJadwal() {
            Swal.fire({
                title: 'Reset Jadwal?',
                text: "Pendaftaran akan langsung ditutup dan tanggal pendaftaran akan dikosongkan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Reset!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form_reset_nias').submit();
                }
            })
        }
    </script>
@endpush