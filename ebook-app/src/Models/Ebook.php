<?php
declare(strict_types=1);

namespace App\Models;

use App\Contracts\Borrowable;
use App\Contracts\Entity;
use App\Models\Concerns\HasId;

class Ebook implements Entity, Borrowable
{
    use HasId;

    public function __construct(
        int $id,
        private string $title,
        private string $author,
        private string $category,
        private int $year,
        private string $description,
        private int $stock
    ) {
        $this->id = $id;
    }

    public function getTitle(): string { return $this->title; }
    public function getAuthor(): string { return $this->author; }
    public function getCategory(): string { return $this->category; }
    public function getYear(): int { return $this->year; }
    public function getDescription(): string { return $this->description; }
    public function getStock(): int { return $this->stock; }

    public function update(string $title, string $author, string $category, int $year, string $description, int $stock): void
    {
        $this->title = $title;
        $this->author = $author;
        $this->category = $category;
        $this->year = $year;
        $this->description = $description;
        $this->stock = max(0, $stock);
    }

    public function isAvailable(): bool
    {
        return $this->stock > 0;
    }

    public function borrow(): void
    {
        if (!$this->isAvailable()) {
            throw new \DomainException('Stok e-book habis.');
        }
        $this->stock--;
    }

    public function giveBack(): void
    {
        $this->stock++;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id, 'title' => $this->title, 'author' => $this->author,
            'category' => $this->category, 'year' => $this->year,
            'description' => $this->description, 'stock' => $this->stock,
        ];
    }

    public static function fromArray(array $r): static
    {
        return new static(
            (int) $r['id'], $r['title'], $r['author'], $r['category'],
            (int) $r['year'], $r['description'] ?? '', (int) $r['stock']
        );
    }
}
