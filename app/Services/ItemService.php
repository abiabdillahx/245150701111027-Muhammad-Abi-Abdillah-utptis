<?php
namespace App\Services;

class ItemService
{
    protected string $path;

    public function __construct()
    {
        $this->path = storage_path('app/data/items.json');
    }

    public function getAll(): array
    {
        return json_decode(file_get_contents($this->path), true) ?? [];
    }

    public function save(array $items): void
    {
        file_put_contents($this->path, json_encode($items, JSON_PRETTY_PRINT));
    }

    public function generateId(): int
    {
        $items = $this->getAll();
        return count($items) > 0 ? max(array_column($items, 'id')) + 1 : 1;
    }

    public function update(int $id, array $data): array|null
    {
        $items = $this->getAll();
        foreach ($items as &$item) {
            if ($item['id'] === $id) {
                $item['nama'] = $data['nama'];
                $item['harga'] = $data['harga'];
                $this->save($items);
                return $item;
            }
        }
        return null;
    }

    public function partialUpdate(int $id, array $data): array|null
    {
        $items = $this->getAll();
        foreach ($items as &$item) {
            if ($item['id'] === $id) {
                if (isset($data['nama'])) $item['nama'] = $data['nama'];
                if (isset($data['harga'])) $item['harga'] = $data['harga'];
                $this->save($items);
                return $item;
            }
        }
        return null;
    }
}