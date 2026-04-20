@extends('layouts.app')
@section('title', 'Detail Data NIAS')

@section('content')
<div class="card page-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="bi bi-person-badge me-2"></i>Detail Data NIAS</h5>
        <div class="d-flex gap-2">
            {{-- Tombol edit: admin bisa edit semua, regular hanya yang belum dikirim --}}
            @if(Auth::user()->role === 'admin' || !$nias->is_sent)
            <a href="{{ route('nias.edit', $nias->ID) }}"
               class="btn btn-warning btn-sm">
                <i class="bi bi-pencil me-1"></i>Edit
            </a>
            @endif
            <a href="{{ route('nias.index') }}" class="btn btn-light btn-sm">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>

    <div class="card-body p-4">

        {{-- Status badge --}}
        <div class="mb-4">
            @php
                $statusLabel = match((int)$nias->STATUS) {
                    1 => ['label' => 'DISETUJUI', 'class' => 'bg-success'],
                    2 => ['label' => 'PENDING ACC', 'class' => 'bg-warning text-dark'],
                    3 => ['label' => 'SUDAH DIKIRIM', 'class' => 'bg-info text-dark'],
                    0 => ['label' => 'EXPIRED', 'class' => 'bg-danger'],
                    default => ['label' => 'TIDAK DIKETAHUI', 'class' => 'bg-secondary'],
                };
            @endphp
            <span class="badge {{ $statusLabel['class'] }} fs-6 px-3 py-2">
                <i class="bi bi-circle-fill me-1 small"></i>{{ $statusLabel['label'] }}
            </span>
            @if($nias->is_update)
                <span class="badge bg-info text-dark ms-2 fs-6 px-3 py-2">
                    <i class="bi bi-arrow-repeat me-1"></i>UPDATE / PERPANJANG
                </span>
            @endif
        </div>

        <div class="row g-4">

            {{-- ── Data Pribadi ─────────────────────────────────── --}}
            <div class="col-md-6">
                <div class="card section-card h-100">
                    <div class="card-header py-2 px-3">
                        <span class="fw-bold text-primary small">
                            <i class="bi bi-person me-1"></i>DATA PRIBADI
                        </span>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="text-muted small" style="width:140px">Nama</td>
                                <td class="fw-semibold">{{ $nias->NAMA }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted small">Jenis Kelamin</td>
                                <td>{{ $nias->GENDER === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted small">Tempat Lahir</td>
                                <td>{{ $nias->TEMPATLAHIR }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted small">Tanggal Lahir</td>
                                <td>{{ $nias->TGLLAHIR?->format('d/m/Y') ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted small">NIK</td>
                                <td><code>{{ $nias->NIK ?? '—' }}</code></td>
                            </tr>
                            <tr>
                                <td class="text-muted small">Email</td>
                                <td>{{ $nias->EMAIL ?? '—' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            {{-- ── Data Club & Domisili ─────────────────────────── --}}
            <div class="col-md-6">
                <div class="card section-card h-100">
                    <div class="card-header py-2 px-3">
                        <span class="fw-bold text-primary small">
                            <i class="bi bi-people me-1"></i>CLUB & DOMISILI
                        </span>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="text-muted small" style="width:140px">Club</td>
                                <td class="fw-semibold text-primary">{{ $nias->NAMACLUB }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted small">Kota Club</td>
                                <td>{{ ($nias->JENIS ?? '') . ' ' . ($nias->NAMAKOTA ?? '') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted small">Domisili</td>
                                <td>{{ ($nias->JENISDOM ?? '') . ' ' . ($nias->NAMAKOTADOM ?? '') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted small">Provinsi</td>
                                <td>{{ $nias->NAMAPROPDOM ?? 'JAWA TIMUR' }}</td>
                            </tr>
                            @if($nias->is_update)
                            <tr>
                                <td class="text-muted small">Tipe Update</td>
                                <td>
                                    @php
                                        $tipeLabel = match($nias->tipe_update) {
                                            'perpanjangan'  => 'Perpanjangan',
                                            'update_club'   => 'Pindah Club',
                                            'update_domisili' => 'Pindah Domisili',
                                            'update_all'    => 'Pindah Club & Domisili',
                                            default => $nias->tipe_update ?? '—',
                                        };
                                    @endphp
                                    <span class="badge bg-secondary">{{ $tipeLabel }}</span>
                                </td>
                            </tr>
                            @if($nias->mutasi_luar_jatim)
                            <tr>
                                <td class="text-muted small">Mutasi Luar Jatim</td>
                                <td>
                                    <span class="badge {{ $nias->mutasi_luar_jatim === 'ya' ? 'bg-warning text-dark' : 'bg-light text-dark border' }}">
                                        {{ strtoupper($nias->mutasi_luar_jatim) }}
                                    </span>
                                </td>
                            </tr>
                            @endif
                            @endif
                        </table>
                    </div>
                </div>
            </div>

            {{-- ── Data NIAS ────────────────────────────────────── --}}
            <div class="col-md-6">
                <div class="card section-card h-100">
                    <div class="card-header py-2 px-3">
                        <span class="fw-bold text-primary small">
                            <i class="bi bi-card-text me-1"></i>DATA NIAS
                        </span>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="text-muted small" style="width:140px">No. NIAS Jatim</td>
                                <td><code>{{ $nias->NONIAS ?? '—' }}</code></td>
                            </tr>
                            <tr>
                                <td class="text-muted small">Tgl Daftar</td>
                                <td>{{ $nias->TGLDAFTAR?->format('d/m/Y') ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted small">Tgl Update</td>
                                <td>{{ $nias->TGLDAFTAR_UPDATE?->format('d/m/Y') ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted small">Expired</td>
                                <td class="{{ $nias->EXPIRED?->isPast() ? 'text-danger fw-semibold' : '' }}">
                                    {{ $nias->EXPIRED?->format('d/m/Y') ?? '—' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted small">Tgl Dikirim</td>
                                <td class="text-success">
                                    {{ $nias->sent_at?->format('d/m/Y H:i') ?? '—' }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            {{-- ── Dokumen Upload ───────────────────────────────── --}}
            <div class="col-md-6">
                <div class="card section-card h-100">
                    <div class="card-header py-2 px-3">
                        <span class="fw-bold text-primary small">
                            <i class="bi bi-paperclip me-1"></i>DOKUMEN UPLOAD
                        </span>
                    </div>
                    <div class="card-body">
                        @php
                            $docs = [
                                'file_kk'       => 'Kartu Keluarga (KK)',
                                'file_foto'     => 'Foto Atlet',
                                'file_akte'     => 'Akte Lahir',
                                'file_ijazah'   => 'Ijazah / Raport',
                                'file_sk_mutasi'=> 'SK Mutasi',
                            ];
                        @endphp
                        <div class="list-group list-group-flush">
                            @foreach($docs as $col => $label)
                            <div class="list-group-item px-0 py-2 d-flex justify-content-between align-items-center">
                                <span class="small text-muted">{{ $label }}</span>
                                @if($nias->$col)
                                    <a href="{{ route('nias.file', ['id' => $nias->ID, 'col' => $col]) }}"
                                       target="_blank"
                                       class="btn btn-sm btn-outline-primary py-0">
                                        <i class="bi bi-eye me-1"></i>Lihat
                                    </a>
                                @else
                                    <span class="badge bg-light text-muted border">Tidak ada</span>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

        </div>{{-- end .row --}}
    </div>
</div>
@endsection
