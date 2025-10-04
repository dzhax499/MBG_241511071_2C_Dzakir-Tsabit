<div class="card">
    <div class="card-body">
        <h5 class="card-title">Dashboard</h5>
        <p>Selamat datang, <?= esc($user['user_name']) ?> — role: <?= esc($user['role']) ?></p>

        <div class="mt-3">
            <?php if ($user['role'] === 'gudang'): ?>
                <a class="btn btn-outline-primary" href="/bahan">Kelola Bahan Baku</a>
                <a class="btn btn-outline-success" href="/permintaan">Permintaan Bahan</a>
            <?php elseif ($user['role'] === 'dapur'): ?>
                <a class="btn btn-outline-success" href="/permintaan">Permintaan Bahan</a>
            <?php endif; ?>
        </div>
    </div>
</div>