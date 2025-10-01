<h3>Edit Bahan Baku</h3>

<form method="post" action="/bahan/update/<?= $bahan['id'] ?>">
    <?= csrf_field() ?>

    <div class="mb-3">
        <label class="form-label">Nama</label>
        <input type="text" name="nama" value="<?= old('nama', $bahan['nama']) ?>" class="form-control">
        <?= session('errors.nama') ? '<div class="text-danger">' . session('errors.nama') . '</div>' : '' ?>
    </div>

    <div class="mb-3">
        <label class="form-label">Kategori</label>
        <input type="text" name="kategori" value="<?= old('kategori', $bahan['kategori']) ?>" class="form-control">
        <?= session('errors.kategori') ? '<div class="text-danger">' . session('errors.kategori') . '</div>' : '' ?>
    </div>

    <div class="mb-3">
        <label class="form-label">Jumlah</label>
        <input type="number" name="jumlah" value="<?= old('jumlah', $bahan['jumlah']) ?>" class="form-control">
        <?= session('errors.jumlah') ? '<div class="text-danger">' . session('errors.jumlah') . '</div>' : '' ?>
    </div>

    <div class="mb-3">
        <label class="form-label">Satuan</label>
        <input type="text" name="satuan" value="<?= old('satuan', $bahan['satuan']) ?>" class="form-control">
        <?= session('errors.satuan') ? '<div class="text-danger">' . session('errors.satuan') . '</div>' : '' ?>
    </div>

    <div class="mb-3">
        <label class="form-label">Tanggal Masuk</label>
        <input type="date" name="tanggal_masuk" value="<?= old('tanggal_masuk', $bahan['tanggal_masuk']) ?>" class="form-control">
        <?= session('errors.tanggal_masuk') ? '<div class="text-danger">' . session('errors.tanggal_masuk') . '</div>' : '' ?>
    </div>

    <div class="mb-3">
        <label class="form-label">Tanggal Kadaluarsa</label>
        <input type="date" name="tanggal_kadaluarsa" value="<?= old('tanggal_kadaluarsa', $bahan['tanggal_kadaluarsa']) ?>" class="form-control">
        <?= session('errors.tanggal_kadaluarsa') ? '<div class="text-danger">' . session('errors.tanggal_kadaluarsa') . '</div>' : '' ?>
    </div>

    <button class="btn btn-success">Update</button>
    <a href="/bahan" class="btn btn-secondary">Kembali</a>
</form>