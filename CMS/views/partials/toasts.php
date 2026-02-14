<div class="toast-wrap" id="toastWrap">
    <?php foreach ($flashes as $flash): ?>
        <div class="toast <?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
    <?php endforeach; ?>
</div>
