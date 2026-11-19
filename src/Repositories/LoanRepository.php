<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Loan;

class LoanRepository extends JsonRepository
{
    public function __construct()
    {
        parent::__construct(DATA_PATH . '/loans.json');
    }

    protected function hydrate(array $row): Loan
    {
        return Loan::fromArray($row);
    }

    /** @return Loan[] */
    public function forUser(int $userId): array
    {
        return array_values(array_filter($this->all(), fn (Loan $l) => $l->getUserId() === $userId));
    }

    /** @return Loan[] */
    public function activeForUser(int $userId): array
    {
        return array_values(array_filter($this->forUser($userId), fn (Loan $l) => !$l->isReturned()));
    }
}
