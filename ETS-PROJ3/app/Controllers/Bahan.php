<?php

namespace App\Controllers;

use App\Models\BahanModel;

class Bahan extends BaseController
{
    public function index()
    {
        $model = new BahanModel();
        $data = [
            'title' => 'Daftar Bahan Baku',
            'bahan' => $model->findAll()
        ];

        echo view('templates/header', $data);
        echo view('bahan/index', $data);
        echo view('templates/footer');
    }

    public function create()
    {
        $data = ['title' => 'Tambah Bahan Baku'];
        echo view('templates/header', $data);
        echo view('bahan/create', $data);
        echo view('templates/footer');
    }

    public function store()
    {
        $rules = [
            'nama'             => 'required|min_length[3]',
            'kategori'         => 'required',
            'jumlah'           => 'required|integer|greater_than_equal_to[0]',
            'satuan'           => 'required',
            'tanggal_masuk'    => 'required|valid_date',
            'tanggal_kadaluarsa' => 'required|valid_date'
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $tanggal_kadaluarsa = $this->request->getPost('tanggal_kadaluarsa');
        $jumlah = (int) $this->request->getPost('jumlah');
        $today = strtotime(date('Y-m-d'));
        $kadaluarsa = strtotime($tanggal_kadaluarsa);
        $selisih_hari = ceil(($kadaluarsa - $today) / 86400);

        if ($jumlah == 0) {
            $status = 'habis';
        } elseif ($today >= $kadaluarsa) {
            $status = 'kadaluarsa';
        } elseif ($selisih_hari <= 3 && $jumlah > 0) {
            $status = 'segera_kadaluarsa';
        } else {
            $status = 'tersedia';
        }

        $model = new BahanModel();
        $model->insert([
            'nama'               => $this->request->getPost('nama'),
            'kategori'           => $this->request->getPost('kategori'),
            'jumlah'             => $this->request->getPost('jumlah'),
            'satuan'             => $this->request->getPost('satuan'),
            'tanggal_masuk'      => $this->request->getPost('tanggal_masuk'),
            'tanggal_kadaluarsa' => $tanggal_kadaluarsa,
            'status'             => $status,
            'created_at'         => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/bahan')->with('success', 'Bahan berhasil ditambahkan');
    }
    public function edit($id)
    {
        $model = new BahanModel();
        $data = [
            'title' => 'Edit Bahan Baku',
            'bahan' => $model->find($id)
        ];

        if (!$data['bahan']) {
            return redirect()->to('/bahan')->with('error', 'Data tidak ditemukan');
        }

        echo view('templates/header', $data);
        echo view('bahan/edit', $data);
        echo view('templates/footer');
    }

    public function update($id)
    {
        $rules = [
            'nama' => 'required|min_length[3]',
            'kategori' => 'required',
            'jumlah' => 'required|integer|greater_than_equal_to[0]',
            'satuan' => 'required',
            'tanggal_masuk' => 'required|valid_date',
            'tanggal_kadaluarsa' => 'required|valid_date'
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $jumlah = (int) $this->request->getPost('jumlah');
        if ($jumlah <= 0) {
            return redirect()->back()->withInput()->with('error', 'Stok tidak boleh kurang dari 0');
        }

        $tanggal_kadaluarsa = $this->request->getPost('tanggal_kadaluarsa');
        $today = strtotime(date('Y-m-d'));
        $kadaluarsa = strtotime($tanggal_kadaluarsa);
        $selisih_hari = ceil(($kadaluarsa - $today) / 86400);

        if ($jumlah == 0) {
            $status = 'habis';
        } elseif ($today >= $kadaluarsa) {
            $status = 'kadaluarsa';
        } elseif ($selisih_hari <= 3 && $jumlah > 0) {
            $status = 'segera_kadaluarsa';
        } else {
            $status = 'tersedia';
        }

        $model = new BahanModel();
        $model->update($id, [
            'nama' => $this->request->getPost('nama'),
            'kategori' => $this->request->getPost('kategori'),
            'jumlah' => $jumlah,
            'satuan' => $this->request->getPost('satuan'),
            'tanggal_masuk' => $this->request->getPost('tanggal_masuk'),
            'tanggal_kadaluarsa' => $tanggal_kadaluarsa,
            'status' => $status
        ]);

        return redirect()->to('/bahan')->with('success', 'Bahan berhasil diupdate');
    }

    public function delete($id)
    {
        $model = new BahanModel();
        $bahan = $model->find($id);

        if (!$bahan) {
            return redirect()->to('/bahan')->with('error', 'Data tidak ditemukan');
        }

        // Hanya bisa hapus jika status kadaluarsa
        if ($bahan['status'] !== 'kadaluarsa') {
            return redirect()->to('/bahan')->with('error', 'Bahan hanya dapat dihapus jika statusnya kadaluarsa');
        }

        // Tampilkan konfirmasi hapus
        $data = [
            'title' => 'Konfirmasi Hapus Bahan Baku',
            'bahan' => $bahan
        ];
        echo view('templates/header', $data);
        echo view('bahan/delete_confirm', $data);
        echo view('templates/footer');
    }
    // Proses hapus setelah konfirmasi
    public function destroy($id)
    {
        $model = new BahanModel();
        $bahan = $model->find($id);

        if (!$bahan) {
            return redirect()->to('/bahan')->with('error', 'Data tidak ditemukan');
        }

        if ($bahan['status'] !== 'kadaluarsa') {
            return redirect()->to('/bahan')->with('error', 'Bahan hanya dapat dihapus jika statusnya kadaluarsa');
        }

        $model->delete($id);
        return redirect()->to('/bahan')->with('success', 'Bahan berhasil dihapus');
    }
}
