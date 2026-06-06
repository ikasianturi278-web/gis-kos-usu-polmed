@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('styles')
<style>
    .hero-card {
        background: linear-gradient(135deg, #1a3a6e 0%, #2563eb 60%, #3b82f6 100%);
        border-radius: 16px;
        padding: 36px 40px;
        color: white;
        position: relative;
        overflow: hidden;
        margin-bottom: 28px;
    }

    .hero-card::before {
        content: '';
        position: absolute;
        right: -40px; top: -40px;
        width: 220px; height: 220px;
        background: rgba(255,255,255,0.07);
        border-radius: 50%;
    }

    .hero-card::after {
        content: '';
        position: absolute;
        right: 60px; bottom: -60px;
        width: 160px; height: 160px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
    }

    .hero-card h2 {
        font-size: 1.9rem;
        font-weight: 800;
        margin-bottom: 10px;
        position: relative;
    }

    .hero-card p {
        opacity: 0.82;
        font-size: 0.95rem;
        max-width: 480px;
        line-height: 1.6;
        position: relative;
    }

    .hero-buttons {
        display: flex;
        gap: 12px;
        margin-top: 24px;
        position: relative;
    }

    .hero-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 22px;
        border-radius: 10px;
        font-size: 0.88rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
    }

    .hero-btn-white {
        background: white;
        color: #1a3a6e;
    }

    .hero-btn-white:hover { background: #f0f4ff; }

    .hero-btn-outline {
        background: transparent;
        color: white;
        border: 2px solid rgba(255,255,255,0.5);
    }

    .hero-btn-outline:hover { border-color: white; background: rgba(255,255,255,0.1); }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 28px;
    }

    .stat-card {
        background: white;
        border-radius: 14px;
        padding: 22px 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        border: 1px solid var(--border);
        box-shadow: 0 1px 4px rgba(0,0,0,0.05);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    }

    .stat-icon {
        width: 52px; height: 52px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .stat-icon-blue   { background: #dbeafe; }
    .stat-icon-green  { background: #dcfce7; }
    .stat-icon-orange { background: #ffedd5; }
    .stat-icon-yellow { background: #fef9c3; }

    .stat-info { flex: 1; }

    .stat-value {
        font-size: 2rem;
        font-weight: 800;
        color: var(--text);
        line-height: 1;
        margin-bottom: 4px;
    }

    .stat-label {
        font-size: 0.78rem;
        color: var(--text-muted);
        font-weight: 500;
    }

    .bottom-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
    }

    .kosan-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 0;
        border-bottom: 1px solid var(--border);
    }

    .kosan-item:last-child { border-bottom: none; }

    .kosan-avatar {
        width: 44px; height: 44px;
        border-radius: 10px;
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .kosan-name {
        font-weight: 700;
        font-size: 0.9rem;
        color: var(--text);
    }

    .kosan-address {
        font-size: 0.78rem;
        color: var(--text-muted);
        margin-top: 2px;
    }

    .kosan-rating {
        margin-left: auto;
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 0.82rem;
        font-weight: 700;
        color: #d97706;
    }

    .kategori-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 11px 0;
        border-bottom: 1px solid var(--border);
        font-size: 0.86rem;
    }

    .kategori-item:last-child { border-bottom: none; }

    .kategori-bar-wrap { flex: 1; margin: 0 12px; }

    .kategori-bar {
        height: 6px;
        border-radius: 4px;
        background: #e2e8f0;
        overflow: hidden;
    }

    .kategori-bar-fill { height: 100%; border-radius: 4px; }

    @media (max-width: 1024px) {
        .stats-grid { grid-template-columns: 1fr 1fr; }
        .bottom-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')

{{-- Hero Banner --}}
<div class="hero-card">
    <h2>🏠 Selamat Datang di KosanMaps</h2>
    <p>Temukan kosan terbaik di sekitar USU dan Politeknik Negeri Medan dengan mudah. Cari, bandingkan, dan temukan tempat tinggal yang sesuai kebutuhanmu.</p>
    <div class="hero-buttons">
        <a href="{{ route('maps') }}" class="hero-btn hero-btn-white">
            <i class="fas fa-map"></i> Buka Peta
        </a>
        <a href="{{ route('kosan.index') }}" class="hero-btn hero-btn-outline">
            <i class="fas fa-list"></i> Lihat Semua Kosan
        </a>
    </div>
</div>

{{-- Statistik Cards --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon stat-icon-blue">🏠</div>
        <div class="stat-info">
            <div class="stat-value">{{ $totalKosan }}</div>
            <div class="stat-label">Total Kosan</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon stat-icon-green">🎓</div>
        <div class="stat-info">
            <div class="stat-value">{{ $dekatUsu }}</div>
            <div class="stat-label">Dekat USU</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon stat-icon-orange">🏛️</div>
        <div class="stat-info">
            <div class="stat-value">{{ $dekatPolmed }}</div>
            <div class="stat-label">Dekat POLMED</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon stat-icon-yellow">⭐</div>
        <div class="stat-info">
            <div class="stat-value">{{ $dekatKeduanya }}</div>
            <div class="stat-label">Dekat Keduanya</div>
        </div>
    </div>
</div>

{{-- Bottom Grid --}}
<div class="bottom-grid">

    {{-- Kosan Terbaru --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">📋 Kosan Terbaru</span>
            <a href="{{ route('kosan.index') }}" class="btn btn-outline" style="font-size:0.78rem;padding:6px 14px;">
                Lihat Semua
            </a>
        </div>
        <div class="card-body" style="padding: 8px 24px;">
            @forelse($kosanTerbaru as $kosan)
                <div class="kosan-item">
                    <div class="kosan-avatar">🏠</div>
                    <div style="flex:1;min-width:0;">
                        <div class="kosan-name">{{ $kosan->nama_kosan }}</div>
                        <div class="kosan-address">{{ Str::limit($kosan->alamat_lengkap, 50) }}</div>
                        <div style="margin-top:4px;">
                            @php
                                $labelKampus = $kosan->label_kampus;
                                $badgeClass = match($labelKampus) {
                                    'USU' => 'badge-usu',
                                    'POLMED' => 'badge-polmed',
                                    'Keduanya' => 'badge-keduanya',
                                    default => 'badge-lainnya'
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ $labelKampus }}</span>
                            <span class="badge" style="margin-left:4px;background:#f1f5f9;color:#475569;">
                                {{ $kosan->kategori_kosan }}
                            </span>
                        </div>
                    </div>
                    @if($kosan->rating)
                        <div class="kosan-rating">
                            <i class="fas fa-star" style="font-size:0.75rem;"></i>
                            {{ number_format($kosan->rating, 1) }}
                        </div>
                    @endif
                </div>
            @empty
                <div style="text-align:center;padding:40px;color:var(--text-muted);">
                    <div style="font-size:2.5rem;">🏠</div>
                    <p style="margin-top:10px;">Belum ada data kosan</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Statistik Kategori --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">📊 Kategori Kosan</span>
        </div>
        <div class="card-body" style="padding: 8px 24px;">
            @php
                $kategoriColors = [
                    'Kos Putri'    => '#be185d',
                    'Kos Putra'    => '#0369a1',
                    'Kos Campur'   => '#6d28d9',
                    'Kos Eksklusif'=> '#854d0e',
                ];
            @endphp

            @foreach($statistikKategori as $kat)
                <div class="kategori-item">
                    <span style="font-weight:600;font-size:0.82rem;min-width:110px;">{{ $kat->kategori_kosan }}</span>
                    <div class="kategori-bar-wrap">
                        <div class="kategori-bar">
                            <div class="kategori-bar-fill"
                                 style="width:{{ $totalKosan > 0 ? ($kat->total / $totalKosan * 100) : 0 }}%;
                                        background:{{ $kategoriColors[$kat->kategori_kosan] ?? '#6d28d9' }};">
                            </div>
                        </div>
                    </div>
                    <span style="font-weight:700;font-size:0.88rem;min-width:24px;text-align:right;">{{ $kat->total }}</span>
                </div>
            @endforeach

            @if($statistikKategori->isEmpty())
                <p style="text-align:center;padding:30px;color:var(--text-muted);font-size:0.85rem;">
                    Belum ada data
                </p>
            @endif
        </div>
    </div>

</div>

@endsection