<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class Comment
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::pdo();
    }

    public function approvedByPost(int $postId): array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM comments WHERE post_id = :post_id AND status = "approved" ORDER BY created_at DESC'
        );
        $stmt->execute(['post_id' => $postId]);
        return $stmt->fetchAll();
    }

    public function pendingAndApproved(): array
    {
        $sql = <<<SQL
            SELECT cm.*, p.title AS post_title
            FROM comments cm
            JOIN posts p ON p.id = cm.post_id
            ORDER BY cm.created_at DESC
        SQL;
        return $this->db->query($sql)->fetchAll();
    }

    public function create(array $data): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO comments(post_id, author_name, author_email, body, status, created_at)
             VALUES(:post_id, :author_name, :author_email, :body, "pending", NOW())'
        );
        $stmt->execute($data);
    }

    public function approve(int $id): void
    {
        $stmt = $this->db->prepare('UPDATE comments SET status = "approved" WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM comments WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    public function countAll(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM comments')->fetchColumn();
    }
}
