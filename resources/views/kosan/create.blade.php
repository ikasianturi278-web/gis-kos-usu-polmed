@extends('layouts.app')

@section('title', 'Tambah Kosan')
@section('page-title', 'Tambah Kosan Baru')

@push('styles')
<style>
    .section-divider {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--text-muted);
        margin: 24px 0 14px;
        padding-bottom: 8px;
        border-bottom: 2px solid var(--border);
    }

    .fasil-checks {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
    }

    .fasil-check-label {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 14px;
        border: 1.5px solid var(--border);
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.15s;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .fasil-check-label:hover { border-color: var(--primary-light); background: #f0f4ff; }

    .fasil-check-label input[type="checkbox"] {
        width: 18px; height: 18px;
        cursor: pointer;
        accent-color: var(--primary);
    }

    #map-picker {
        height: 280px;
        border-radius: 10px;
        border: 1.5px solid var(--border);
        margin-bottom: 8px;
    }

    .map-hint {
        font-size: 0.78rem;
        color: var(--text-muted);
        margin-bottom: 14px;
    }

    .map-hint i { color: var(--accent); }
</style>
@endpush

@section('content')

<div style="max-width: 860px;">

    <div style="margin-bottom: 16px;">
        <a href="{{ route('kosan.index') }}" class="btn btn-outline" style="font-size:0.82rem;">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card" style="padding: 28px;">

        <form method="POST" action="{{ route('kosan.store') }}">
            @csrf

            {{-- ======= INFO DASAR ======= --}}
            <div class="section-divider">📋 Informasi Dasar</div>

            <div class="form-row form-row-2">
                <div class="form-group">
                    <label class="form-label">Nama Kosan <span style="color:red;">*</span></label>
                    <input type="text" name="nama_kosan" class="form-control"
                           placeholder="Contoh: Kost Putri Melati" required
                           value="{{ old('nama_kosan') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Kategori Kosan <span style="color:red;">*</span></label>
                    <select name="kategori_kosan" class="form-control" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="Kos Putra"     {{ old('kategori_kosan') === 'Kos Putra' ? 'selected' : '' }}>Kos Putra</option>
                        <option value="Kos Putri"     {{ old('kategori_kosan') === 'Kos Putri' ? 'selected' : '' }}>Kos Putri</option>
                        <option value="Kos Campur"    {{ old('kategori_kosan') === 'Kos Campur' ? 'selected' : '' }}>Kos Campur</option>
                        <option value="Kos Eksklusif" {{ old('kategori_kosan') === 'Kos Eksklusif' ? 'selected' : '' }}>Kos Eksklusif</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi Singkat</label>
                <textarea name="deskripsi_singkat" class="form-control" rows="3"
                          placeholder="Deskripsikan kosan ini...">{{ old('deskripsi_singkat') }}</textarea>
            </div>

            <div class="form-row form-row-2">
                <div class="form-group">
                    <label class="form-label">Target Penghuni</label>
                    <input type="text" name="target_penghuni" class="form-control"
                           placeholder="Mahasiswi, Karyawan, dll."
                           value="{{ old('target_penghuni') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Jarak ke Kampus</label>
                    <input type="text" name="jarak_ke_kampus" class="form-control"
                           placeholder="Contoh: 0.5 km / 3 mnt"
                           value="{{ old('jarak_ke_kampus') }}">
                </div>
            </div>

            {{-- ======= LOKASI ======= --}}
            <div class="section-divider">📍 Lokasi</div>

            <div class="form-group">
                <label class="form-label">Alamat Lengkap <span style="color:red;">*</span></label>
                <input type="text" name="alamat_lengkap" class="form-control"
                       placeholder="Jl. Dr. Mansyur No. 12, Padang Bulan"
                       required value="{{ old('alamat_lengkap') }}">
            </div>

            <div class="form-row form-row-2">
                <div class="form-group">
                    <label class="form-label">Kelurahan</label>
                    <input type="text" name="kelurahan" class="form-control"
                           placeholder="Padang Bulan" value="{{ old('kelurahan') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Kecamatan</label>
                    <input type="text" name="kecamatan" class="form-control"
                           placeholder="Medan Baru" value="{{ old('kecamatan') }}">
                </div>
            </div>

            <div class="map-hint">
                <i class="fas fa-info-circle"></i>
                Klik peta di bawah untuk memilih koordinat lokasi kosan, atau isi manual.
            </div>

            <div id="map-picker"></div>

            <div class="form-row form-row-2">
                <div class="form-group">
                    <label class="form-label">Latitude <span style="color:red;">*</span></label>
                    <input type="number" name="latitude" id="lat" class="form-control"
                           step="any" placeholder="Contoh: 3.5660"
                           required value="{{ old('latitude') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Longitude <span style="color:red;">*</span></label>
                    <input type="number" name="longitude" id="lng" class="form-control"
                           step="any" placeholder="Contoh: 98.6535"
                           required value="{{ old('longitude') }}">
                </div>
            </div>

            {{-- ======= HARGA ======= --}}
            <div class="section-divider">💰 Harga & Kontak</div>

            <div class="form-row form-row-2">
                <div class="form-group">
                    <label class="form-label">Harga Sewa/Bulan</label>
                    <input type="text" name="harga_sewa_bulan" class="form-control"
                           placeholder="500.000 - 750.000"
                           value="{{ old('harga_sewa_bulan') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Keterangan Harga</label>
                    <input type="text" name="keterangan_harga" class="form-control"
                           placeholder="Per bulan, termasuk air"
                           value="{{ old('keterangan_harga') }}">
                </div>
            </div>

            <div class="form-row form-row-2">
                <div class="form-group">
                    <label class="form-label">No. Telp / WhatsApp</label>
                    <input type="text" name="no_telp_wa" class="form-control"
                           placeholder="0812-3456-7890"
                           value="{{ old('no_telp_wa') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Rating (0-5)</label>
                    <input type="number" name="rating" class="form-control"
                           min="0" max="5" step="0.1" placeholder="4.2"
                           value="{{ old('rating') }}">
                </div>
            </div>

            {{-- Operasional --}}
            <div class="form-row form-row-3">
                <div class="form-group">
                    <label class="form-label">Hari Operasional</label>
                    <input type="text" name="hari_operasional" class="form-control"
                           placeholder="Senin s/d Minggu"
                           value="{{ old('hari_operasional', 'Senin s/d Minggu') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Jam Buka</label>
                    <input type="text" name="jam_buka" class="form-control"
                           placeholder="06:00" value="{{ old('jam_buka', '06:00') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Jam Tutup</label>
                    <input type="text" name="jam_tutup" class="form-control"
                           placeholder="22:00" value="{{ old('jam_tutup', '22:00') }}">
                </div>
            </div>

            {{-- ======= FASILITAS ======= --}}
            <div class="section-divider">🛋️ Fasilitas</div>

            <div class="fasil-checks">
                @php
                    $fasilitas = [
                        'kasur_lemari'      => ['Kasur & Lemari', 'fas fa-bed'],
                        'meja_kursi'        => ['Meja & Kursi', 'fas fa-chair'],
                        'kamar_mandi_dalam' => ['Kamar Mandi Dalam', 'fas fa-bath'],
                        'ac'                => ['AC', 'fas fa-snowflake'],
                        'air_panas'         => ['Air Panas', 'fas fa-fire'],
                        'wifi'              => ['WiFi', 'fas fa-wifi'],
                        'parkir_motor'      => ['Parkir Motor', 'fas fa-motorcycle'],
                        'dapur_bersama'     => ['Dapur Bersama', 'fas fa-utensils'],
                        'laundry'           => ['Laundry', 'fas fa-tshirt'],
                        'cctv'              => ['CCTV', 'fas fa-video'],
                    ];
                @endphp

                @foreach($fasilitas as $field => [$label, $icon])
                    <label class="fasil-check-label">
                        <input type="hidden" name="{{ $field }}" value="Tidak">
                        <input type="checkbox" name="{{ $field }}" value="Ada"
                               {{ old($field) === 'Ada' ? 'checked' : '' }}>
                        <i class="{{ $icon }}"></i>
                        {{ $label }}
                    </label>
                @endforeach
            </div>

            {{-- ======= SUBMIT ======= --}}
            <div style="display:flex;gap:12px;margin-top:28px;padding-top:20px;border-top:1px solid var(--border);">
                <button type="submit" class="btn btn-success" style="padding:11px 28px;">
                    <i class="fas fa-save"></i> Simpan Kosan
                </button>
                <a href="{{ route('kosan.index') }}" class="btn btn-outline">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>

        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Peta picker untuk input koordinat
    const mapPicker = L.map('map-picker').setView([3.5669, 98.6538], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(mapPicker);

    // Marker USU & POLMED sebagai referensi
    L.marker([3.5669, 98.6535]).addTo(mapPicker).bindPopup('USU');
    L.marker([3.5700, 98.6530]).addTo(mapPicker).bindPopup('POLMED');

    let selectedMarker = null;
    const latInput = document.getElementById('lat');
    const lngInput = document.getElementById('lng');

    // Jika ada nilai lama, letakkan marker
    if (latInput.value && lngInput.value) {
        selectedMarker = L.marker([parseFloat(latInput.value), parseFloat(lngInput.value)], {
            draggable: true
        }).addTo(mapPicker);
        mapPicker.setView([parseFloat(latInput.value), parseFloat(lngInput.value)], 16);
    }

    mapPicker.on('click', function(e) {
        const lat = e.latlng.lat.toFixed(6);
        const lng = e.latlng.lng.toFixed(6);

        latInput.value = lat;
        lngInput.value = lng;

        if (selectedMarker) {
            selectedMarker.setLatLng(e.latlng);
        } else {
            selectedMarker = L.marker(e.latlng, { draggable: true }).addTo(mapPicker);
            selectedMarker.on('drag', function(ev) {
                latInput.value = ev.latlng.lat.toFixed(6);
                lngInput.value = ev.latlng.lng.toFixed(6);
            });
        }

        selectedMarker.bindPopup(`
            <b>Lokasi dipilih</b><br>
            Lat: ${lat}<br>
            Lng: ${lng}
        `).openPopup();
    });
</script>
@endpush