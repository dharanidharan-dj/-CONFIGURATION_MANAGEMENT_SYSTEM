<?php

declare(strict_types=1);

require __DIR__ . '/app/bootstrap.php';

use App\Controllers\AdminController;
use App\Controllers\AuthController;
use App\Controllers\CategoryController;
use App\Controllers\CommentController;
use App\Controllers\PostController;
use App\Core\Router;

$router = new Router();

// Public routes
$router->get('/', [PostController::class, 'index']);
$router->get('/config/{slug}', [PostController::class, 'show']);
$router->post('/config/{slug}/note', [CommentController::class, 'store']);
$router->get('/post/{slug}', [PostController::class, 'show']);
$router->post('/post/{slug}/comment', [CommentController::class, 'store']);

// Authentication
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->post('/logout', [AuthController::class, 'logout']);

// Admin dashboard
$router->get('/admin', [AdminController::class, 'dashboard'], ['admin', 'editor']);

// Posts (admin/editor)
$router->get('/admin/posts', [PostController::class, 'adminIndex'], ['admin', 'editor']);
$router->get('/admin/posts/create', [PostController::class, 'create'], ['admin', 'editor']);
$router->post('/admin/posts', [PostController::class, 'store'], ['admin', 'editor']);
$router->get('/admin/posts/{id}/edit', [PostController::class, 'edit'], ['admin', 'editor']);
$router->post('/admin/posts/{id}/update', [PostController::class, 'update'], ['admin', 'editor']);
$router->post('/admin/posts/{id}/delete', [PostController::class, 'delete'], ['admin']);

// Categories (admin/editor)
$router->get('/admin/categories', [CategoryController::class, 'index'], ['admin', 'editor']);
$router->post('/admin/categories', [CategoryController::class, 'store'], ['admin', 'editor']);
$router->post('/admin/categories/{id}/update', [CategoryController::class, 'update'], ['admin', 'editor']);
$router->post('/admin/categories/{id}/delete', [CategoryController::class, 'delete'], ['admin']);

// Comments moderation (admin/editor)
$router->get('/admin/comments', [CommentController::class, 'adminIndex'], ['admin', 'editor']);
$router->post('/admin/comments/{id}/approve', [CommentController::class, 'approve'], ['admin', 'editor']);
$router->post('/admin/comments/{id}/delete', [CommentController::class, 'delete'], ['admin', 'editor']);

$router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $_SERVER['REQUEST_URI'] ?? '/');
