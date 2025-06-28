<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengguna extends Model
{
    protected $table = 'pengguna';
    public $timestamps = false;
    protected $fillable = ['id_akun', 'alamat', 'no_telepon'];
    public $incrementing = true;

    public function akun()
    {
        return $this->belongsTo(Akun::class, 'id_akun', 'id');
    }
    public function barang()
    {
        return $this->hasMany(Barang::class, 'id_pengguna', 'id');
    }
}
