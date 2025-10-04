<h3>Detail Permintaan Bahan</h3>
<table class="table">
    <tr>
        <th>Pemohon</th>
        <td><?= esc($permintaan['pemohon']) ?></td>
    </tr>
    <tr>
        <th>Tanggal Masak</th>
        <td><?= esc($permintaan['tgl_masak']) ?></td>
    </tr>
    <tr>
        <th>Menu</th>
        <td><?= esc($permintaan['menu_makan']) ?></td>
    </tr>
    <tr>
        <th>Porsi</th>
        <td><?= esc($permintaan['jumlah_porsi']) ?></td>
    </tr>
    <tr>
        <th>Status</th>
        <td>
            <?php if ($permintaan['status'] == 'menunggu'): ?>
                <span class="badge bg-warning">Menunggu</span>
            <?php elseif ($permintaan['status'] == 'disetujui'): ?>
                <span class="badge bg-success">Disetujui</span>
            <?php else: ?>
                <span class="badge bg-danger">Ditolak</span>
            <?php endif; ?>
        </td>
    </tr>
    <?php if ($permintaan['status'] == 'ditolak' && !empty($permintaan['alasan'])): ?>
    <tr>
        <th>Alasan Penolakan</th>
        <td><?= esc($permintaan['alasan']) ?></td>
    </tr>
    <?php endif; ?>
</table>

<h5>Daftar Bahan Diminta</h5>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Bahan</th>
            <th>Jumlah Diminta</th>
            <th>Satuan</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($details as $i => $d): ?>
        <tr>
            <td><?= $i+1 ?></td>
            <td><?= esc($d['nama_bahan']) ?></td>
            <td><?= esc($d['jumlah_diminta']) ?></td>
            <td><?= esc($d['satuan']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<a href="/permintaan" class="btn btn-secondary">Kembali</a>