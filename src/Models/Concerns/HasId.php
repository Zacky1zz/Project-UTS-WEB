<?php
declare(strict_types=1);

namespace App\Models\Concerns;

trait HasId
{
    protected int $id = 0;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }
}
