<?php
declare(strict_types=1);

namespace App\Models;

use App\Contracts\Entity;
use App\Models\Concerns\HasId;

/** Kelas abstrak: tiap jenis pengguna mendefinisikan aturan peminjamannya sendiri (polymorphism). */
abstract class User implements Entity
{
    use HasId;

    public function __construct(
        int $id,
        protected string $name,
        protected string $email,
        protected string $passwordHash
    ) {
        $this->id = $id;
    }

    abstract public function getRole(): string;
    abstract public function getBorrowLimit(): int;
    abstract public function getLoanDays(): int;

    public function getName(): string { return $this->name; }
    public function getEmail(): string { return $this->email; }

    public function verifyPassword(string $plain): bool
    {
        return password_verify($plain, $this->passwordHash);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id, 'name' => $this->name, 'email' => $this->email,
            'password' => $this->passwordHash, 'role' => $this->getRole(),
        ];
    }

    /** Factory: membuat objek turunan yang sesuai dengan role di data. */
    public static function fromArray(array $r): self
    {
        $args = [(int) $r['id'], $r['name'], $r['email'], $r['password']];
        return match ($r['role'] ?? 'member') {
            'admin'   => new Admin(...$args),
            'premium' => new PremiumMember(...$args),
            default   => new Member(...$args),
        };
    }
}
