<div class="topbar">
    <h1>Configuration Groups</h1>
</div>

<section class="card">
    <h2>Add Group</h2>
    <form method="post" action="<?= e(url('/admin/categories')) ?>" class="row-form">
        <?= csrf_field() ?>
        <input type="text" name="name" class="light-input" placeholder="Group name" required>
        <button class="btn" type="submit">Add</button>
    </form>
</section>

<section class="card">
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Slug</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $category): ?>
                <tr>
                    <td>
                        <form method="post" action="<?= e(url('/admin/categories/' . $category['id'] . '/update')) ?>" class="row-form">
                            <?= csrf_field() ?>
                            <input type="text" name="name" class="light-input" value="<?= e($category['name']) ?>" required>
                            <button class="btn ghost" type="submit">Save</button>
                        </form>
                    </td>
                    <td><?= e($category['slug']) ?></td>
                    <td>
                        <?php if (App\Core\Auth::user()['role'] === 'admin'): ?>
                        <form method="post" action="<?= e(url('/admin/categories/' . $category['id'] . '/delete')) ?>">
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
