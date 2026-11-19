<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Loan;
use App\Models\User;
use App\Repositories\EbookRepository;
use App\Repositories\LoanRepository;

/** Logika bisnis transaksi peminjaman e-book. */
class LoanService
{
    public function __construct(
        private EbookRepository $ebooks,
        private LoanRepository $loans
    ) {
    }

    /** @throws \DomainException */
    public function borrow(User $user, int $ebookId): Loan
    {
        $ebook = $this->ebooks->find($ebookId);
        if ($ebook === null) {
            throw new \DomainException('E-book tidak ditemukan.');
        }

        $active = $this->loans->activeForUser($user->getId());
        if (count($active) >= $user->getBorrowLimit()) {
            throw new \DomainException("Batas peminjaman {$user->getBorrowLimit()} e-book untuk akun {$user->getRole()} sudah tercapai.");
        }
        foreach ($active as $loan) {
            if ($loan->getEbookId() === $ebookId) {
                throw new \DomainException('Kamu sedang meminjam e-book ini.');
            }
        }

        $ebook->borrow(); // melempar DomainException jika stok habis
        $this->ebooks->save($ebook);

        $today = new \DateTimeImmutable('today');
        $loan = new Loan(
            0, $user->getId(), $ebookId,
            $today->format('Y-m-d'),
            $today->modify("+{$user->getLoanDays()} days")->format('Y-m-d')
        );
        $this->loans->save($loan);
        return $loan;
    }

    /** @throws \DomainException */
    public function giveBack(User $user, int $loanId): Loan
    {
        $loan = $this->loans->find($loanId);
        if ($loan === null || $loan->isReturned()) {
            throw new \DomainException('Data peminjaman tidak ditemukan atau sudah dikembalikan.');
        }
        if ($loan->getUserId() !== $user->getId() && $user->getRole() !== 'admin') {
            throw new \DomainException('Kamu tidak berhak mengembalikan peminjaman ini.');
        }

        $loan->markReturned();
        $this->loans->save($loan);

        $ebook = $this->ebooks->find($loan->getEbookId());
        if ($ebook !== null) {
            $ebook->giveBack();
            $this->ebooks->save($ebook);
        }
        return $loan;
    }
}
