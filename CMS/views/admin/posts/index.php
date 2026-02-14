<div class="topbar">
    <h1>Configurations</h1>
    <a class="btn" href="<?= e(url('/admin/posts/create')) ?>">Create Configuration</a>
</div>

<section class="card">
    <table class="table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Group</th>
                <th>Author</th>
                <th>Slug</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($posts as $post): ?>
                <tr>
                    <td><?= e($post['title']) ?></td>
                    <td><?= e($post['category_name'] ?? 'Ungrouped') ?></td>
                    <td><?= e($post['author_name'] ?? '-') ?></td>
                    <td><?= e($post['slug']) ?></td>
                    <td class="actions">
                        <a class="btn ghost" href="<?= e(url('/admin/posts/' . $post['id'] . '/edit')) ?>">Edit</a>
                        <?php if (App\Core\Auth::user()['role'] === 'admin'): ?>
                        <form method="post" action="<?= e(url('/admin/posts/' . $post['id'] . '/delete')) ?>">
                            <?= csrf_field() ?>
                            <button class="btn danger" type="submit">Delete</button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>
