<article class="card post-view">
    <a class="btn ghost" href="<?= e(url('/')) ?>">Back</a>
    <h1><?= e($post['title']) ?></h1>
    <p class="muted"><?= e($post['category_name'] ?? 'Ungrouped') ?> · <?= e($post['created_at']) ?></p>
    <?php if (!empty($post['image_path'])): ?>
        <img src="<?= e(url($post['image_path'])) ?>" class="hero-image" alt="<?= e($post['title']) ?>">
    <?php endif; ?>
    <div class="content-body"><?= $post['content'] ?></div>
</article>

<section class="card">
    <h2>Add Review Note</h2>
    <form method="post" action="<?= e(url('/config/' . $post['slug'] . '/note')) ?>" class="form-grid">
        <?= csrf_field() ?>
        <input type="text" name="author_name" placeholder="Your name" required>
        <input type="email" name="author_email" placeholder="Your email" required>
        <textarea name="body" rows="4" placeholder="Add your review note..." required></textarea>
        <button class="btn" type="submit">Submit Note</button>
    </form>
</section>

<section class="card">
    <h2>Review Notes</h2>
    <?php if (empty($comments)): ?>
        <p class="muted">No approved review notes yet.</p>
    <?php endif; ?>
    <?php foreach ($comments as $comment): ?>
        <div class="comment-item">
            <strong><?= e($comment['author_name']) ?></strong>
            <span class="muted"><?= e($comment['created_at']) ?></span>
            <p><?= nl2br(e($comment['body'])) ?></p>
        </div>
    <?php endforeach; ?>
</section>
