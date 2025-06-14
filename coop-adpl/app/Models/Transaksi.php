<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';
    public $timestamps = true;
    protected $fillable = ['id_barang', 'id_pembeli', 'status'];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id');
    }
    public function pembeli()
    {
        return $this->belongsTo(Pengguna::class, 'id_pembeli', 'id');
    }
    public function ulasan()
    {
        return $this->hasOne(Ulasan::class, 'id_transaksi', 'id');
    }
}
