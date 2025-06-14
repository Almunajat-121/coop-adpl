<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $table = 'laporan';
    protected $fillable = ['id_barang', 'id_pelapor', 'alasan', 'id_admin'];
    public $timestamps = true;

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id');
    }
    public function pelapor()
    {
        return $this->belongsTo(Pengguna::class, 'id_pelapor', 'id');
    }
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'id_admin', 'id');
    }
}
