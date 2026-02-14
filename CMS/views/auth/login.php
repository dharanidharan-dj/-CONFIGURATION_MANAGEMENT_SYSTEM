<section class="auth-shell">
    <div class="auth-card">
        <h1>Sign In</h1>
        <p>Professional Configuration Management access with role permissions and CSRF protection.</p>
        <form method="post" action="<?= e(url('/login')) ?>" class="form-stack">
            <?= csrf_field() ?>
            <label>Username</label>
            <input type="text" name="username" required>
            <label>Password</label>
            <input type="password" name="password" required>
            <button class="btn" type="submit">Login</button>
        </form>
    </div>
</section>
