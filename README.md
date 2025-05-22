# KerjaAja
Sistem informasi ini bertujuan untuk membantu warga mendaftar program pelatihan kerja yang diselenggarakan oleh desa/kecamatan dan menghubungkan mereka ke lowongan kerja lokal.
---

## Actor:

1. **Warga (User):**

   * Registrasi & login
   * Melihat daftar pelatihan dan lowongan kerja
   * Mendaftar pelatihan
   * Melamar lowongan

2. **Admin (Desa/Kecamatan):**

   * Menambahkan jadwal pelatihan
   * Menambahkan info lowongan kerja
   * Melihat warga yang mendaftar
   * Menerima/menolak pendaftaran
---
## Fitur Lengkap:

#### 1. **Autentikasi Multi-role**

* Login & register user
* Halaman dashboard berbeda untuk admin dan user

#### 2. **Manajemen Pelatihan**

* Admin membuat data pelatihan: nama, tanggal, tempat, kuota, deskripsi
* Warga mendaftar
* Admin melihat siapa saja yang mendaftar

#### 3. **Manajemen Lowongan Kerja**

* Admin mengunggah lowongan kerja (dari perusahaan lokal/UMKM)
* Warga dapat melamar (upload CV PDF)

#### 4. **Status & Riwayat**

* Halaman warga untuk melihat riwayat pelatihan & lamaran kerja
* Admin bisa update status: diterima, ditolak, selesai

#### 5. **Dashboard Admin**

* Statistik pelatihan: jumlah peserta, jumlah pelatihan aktif
* Statistik lowongan: total lowongan, pelamar per lowongan (gunakan Chart.js)

---
### Teknologi:

* HTML, CSS, JavaScript (Frontend)
* PHP Native (Backend)
* MySQL (Database)
* XAMPP
