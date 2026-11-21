<?php
declare(strict_types=1);

namespace App\Contracts;

interface Entity
{
    public function getId(): int;
    public function setId(int $id): void;
    public function toArray(): array;
}
