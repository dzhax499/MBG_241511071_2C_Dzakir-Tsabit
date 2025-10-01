<h3>Daftar Bahan Baku</h3>
<a href="/bahan/create" class="btn btn-primary mb-3">+ Tambah Bahan</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Kategori</th>
            <th>Jumlah</th>
            <th>Satuan</th>
            <th>Tanggal Masuk</th>
            <th>Tanggal Kadaluarsa</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($bahan): $no = 1;
            foreach ($bahan as $b): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= esc($b['nama']) ?></td>
                    <td><?= esc($b['kategori']) ?></td>
                    <td><?= esc($b['jumlah']) ?></td>
                    <td><?= esc($b['satuan']) ?></td>
                    <td><?= esc($b['tanggal_masuk']) ?></td>
                    <td><?= esc($b['tanggal_kadaluarsa']) ?></td>
                    <td><?= esc($b['status']) ?></td>
                </tr>
            <?php endforeach;
        else: ?>
            <tr>
                <td colspan="8" class="text-center">Belum ada data</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>