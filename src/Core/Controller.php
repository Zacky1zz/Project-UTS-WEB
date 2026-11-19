<?php
declare(strict_types=1);

namespace App\Core;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\AuthService;

abstract class Controller
{
    protected AuthService $auth;

    public function __construct()
    {
        $this->auth = new AuthService(new UserRepository());
    }

    protected function view(string $view, array $data = [], string $title = ''): void
    {
        $user = $this->auth->user();
        extract($data, EXTR_SKIP);

        ob_start();
        require BASE_PATH . "/src/Views/{$view}.php";
        $content = ob_get_clean();

        require BASE_PATH . '/src/Views/layout.php';
    }

    protected function redirect(string $to): never
    {
        header('Location: ' . $to);
        exit;
    }

    protected function checkCsrf(): void
    {
        if (!Session::verifyCsrf($_POST['_csrf'] ?? null)) {
            http_response_code(419);
            exit('Sesi tidak valid. Silakan muat ulang halaman.');
        }
    }

    protected function requireLogin(): User
    {
        $user = $this->auth->user();
        if ($user === null) {
            Session::flash('error', 'Silakan masuk terlebih dahulu.');
            $this->redirect('/login');
        }
        return $user;
    }

    protected function requireAdmin(): User
    {
        $user = $this->requireLogin();
        if ($user->getRole() !== 'admin') {
            http_response_code(403);
            exit('Akses ditolak: khusus admin.');
        }
        return $user;
    }
}
