<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Ebook;

class EbookRepository extends JsonRepository
{
    public function __construct()
    {
        parent::__construct(DATA_PATH . '/ebooks.json');
    }

    protected function hydrate(array $row): Ebook
    {
        return Ebook::fromArray($row);
    }

    /** @return Ebook[] */
    public function search(string $q = '', string $category = ''): array
    {
        return array_values(array_filter($this->all(), function (Ebook $b) use ($q, $category) {
            $matchQ = $q === '' || mb_stripos($b->getTitle() . ' ' . $b->getAuthor(), $q) !== false;
            $matchC = $category === '' || $b->getCategory() === $category;
            return $matchQ && $matchC;
        }));
    }

    /** @return string[] */
    public function categories(): array
    {
        $cats = array_unique(array_map(fn (Ebook $b) => $b->getCategory(), $this->all()));
        sort($cats);
        return $cats;
    }
}
