KosanMaps — USU & POLMED Medan

Aplikasi web berbasis GIS (Geographic Information System) yang dirancang untuk memetakan dan menampilkan informasi persebaran kosan di sekitar Universitas Sumatera Utara (USU) dan Politeknik Negeri Medan (POLMED), Kota Medan, Sumatera Utara secara interaktif dan mudah diakses oleh mahasiswa.

Latar Belakang

Mahasiswa yang kesulitan menemukan kosan terdekat di sekitar USU dan POLMED beserta informasi harga dan fasilitasnya secara cepat dan akurat mendorong pembuatan aplikasi ini sebagai solusi nyata.

Fitur Utama

🗺️ Peta interaktif persebaran kosan berbasis OpenStreetMap 🔍 Filter berdasarkan kampus terdekat dan tipe kosan 📍 Pencarian kosan terdekat dari USU dan POLMED ℹ️ Detail informasi & fasilitas setiap kosan ⭐ Rating dan kategori kosan (Putra/Putri/Campur) 🔒 Panel admin untuk manajemen data kosan (CRUD)

🛠️ Teknologi

Backend: PHP Laravel Database: PostgreSQL + PostGIS Peta: Leaflet.js + OpenStreetMap Frontend: Bootstrap 5 + JavaScript

⚙️ Cara Install

Clone repository git clone https://github.com/ikasianturi278-web/gis-kos-usu-polmed Install dependencies composer install Copy file environment cp .env.example .env Setting database PostgreSQL di .env Jalankan migrasi php artisan migrate Jalankan serve php artisan serve
