<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class Category
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::pdo();
    }

    public function all(): array
    {
        return $this->db->query('SELECT * FROM categories ORDER BY name ASC')->fetchAll();
    }

    public function create(string $name, string $slug): void
    {
        $stmt = $this->db->prepare('INSERT INTO categories(name, slug) VALUES(:name, :slug)');
        $stmt->execute(['name' => $name, 'slug' => $slug]);
    }

    public function update(int $id, string $name, string $slug): void
    {
        $stmt = $this->db->prepare('UPDATE categories SET name = :name, slug = :slug WHERE id = :id');
        $stmt->execute(['id' => $id, 'name' => $name, 'slug' => $slug]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM categories WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}
