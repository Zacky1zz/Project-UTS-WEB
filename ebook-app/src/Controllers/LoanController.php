<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Repositories\EbookRepository;
use App\Repositories\LoanRepository;
use App\Repositories\UserRepository;
use App\Services\LoanService;

class LoanController extends Controller
{
    private LoanRepository $loans;
    private EbookRepository $ebooks;
    private LoanService $service;

    public function __construct()
    {
        parent::__construct();
        $this->loans = new LoanRepository();
        $this->ebooks = new EbookRepository();
        $this->service = new LoanService($this->ebooks, $this->loans);
    }

    public function index(): void
    {
        $user = $this->requireLogin();
        $isAdmin = $user->getRole() === 'admin';
        $loans = $isAdmin ? $this->loans->all() : $this->loans->forUser($user->getId());
        $users = new UserRepository();

        $rows = array_map(fn ($loan) => [
            'loan' => $loan,
            'ebook' => $this->ebooks->find($loan->getEbookId()),
            'owner' => $users->find($loan->getUserId()),
        ], array_reverse($loans));

        $this->view('loans/index', ['rows' => $rows, 'isAdmin' => $isAdmin], 'Peminjaman');
    }

    public function borrow(string $id): void
    {
        $user = $this->requireLogin();
        $this->checkCsrf();
        try {
            $loan = $this->service->borrow($user, (int) $id);
            Session::flash('success', 'Berhasil meminjam. Jatuh tempo: ' . format_date($loan->getDueDate()) . '.');
        } catch (\DomainException $e) {
            Session::flash('error', $e->getMessage());
        }
        $this->redirect('/loans');
    }

    public function giveBack(string $id): void
    {
        $user = $this->requireLogin();
        $this->checkCsrf();
        try {
            $this->service->giveBack($user, (int) $id);
            Session::flash('success', 'E-book berhasil dikembalikan.');
        } catch (\DomainException $e) {
            Session::flash('error', $e->getMessage());
        }
        $this->redirect('/loans');
    }
}
