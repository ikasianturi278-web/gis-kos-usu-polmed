@extends('layouts.app')

@section('title', $kosan->nama_kosan)
@section('page-title', 'Detail Kosan')

@push('styles')
<style>
    .detail-grid { display: grid; grid-template-columns: 1fr 340px; gap: 20px; }

    .info-section { margin-bottom: 24px; }
    .info-title {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--text-muted);
        margin-bottom: 14px;
        padding-bottom: 8px;
        border-bottom: 2px solid var(--border);
    }

    .info-row {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .info-row:last-child { border-bottom: none; }
    .info-label { width: 140px; font-size: 0.82rem; color: var(--text-muted); flex-shrink: 0; }
    .info-value { font-size: 0.88rem; font-weight: 600; color: var(--text); flex: 1; }

    .fasil-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }

    .fasil-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        border-radius: 10px;
        font-size: 0.84rem;
        font-weight: 500;
    }

    .fasil-item.ada { background: #dcfce7; color: #15803d; }
    .fasil-item.tidak { background: #f1f5f9; color: #94a3b8; }
    .fasil-item i { font-size: 1rem; }

    .kosan-name-big {
        font-size: 1.6rem;
        font-weight: 800;
        color: var(--text);
        margin-bottom: 8px;
    }

    .kosan-meta {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 16px;
    }

    #detail-map { height: 220px; border-radius: 10px; margin-bottom: 16px; }

    .harga-box {
        background: linear-gradient(135deg, #1a3a6e, #2563eb);
        color: white;
        border-radius: 12px;
        padding: 18px 20px;
        margin-bottom: 16px;
    }

    .harga-box .label { font-size: 0.75rem; opacity: 0.75; margin-bottom: 4px; }
    .harga-box .value { font-size: 1.1rem; font-weight: 800; }
    .harga-box .keterangan { font-size: 0.75rem; opacity: 0.7; margin-top: 4px; }
</style>
@endpush

@section('content')

<div style="margin-bottom: 16px;">
    <a href="{{ route('kosan.index') }}" class="btn btn-outline" style="font-size:0.82rem;">
        <i class="fas fa-arrow-left"></i> Kembali ke Data Kosan
    </a>
</div>

<div class="detail-grid">

    {{-- KIRI: Info Detail --}}
    <div>
        <div class="card" style="padding: 28px;">

            <div class="kosan-name-big">{{ $kosan->nama_kosan }}</div>
            <div class="kosan-meta">
                @php
                    $labelKampus = $kosan->label_kampus;
                    $badgeKampus = match($labelKampus) {
                        'USU' => 'badge-usu', 'POLMED' => 'badge-polmed',
                        'Keduanya' => 'badge-keduanya', default => 'badge-lainnya'
                    };
                    $badgeKat = match($kosan->kategori_kosan) {
                        'Kos Putri' => 'badge-putri', 'Kos Putra' => 'badge-putra',
                        'Kos Campur' => 'badge-campur', 'Kos Eksklusif' => 'badge-eksklusif',
                        default => 'badge-lainnya'
                    };
                @endphp
                <span class="badge {{ $badgeKampus }}">{{ $labelKampus }}</span>
                <span class="badge {{ $badgeKat }}">{{ $kosan->kategori_kosan }}</span>
                @if($kosan->rating)
                    <span class="badge" style="background:#fef9c3;color:#854d0e;">
                        ⭐ {{ number_format($kosan->rating, 1) }}/5.0
                    </span>
                @endif
            </div>

            @if($kosan->deskripsi_singkat)
                <p style="color:var(--text-muted);font-size:0.9rem;line-height:1.7;margin-bottom:24px;">
                    {{ $kosan->deskripsi_singkat }}
                </p>
            @endif

            {{-- Info Umum --}}
            <div class="info-section">
                <div class="info-title">📍 Informasi Lokasi</div>
                <div class="info-row">
                    <span class="info-label">Alamat Lengkap</span>
                    <span class="info-value">{{ $kosan->alamat_lengkap ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Kelurahan</span>
                    <span class="info-value">{{ $kosan->kelurahan ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Kecamatan</span>
                    <span class="info-value">{{ $kosan->kecamatan ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Jarak ke Kampus</span>
                    <span class="info-value">{{ $kosan->jarak_ke_kampus ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Target Penghuni</span>
                    <span class="info-value">{{ $kosan->target_penghuni ?? '-' }}</span>
                </div>
            </div>

            {{-- Operasional --}}
            <div class="info-section">
                <div class="info-title">🕐 Operasional</div>
                <div class="info-row">
                    <span class="info-label">Hari Operasional</span>
                    <span class="info-value">{{ $kosan->hari_operasional ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Jam Buka</span>
                    <span class="info-value">{{ $kosan->jam_buka ?? '-' }} – {{ $kosan->jam_tutup ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">No. Telp/WA</span>
                    <span class="info-value">
                        @if($kosan->no_telp_wa && $kosan->no_telp_wa !== '–')
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $kosan->no_telp_wa) }}"
                               target="_blank" style="color:var(--primary);text-decoration:none;">
                                <i class="fab fa-whatsapp" style="color:#25d366;"></i>
                                {{ $kosan->no_telp_wa }}
                            </a>
                        @else
                            -
                        @endif
                    </span>
                </div>
            </div>

            {{-- Fasilitas --}}
            <div class="info-section">
                <div class="info-title">🛋️ Fasilitas</div>
                <div class="fasil-grid">
                    @php
                        $fasilitas = [
                            'Kasur & Lemari'    => [$kosan->kasur_lemari, 'fas fa-bed'],
                            'Meja & Kursi'      => [$kosan->meja_kursi, 'fas fa-chair'],
                            'Kamar Mandi Dalam' => [$kosan->kamar_mandi_dalam, 'fas fa-bath'],
                            'AC'                => [$kosan->ac, 'fas fa-snowflake'],
                            'Air Panas'         => [$kosan->air_panas, 'fas fa-fire'],
                            'WiFi'              => [$kosan->wifi, 'fas fa-wifi'],
                            'Parkir Motor'      => [$kosan->parkir_motor, 'fas fa-motorcycle'],
                            'Dapur Bersama'     => [$kosan->dapur_bersama, 'fas fa-utensils'],
                            'Laundry'           => [$kosan->laundry, 'fas fa-tshirt'],
                            'CCTV'              => [$kosan->cctv, 'fas fa-video'],
                        ];
                    @endphp

                    @foreach($fasilitas as $nama => [$status, $icon])
                        <div class="fasil-item {{ $status === 'Ada' ? 'ada' : 'tidak' }}">
                            <i class="{{ $icon }}"></i>
                            <span>{{ $nama }}</span>
                            @if($status === 'Ada')
                                <i class="fas fa-check-circle" style="margin-left:auto;"></i>
                            @else
                                <i class="fas fa-times-circle" style="margin-left:auto;opacity:0.4;"></i>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    {{-- KANAN: Harga + Peta --}}
    <div>

        {{-- Harga --}}
        <div class="harga-box">
            <div class="label">Harga Sewa</div>
            <div class="value">{{ $kosan->harga_sewa_bulan ?? 'Hubungi pemilik' }}</div>
            @if($kosan->keterangan_harga)
                <div class="keterangan">{{ $kosan->keterangan_harga }}</div>
            @endif
        </div>

        {{-- Mini Peta --}}
        @if($kosan->latitude && $kosan->longitude)
            <div class="card" style="overflow:hidden;margin-bottom:16px;">
                <div id="detail-map"></div>
                <div style="padding:12px 16px;font-size:0.78rem;color:var(--text-muted);border-top:1px solid var(--border);">
                    <i class="fas fa-map-marker-alt" style="color:#dc2626;"></i>
                    {{ $kosan->latitude }}, {{ $kosan->longitude }}
                </div>
            </div>
        @endif

        {{-- Tombol Aksi --}}
        <div style="display:flex;flex-direction:column;gap:10px;">
            <a href="{{ route('maps') }}" class="btn btn-primary" style="width:100%;justify-content:center;">
                <i class="fas fa-map"></i> Lihat di Peta
            </a>

            @if($kosan->no_telp_wa && $kosan->no_telp_wa !== '–')
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $kosan->no_telp_wa) }}"
                   target="_blank"
                   class="btn"
                   style="width:100%;justify-content:center;background:#25d366;color:white;">
                    <i class="fab fa-whatsapp"></i> Hubungi via WhatsApp
                </a>
            @endif

            <form method="POST" action="{{ route('kosan.destroy', $kosan->id) }}"
                  onsubmit="return confirm('Yakin hapus {{ addslashes($kosan->nama_kosan) }}?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" style="width:100%;justify-content:center;">
                    <i class="fas fa-trash"></i> Hapus Kosan
                </button>
            </form>
        </div>
    </div>

</div>

@endsection

@push('scripts')
@if($kosan->latitude && $kosan->longitude)
<script>
    const detailMap = L.map('detail-map').setView([{{ $kosan->latitude }}, {{ $kosan->longitude }}], 16);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(detailMap);

    const icon = L.divIcon({
        className: '',
        html: `<div style="background:#dc2626;color:white;padding:6px 10px;border-radius:8px;font-size:12px;font-weight:700;box-shadow:0 2px 8px rgba(0,0,0,0.3);">
                 🏠 {{ addslashes($kosan->nama_kosan) }}
               </div>`,
        iconAnchor: [0, 14]
    });

    L.marker([{{ $kosan->latitude }}, {{ $kosan->longitude }}], { icon })
        .addTo(detailMap)
        .bindPopup('<b>{{ addslashes($kosan->nama_kosan) }}</b><br>{{ addslashes($kosan->alamat_lengkap ?? '') }}')
        .openPopup();
</script>
@endif
@endpush