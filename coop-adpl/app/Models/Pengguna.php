<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengguna extends Model
{
    protected $table = 'pengguna';
    public $timestamps = false;
    protected $fillable = ['id', 'alamat', 'no_telepon'];
    public $incrementing = false;

    public function akun()
    {
        return $this->belongsTo(Akun::class, 'id', 'id');
    }
    public function barang()
    {
        return $this->hasMany(Barang::class, 'id_pengguna', 'id');
    }
}
