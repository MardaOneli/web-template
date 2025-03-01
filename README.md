# Web Template Project

## Deskripsi Proyek
Proyek ini adalah template sederhana untuk membangun aplikasi web berbasis PHP tanpa framework. Sistem ini menggunakan **MVC (Model-View-Controller)** untuk struktur yang rapi dan modular.

Fitur utama dalam proyek ini mencakup **sistem autentikasi**, **middleware**, **role management**, dan **error handling** yang memastikan keamanan serta kestabilan aplikasi.

---

## Fitur Utama

### 1. **Autentikasi (Login & Logout)**
- User dapat login menggunakan **username dan password**.
- Password dienkripsi menggunakan **bcrypt (`password_hash`)** untuk keamanan.
- Session digunakan untuk menyimpan informasi pengguna yang sedang login.
- Logout akan menghapus session dan mengarahkan pengguna ke halaman login.

### 2. **Middleware**
- `auth()`: Memastikan hanya user yang sudah login yang bisa mengakses halaman tertentu.
- `guest()`: Mencegah user yang sudah login untuk mengakses halaman login.
- `role($allowedRoles)`: Mengatur akses berdasarkan peran (`admin` atau `user`).

### 3. **Manajemen Role (Admin & User)**
- User memiliki role **admin** atau **user**.
- Admin memiliki akses ke halaman khusus seperti dashboard admin.
- User hanya dapat mengakses fitur yang diperuntukkan bagi mereka.

### 4. **Routing Dinamis**
- Menggunakan `Router.php` untuk menangani berbagai request.
- Setiap URL akan diarahkan ke controller yang sesuai.

### 5. **Controller & View**
- Controller menangani logika bisnis dan memanggil view yang diperlukan.
- View digunakan untuk menampilkan halaman kepada pengguna dengan data yang diberikan dari controller.

### 6. **Manajemen Error**
- Jika terjadi error di dalam aplikasi, halaman error akan ditampilkan.
- Jika ada error di dalam `$content` pada `app.php`, halaman lain akan disembunyikan dan hanya menampilkan halaman error.

### 7. **Database (MariaDB)**
- Menggunakan **PDO (PHP Data Objects)** untuk koneksi yang aman dan fleksibel.
- UUID digunakan sebagai **primary key** untuk keamanan dan uniknya setiap user.
- Tabel `users` memiliki kolom:
  - `id` (UUID)
  - `username` (unique)
  - `password` (hashed password)
  - `role` (enum: `user`, `admin`)

### 8. **Manajemen User**
- `getUserById($id)`: Mengambil data user berdasarkan UUID.
- **Bind parameter dengan `PDO::PARAM_STR`** untuk menangani UUID dengan benar.

### 9. **Sistem Frontend Minimalis (Bootstrap & Vanilla JS)**
- Desain menggunakan **Bootstrap** untuk tampilan yang rapi dan responsif.
- Modal Bootstrap digunakan untuk fitur **edit tanpa halaman baru**.
- JavaScript digunakan untuk meningkatkan interaktivitas (misal: validasi form, redirect otomatis, dsb.).

---

## **Struktur Database**
Tabel utama yang digunakan dalam sistem:

### **Tabel `users`**
| Kolom    | Tipe Data             | Keterangan                     |
|----------|----------------------|--------------------------------|
| `id`     | `VARCHAR(36)` (UUID)  | Primary key, unik untuk setiap pengguna. |
| `username` | `VARCHAR(50)`       | Username unik untuk setiap pengguna. |
| `password` | `VARCHAR(255)`      | Password yang telah di-hash menggunakan bcrypt. |
| `role`   | `ENUM('user','admin')` | Peran pengguna dalam sistem. |

## **Cara Menggunakan**

### **1. Setup Database**
Jalankan perintah berikut di terminal untuk membuat database dan tabel:
```sh
php generate_db.php setup
```

### **2. Menambah Admin**
Tambahkan pengguna dengan peran `admin` menggunakan perintah berikut:
```sh
php generate_db.php add <username> <password>
```
Contoh:
```sh
php generate_db.php add admin password123
```

### **3. Menghapus Pengguna**
Hapus pengguna berdasarkan username:
```sh
php generate_db.php delete <username>
```
Contoh:
```sh
php generate_db.php delete admin
```

### **4. Menampilkan Daftar Admin**
Tampilkan daftar pengguna dengan peran `admin`:
```sh
php generate_db.php list
```

### **5. Menghapus Database**
Jika ingin menghapus seluruh database, gunakan perintah berikut:
```sh
php generate_db.php drop
```

## **Model User (`User.php`)**
File ini menangani operasi terkait pengguna dalam database, seperti autentikasi dan pengelolaan akun.

### **Fungsi dalam Model User**
✅ `getUserById($id)`: Mengambil data pengguna berdasarkan ID.
✅ `getUserByUsername($username)`: Mengambil data pengguna berdasarkan username.
✅ `createUser($username, $password, $role)`: Menambahkan pengguna baru ke database dengan hashing password.
✅ `getRole($userId)`: Mengambil peran (role) pengguna berdasarkan ID.
✅ `updatePassword($userId, $newPassword)`: Memperbarui password pengguna.
✅ `deleteUser($userId)`: Menghapus pengguna berdasarkan ID.

## **Teknologi yang Digunakan**
- PHP (Native, tanpa framework)
- MariaDB / MySQL sebagai database
- PDO (PHP Data Objects) untuk koneksi database
- BCrypt untuk keamanan password

---

## Instalasi & Konfigurasi

### 1. **Kloning Repository**
```sh
git clone https://github.com/MardaOneli/web-template.git
cd web-template
```

### 2. **Buat Database & Konfigurasi Koneksi**
- Sesuaikan konfigurasi di `config/database.php`.

### 3. **Jalankan Server Lokal**
```sh
php -S localhost:8080 -t public
```
Lalu akses di browser: [http://localhost:8080](http://localhost:8080)

---

## Struktur Direktori
```
/project-root
│── app/
│   ├── Controllers/
│   ├── Models/
│   ├── Views/
│── config/
│── core/
│   ├── Controller.php
│   ├── Database.php
│   ├── Middleware.php
│   ├── Router.php
│   ├── Web.php
│── public/
│   ├── index.php
│── includes/
│── README.md
```

---

## Lisensi
Proyek ini bersifat open-source dan dapat digunakan secara bebas.

Jika ada pertanyaan atau kontribusi, silakan buat **issue** atau **pull request** di repository ini.

---

**📌 Catatan:**
- Jika mengalami error `Unknown constant "Guest"`, pastikan konstanta `Guest` didefinisikan sebagai string (`'Guest'`).
- UUID harus diproses sebagai string (`PDO::PARAM_STR`).

