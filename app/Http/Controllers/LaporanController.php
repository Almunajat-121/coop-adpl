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

    public function destroy($id)
    {
        if (!Session::has('user') || Session::get('role') !== 'admin') {
            return redirect('/login');
        }
        $laporan = Laporan::findOrFail($id);
        $laporan->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Laporan berhasil dihapus.');
    }

    public function abaikan($id)
    {
        if (!Session::has('user') || Session::get('role') !== 'admin') {
            return redirect('/login');
        }
        // Abaikan laporan: bisa dihapus atau update status, di sini kita hapus saja
        $laporan = Laporan::findOrFail($id);
        $laporan->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Laporan diabaikan.');
    }

    public function barangDetail($id)
    {
        if (!Session::has('user') || Session::get('role') !== 'admin') {
            return redirect('/login');
        }
        $barang = \App\Models\Barang::with(['foto', 'kategori', 'pengguna.akun'])->findOrFail($id);
        return view('barang-detail-admin', compact('barang'));
    }

    public function hapusBarang($id)
    {
        if (!Session::has('user') || Session::get('role') !== 'admin') {
            return redirect('/login');
        }
        $barang = \App\Models\Barang::findOrFail($id);
        $barang->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Barang berhasil dihapus.');
    }
}
