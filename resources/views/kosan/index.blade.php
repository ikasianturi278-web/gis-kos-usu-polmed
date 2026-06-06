@extends('layouts.app')

@section('title', 'Data Kosan')
@section('page-title', 'Data Kosan')

@push('styles')
<style>
    .filter-bar {
        background: white;
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 18px 20px;
        margin-bottom: 20px;
        display: flex;
        gap: 12px;
        align-items: center;
        flex-wrap: wrap;
    }

    .search-box {
        position: relative;
        flex: 1;
        min-width: 200px;
    }

    .search-box i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        font-size: 0.9rem;
    }

    .search-box input {
        padding-left: 36px !important;
    }

    .nama-kosan-cell { font-weight: 700; color: var(--text); }
    .nama-kosan-cell small { display: block; font-weight: 400; color: var(--text-muted); font-size: 0.75rem; margin-top: 2px; }

    .fasil-cell { display: flex; gap: 4px; }

    .harga-cell { font-weight: 600; color: var(--primary); font-size: 0.82rem; }

    .rating-cell {
        display: flex;
        align-items: center;
        gap: 4px;
        font-weight: 700;
        color: #d97706;
        font-size: 0.85rem;
    }

    .action-cell { display: flex; gap: 6px; align-items: center; }

    .btn-sm { padding: 6px 12px; font-size: 0.78rem; }
    .btn-icon { width: 30px; height: 30px; padding: 0; justify-content: center; }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--text-muted);
    }

    .empty-state .empty-icon { font-size: 3rem; margin-bottom: 12px; }
    .empty-state p { font-size: 0.9rem; }
</style>
@endpush

@section('content')

{{-- Filter Bar --}}
<form method="GET" action="{{ route('kosan.index') }}">
    <div class="filter-bar">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" name="search" class="form-control"
                   placeholder="Cari nama kosan, alamat..."
                   value="{{ request('search') }}">
        </div>

        <select name="kampus" class="form-control" style="width:160px;">
            <option value="">Semua Kampus</option>
            <option value="USU"      {{ request('kampus') === 'USU' ? 'selected' : '' }}>Dekat USU</option>
            <option value="POLMED"   {{ request('kampus') === 'POLMED' ? 'selected' : '' }}>Dekat POLMED</option>
            <option value="Keduanya" {{ request('kampus') === 'Keduanya' ? 'selected' : '' }}>Keduanya</option>
        </select>

        <select name="kategori" class="form-control" style="width:160px;">
            <option value="">Semua Tipe</option>
            <option value="Kos Putra"     {{ request('kategori') === 'Kos Putra' ? 'selected' : '' }}>Kos Putra</option>
            <option value="Kos Putri"     {{ request('kategori') === 'Kos Putri' ? 'selected' : '' }}>Kos Putri</option>
            <option value="Kos Campur"    {{ request('kategori') === 'Kos Campur' ? 'selected' : '' }}>Kos Campur</option>
            <option value="Kos Eksklusif" {{ request('kategori') === 'Kos Eksklusif' ? 'selected' : '' }}>Kos Eksklusif</option>
        </select>

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-filter"></i> Filter
        </button>

        @if(request()->anyFilled(['search','kampus','kategori']))
            <a href="{{ route('kosan.index') }}" class="btn btn-outline">
                <i class="fas fa-times"></i> Reset
            </a>
        @endif

        <a href="{{ route('kosan.create') }}" class="btn btn-success" style="margin-left:auto;">
            <i class="fas fa-plus"></i> Tambah Kosan
        </a>
    </div>
</form>

{{-- Tabel --}}
<div class="card">
    <div class="card-header">
        <span class="card-title">
            Daftar Kosan
            <span style="font-size:0.82rem;font-weight:500;color:var(--text-muted);margin-left:8px;">
                ({{ $kosanList->total() }} kosan ditemukan)
            </span>
        </span>
    </div>

    <div style="overflow-x:auto;">
        <table class="table">
            <thead>
                <tr>
                    <th style="width:40px;">No</th>
                    <th>Nama Kosan</th>
                    <th>Alamat</th>
                    <th>Kampus</th>
                    <th>Kategori</th>
                    <th>Harga/Bulan</th>
                    <th>Rating</th>
                    <th>Fasilitas</th>
                    <th style="width:100px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kosanList as $k)
                    @php
                        $labelKampus = $k->label_kampus;
                        $badgeKampus = match($labelKampus) {
                            'USU' => 'badge-usu',
                            'POLMED' => 'badge-polmed',
                            'Keduanya' => 'badge-keduanya',
                            default => 'badge-lainnya'
                        };
                        $badgeKat = match($k->kategori_kosan) {
                            'Kos Putri' => 'badge-putri',
                            'Kos Putra' => 'badge-putra',
                            'Kos Campur' => 'badge-campur',
                            'Kos Eksklusif' => 'badge-eksklusif',
                            default => 'badge-lainnya'
                        };
                    @endphp
                    <tr>
                        <td style="color:var(--text-muted);font-size:0.8rem;">{{ $k->no }}</td>

                        <td>
                            <div class="nama-kosan-cell">
                                {{ $k->nama_kosan }}
                                <small>{{ $k->jarak_ke_kampus ?? '-' }} ke kampus</small>
                            </div>
                        </td>

                        <td style="max-width:200px;font-size:0.82rem;color:var(--text-muted);">
                            {{ Str::limit($k->alamat_lengkap, 45) }}
                        </td>

                        <td><span class="badge {{ $badgeKampus }}">{{ $labelKampus }}</span></td>

                        <td><span class="badge {{ $badgeKat }}">{{ $k->kategori_kosan }}</span></td>

                        <td>
                            <div class="harga-cell">{{ $k->harga_sewa_bulan ?? '-' }}</div>
                        </td>

                        <td>
                            @if($k->rating)
                                <div class="rating-cell">
                                    <i class="fas fa-star" style="font-size:0.75rem;"></i>
                                    {{ number_format($k->rating, 1) }}
                                </div>
                            @else
                                <span style="color:var(--text-muted);font-size:0.82rem;">-</span>
                            @endif
                        </td>

                        <td>
                            <div class="fasil-cell">
                                <span class="fasil-icon {{ $k->wifi === 'Ada' ? 'fasil-ada' : 'fasil-tidak' }}"
                                      title="WiFi: {{ $k->wifi }}">
                                    <i class="fas fa-wifi"></i>
                                </span>
                                <span class="fasil-icon {{ $k->ac === 'Ada' ? 'fasil-ada' : 'fasil-tidak' }}"
                                      title="AC: {{ $k->ac }}">
                                    <i class="fas fa-snowflake"></i>
                                </span>
                                <span class="fasil-icon {{ $k->parkir_motor === 'Ada' ? 'fasil-ada' : 'fasil-tidak' }}"
                                      title="Parkir: {{ $k->parkir_motor }}">
                                    <i class="fas fa-motorcycle"></i>
                                </span>
                            </div>
                        </td>

                        <td>
                            <div class="action-cell">
                                <a href="{{ route('kosan.show', $k->id) }}"
                                   class="btn btn-outline btn-sm btn-icon"
                                   title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <form method="POST" action="{{ route('kosan.destroy', $k->id) }}"
                                      onsubmit="return confirm('Yakin hapus {{ addslashes($k->nama_kosan) }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm btn-icon" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9">
                            <div class="empty-state">
                                <div class="empty-icon">🏠</div>
                                <p><strong>Tidak ada kosan ditemukan</strong></p>
                                <p>Coba ubah kata kunci pencarian atau filter</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($kosanList->hasPages())
        <div style="padding: 0 20px;">
            <div class="pagination-wrapper">
                <span>
                    Menampilkan {{ $kosanList->firstItem() }}–{{ $kosanList->lastItem() }}
                    dari {{ $kosanList->total() }} kosan
                </span>
                {{ $kosanList->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>

@endsection