# 🔐 Admin Accounts - Metland Recruitment System

## 📋 Daftar Akun Admin

### 👑 **Admin Pusat** (Akses Semua Data)
| Nama | Email | Password | Akses |
|------|-------|----------|-------|
| Admin Pusat | `admin.pusat@metland.com` | `admin123` | **SEMUA LOKASI** |

---

### 🏢 **Admin Lokasi** (Akses Per Lokasi)
| Nama | Email | Password | Lokasi Akses |
|------|-------|----------|--------------|
| Admin Metland Cileungsi | `admin.cileungsi@metland.com` | `admin123` | Metland Cileungsi |
| Admin Metland Tanjung | `admin.tanjung@metland.com` | `admin123` | Metland Tanjung |
| Admin Metland Tambun | `admin.tambun@metland.com` | `admin123` | Metland Tambun |
| Admin Metland Cibitung | `admin.cibitung@metland.com` | `admin123` | Metland Cibitung |
| Admin Metland Menteng | `admin.menteng@metland.com` | `admin123` | Metland Menteng |
| Admin Metland Puri | `admin.puri@metland.com` | `admin123` | Metland Puri |
| Admin Metland Cyber Puri | `admin.cyberpuri@metland.com` | `admin123` | Metland Cyber Puri |
| Admin Metland Bekasi | `admin.bekasi@metland.com` | `admin123` | Metland Bekasi |
| Admin Metland Bogor | `admin.bogor@metland.com` | `admin123` | Bogor Barat |
| Admin Metland Jakarta | `admin.jakarta@metland.com` | `admin123` | DKI Jakarta |

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
- ✅ Dapat mengakses **SEMUA** reports dan data

### **Admin Lokasi:**
- ✅ Hanya dapat melihat job vacancy dari **lokasi mereka saja**
- ✅ Hanya dapat melihat applicants yang apply ke job di **lokasi mereka saja**
- ✅ Hanya dapat melihat apply jobs untuk job di **lokasi mereka saja**
- ❌ **TIDAK DAPAT** melihat data dari lokasi lain

---

## 🛠️ **Setup Admin Accounts**

### **Jalankan Seeder:**
```bash
cd /var/www/trialrecruitment.metropolitanland.com/html/Metland-Recruit
php artisan db:seed --class=AdminUserSeeder --force
```

### **Manual Create Admin:**
```bash
php artisan tinker
```
```php
use App\Models\User;
use Illuminate\Support\Facades\Hash;

User::create([
    'name' => 'Admin Cileungsi',
    'email' => 'admin.cileungsi@metland.com',
    'password' => Hash::make('admin123'),
    'role' => 'admin_location',
    'location_id' => 1, // ID lokasi Cileungsi
    'email_verified_at' => now(),
]);
```

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
- Pastikan `job_vacancy_hris_location_id` pada job vacancy sudah sesuai
- Clear cache dan restart web server

---

## 📞 **Support**

Jika ada masalah dengan akun admin, hubungi:
- **Developer**: Technical Team
- **System Admin**: Server Administrator

---

*Last updated: November 20, 2025*
