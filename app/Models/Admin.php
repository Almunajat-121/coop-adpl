<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'admin';
    public $timestamps = false;
    protected $fillable = ['id', 'no_pegawai'];
    public $incrementing = false;

    public function akun()
    {
        return $this->belongsTo(Akun::class, 'id', 'id');
    }
}
