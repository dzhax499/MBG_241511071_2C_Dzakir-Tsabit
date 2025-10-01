<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        helper('form');
        $data = [
            'title' => 'Login - MBG'
        ];
        echo view('templates/header', $data);
        echo view('auth/login', $data);
        echo view('templates/footer', $data);
    }

    public function attempt()
    {
        $session = session();
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required'
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $userModel = new UserModel();
        $user = $userModel->getByEmail($email);

        if (! $user) {
            return redirect()->back()->with('error', 'Email tidak ditemukan')->withInput();
        }

        // Password harus disimpan hashed di DB (password_hash)
        if (password_verify($password, $user['password'])) {
            // Password sudah di-hash dengan password_hash, login lanjut
        } elseif (md5($password) === $user['password']) {
            // Password masih pakai MD5, lakukan rehash dan update DB
            $newHash = password_hash($password, PASSWORD_DEFAULT);
            $userModel->update($user['id'], ['password' => $newHash]);
        } else {
            return redirect()->back()->with('error', 'Password salah')->withInput();
        }

        // Set session
        $session->set([
            'user_id'    => $user['id'],
            'user_name'  => $user['name'],
            'user_email' => $user['email'],
            'role'       => $user['role'],
            'isLoggedIn' => true
        ]);

        return redirect()->to('/dashboard');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
