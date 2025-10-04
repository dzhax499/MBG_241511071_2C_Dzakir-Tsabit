<h3>Daftar Permintaan</h3>
<?php if (session()->get('role') === 'dapur'): ?>
    <a href="/permintaan/create" class="btn btn-primary mb-3">+ Permintaan Baru</a>
<?php endif; ?>
<a href="/dashboard" class="btn btn-secondary mb-3">Kembali</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>Pemohon</th>
            <th>Tanggal Masak</th>
            <th>Menu</th>
            <th>Porsi</th>
            <th>Status</th>
            <?php if (session()->get('role') === 'gudang'): ?>
                <th>Aksi</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php if ($permintaan): $no = 1;
            foreach ($permintaan as $p): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= esc($p['pemohon']) ?></td>
                    <td><?= esc($p['tgl_masak']) ?></td>
                    <td><?= esc($p['menu_makan']) ?></td>
                    <td><?= esc($p['jumlah_porsi']) ?></td>
                    <td>
                        <?php if ($p['status'] == 'menunggu'): ?>
                            <span class="badge bg-warning">Menunggu</span>
                        <?php elseif ($p['status'] == 'disetujui'): ?>
                            <span class="badge bg-success">Disetujui</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Ditolak</span>
                        <?php endif; ?>

                        <?php if (session()->getFlashdata('error') && session()->get('role') === 'dapur'): ?>
                            <br><small class="text-danger"><?= session()->getFlashdata('error') ?></small>
                        <?php endif; ?>
                    </td>

                    <?php if (session()->get('role') === 'gudang'): ?>
                        <td>soon!</td>
                    <?php endif; ?>
                </tr>
            <?php endforeach;
        else: ?>
            <tr>
                <td colspan="7" class="text-center">Belum ada permintaan</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>