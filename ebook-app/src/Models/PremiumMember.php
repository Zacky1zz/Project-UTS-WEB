<?php
declare(strict_types=1);

namespace App\Models;

class PremiumMember extends Member
{
    public function getRole(): string { return 'premium'; }
    public function getBorrowLimit(): int { return 10; }
    public function getLoanDays(): int { return 21; }
}
