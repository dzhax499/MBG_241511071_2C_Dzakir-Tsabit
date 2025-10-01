<div class="card">
    <div class="card-body">
        <h5 class="card-title">Dashboard</h5>
        <p>Selamat datang, <?= esc($user['user_name']) ?> — role: <?= esc($user['role']) ?></p>

        <div class="mt-3">
            <a class="btn btn-outline-primary" href="/bahan">Kelola Bahan Baku</a>
        </div>
    </div>
</div>