<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Laporan;
use App\Models\Barang;

class LaporanController extends Controller
{
    public function store(Request $request, $id)
    {
        if (!Session::has('user') || Session::get('role') !== 'pengguna') {
            return redirect('/login');
        }
        $request->validate([
            'alasan' => 'required|string|min:5',
        ]);
        Laporan::create([
            'id_barang' => $id,
            'id_pelapor' => Session::get('user')->id,
            'alasan' => $request->alasan,
        ]);
        return back()->with('success', 'Laporan berhasil dikirim!');
    }

    public function index()
    {
        if (!Session::has('user') || Session::get('role') !== 'admin') {
            return redirect('/login');
        }
        $laporan = Laporan::with(['barang', 'barang.foto', 'pelapor'])->orderByDesc('id')->get();
        return view('dashboard-admin', compact('laporan'));
    }
}
