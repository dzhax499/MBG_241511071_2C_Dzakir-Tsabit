<h3>Buat Permintaan Bahan</h3>

<form method="post" action="/permintaan/store">
    <?= csrf_field() ?>

    <div class="mb-3">
        <label>Tanggal Masak</label>
        <input type="date" name="tgl_masak" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Menu Makan</label>
        <input type="text" name="menu_makan" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Jumlah Porsi</label>
        <input type="number" name="jumlah_porsi" class="form-control" required>
    </div>

    <h5>Pilih Bahan Baku</h5>
    <?php foreach ($bahan as $b): ?>
        <div class="row mb-2 align-items-center">
            <div class="col-md-1">
                <input type="checkbox" name="bahan_id[]" value="<?= $b['id'] ?>" id="bahan<?= $b['id'] ?>">
            </div>
            <div class="col-md-5">
                <label for="bahan<?= $b['id'] ?>"><?= $b['nama'] ?> (stok: <?= $b['jumlah'] . ' ' . $b['satuan'] ?>)</label>
            </div>
            <div class="col-md-4">
                <input type="number" name="jumlah_diminta[<?= $b['id'] ?>]" placeholder="Jumlah" class="form-control">
            </div>
        </div>
    <?php endforeach; ?>

    <button class="btn btn-success">Simpan</button>
    <a href="/permintaan" class="btn btn-secondary">Kembali</a>
</form>