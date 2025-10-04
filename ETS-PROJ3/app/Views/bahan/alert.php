<h3>Konfirmasi Hapus Bahan Baku</h3>
<div class="alert alert-warning">
    <strong>Perhatian!</strong> Data berikut akan dihapus secara permanen:
</div>
<ul>
    <li>Nama: <?= esc($bahan['nama']) ?></li>
    <li>Kategori: <?= esc($bahan['kategori']) ?></li>
    <li>Jumlah: <?= esc($bahan['jumlah']) ?></li>
    <li>Status: <?= esc($bahan['status']) ?></li>
</ul>
<form method="post" action="/bahan/destroy/<?= $bahan['id'] ?>">
    <?= csrf_field() ?>
    <button class="btn btn-danger">Hapus</button>
    <a href="/bahan" class="btn btn-secondary">Batal</a>
</form>