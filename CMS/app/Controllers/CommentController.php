<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Flash;
use App\Core\Response;
use App\Models\Comment;
use App\Models\Post;

final class CommentController extends Controller
{
    public function store(array $params): void
    {
        $slug = (string) ($params['slug'] ?? '');
        $post = (new Post())->findBySlug($slug);
        if (!$post) {
            Response::redirect('/');
        }

        $name = trim((string) ($_POST['author_name'] ?? ''));
        $email = trim((string) ($_POST['author_email'] ?? ''));
        $body = trim((string) ($_POST['body'] ?? ''));

        if ($name === '' || $email === '' || $body === '') {
            Flash::set('error', 'All review note fields are required.');
            Response::redirect('/config/' . $slug);
        }

        (new Comment())->create([
            'post_id' => (int) $post['id'],
            'author_name' => $name,
            'author_email' => $email,
            'body' => $body,
        ]);

        Flash::set('success', 'Review note submitted and awaiting moderation.');
        Response::redirect('/config/' . $slug);
    }

    public function adminIndex(): void
    {
        $this->view('admin/comments/index', [
            'comments' => (new Comment())->pendingAndApproved(),
        ], 'admin');
    }

    public function approve(array $params): void
    {
        $id = (int) ($params['id'] ?? 0);
        (new Comment())->approve($id);
        Flash::set('success', 'Review note approved.');
        Response::redirect('/admin/comments');
    }

    public function delete(array $params): void
    {
        $id = (int) ($params['id'] ?? 0);
        (new Comment())->delete($id);
        Flash::set('success', 'Review note deleted.');
        Response::redirect('/admin/comments');
    }
}
