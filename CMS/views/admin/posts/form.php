<div class="topbar">
    <h1><?= $post ? 'Edit Configuration' : 'Create Configuration' ?></h1>
    <a class="btn ghost" href="<?= e(url('/admin/posts')) ?>">Back</a>
</div>

<section class="card">
    <form method="post" enctype="multipart/form-data" action="<?= e(url($action)) ?>" class="form-grid">
        <?= csrf_field() ?>
        <label>Title</label>
        <input type="text" name="title" required value="<?= e($post['title'] ?? '') ?>">

        <label>Group</label>
        <select name="category_id">
            <option value="0">Ungrouped</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?= (int) $category['id'] ?>" <?= ($post['category_id'] ?? 0) == $category['id'] ? 'selected' : '' ?>>
                    <?= e($category['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Reference Image (max 2MB, jpg/png/webp/gif)</label>
        <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp,.gif">
        <?php if (!empty($post['image_path'])): ?>
            <img src="<?= e(url($post['image_path'])) ?>" class="thumb" alt="Current image">
        <?php endif; ?>

        <label>Configuration Details</label>
        <textarea
            id="editor"
            name="content"
            rows="14"
            style="background:#f7fcff;color:#17324f;min-height:320px;pointer-events:auto;position:relative;z-index:2;"
        ><?= e($post['content'] ?? '') ?></textarea>
        <button class="btn" type="submit"><?= $post ? 'Update Configuration' : 'Save Configuration' ?></button>
    </form>
</section>
