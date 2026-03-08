# Mabel POS (PHP Native + MySQL XAMPP)

POS sederhana untuk toko meubel/perabot rumah tangga: produk & stok, penjualan (pemasukan), pengeluaran, laporan laba-rugi (harian/mingguan/bulanan/periode), export CSV (Excel) dan PDF.

## Prasyarat
- XAMPP (Apache + MySQL)
- MySQL berjalan di port **3308** (sesuai konfigurasi)

## Setup
1. Buat database sesuai `DB_NAME` di `src/config.php` (contoh: `mebel`)
2. Pastikan MySQL berjalan di port **3308**
3. Buka di browser:
   - `http://localhost/mabel/?page=login`
4. Tabel akan dibuat otomatis saat pertama kali dibuka (auto-setup)
5. Login default:
   - Username: `admin`
   - Password: `admin`

## Export PDF (opsional, Dompdf)
Agar tombol **Export PDF** menghasilkan file PDF otomatis, install Dompdf via Composer di folder proyek:

- Jalankan: `composer require dompdf/dompdf`

Jika Dompdf belum ter-install, export PDF akan tampil sebagai halaman HTML yang bisa di-Print lalu **Save as PDF**.

## Catatan Perhitungan
- **HPP** diambil dari `harga modal` produk saat transaksi penjualan disimpan.
- **Laba Kotor** = Total Penjualan − HPP
- **Laba/Rugi Bersih** = Laba Kotor − Pengeluaran
# POS-Meubel
