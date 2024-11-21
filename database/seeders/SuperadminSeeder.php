<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Superadmin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperadminSeeder extends Seeder
{
    public function run()
    {
        Superadmin::create([
            'username' => 'superdev',
            'fullname' => 'Super Developer',
            'email' => 'superdev@company.com',
            'password' => 'super_secret_password', // Will be hashed automatically
            'is_active' => true
        ]);
    }
}
