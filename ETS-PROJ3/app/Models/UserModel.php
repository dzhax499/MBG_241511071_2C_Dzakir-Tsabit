<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'user';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'email', 'password', 'role', 'created_at'];
    protected $returnType = 'array';

    // Ambil user berdasarkan email
    public function getByEmail(string $email)
    {
        return $this->where('email', $email)->first();
    }
}
