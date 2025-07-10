<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Cek apakah user admin sudah ada untuk menghindari duplikasi
        if (User::where('email', 'admin@example.com')->doesntExist()) {
            User::create([
                'name' => 'Admin User',
                'email' => 'admin@upload.com',
                'email_verified_at' => now(), // Opsional, menandakan email sudah diverifikasi
                'password' => Hash::make('Upload*2025'), // Ganti 'password' dengan password kuat
                // 'is_admin' => true, // Contoh kolom untuk menandai sebagai admin
                // Tambahkan kolom lain jika ada, misalnya 'role_id'
            ]);

            $this->command->info('Admin user created successfully!');
        } else {
            $this->command->info('Admin user already exists.');
        }
    }
}
