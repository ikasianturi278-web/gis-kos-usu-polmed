<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel ini disesuaikan dengan SQL pgAdmin: kosan_usu_polmed_postgresql.sql
     */
    public function up(): void
    {
        Schema::create('kosan', function (Blueprint $table) {
            $table->id();
            $table->integer('no');
            $table->string('nama_kosan', 100);
            $table->text('deskripsi_singkat')->nullable();
            $table->string('jarak_ke_kampus', 50)->nullable();
            $table->decimal('latitude', 10, 6)->nullable();
            $table->decimal('longitude', 10, 6)->nullable();
            $table->string('alamat_lengkap', 200)->nullable();
            $table->string('kecamatan', 100)->nullable();
            $table->string('kelurahan', 100)->nullable();
            $table->string('kategori_kosan', 50)->nullable(); // Kos Putra, Kos Putri, Kos Campur, Kos Eksklusif
            $table->string('target_penghuni', 100)->nullable();
            $table->string('kasur_lemari', 10)->default('Tidak');
            $table->string('meja_kursi', 10)->default('Tidak');
            $table->string('kamar_mandi_dalam', 10)->default('Tidak');
            $table->string('ac', 10)->default('Tidak');
            $table->string('air_panas', 10)->default('Tidak');
            $table->string('wifi', 10)->default('Tidak');
            $table->string('parkir_motor', 10)->default('Tidak');
            $table->string('dapur_bersama', 10)->default('Tidak');
            $table->string('laundry', 10)->default('Tidak');
            $table->string('cctv', 10)->default('Tidak');
            $table->string('jam_buka', 10)->nullable();
            $table->string('jam_tutup', 10)->nullable();
            $table->string('hari_operasional', 100)->nullable();
            $table->string('harga_sewa_bulan', 50)->nullable();
            $table->string('keterangan_harga', 200)->nullable();
            $table->string('no_telp_wa', 50)->nullable();
            $table->decimal('rating', 3, 1)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kosan');
    }
};