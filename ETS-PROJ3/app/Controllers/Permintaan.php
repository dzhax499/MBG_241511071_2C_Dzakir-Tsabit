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
}
