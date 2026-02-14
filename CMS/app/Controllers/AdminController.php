<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;

final class AdminController extends Controller
{
    public function dashboard(): void
    {
        $postModel = new Post();
        $categoryModel = new Category();
        $commentModel = new Comment();

        $this->view('admin/dashboard', [
            'totalPosts' => $postModel->countAll(),
            'totalCategories' => count($categoryModel->all()),
            'totalComments' => $commentModel->countAll(),
        ], 'admin');
    }
}
