<div class="topbar">
    <h1>Dashboard</h1>
    <p class="muted">Welcome, <?= e(App\Core\Auth::user()['username']) ?> (<?= e(App\Core\Auth::user()['role']) ?>)</p>
</div>

<section class="cards-grid admin-cards">
    <div class="card stat"><h3><?= (int) $totalPosts ?></h3><p>Total Configurations</p></div>
    <div class="card stat"><h3><?= (int) $totalCategories ?></h3><p>Total Groups</p></div>
    <div class="card stat"><h3><?= (int) $totalComments ?></h3><p>Total Review Notes</p></div>
</section>

<section class="card">
    <h2>Configuration Overview</h2>
    <canvas id="overviewChart" height="90"></canvas>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('overviewChart');
    if (!ctx) return;
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Configurations', 'Groups', 'Review Notes'],
            datasets: [{
                data: [<?= (int) $totalPosts ?>, <?= (int) $totalCategories ?>, <?= (int) $totalComments ?>],
                backgroundColor: ['#67cdff', '#8ddfff', '#b5ecff']
            }]
        },
        options: { responsive: true, plugins: { legend: { display: false } } }
    });
});
</script>
