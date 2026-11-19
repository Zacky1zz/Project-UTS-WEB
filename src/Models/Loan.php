<?php
declare(strict_types=1);

namespace App\Models;

use App\Contracts\Entity;
use App\Models\Concerns\HasId;

class Loan implements Entity
{
    use HasId;

    public function __construct(
        int $id,
        private int $userId,
        private int $ebookId,
        private string $borrowedAt,
        private string $dueDate,
        private ?string $returnedAt = null
    ) {
        $this->id = $id;
    }

    public function getUserId(): int { return $this->userId; }
    public function getEbookId(): int { return $this->ebookId; }
    public function getBorrowedAt(): string { return $this->borrowedAt; }
    public function getDueDate(): string { return $this->dueDate; }
    public function getReturnedAt(): ?string { return $this->returnedAt; }

    public function isReturned(): bool
    {
        return $this->returnedAt !== null;
    }

    public function isOverdue(): bool
    {
        return !$this->isReturned() && $this->dueDate < date('Y-m-d');
    }

    public function markReturned(): void
    {
        $this->returnedAt = date('Y-m-d');
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id, 'userId' => $this->userId, 'ebookId' => $this->ebookId,
            'borrowedAt' => $this->borrowedAt, 'dueDate' => $this->dueDate, 'returnedAt' => $this->returnedAt,
        ];
    }

    public static function fromArray(array $r): static
    {
        return new static(
            (int) $r['id'], (int) $r['userId'], (int) $r['ebookId'],
            $r['borrowedAt'], $r['dueDate'], $r['returnedAt'] ?? null
        );
    }
}
