<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kosan extends Model
{
    use HasFactory;

    /**
     * Nama tabel di PostgreSQL (sesuai SQL pgAdmin)
     */
    protected $table = 'kosan';

    /**
     * Matikan timestamps otomatis (tabel tidak punya created_at/updated_at)
     */
    public $timestamps = false;

    /**
     * Kolom yang boleh diisi (mass assignment)
     */
    protected $fillable = [
        'no',
        'nama_kosan',
        'deskripsi_singkat',
        'jarak_ke_kampus',
        'latitude',
        'longitude',
        'alamat_lengkap',
        'kecamatan',
        'kelurahan',
        'kategori_kosan',
        'target_penghuni',
        'kasur_lemari',
        'meja_kursi',
        'kamar_mandi_dalam',
        'ac',
        'air_panas',
        'wifi',
        'parkir_motor',
        'dapur_bersama',
        'laundry',
        'cctv',
        'jam_buka',
        'jam_tutup',
        'hari_operasional',
        'harga_sewa_bulan',
        'keterangan_harga',
        'no_telp_wa',
        'rating',
    ];

    /**
     * Cast tipe data
     */
    protected $casts = [
        'latitude'  => 'float',
        'longitude' => 'float',
        'rating'    => 'float',
    ];

    /**
     * Scope: filter berdasarkan kampus (kategori kosan atau kelurahan area USU/POLMED)
     * USU ada di area Padang Bulan / Medan Baru / Medan Selayang
     * POLMED ada di area Padang Bulan Selayang (Jl. Almamater)
     */
    public function scopeDekatUsu($query)
    {
        // Koordinat USU: sekitar lat 3.5665, lng 98.6538
        // Kosan dengan jarak < 2km dianggap dekat USU
        return $query->whereBetween('latitude', [3.550, 3.580])
                     ->whereBetween('longitude', [98.640, 98.670]);
    }

    public function scopeDekatPolmed($query)
    {
        // Koordinat POLMED: sekitar lat 3.5700, lng 98.6530
        return $query->whereBetween('latitude', [3.555, 3.585])
                     ->whereBetween('longitude', [98.645, 98.665]);
    }

    /**
     * Helper: cek apakah dekat kedua kampus
     */
    public function isDekatKeduanya(): bool
    {
        return $this->isDekatUsu() && $this->isDekatPolmed();
    }

    public function isDekatUsu(): bool
    {
        return $this->latitude >= 3.550 && $this->latitude <= 3.580
            && $this->longitude >= 98.640 && $this->longitude <= 98.670;
    }

    public function isDekatPolmed(): bool
    {
        return $this->latitude >= 3.555 && $this->latitude <= 3.585
            && $this->longitude >= 98.645 && $this->longitude <= 98.665;
    }

    /**
     * Helper: warna marker peta berdasarkan lokasi
     */
    public function getWarnaPetaAttribute(): string
    {
        if ($this->isDekatUsu() && $this->isDekatPolmed()) return 'orange';
        if ($this->isDekatUsu()) return 'blue';
        if ($this->isDekatPolmed()) return 'green';
        return 'purple';
    }

    /**
     * Helper: label kampus
     */
    public function getLabelKampusAttribute(): string
    {
        if ($this->isDekatUsu() && $this->isDekatPolmed()) return 'Keduanya';
        if ($this->isDekatUsu()) return 'USU';
        if ($this->isDekatPolmed()) return 'POLMED';
        return 'Lainnya';
    }
}