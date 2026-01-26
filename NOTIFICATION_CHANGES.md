# 📋 Notification System Changes

## 📝 Summary
Fitur notifikasi telah diperbarui untuk:
1. **Menampilkan notifikasi per-tabel** bukan per-item (mengurangi jumlah notifikasi)
2. **Menambahkan tombol hapus** pada setiap notifikasi
3. **Menampilkan jumlah update** pada tabel tertentu dalam satu notifikasi

---

## ✅ Perubahan yang Dilakukan

### 1. **Backend - Controller** 
📁 File: `app/Http/Controllers/Admin/NotificationController.php`

**Fungsi `getNotifications()` diperbarui:**
- Notifikasi sekarang dikelompokkan berdasarkan:
  - `table_name` (nama tabel yang diupdate)
  - Tanggal (hari yang sama)
- Menampilkan jumlah update dalam satu notifikasi (`count` field)
- Response JSON sekarang berisi:
  - `table_name`: Nama tabel (misal: "Daily Reports", "Users")
  - `count`: Jumlah update/create pada tabel itu
  - `type`: 'update', 'create', atau 'delete'
  - `message`: Pesan notifikasi

**Fungsi `deleteNotification()` sudah tersedia:**
- Untuk menghapus notifikasi individual
- Route sudah ada: `DELETE /admin/notifications/{id}`

---

### 2. **Backend - Observers**

#### 📁 `app/Observers/DailyReportObserver.php`
- ✅ `table_name` ditambahkan ke notification data: `"Daily Reports"`
- ✅ Message disederhanakan (tidak menampilkan daftar field yang berubah)

#### 📁 `app/Observers/UserObserver.php`
- ✅ `table_name` ditambahkan ke notification data: `"Users"`
- ✅ Message disederhanakan

---

### 3. **Frontend - Header Template**
📁 File: `resources/views/dashboard/layouts/header.blade.php`

#### Perubahan tampilan notifikasi:
```
Sebelum:
- Menampilkan judul custom per notifikasi
- Tanpa tombol hapus

Sesudah:
- Menampilkan nama TABEL (misal: "Daily Reports")
- Menampilkan pesan dari user yang update
- Menampilkan waktu update
- Menampilkan badge tipe (Update/Baru/Hapus)
- Menampilkan jumlah update pada tabel: "📊 X update(s) pada tabel ini"
- Tombol HAPUS 🗑️ untuk menghapus notifikasi
```

#### JavaScript Functions Baru:
```javascript
// Fungsi baru untuk hapus notifikasi
deleteNotification(event, notificationId)
  - Menampilkan konfirmasi sebelum menghapus
  - Mengirim DELETE request ke backend
  - Reload notifikasi setelah berhasil
  - Handle error dengan alert
```

---

## 🎯 Tabel yang Terdapat dalam Sistem

Berdasarkan observers yang ada, ada **2 tabel utama** yang mengirim notifikasi:

1. **Daily Reports** - Laporan harian (Create/Update)
2. **Users** - Data pengguna (Create/Update)

**Potensi ekspansi ke 5 tabel:**
- Jika menambah observers untuk:
  - Cars (Mobil)
  - Driver Items (Item Driver)
  - Armada Items (Item Armada)
  - Dokuments (Dokumen)
  - Environment (Lingkungan)
  - Safety (Keselamatan)

---

## 🔄 Flow Sistem Notifikasi

```
User mengirim data (create/update)
    ↓
Observer terdeteksi (DailyReportObserver / UserObserver)
    ↓
Notification dibuat dengan data:
  - user_id, admin_id, title, message, type
  - data.table_name (nama tabel)
    ↓
Admin login → Header menampilkan notification bell
    ↓
loadNotifications() dipanggil setiap 10 detik
    ↓
Notifikasi DIKELOMPOKKAN per tabel per hari
    ↓
Tampil di dropdown dengan:
  - Nama tabel
  - Pesan update
  - Jumlah update
  - Badge tipe
  - Tombol hapus
```

---

## 🚀 Testing Checklist

- [ ] Login sebagai Admin
- [ ] Lihat bell icon notification di header
- [ ] Buat/update Daily Report sebagai User → cek notification muncul
- [ ] Buat/update User data → cek notification muncul
- [ ] Click notification → tandai sebagai read (background berubah)
- [ ] Click tombol "Hapus" → notifikasi hilang
- [ ] Click "Tandai semua terbaca" → semua notification hilang dari unread
- [ ] Refresh page → notifikasi masih ada dan state terjaga
- [ ] Verifikasi notifikasi dikelompokkan per tabel (jika 5+ update dalam 1 hari, hanya 1 notifikasi)

---

## 📌 Routes yang Digunakan

```php
GET  /admin/notifications/get              // Ambil notifikasi
GET  /admin/notifications/count            // Hitung unread
POST /admin/notifications/mark-as-read     // Tandai terbaca
POST /admin/notifications/mark-all-as-read // Tandai semua terbaca
DELETE /admin/notifications/{id}           // Hapus notifikasi
```

---

## ⚙️ Konfigurasi

Notifikasi di-refresh otomatis setiap **10 detik** di header:
```javascript
setInterval(loadNotifications, 10000); // 10 seconds
```

Dapat diubah di `header.blade.php` line ~445 sesuai kebutuhan.

---

## 🐛 Known Limitations

1. Notifikasi dikelompokkan per hari (tidak mempertimbangkan jam untuk grouping)
2. Jika ada 100 update dalam 1 hari pada 1 tabel, tetap hanya 1 notifikasi
3. Notifikasi lama otomatis dihapus jika limit tercapai (ambil hanya `limit * 5`)

---

## 📞 Support

Untuk menambah tabel baru ke sistem notifikasi:
1. Buat Observer baru untuk model
2. Tambahkan `'table_name' => 'Nama Tabel'` ke notification data
3. Register observer di `AppServiceProvider.php`

Done! ✨
