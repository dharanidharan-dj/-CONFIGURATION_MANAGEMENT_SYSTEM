<div class="topbar">
    <h1>Review Notes Moderation</h1>
</div>

<section class="card">
    <table class="table">
        <thead>
            <tr>
                <th>Configuration</th>
                <th>Author</th>
                <th>Review Note</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($comments as $comment): ?>
                <tr>
                    <td><?= e($comment['post_title']) ?></td>
                    <td><?= e($comment['author_name']) ?><br><small><?= e($comment['author_email']) ?></small></td>
                    <td><?= e($comment['body']) ?></td>
                    <td>
                        <span class="badge <?= e($comment['status']) ?>"><?= e($comment['status']) ?></span>
                    </td>
                    <td class="actions">
                        <?php if ($comment['status'] !== 'approved'): ?>
                        <form method="post" action="<?= e(url('/admin/comments/' . $comment['id'] . '/approve')) ?>">
                            <?= csrf_field() ?>
                            <button class="btn" type="submit">Approve</button>
                        </form>
                        <?php endif; ?>
                        <form method="post" action="<?= e(url('/admin/comments/' . $comment['id'] . '/delete')) ?>">
                            <?= csrf_field() ?>
                            <button class="btn danger" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>
