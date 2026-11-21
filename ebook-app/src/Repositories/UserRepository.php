<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;

class UserRepository extends JsonRepository
{
    public function __construct()
    {
        parent::__construct(DATA_PATH . '/users.json');
    }

    protected function hydrate(array $row): User
    {
        return User::fromArray($row);
    }

    public function findByEmail(string $email): ?User
    {
        foreach ($this->all() as $user) {
            if (strcasecmp($user->getEmail(), $email) === 0) {
                return $user;
            }
        }
        return null;
    }
}
