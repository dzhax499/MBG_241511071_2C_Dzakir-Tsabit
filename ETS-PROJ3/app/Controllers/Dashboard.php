<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    public function index()
    {
        $session = session();
        if (! $session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Dashboard - MBG',
            'user'  => $session->get()
        ];

        echo view('templates/header', $data);
        echo view('dashboard/index', $data);
        echo view('templates/footer', $data);
    }
}
