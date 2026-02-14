<section class="hero">
    <h1>Configuration Management Portal</h1>
    <p>Search, paginate, and browse configuration records by group in a premium UI.</p>
    <form method="get" action="<?= e(url('/')) ?>" class="search-bar">
        <input type="text" name="search" value="<?= e($search) ?>" placeholder="Search configuration title or details...">
        <button class="btn" type="submit">Search</button>
    </form>
</section>

<section class="cards-grid">
    <?php foreach ($posts as $post): ?>
        <article class="card">
            <?php if (!empty($post['image_path'])): ?>
                <img src="<?= e(url($post['image_path'])) ?>" alt="<?= e($post['title']) ?>" class="thumb">
            <?php endif; ?>
            <h3><?= e($post['title']) ?></h3>
            <p class="muted">
                <?= e($post['category_name'] ?? 'Ungrouped') ?> · <?= e($post['created_at']) ?>
            </p>
            <p><?= e(substr(strip_tags($post['content']), 0, 140)) ?>...</p>
            <a class="btn ghost" href="<?= e(url('/config/' . $post['slug'])) ?>">View Details</a>
        </article>
    <?php endforeach; ?>
</section>

<?php if (empty($posts)): ?>
    <div class="card">No posts found.</div>
<?php endif; ?>

<nav class="pagination">
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a class="page-link <?= $i === $page ? 'active' : '' ?>"
           href="<?= e(url('/?page=' . $i . ($search !== '' ? '&search=' . urlencode($search) : ''))) ?>">
            <?= $i ?>
        </a>
    <?php endfor; ?>
</nav>
