<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Ebook;
use App\Repositories\EbookRepository;

class EbookController extends Controller
{
    private EbookRepository $ebooks;

    public function __construct()
    {
        parent::__construct();
        $this->ebooks = new EbookRepository();
    }

    public function index(): void
    {
        $q = trim((string) ($_GET['q'] ?? ''));
        $category = trim((string) ($_GET['category'] ?? ''));

        $this->view('ebooks/index', [
            'ebooks' => $this->ebooks->search($q, $category),
            'categories' => $this->ebooks->categories(),
            'q' => $q,
            'category' => $category,
        ], 'Katalog E-Book');
    }

    public function show(string $id): void
    {
        $ebook = $this->findOr404((int) $id);
        $this->view('ebooks/show', ['ebook' => $ebook], $ebook->getTitle());
    }

    public function createForm(): void
    {
        $this->requireAdmin();
        $this->view('ebooks/form', [
            'heading' => 'Tambah E-Book', 'action' => '/ebooks/create', 'old' => [], 'errors' => [],
        ], 'Tambah E-Book');
    }

    public function store(): void
    {
        $this->requireAdmin();
        $this->checkCsrf();
        [$data, $errors] = $this->validated($_POST);

        if ($errors) {
            $this->view('ebooks/form', [
                'heading' => 'Tambah E-Book', 'action' => '/ebooks/create', 'old' => $_POST, 'errors' => $errors,
            ], 'Tambah E-Book');
            return;
        }

        $this->ebooks->save(new Ebook(0, ...array_values($data)));
        Session::flash('success', 'E-book berhasil ditambahkan.');
        $this->redirect('/ebooks');
    }

    public function editForm(string $id): void
    {
        $this->requireAdmin();
        $ebook = $this->findOr404((int) $id);
        $this->view('ebooks/form', [
            'heading' => 'Edit E-Book', 'action' => "/ebooks/{$ebook->getId()}/edit",
            'old' => $ebook->toArray(), 'errors' => [],
        ], 'Edit E-Book');
    }

    public function update(string $id): void
    {
        $this->requireAdmin();
        $this->checkCsrf();
        $ebook = $this->findOr404((int) $id);
        [$data, $errors] = $this->validated($_POST);

        if ($errors) {
            $this->view('ebooks/form', [
                'heading' => 'Edit E-Book', 'action' => "/ebooks/{$ebook->getId()}/edit",
                'old' => $_POST, 'errors' => $errors,
            ], 'Edit E-Book');
            return;
        }

        $ebook->update(...array_values($data));
        $this->ebooks->save($ebook);
        Session::flash('success', 'E-book berhasil diperbarui.');
        $this->redirect('/ebooks');
    }

    public function destroy(string $id): void
    {
        $this->requireAdmin();
        $this->checkCsrf();
        $ebook = $this->findOr404((int) $id);
        $this->ebooks->delete($ebook->getId());
        Session::flash('success', 'E-book dihapus.');
        $this->redirect('/ebooks');
    }

    private function findOr404(int $id): Ebook
    {
        $ebook = $this->ebooks->find($id);
        if ($ebook === null) {
            http_response_code(404);
            exit('<h1>E-book tidak ditemukan</h1><a href="/ebooks">Kembali</a>');
        }
        return $ebook;
    }

    /** @return array{0:array<string,mixed>,1:array<string,string>} */
    private function validated(array $in): array
    {
        $data = [
            'title' => trim((string) ($in['title'] ?? '')),
            'author' => trim((string) ($in['author'] ?? '')),
            'category' => trim((string) ($in['category'] ?? '')),
            'year' => (int) ($in['year'] ?? 0),
            'description' => trim((string) ($in['description'] ?? '')),
            'stock' => (int) ($in['stock'] ?? 0),
        ];
        $errors = [];
        if ($data['title'] === '') $errors['title'] = 'Judul wajib diisi.';
        if ($data['author'] === '') $errors['author'] = 'Penulis wajib diisi.';
        if ($data['category'] === '') $errors['category'] = 'Kategori wajib diisi.';
        if ($data['year'] < 1000 || $data['year'] > (int) date('Y') + 1) $errors['year'] = 'Tahun terbit tidak valid.';
        if ($data['stock'] < 0) $errors['stock'] = 'Stok tidak boleh negatif.';

        return [$data, $errors];
    }
}
