# 🔐 Complete Admin Accounts - Metland Recruitment System

## 📋 Daftar Lengkap Akun Admin

### 👑 **Admin Pusat** (Akses Semua Data)
| Nama | Email | Password | Akses |
|------|-------|----------|-------|
| Admin Pusat | `admin.pusat@metland.com` | `admin123` | **SEMUA LOKASI** |

---

### 🏢 **Admin Lokasi** (Akses Per Lokasi)

| No | Nama Lokasi | Email Admin | Password | HRIS ID |
|----|-------------|-------------|----------|---------|
| 1 | Metland Transyogi | `admin.transyogi@metland.com` | `admin123` | 2 |
| 2 | Metland Cileungsi | `admin.cileungsi@metland.com` | `admin123` | 3 |
| 3 | Metland Tambun | `admin.tambun@metland.com` | `admin123` | 4 |
| 4 | Metland Cibitung | `admin.cibitung@metland.com` | `admin123` | 5 |
| 5 | Metland Menteng | `admin.menteng@metland.com` | `admin123` | 6 |
| 6 | Metland Puri | `admin.puri@metland.com` | `admin123` | 7 |
| 7 | Metland Cyber Puri | `admin.cyberpuri@metland.com` | `admin123` | 8 |
| 8 | Mal Metropolitan Bekasi | `admin.malbekasi@metland.com` | `admin123` | 9 |
| 9 | M Gold Tower | `admin.goldtower@metland.com` | `admin123` | 10 |
| 10 | Grand Metropolitan Mall | `admin.grandmetmall@metland.com` | `admin123` | 11 |
| 11 | Mal Metropolitan Cileungsi | `admin.malcileungsi@metland.com` | `admin123` | 12 |
| 12 | Kaliana Apartment | `admin.kaliana@metland.com` | `admin123` | 13 |
| 13 | Metland Hotel Cirebon | `admin.hotelcirebon@metland.com` | `admin123` | 14 |
| 14 | Hotel Horison Ultima Bekasi | `admin.horisonbekasi@metland.com` | `admin123` | 15 |
| 15 | Hotel Horison Ultima Seminyak | `admin.horisonseminyak@metland.com` | `admin123` | 16 |
| 16 | Plaza Metropolitan | `admin.plazamet@metland.com` | `admin123` | 17 |
| 17 | Metland Hotel Bekasi | `admin.hotelbekasi@metland.com` | `admin123` | 18 |
| 18 | Kantor Pusat - MT Haryono | `admin.mtharyono@metland.com` | `admin123` | 19 |
| 19 | Kantor Pusat - Hotel Division | `admin.hoteldiv@metland.com` | `admin123` | 20 |
| 20 | Metland Smara Kertajati | `admin.smarakertajati@metland.com` | `admin123` | 21 |
| 21 | Metland Cikarang | `admin.cikarang@metland.com` | `admin123` | 22 |
| 22 | One District Puri | `admin.onedistrict@metland.com` | `admin123` | 23 |
| 23 | Metland Venya Ubud | `admin.venyaubud@metland.com` | `admin123` | 24 |
| 24 | Recreation & Sport Facility | `admin.recreation@metland.com` | `admin123` | 25 |
| 25 | Koperasi Metland Maju Bersama | `admin.koperasi@metland.com` | `admin123` | 26 |
| 26 | Metland Kertajati | `admin.kertajati@metland.com` | `admin123` | 27 |
| 27 | DIUBUD | `admin.diubud@metland.com` | `admin123` | 28 |
| 28 | Roku Ramen | `admin.rokuramen@metland.com` | `admin123` | 29 |

---

## 🌐 **Cara Login**

### **URL Admin Panel:**
```
https://trialrecruitment.metropolitanland.com/admin
```

### **Langkah Login:**
1. Buka URL admin panel di browser
2. Masukkan email admin sesuai tabel di atas
3. Masukkan password: `admin123`
4. Klik "Sign in"

---

## 🔒 **Perbedaan Akses**

### **Admin Pusat:**
- ✅ Dapat melihat **SEMUA** job vacancy dari semua lokasi
- ✅ Dapat melihat **SEMUA** applicants dari semua lokasi  
- ✅ Dapat melihat **SEMUA** apply jobs dari semua lokasi
- ✅ Dapat mengakses **SEMUA** reports dari semua lokasi

### **Admin Lokasi:**
- ✅ Hanya dapat melihat job vacancy dari **lokasi mereka saja**
- ✅ Hanya dapat melihat applicants yang apply ke job di **lokasi mereka saja**
- ✅ Hanya dapat melihat apply jobs untuk job di **lokasi mereka saja**
- ✅ Hanya dapat melihat reports untuk job di **lokasi mereka saja**
- ❌ **TIDAK DAPAT** melihat data dari lokasi lain

---

## 🛠️ **Setup Instructions**

### **1. Jalankan Script di Server:**
```bash
cd /var/www/trialrecruitment.metropolitanland.com/html/Metland-Recruit
php create-all-locations-and-admins.php
```

### **2. Clear Cache:**
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### **3. Upload Files yang Diupdate:**
- `app/Filament/Resources/JobVacancies/JobVacancyResource.php`
- `app/Filament/Resources/ApplyJobs/ApplyJobResource.php`
- `app/Filament/Resources/Applicants/ApplicantResource.php`
- `app/Filament/Resources/ReportResource.php`

---

## 🔧 **Troubleshooting**

### **Jika Login Gagal:**
1. **Clear cache:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

2. **Cek user exists:**
   ```bash
   php artisan tinker
   User::where('email', 'admin.cileungsi@metland.com')->first();
   ```

3. **Reset password:**
   ```bash
   php artisan tinker
   $user = User::where('email', 'admin.cileungsi@metland.com')->first();
   $user->password = Hash::make('admin123');
   $user->save();
   ```

### **Jika Data Tidak Terfilter:**
- Pastikan `location_id` pada user admin sudah benar
- Pastikan `job_vacancy_hris_location_id` pada job vacancy sudah sesuai dengan `hris_location_id` di tabel locations
- Clear cache dan restart web server

---

## 📊 **System Architecture**

### **Database Tables:**
- `users` - Menyimpan data admin dengan role dan location_id
- `locations` - Menyimpan data lokasi dengan hris_location_id
- `job_vacancy` - Job vacancy dengan job_vacancy_hris_location_id
- `apply_jobs` - Apply jobs yang ter-relasi ke job_vacancy

### **Filtering Logic:**
```
Admin Login → Check role → 
  If admin_pusat: Show ALL data
  If admin_location: Filter by location_id → 
    Match hris_location_id with job_vacancy_hris_location_id
```

---

## 📞 **Support**

Jika ada masalah dengan akun admin, hubungi:
- **Developer**: Technical Team
- **System Admin**: Server Administrator

---

## 🔐 **Security Notes**

⚠️ **PENTING:**
- Password default `admin123` harus diganti setelah first login
- Jangan share credentials admin ke pihak yang tidak berwenang
- Gunakan HTTPS untuk akses admin panel
- Enable 2FA jika tersedia

---

*Last updated: November 20, 2025*
*Total Admin Accounts: 29 (1 Pusat + 28 Lokasi)*
