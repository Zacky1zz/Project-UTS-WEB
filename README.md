# E-Book Library — UTS Pemrograman Web (PHP OOP tanpa framework)

Aplikasi katalog & peminjaman e-book. Front-end: HTML, CSS, JS. Backend: PHP 8.x OOP murni.

## Cara menjalankan
Syarat: PHP >= 8.1 dengan ekstensi `mbstring` (sudah aktif di XAMPP/Laragon).

```bash
php -S localhost:8000 -t public
```
Buka http://localhost:8000

Akun demo (dibuat otomatis saat pertama kali jalan):
| Role | Email | Password |
|---|---|---|
| Admin | admin@ebook.test | admin123 |
| Member | member@ebook.test | member123 |

## Struktur
```
public/            index.php (front controller + routing), assets/css, assets/js
src/Core/          Router, Controller (base), Session
src/Contracts/     Entity, Borrowable (interface)
src/Models/        User (abstract) -> Member -> PremiumMember, Admin; Ebook; Loan
src/Repositories/  JsonRepository (abstract) -> Ebook/User/LoanRepository
src/Services/      AuthService, LoanService (logika bisnis)
src/Controllers/   Auth, Ebook, Loan
src/Views/         template halaman
data/              penyimpanan JSON (ebooks, users, loans)
```

## Pemetaan ke rubrik
- **Namespace**: semua class di `App\...`, autoload PSR-4 (`src/Autoloader.php` atau Composer).
- **Encapsulation**: properti `private/protected`, akses via getter; `Ebook::borrow()` menjaga aturan stok.
- **Inheritance**: `User` (abstract) -> `Member` -> `PremiumMember`; `User` -> `Admin`; `JsonRepository` -> 3 repository.
- **Polymorphism**: `getBorrowLimit()` / `getLoanDays()` berbeda tiap role; `Borrowable` interface; `User::fromArray()` factory.
- **Front-end**: HTML semantik, form + validasi (HTML5 + JS), layout responsif, filter katalog live, indikator password, konfirmasi aksi