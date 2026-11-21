<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\Entity;

/** Basis penyimpanan berbasis file JSON. Subclass cukup mendefinisikan hydrate(). */
abstract class JsonRepository
{
    public function __construct(private string $file)
    {
    }

    abstract protected function hydrate(array $row): Entity;

    /** @return Entity[] */
    public function all(): array
    {
        return array_map(fn (array $row) => $this->hydrate($row), $this->read());
    }

    public function find(int $id): ?Entity
    {
        foreach ($this->read() as $row) {
            if ((int) $row['id'] === $id) {
                return $this->hydrate($row);
            }
        }
        return null;
    }

    /** Insert (id = 0) atau update. */
    public function save(Entity $entity): Entity
    {
        $rows = $this->read();

        if ($entity->getId() === 0) {
            $entity->setId($rows ? max(array_column($rows, 'id')) + 1 : 1);
            $rows[] = $entity->toArray();
        } else {
            foreach ($rows as $i => $row) {
                if ((int) $row['id'] === $entity->getId()) {
                    $rows[$i] = $entity->toArray();
                }
            }
        }

        $this->write($rows);
        return $entity;
    }

    public function delete(int $id): void
    {
        $rows = array_values(array_filter($this->read(), fn ($r) => (int) $r['id'] !== $id));
        $this->write($rows);
    }

    private function read(): array
    {
        if (!is_file($this->file)) {
            return [];
        }
        $data = json_decode((string) file_get_contents($this->file), true);
        return is_array($data) ? $data : [];
    }

    private function write(array $rows): void
    {
        file_put_contents(
            $this->file,
            json_encode(array_values($rows), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            LOCK_EX
        );
    }
}
