<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Flash;
use App\Core\Response;
use App\Models\Category;

final class CategoryController extends Controller
{
    public function index(): void
    {
        $this->view('admin/categories/index', [
            'categories' => (new Category())->all(),
        ], 'admin');
    }

    public function store(): void
    {
        $name = trim((string) ($_POST['name'] ?? ''));
        if ($name === '') {
            Flash::set('error', 'Group name is required.');
            Response::redirect('/admin/categories');
        }

        $slug = $this->slugify($name);
        (new Category())->create($name, $slug);
        Flash::set('success', 'Group created.');
        Response::redirect('/admin/categories');
    }

    public function update(array $params): void
    {
        $id = (int) ($params['id'] ?? 0);
        $name = trim((string) ($_POST['name'] ?? ''));
        if ($name === '') {
            Flash::set('error', 'Group name is required.');
            Response::redirect('/admin/categories');
        }

        (new Category())->update($id, $name, $this->slugify($name));
        Flash::set('success', 'Group updated.');
        Response::redirect('/admin/categories');
    }

    public function delete(array $params): void
    {
        $id = (int) ($params['id'] ?? 0);
        (new Category())->delete($id);
        Flash::set('success', 'Group deleted.');
        Response::redirect('/admin/categories');
    }

    private function slugify(string $value): string
    {
        $slug = strtolower(trim($value));
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug) ?? '';
        return trim($slug, '-') ?: 'category';
    }
}
