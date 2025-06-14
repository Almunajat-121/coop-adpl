<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';
    public $timestamps = false;
    protected $fillable = ['nama', 'deskripsi', 'tipe', 'harga', 'id_pengguna', 'id_kategori', 'status'];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id');
    }
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id');
    }
    public function foto()
    {
        return $this->hasMany(Foto::class, 'id_barang', 'id');
    }
}
