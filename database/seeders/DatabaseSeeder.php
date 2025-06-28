<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use App\Models\Akun;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Seed akun admin
        $akun = Akun::create([
            'nama' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
        ]);
        Admin::create([
            'id_akun' => $akun->id,
            'no_pegawai' => 'ADM001',
        ]);

        // Seed kategori
        DB::table('kategori')->insert([
            ['nama' => 'Elektronik'],
            ['nama' => 'Pakaian'],
            ['nama' => 'Buku'],
            ['nama' => 'Perabot'],
            ['nama' => 'Olahraga'],
            ['nama' => 'Lainnya'],
        ]);

        $this->call(\Database\Seeders\AdminManualSeeder::class);
    }
}
