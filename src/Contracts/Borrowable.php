<?php
declare(strict_types=1);

namespace App\Contracts;

/** Kontrak untuk item yang dapat dipinjam (dasar polymorphism). */
interface Borrowable
{
    public function isAvailable(): bool;
    public function borrow(): void;
    public function giveBack(): void;
}
