@extends('layouts.app')
@section('title','Data NIAS')

@section('content')
<div class="card page-card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="mb-0"><i class="bi bi-table me-2"></i>Data NIAS — POSSI Jawa Timur</h5>
        <a href="{{ route('nias.create') }}" class="btn btn-warning btn-sm fw-semibold">
            <i class="bi bi-person-plus me-1"></i>Daftar Baru
        </a>
    </div>

    <div class="card-body p-3">

        {{-- Search bar --}}
        <form method="GET" action="{{ route('nias.index') }}" class="row g-2 mb-3">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0"
                           placeholder="Cari nama, No. NIAS, atau klub…"
                           value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-auto d-flex gap-1">
                <button type="submit" class="btn btn-primary">Cari</button>
                @if(request('search'))
                    <a href="{{ route('nias.index') }}" class="btn btn-outline-secondary">Reset</a>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-nias table-bordered table-sm align-middle mb-2">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>No. NIAS</th>
                        <th>Nama</th>
                        <th>L/P</th>
                        <th>Tgl Lahir</th>
                        <th>Klub</th>
                        <th>Kota / Kab Domisili</th>
                        <th>Tgl Daftar</th>
                        <th>Expired</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $r)
                    <tr>
                        <td class="text-muted small">{{ $records->firstItem() + $loop->index }}</td>
                        <td><code class="small">{{ $r->NONIAS ?? '—' }}</code></td>
                        <td class="fw-semibold">{{ $r->NAMA }}</td>
                        <td>
                            @if($r->GENDER === 'L')
                                <span class="badge bg-primary">L</span>
                            @else
                                <span class="badge bg-danger">P</span>
                            @endif
                        </td>
                        <td class="small">{{ $r->TGLLAHIR?->format('d/m/Y') }}</td>
                        <td class="small">{{ $r->NAMACLUB }}</td>
                        <td class="small">
                            <span class="text-muted">{{ $r->JENISDOM }}</span>
                            {{ $r->NAMAKOTADOM }}
                        </td>
                        <td class="small">{{ $r->TGLDAFTAR?->format('d/m/Y') }}</td>
                        <td class="small {{ $r->EXPIRED?->isPast() ? 'text-danger fw-semibold' : '' }}">
                            {{ $r->EXPIRED?->format('d/m/Y') }}
                        </td>
                        <td>
                            @if($r->STATUS == 1 && !$r->EXPIRED?->isPast())
                                <span class="badge badge-aktif">AKTIF</span>
                            @else
                                <span class="badge badge-expired">EXPIRED</span>
                            @endif
                        </td>
                        <td class="text-center" style="white-space:nowrap">
                            <a href="{{ route('nias.show', $r) }}"
                               class="btn btn-sm btn-outline-primary py-0" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('nias.edit', $r) }}"
                               class="btn btn-sm btn-outline-warning py-0" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('nias.destroy', $r) }}"
                                  class="d-inline"
                                  onsubmit="return confirm('Hapus data {{ addslashes($r->NAMA) }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger py-0" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center text-muted py-5">
                            <i class="bi bi-inbox fs-2 d-block mb-2 opacity-50"></i>
                            Belum ada data NIAS terdaftar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <small class="text-muted">
                @if($records->total())
                    Menampilkan {{ $records->firstItem() }}–{{ $records->lastItem() }}
                    dari <strong>{{ $records->total() }}</strong> data
                @endif
            </small>
            {{ $records->links() }}
        </div>

    </div>
</div>
@endsection
