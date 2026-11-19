<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;

class AuthController extends Controller
{
    public function loginForm(): void
    {
        $this->view('auth/login', ['old' => [], 'error' => null], 'Masuk');
    }

    public function login(): void
    {
        $this->checkCsrf();
        $email = (string) ($_POST['email'] ?? '');
        $user = $this->auth->login($email, (string) ($_POST['password'] ?? ''));

        if ($user === null) {
            $this->view('auth/login', ['old' => ['email' => $email], 'error' => 'Email atau password salah.'], 'Masuk');
            return;
        }
        Session::flash('success', 'Selamat datang, ' . $user->getName() . '!');
        $this->redirect('/ebooks');
    }

    public function registerForm(): void
    {
        $this->view('auth/register', ['old' => [], 'error' => null], 'Daftar');
    }

    public function register(): void
    {
        $this->checkCsrf();
        try {
            $this->auth->register(
                (string) ($_POST['name'] ?? ''),
                (string) ($_POST['email'] ?? ''),
                (string) ($_POST['password'] ?? ''),
                (string) ($_POST['confirm'] ?? '')
            );
        } catch (\InvalidArgumentException $e) {
            $this->view('auth/register', ['old' => $_POST, 'error' => $e->getMessage()], 'Daftar');
            return;
        }
        Session::flash('success', 'Pendaftaran berhasil. Silakan masuk.');
        $this->redirect('/login');
    }

    public function logout(): void
    {
        $this->checkCsrf();
        $this->auth->logout();
        Session::flash('success', 'Kamu sudah keluar.');
        $this->redirect('/login');
    }
}
