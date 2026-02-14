<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\App;
use App\Core\Auth;
use App\Core\Controller;
use App\Core\Flash;
use App\Core\Response;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;

final class PostController extends Controller
{
    public function index(): void
    {
        $postModel = new Post();
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $search = trim((string) ($_GET['search'] ?? ''));
        $perPage = 5;

        $result = $postModel->paginatePublic($page, $perPage, $search);
        $totalPages = (int) ceil(max(1, $result['total']) / $perPage);

        $this->view('blog/index', [
            'posts' => $result['items'],
            'page' => $page,
            'totalPages' => $totalPages,
            'search' => $search,
        ], 'main');
    }

    public function show(array $params): void
    {
        $postModel = new Post();
        $commentModel = new Comment();
        $slug = $params['slug'] ?? '';

        $post = $postModel->findBySlug($slug);
        if (!$post) {
            http_response_code(404);
            echo 'Configuration record not found';
            return;
        }

        $comments = $commentModel->approvedByPost((int) $post['id']);
        $this->view('blog/show', [
            'post' => $post,
            'comments' => $comments,
        ], 'main');
    }

    public function adminIndex(): void
    {
        $this->view('admin/posts/index', [
            'posts' => (new Post())->adminAll(),
        ], 'admin');
    }

    public function create(): void
    {
        $this->view('admin/posts/form', [
            'categories' => (new Category())->all(),
            'post' => null,
            'action' => '/admin/posts',
        ], 'admin');
    }

    public function store(): void
    {
        $title = trim((string) ($_POST['title'] ?? ''));
        $content = trim((string) ($_POST['content'] ?? ''));
        $categoryId = (int) ($_POST['category_id'] ?? 0);

        if ($title === '' || $content === '') {
            Flash::set('error', 'Title and content are required.');
            Response::redirect('/admin/posts/create');
        }

        $postModel = new Post();
        $slug = $this->uniqueSlug($this->slugify($title));
        $imagePath = $this->handleUpload($_FILES['image'] ?? null);

        $postModel->create([
            'user_id' => Auth::user()['id'],
            'category_id' => $categoryId > 0 ? $categoryId : null,
            'title' => $title,
            'slug' => $slug,
            'content' => $content,
            'image_path' => $imagePath,
        ]);

        Flash::set('success', 'Configuration created successfully.');
        Response::redirect('/admin/posts');
    }

    public function edit(array $params): void
    {
        $id = (int) ($params['id'] ?? 0);
        $post = (new Post())->findById($id);

        if (!$post) {
            Flash::set('error', 'Configuration not found.');
            Response::redirect('/admin/posts');
        }

        $this->view('admin/posts/form', [
            'categories' => (new Category())->all(),
            'post' => $post,
            'action' => '/admin/posts/' . $id . '/update',
        ], 'admin');
    }

    public function update(array $params): void
    {
        $id = (int) ($params['id'] ?? 0);
        $postModel = new Post();
        $existing = $postModel->findById($id);

        if (!$existing) {
            Flash::set('error', 'Configuration not found.');
            Response::redirect('/admin/posts');
        }

        $title = trim((string) ($_POST['title'] ?? ''));
        $content = trim((string) ($_POST['content'] ?? ''));
        $categoryId = (int) ($_POST['category_id'] ?? 0);

        if ($title === '' || $content === '') {
            Flash::set('error', 'Title and content are required.');
            Response::redirect('/admin/posts/' . $id . '/edit');
        }

        $slug = $this->uniqueSlug($this->slugify($title), $id);
        $imagePath = $existing['image_path'];
        $newImagePath = $this->handleUpload($_FILES['image'] ?? null, true);
        if ($newImagePath !== null) {
            $imagePath = $newImagePath;
        }

        $postModel->update($id, [
            'category_id' => $categoryId > 0 ? $categoryId : null,
            'title' => $title,
            'slug' => $slug,
            'content' => $content,
            'image_path' => $imagePath,
        ]);

        Flash::set('success', 'Configuration updated successfully.');
        Response::redirect('/admin/posts');
    }

    public function delete(array $params): void
    {
        $id = (int) ($params['id'] ?? 0);
        (new Post())->delete($id);
        Flash::set('success', 'Configuration deleted.');
        Response::redirect('/admin/posts');
    }

    private function slugify(string $title): string
    {
        $slug = strtolower(trim($title));
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug) ?? '';
        return trim($slug, '-') ?: 'post';
    }

    private function uniqueSlug(string $base, ?int $exceptId = null): string
    {
        $postModel = new Post();
        $slug = $base;
        $i = 1;
        while ($postModel->slugExists($slug, $exceptId)) {
            $slug = $base . '-' . $i;
            $i++;
        }
        return $slug;
    }

    private function handleUpload(?array $file, bool $optional = false): ?string
    {
        if (!$file || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return $optional ? null : '';
        }

        if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
            Flash::set('error', 'Image upload failed.');
            Response::redirect('/admin/posts');
        }

        $maxSize = (int) App::config('upload.max_bytes');
        if (($file['size'] ?? 0) > $maxSize) {
            Flash::set('error', 'Image too large (max 2MB).');
            Response::redirect('/admin/posts');
        }

        $mime = mime_content_type($file['tmp_name']);
        $allowed = App::config('upload.allowed_mime');
        if (!isset($allowed[$mime])) {
            Flash::set('error', 'Invalid image type. Allowed: jpg, png, webp, gif.');
            Response::redirect('/admin/posts');
        }

        $targetDir = App::config('upload.dir');
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0775, true);
        }

        $filename = uniqid('post_', true) . '.' . $allowed[$mime];
        $target = rtrim($targetDir, '/\\') . DIRECTORY_SEPARATOR . $filename;
        move_uploaded_file($file['tmp_name'], $target);

        return rtrim((string) App::config('upload.url'), '/') . '/' . $filename;
    }
}
