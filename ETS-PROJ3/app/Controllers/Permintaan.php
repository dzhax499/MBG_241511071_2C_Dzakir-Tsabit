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
}
