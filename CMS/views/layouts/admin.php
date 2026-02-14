<!doctype html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | <?= e(App\Core\App::config('app_name')) ?></title>
    <link rel="stylesheet" href="<?= e(asset('assets/pro.css')) ?>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="admin-layout">
    <aside class="sidebar">
        <h2>ConfigMS</h2>
        <a href="<?= e(url('/admin')) ?>">Dashboard</a>
        <a href="<?= e(url('/admin/posts')) ?>">Configurations</a>
        <a href="<?= e(url('/admin/categories')) ?>">Groups</a>
        <a href="<?= e(url('/admin/comments')) ?>">Review Notes</a>
        <a href="<?= e(url('/')) ?>" target="_blank">View Site</a>
        <form method="post" action="<?= e(url('/logout')) ?>">
            <?= csrf_field() ?>
            <button type="submit" class="btn danger w-full">Logout</button>
        </form>
        <button class="btn w-full" id="themeToggle" type="button">Toggle Theme</button>
    </aside>
    <section class="admin-main">
        <?= $content ?>
    </section>
</div>
<?php include __DIR__ . '/../partials/toasts.php'; ?>
<script src="<?= e(asset('assets/pro.js')) ?>"></script>
</body>
</html>
