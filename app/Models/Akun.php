<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Akun extends Model
{
    protected $table = 'akun';
    public $timestamps = false;
    protected $fillable = ['nama', 'username', 'email', 'password'];

    public function pengguna()
    {
        return $this->hasOne(Pengguna::class, 'id_akun', 'id');
    }
    public function admin()
    {
        return $this->hasOne(Admin::class, 'id_akun', 'id');
    }
}
