<?php
declare(strict_types=1);

namespace App\Services;

use App\Core\Session;
use App\Models\Member;
use App\Models\User;
use App\Repositories\UserRepository;

class AuthService
{
    public function __construct(private UserRepository $users)
    {
    }

    /** @throws \InvalidArgumentException */
    public function register(string $name, string $email, string $password, string $confirm): User
    {
        $name = trim($name);
        $email = trim($email);

        if ($name === '' || mb_strlen($name) < 3) {
            throw new \InvalidArgumentException('Nama minimal 3 karakter.');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Format email tidak valid.');
        }
        if (strlen($password) < 6) {
            throw new \InvalidArgumentException('Password minimal 6 karakter.');
        }
        if ($password !== $confirm) {
            throw new \InvalidArgumentException('Konfirmasi password tidak cocok.');
        }
        if ($this->users->findByEmail($email) !== null) {
            throw new \InvalidArgumentException('Email sudah terdaftar.');
        }

        $member = new Member(0, $name, $email, password_hash($password, PASSWORD_DEFAULT));
        $this->users->save($member);
        return $member;
    }

    public function login(string $email, string $password): ?User
    {
        $user = $this->users->findByEmail(trim($email));
        if ($user === null || !$user->verifyPassword($password)) {
            return null;
        }
        Session::regenerate();
        Session::set('user_id', $user->getId());
        return $user;
    }

    public function user(): ?User
    {
        $id = Session::get('user_id');
        return $id ? $this->users->find((int) $id) : null;
    }

    public function logout(): void
    {
        Session::forget('user_id');
        Session::regenerate();
    }
}
