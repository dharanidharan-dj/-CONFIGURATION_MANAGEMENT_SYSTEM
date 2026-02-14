<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Flash;
use App\Core\Response;

final class AuthController extends Controller
{
    public function showLogin(): void
    {
        if (Auth::check()) {
            $role = Auth::user()['role'] ?? 'user';
            Response::redirect(in_array($role, ['admin', 'editor'], true) ? '/admin' : '/');
        }
        $this->view('auth/login');
    }

    public function login(): void
    {
        $username = trim($_POST['username'] ?? '');
        $password = (string) ($_POST['password'] ?? '');

        if ($username === '' || $password === '') {
            Flash::set('error', 'Username and password are required.');
            Response::redirect('/login');
        }

        if (!Auth::attempt($username, $password)) {
            Flash::set('error', 'Invalid credentials.');
            Response::redirect('/login');
        }

        Flash::set('success', 'Login successful.');
        $role = Auth::user()['role'] ?? 'user';
        Response::redirect(in_array($role, ['admin', 'editor'], true) ? '/admin' : '/');
    }

    public function logout(): void
    {
        Auth::logout();
        Flash::set('success', 'You have been logged out.');
        Response::redirect('/login');
    }
}
