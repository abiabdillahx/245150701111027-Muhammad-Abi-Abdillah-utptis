# UTP Teknologi Integrasi Sistem
> Nama: Muhammad Abi Abdillah <br>
> NIM: 245150701111027 <br>
> Kelas: TI - A

**Live Demo**: http://utp.zenc.cc/api/docs

**Goals:**
Membangun ecommerce-like backend API sederhana menggunakan Laravel
dengan mock data JSON (non db).

## Project Structure

Project ini menggunakan **Service Layer Pattern** sebagai helper pengganti database. Karena tidak menggunakan DB, operasi baca/tulis data dilakukan melalui `ItemService` yang membaca dan menulis ke file `storage/app/data/items.json`.

```
app/
├── Http/
│   └── Controllers/
│       └── ItemController.php  # Handle request & response
├── Services/
│   └── ItemService.php         # Logic baca/tulis JSON (buat gantiin Model/DB)
storage/
└── app/
    └── data/
        └── items.json          # Mock database
```

## How to Run Project
```sh
git clone https://github.com/abiabdillahx/245150701111027-Muhammad-Abi-Abdillah-utptis
cd 245150701111027-Muhammad-Abi-Abdillah-utptis

composer install
cp .env.example .env

php artisan key:generate
php artisan l5-swagger:generate
php artisan serve
```
Lalu akses http://localhost:8000/api/health

## Swagger UI
Akses dokumentasi interaktif Swagger di:
http://localhost:8000/api/docs

*atau*

Try it out di live demo: http://utp.zenc.cc/api/docs

## List API Endpoints

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | /api/items | Ambil seluruh daftar barang |
| POST | /api/items | Tambah barang baru |
| GET | /api/items/{id} | Cari barang berdasarkan ID |
| PUT | /api/items/{id} | Perbarui semua field barang |
| PATCH | /api/items/{id} | Perbarui sebagian field barang |
| DELETE | /api/items/{id} | Hapus barang dari daftar |

## Contoh Request & Response

### GET /api/items
Response:
```json
{
    "success": true,
    "data": [
        { "id": 1, "nama": "Laptop", "harga": 15000000 },
        { "id": 2, "nama": "Mouse", "harga": 150000 }
    ]
}
```

### POST /api/items
Request:
```json
{
    "nama": "Monitor",
    "harga": 3000000
}
```
Response:
```json
{
    "success": true,
    "message": "Item berhasil ditambahkan",
    "data": { "id": 4, "nama": "Monitor", "harga": 3000000 }
}
```

### GET /api/items/{id}
Response (200):
```json
{
    "success": true,
    "data": { "id": 1, "nama": "Laptop", "harga": 15000000 }
}
```
Response (404):
```json
{
    "success": false,
    "message": "Item dengan ID 99 tidak ditemukan"
}
```

### PUT /api/items/{id}
Request:
```json
{
    "nama": "Laptop Pro",
    "harga": 20000000
}
```
Response:
```json
{
    "success": true,
    "message": "Item berhasil diupdate",
    "data": { "id": 1, "nama": "Laptop Pro", "harga": 20000000 }
}
```

### PATCH /api/items/{id}
Request:
```json
{
    "harga": 18000000
}
```
Response:
```json
{
    "success": true,
    "message": "Item berhasil diupdate sebagian",
    "data": { "id": 1, "nama": "Laptop Pro", "harga": 18000000 }
}
```

### DELETE /api/items/{id}
Response:
```json
{
    "success": true,
    "message": "Item berhasil dihapus"
}
```

## Tech Stack
- PHP 8.2
- Laravel 12
- L5-Swagger (darkaonline/l5-swagger)