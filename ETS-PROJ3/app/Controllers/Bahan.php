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
        $today = strtotime(date('Y-m-d'));
        $kadaluarsa = strtotime($tanggal_kadaluarsa);

        if ($kadaluarsa < $today) {
            $status = 'kadaluarsa';
        } elseif ($kadaluarsa <= strtotime('+3 days', $today)) {
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
}
