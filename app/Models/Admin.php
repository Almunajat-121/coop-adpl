<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'admin';
    public $timestamps = false;
    protected $fillable = ['id_akun', 'no_pegawai'];
    public $incrementing = true;

    public function akun()
    {
        return $this->belongsTo(Akun::class, 'id_akun', 'id');
    }
}
