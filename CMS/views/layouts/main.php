<!doctype html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e(App\Core\App::config('app_name')) ?></title>
    <link rel="stylesheet" href="<?= e(asset('assets/pro.css')) ?>">
</head>
<body>
    <?php $authUser = App\Core\Auth::user(); ?>
    <header class="site-header">
        <div class="container nav-row">
            <a href="<?= e(url('/')) ?>" class="brand">Config Management Pro</a>
            <div class="nav-actions">
                <?php if ($authUser): ?>
                    <?php if (in_array($authUser['role'], ['admin', 'editor'], true)): ?>
                        <a class="btn ghost" href="<?= e(url('/admin')) ?>">Dashboard</a>
                    <?php else: ?>
                        <span class="btn ghost">User: <?= e($authUser['username']) ?></span>
                    <?php endif; ?>
                    <form method="post" action="<?= e(url('/logout')) ?>" style="display:inline;">
                        <?= csrf_field() ?>
                        <button class="btn ghost" type="submit">Logout</button>
                    </form>
                <?php else: ?>
                    <a class="btn ghost" href="<?= e(url('/login')) ?>">Login</a>
                <?php endif; ?>
                <button class="btn" id="themeToggle" type="button">Toggle Theme</button>
            </div>
        </div>
    </header>
    <main class="container">
        <?= $content ?>
    </main>
    <?php include __DIR__ . '/../partials/toasts.php'; ?>
    <script src="<?= e(asset('assets/pro.js')) ?>"></script>
</body>
</html>
