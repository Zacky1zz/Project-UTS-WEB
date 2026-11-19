<?php
declare(strict_types=1);

namespace App\Models;

class Admin extends User
{
    public function getRole(): string { return 'admin'; }
    public function getBorrowLimit(): int { return 5; }
    public function getLoanDays(): int { return 14; }
}
