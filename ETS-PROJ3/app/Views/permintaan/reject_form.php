<h3>Alasan Penolakan Permintaan</h3>
<form method="post" action="/permintaan/reject/<?= $permintaan['id'] ?>">
    <?= csrf_field() ?>
    <div class="mb-3">
        <label>Alasan Penolakan</label>
        <textarea name="alasan" class="form-control" required></textarea>
    </div>
    <button class="btn btn-danger">Tolak Permintaan</button>
    <a href="/permintaan" class="btn btn-secondary">Batal</a>
</form>