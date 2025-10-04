<?php namespace App\Controllers;

use App\Models\PermintaanModel;
use App\Models\PermintaanDetailModel;
use App\Models\BahanModel;

class Permintaan extends BaseController
{
    public function index()
    {
        $permintaanModel = new PermintaanModel();
        $role = session()->get('role');
        $userId = session()->get('user_id');

        if ($role === 'gudang') {
            // Gudang: tampilkan semua permintaan
            $permintaan = $permintaanModel
                ->select('permintaan.*, user.name as pemohon')
                ->join('user', 'user.id=permintaan.pemohon_id')
                ->orderBy('created_at', 'DESC')
                ->findAll();
        } else {
            // Dapur: tampilkan permintaan miliknya sendiri
            $permintaan = $permintaanModel
                ->select('permintaan.*, user.name as pemohon')
                ->join('user', 'user.id=permintaan.pemohon_id')
                ->where('pemohon_id', $userId)
                ->orderBy('created_at', 'DESC')
                ->findAll();
        }

        $data = [
            'title' => 'Daftar Permintaan',
            'permintaan' => $permintaan
        ];

        return view('templates/header', $data)
            . view('permintaan/index', $data)
            . view('templates/footer');
    }

    public function create()
    {
        $bahanModel = new BahanModel();
        $data = [
            'title' => 'Buat Permintaan Baru',
            'bahan' => $bahanModel->findAll()
        ];

        echo view('templates/header', $data);
        echo view('permintaan/create', $data);
        echo view('templates/footer');
    }

    public function store()
    {
        $db = \Config\Database::connect();
        $db->transStart();

        $permintaanModel = new \App\Models\PermintaanModel();
        $detailModel = new \App\Models\PermintaanDetailModel();

        $permintaanId = $permintaanModel->insert([
            'pemohon_id'   => session()->get('user_id'),
            'tgl_masak'    => $this->request->getPost('tgl_masak'),
            'menu_makan'   => $this->request->getPost('menu_makan'),
            'jumlah_porsi' => $this->request->getPost('jumlah_porsi'),
            'status'       => 'menunggu',
            'created_at'   => date('Y-m-d H:i:s')
        ]);

        if (! $permintaanId) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Gagal menyimpan permintaan')->withInput();
        }

        $bahan_ids = $this->request->getPost('bahan_id') ?? [];
        $jumlahs   = $this->request->getPost('jumlah_diminta') ?? [];

        foreach ($bahan_ids as $bahanId) {
            $jumlah_diminta = isset($jumlahs[$bahanId]) ? (int)$jumlahs[$bahanId] : 0;
            if ($jumlah_diminta <= 0) continue;

            $detailModel->insert([
                'permintaan_id'   => $permintaanId,
                'bahan_id'        => $bahanId,
                'jumlah_diminta'  => $jumlah_diminta
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal menyimpan permintaan (transaksi)')->withInput();
        }

        return redirect()->to('/permintaan')->with('success', 'Permintaan berhasil dibuat');
    }

    public function approve($id)
    {
        $permintaanModel = new PermintaanModel();
        $detailModel = new PermintaanDetailModel();
        $bahanModel = new BahanModel();

        $details = $detailModel->where('permintaan_id', $id)->findAll();

        // Cek stok cukup
        foreach ($details as $d) {
            $bahan = $bahanModel->find($d['bahan_id']);
            if ($bahan['jumlah'] < $d['jumlah_diminta']) {
                return redirect()->back()->with('error', 'Stok ' . $bahan['nama'] . ' tidak mencukupi');
            }
        }

        // Kurangi stok dan update status bahan jika habis
        foreach ($details as $d) {
            $bahan = $bahanModel->find($d['bahan_id']);
            $stok_akhir = $bahan['jumlah'] - $d['jumlah_diminta'];
            $status = $bahan['status'];
            if ($stok_akhir <= 0) {
                $stok_akhir = 0;
                $status = 'habis';
            }
            $bahanModel->update($bahan['id'], [
                'jumlah' => $stok_akhir,
                'status' => $status
            ]);
        }

        $permintaanModel->update($id, ['status' => 'disetujui']);
        return redirect()->to('/permintaan')->with('success', 'Permintaan disetujui dan stok bahan diperbarui');
    }

    public function reject($id)
    {
        $permintaanModel = new PermintaanModel();
        $permintaanModel->update($id, ['status' => 'ditolak']);
        return redirect()->to('/permintaan')->with('success', 'Permintaan ditolak');
    }
    public function reject_form($id)
    {
        $permintaanModel = new PermintaanModel();
        $permintaan = $permintaanModel->find($id);

        if (!$permintaan) {
            return redirect()->to('/permintaan')->with('error', 'Permintaan tidak ditemukan');
        }

        $data = [
            'title' => 'Alasan Penolakan',
            'permintaan' => $permintaan
        ];

        echo view('templates/header', $data);
        echo view('permintaan/reject_form', $data);
        echo view('templates/footer');
    }

    public function detail($id)
    {
        $permintaanModel = new PermintaanModel();
        $detailModel = new PermintaanDetailModel();
        $bahanModel = new BahanModel();

        $permintaan = $permintaanModel
            ->select('permintaan.*, user.name as pemohon')
            ->join('user', 'user.id=permintaan.pemohon_id')
            ->where('permintaan.id', $id)
            ->first();

        if (!$permintaan) {
            return redirect()->to('/permintaan')->with('error', 'Permintaan tidak ditemukan');
        }

        // Ambil detail bahan yang diminta
        $details = $detailModel
            ->where('permintaan_id', $id)
            ->findAll();

        // Gabungkan dengan data bahan
        foreach ($details as &$d) {
            $bahan = $bahanModel->find($d['bahan_id']);
            $d['nama_bahan'] = $bahan['nama'] ?? '-';
            $d['satuan'] = $bahan['satuan'] ?? '';
        }

        $data = [
            'title' => 'Detail Permintaan',
            'permintaan' => $permintaan,
            'details' => $details
        ];

        return view('templates/header', $data)
            . view('permintaan/detail', $data)
            . view('templates/footer');
    }
}
