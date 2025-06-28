<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminManualSeeder extends Seeder
{
    public function run(): void
    {
        $akunId = DB::table('akun')->insertGetId([
            'nama' => 'Admin Manual',
            'username' => 'adminmanual',
            'email' => 'adminmanual@example.com',
            'password' => bcrypt('admin123'),
        ]);
        DB::table('admin')->insert([
            'id_akun' => $akunId,
            'no_pegawai' => 'ADM999',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
