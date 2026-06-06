@extends('layouts.app')

@section('title', 'Peta Kosan')
@section('page-title', 'Peta Kosan')

@push('styles')
<style>
    .map-page { display: flex; gap: 0; height: calc(100vh - 130px); }

    .map-filter {
        background: white;
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 16px;
        margin-bottom: 16px;
        display: flex;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
    }

    .map-filter input, .map-filter select {
        padding: 8px 14px;
        border: 1.5px solid var(--border);
        border-radius: 8px;
        font-family: inherit;
        font-size: 0.85rem;
        color: var(--text);
        outline: none;
    }

    .map-filter input { flex: 1; min-width: 200px; }
    .map-filter input:focus, .map-filter select:focus { border-color: #2563eb; }

    #map {
        border-radius: 14px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.08);
        border: 1px solid var(--border);
        height: calc(100vh - 230px);
        min-height: 400px;
    }

    #kosan-count {
        background: white;
        border: 1.5px solid var(--border);
        padding: 7px 16px;
        border-radius: 20px;
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--text-muted);
    }

    /* Popup Leaflet custom */
    .kosan-popup h3 {
        font-weight: 800;
        font-size: 1rem;
        color: #1e293b;
        margin-bottom: 6px;
    }

    .kosan-popup .popup-address {
        color: #64748b;
        font-size: 0.8rem;
        margin-bottom: 8px;
    }

    .kosan-popup .popup-harga {
        font-weight: 700;
        color: #1a3a6e;
        font-size: 0.9rem;
        margin-bottom: 8px;
    }

    .kosan-popup .popup-fasil {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
        margin-bottom: 10px;
    }

    .kosan-popup .fasil-tag {
        background: #f1f5f9;
        padding: 2px 8px;
        border-radius: 10px;
        font-size: 0.72rem;
        font-weight: 600;
        color: #475569;
    }

    .kosan-popup .fasil-tag.ada { background: #dcfce7; color: #15803d; }

    .kosan-popup .popup-rating {
        display: flex;
        align-items: center;
        gap: 4px;
        color: #d97706;
        font-weight: 700;
        font-size: 0.85rem;
    }

    .popup-detail-btn {
        display: block;
        margin-top: 10px;
        padding: 7px 14px;
        background: #1a3a6e;
        color: white;
        text-decoration: none;
        border-radius: 7px;
        font-size: 0.8rem;
        font-weight: 600;
        text-align: center;
    }

    .leaflet-popup-content-wrapper { border-radius: 12px !important; }
    .leaflet-popup-content { margin: 16px; min-width: 220px; }

    /* Legenda */
    .map-legend {
        background: white;
        border-radius: 10px;
        padding: 14px 16px;
        font-size: 0.8rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.12);
        border: 1px solid #e2e8f0;
    }

    .legend-title { font-weight: 700; margin-bottom: 10px; color: #1e293b; }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 6px;
    }

    .legend-dot {
        width: 14px; height: 14px;
        border-radius: 50%;
        border: 2px solid rgba(0,0,0,0.2);
    }
</style>
@endpush

@section('content')

{{-- Filter Bar --}}
<div class="map-filter">
    <input type="text" id="searchInput" placeholder="🔍 Cari nama kosan..." value="{{ request('search') }}">

    <select id="kampusFilter">
        <option value="">Semua Kampus</option>
        <option value="USU"      {{ request('kampus') === 'USU' ? 'selected' : '' }}>Dekat USU</option>
        <option value="POLMED"   {{ request('kampus') === 'POLMED' ? 'selected' : '' }}>Dekat POLMED</option>
        <option value="Keduanya" {{ request('kampus') === 'Keduanya' ? 'selected' : '' }}>Keduanya</option>
    </select>

    <select id="kategoriFilter">
        <option value="">Semua Tipe</option>
        <option value="Kos Putra">Kos Putra</option>
        <option value="Kos Putri">Kos Putri</option>
        <option value="Kos Campur">Kos Campur</option>
        <option value="Kos Eksklusif">Kos Eksklusif</option>
    </select>

    <button onclick="applyFilter()" class="btn btn-primary" style="padding:8px 18px;">
        <i class="fas fa-filter"></i> Filter
    </button>

    <div id="kosan-count">0 kosan</div>
</div>

{{-- Map --}}
<div id="map"></div>

{{-- Data JSON --}}
<script id="kosan-data" type="application/json">
    {!! $kosanGeoJson->toJson() !!}
</script>

@endsection

@push('scripts')
<script>
    // Data kosan dari Laravel
    const allKosan = JSON.parse(document.getElementById('kosan-data').textContent);

    // Inisialisasi peta - center di area USU/POLMED Medan
    const map = L.map('map').setView([3.5658, 98.6538], 14);

    // Tile layer OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://openstreetmap.org">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Legenda
    const legend = L.control({ position: 'bottomright' });
    legend.onAdd = function() {
        const div = L.DomUtil.create('div', 'map-legend');
        div.innerHTML = `
            <div class="legend-title">🗺️ Legenda</div>
            <div class="legend-item"><div class="legend-dot" style="background:#3b82f6;"></div> Dekat USU</div>
            <div class="legend-item"><div class="legend-dot" style="background:#16a34a;"></div> Dekat POLMED</div>
            <div class="legend-item"><div class="legend-dot" style="background:#f97316;"></div> Dekat Keduanya</div>
            <div class="legend-item"><div class="legend-dot" style="background:#8b5cf6;"></div> Kampus</div>
        `;
        return div;
    };
    legend.addTo(map);

    // Marker kampus
    const campusIcon = (color, label) => L.divIcon({
        className: '',
        html: `<div style="background:${color};color:white;padding:5px 10px;border-radius:8px;font-size:11px;font-weight:700;white-space:nowrap;box-shadow:0 2px 8px rgba(0,0,0,0.3);">
                 🎓 ${label}
               </div>`,
        iconAnchor: [40, 14]
    });

    // Koordinat kampus
    L.marker([3.5669, 98.6535], { icon: campusIcon('#1d4ed8', 'USU') }).addTo(map)
        .bindPopup('<b>Universitas Sumatera Utara (USU)</b><br>Padang Bulan, Medan Baru');

    L.marker([3.5700, 98.6530], { icon: campusIcon('#15803d', 'POLMED') }).addTo(map)
        .bindPopup('<b>Politeknik Negeri Medan (POLMED)</b><br>Jl. Almamater, Padang Bulan');

    // Buat icon kustom untuk marker kosan
    function makeMarkerIcon(color) {
        return L.divIcon({
            className: '',
            html: `<div style="
                width: 28px; height: 28px;
                background: ${color};
                border: 3px solid white;
                border-radius: 50%;
                box-shadow: 0 2px 8px rgba(0,0,0,0.3);
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 12px;
            ">🏠</div>`,
            iconSize: [28, 28],
            iconAnchor: [14, 14],
            popupAnchor: [0, -16]
        });
    }

    const warnaPeta = {
        'blue':   '#3b82f6',
        'green':  '#16a34a',
        'orange': '#f97316',
        'purple': '#8b5cf6',
    };

    let markers = [];
    let markersLayer = L.layerGroup().addTo(map);

    // Render semua marker
    function renderMarkers(data) {
        markersLayer.clearLayers();
        markers = [];

        data.forEach(kosan => {
            if (!kosan.latitude || !kosan.longitude) return;

            const warna = warnaPeta[kosan.warna] || '#6b7280';
            const icon = makeMarkerIcon(warna);

            const marker = L.marker([kosan.latitude, kosan.longitude], { icon });

            // Popup konten
            const fasilList = [
                kosan.wifi === 'Ada' ? '<span class="fasil-tag ada">✓ WiFi</span>' : '',
                kosan.ac === 'Ada' ? '<span class="fasil-tag ada">✓ AC</span>' : '',
                kosan.parkir === 'Ada' ? '<span class="fasil-tag ada">✓ Parkir</span>' : '',
            ].filter(Boolean).join('');

            const popupContent = `
                <div class="kosan-popup">
                    <h3>${kosan.nama_kosan}</h3>
                    <div class="popup-address">📍 ${kosan.alamat_lengkap || '-'}</div>
                    <div class="popup-harga">💰 ${kosan.harga || '-'}</div>
                    <div class="popup-fasil">${fasilList || '<span class="fasil-tag">Fasilitas tidak tercatat</span>'}</div>
                    ${kosan.rating ? `<div class="popup-rating">⭐ ${parseFloat(kosan.rating).toFixed(1)}/5.0</div>` : ''}
                    <div style="margin-top:6px;font-size:0.78rem;color:#64748b;">
                        🚶 ${kosan.jarak || '-'} dari kampus
                    </div>
                    <a href="/kosan/${kosan.id}" class="popup-detail-btn">Lihat Detail →</a>
                </div>
            `;

            marker.bindPopup(popupContent, { maxWidth: 280 });
            markersLayer.addLayer(marker);
            markers.push({ marker, kosan });
        });

        document.getElementById('kosan-count').textContent = `${data.length} kosan`;
    }

    // Initial render
    renderMarkers(allKosan);

    // Filter real-time
    function applyFilter() {
        const search   = document.getElementById('searchInput').value.toLowerCase().trim();
        const kampus   = document.getElementById('kampusFilter').value;
        const kategori = document.getElementById('kategoriFilter').value;

        const filtered = allKosan.filter(k => {
            const matchSearch = !search ||
                k.nama_kosan.toLowerCase().includes(search) ||
                (k.alamat_lengkap && k.alamat_lengkap.toLowerCase().includes(search));

            const matchKampus = !kampus || k.label_kampus === kampus ||
                (kampus === 'USU' && (k.label_kampus === 'USU' || k.label_kampus === 'Keduanya')) ||
                (kampus === 'POLMED' && (k.label_kampus === 'POLMED' || k.label_kampus === 'Keduanya')) ||
                (kampus === 'Keduanya' && k.label_kampus === 'Keduanya');

            const matchKategori = !kategori || k.kategori_kosan === kategori;

            return matchSearch && matchKampus && matchKategori;
        });

        renderMarkers(filtered);

        // Zoom ke hasil jika ada
        if (filtered.length > 0 && filtered.length < 20) {
            const lats = filtered.map(k => k.latitude);
            const lngs = filtered.map(k => k.longitude);
            const bounds = [[Math.min(...lats), Math.min(...lngs)], [Math.max(...lats), Math.max(...lngs)]];
            map.fitBounds(bounds, { padding: [40, 40] });
        }
    }

    // Search dengan Enter atau real-time (debounce)
    let debounceTimer;
    document.getElementById('searchInput').addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(applyFilter, 400);
    });

    document.getElementById('kampusFilter').addEventListener('change', applyFilter);
    document.getElementById('kategoriFilter').addEventListener('change', applyFilter);
</script>
@endpush