<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\Akun;
use App\Models\Admin;
use App\Models\Pengguna;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Cek admin
        $admin = Admin::with('akun')->whereHas('akun', function($q) use ($request) {
            $q->where('username', $request->username);
        })->first();
        if ($admin && Hash::check($request->password, $admin->akun->password)) {
            Session::put('user', $admin->akun);
            Session::put('role', 'admin');
            return redirect()->route('admin.dashboard'); // ubah redirect ke halaman laporan
        }

        // Cek pengguna
        $pengguna = Pengguna::with('akun')->whereHas('akun', function($q) use ($request) {
            $q->where('username', $request->username);
        })->first();
        if ($pengguna && Hash::check($request->password, $pengguna->akun->password)) {
            Session::put('user', $pengguna); // simpan objek Pengguna, bukan Akun
            Session::put('role', 'pengguna');
            return redirect('/');
        }

        return back()->with('error', 'Username atau password salah!');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'username' => 'required|unique:akun,username',
            'email' => 'required|email|unique:akun,email',
            'password' => 'required|min:6',
            'no_telepon' => 'required',
            'alamat' => 'required',
        ]);

        $akun = Akun::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        Pengguna::create([
            'id_akun' => $akun->id, // foreign key ke akun
            'alamat' => $request->alamat,
            'no_telepon' => $request->no_telepon,
        ]);
        return redirect()->route('login')->with('success', 'Registrasi berhasil, silakan login!');
    }

    public function logout()
    {
        Session::flush();
        return redirect()->route('login');
    }

    public function dashboardPengguna()
    {
        if (session('role') !== 'pengguna') {
            return redirect('/login');
        }
        return view('dashboard-pengguna');
    }

    public function dashboardAdmin()
    {
        if (session('role') !== 'admin') {
            return redirect('/login');
        }
        return view('dashboard-admin');
    }
}
