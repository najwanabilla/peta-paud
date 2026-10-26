<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $nama = env('ADMIN_NAME', 'Admin');
        $email = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');

        if (blank($email) || blank($password)) {
            throw new RuntimeException(
                'Isi ADMIN_EMAIL dan ADMIN_PASSWORD di file .env terlebih dahulu.'
            );
        }

        // Password otomatis di-hash oleh Laravel
        User::updateOrCreate(
            ['email' => $email],
            ['name' => $nama, 'password' => $password]
        );
    }
}