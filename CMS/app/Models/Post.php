<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class Post
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::pdo();
    }

    public function paginatePublic(int $page, int $perPage, string $search = ''): array
    {
        $offset = max(0, ($page - 1) * $perPage);
        $params = [];
        $where = '';

        if ($search !== '') {
            $where = ' WHERE p.title LIKE :search_title OR p.content LIKE :search_content ';
            $params['search_title'] = '%' . $search . '%';
            $params['search_content'] = '%' . $search . '%';
        }

        $countStmt = $this->db->prepare('SELECT COUNT(*) FROM posts p ' . $where);
        $countStmt->execute($params);
        $total = (int) $countStmt->fetchColumn();

        $sql = <<<SQL
            SELECT p.*, c.name AS category_name
            FROM posts p
            LEFT JOIN categories c ON c.id = p.category_id
            {$where}
            ORDER BY p.created_at DESC
            LIMIT :limit OFFSET :offset
        SQL;
        $stmt = $this->db->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue(':' . $k, $v);
        }
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return [
            'items' => $stmt->fetchAll(),
            'total' => $total,
        ];
    }

    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT p.*, c.name AS category_name FROM posts p LEFT JOIN categories c ON c.id = p.category_id WHERE p.slug = :slug LIMIT 1'
        );
        $stmt->execute(['slug' => $slug]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function adminAll(): array
    {
        $sql = <<<SQL
            SELECT p.*, c.name AS category_name, u.username AS author_name
            FROM posts p
            LEFT JOIN categories c ON c.id = p.category_id
            LEFT JOIN users u ON u.id = p.user_id
            ORDER BY p.created_at DESC
        SQL;
        return $this->db->query($sql)->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM posts WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(array $data): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO posts(user_id, category_id, title, slug, content, image_path, created_at, updated_at)
             VALUES(:user_id, :category_id, :title, :slug, :content, :image_path, NOW(), NOW())'
        );
        $stmt->execute($data);
    }

    public function update(int $id, array $data): void
    {
        $data['id'] = $id;
        $stmt = $this->db->prepare(
            'UPDATE posts
             SET category_id = :category_id, title = :title, slug = :slug, content = :content, image_path = :image_path, updated_at = NOW()
             WHERE id = :id'
        );
        $stmt->execute($data);
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM posts WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    public function slugExists(string $slug, ?int $exceptId = null): bool
    {
        $sql = 'SELECT COUNT(*) FROM posts WHERE slug = :slug';
        $params = ['slug' => $slug];

        if ($exceptId !== null) {
            $sql .= ' AND id != :id';
            $params['id'] = $exceptId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function countAll(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM posts')->fetchColumn();
    }
}
