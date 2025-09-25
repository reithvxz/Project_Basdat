<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Surat;
use App\Models\JenisSurat;
use App\Models\Ormawa;
use App\Models\Lampiran;
use App\Models\Mahasiswa;

class SuratController extends Controller
{
    // Menampilkan daftar status surat mahasiswa (status.php)
    public function index()
    {
        $mhs_id = Auth::guard('mahasiswa')->id();
        $surats = Surat::with('jenisSurat', 'lampiran')
                        ->where('mhs_id', $mhs_id)
                        ->orderBy('created_at', 'desc')
                        ->get();
        return view('mahasiswa.status', compact('surats'));
    }

    // Menampilkan form pengajuan (pengajuan.php)
    public function create()
    {
        $jenisSurats = JenisSurat::all();
        $himas = Ormawa::where('tipe', 'HIMA')->get();
        $bsos = Ormawa::where('tipe', 'BSO')->get();
        return view('mahasiswa.pengajuan', compact('jenisSurats', 'himas', 'bsos'));
    }

    // Memproses form pengajuan (pengajuan.php)
    public function store(Request $request)
    {
        $request->validate([
            'atas_nama' => 'required|string',
            'ormawa_id' => 'nullable|integer',
            'jenis_id' => 'required|integer',
            'perihal' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'lampiran' => 'required|file|mimes:pdf|max:2048',
        ]);

        $mhs_id = Auth::guard('mahasiswa')->id();
        $ormawa_id = null;

        if ($request->atas_nama == 'HIMA' || $request->atas_nama == 'BSO') {
            $ormawa_id = $request->ormawa_id;
        } elseif ($request->atas_nama == 'BEM') {
            $bem = Ormawa::where('tipe', 'BEM')->first();
            $ormawa_id = $bem ? $bem->ormawa_id : null;
        }

        $status = ($request->atas_nama == 'BEM') ? 'Menunggu Akademik' : 'Menunggu BEM';

        $surat = Surat::create([
            'mhs_id' => $mhs_id,
            'atas_nama' => $request->atas_nama,
            'ormawa_id' => $ormawa_id,
            'jenis_surat_id' => $request->jenis_id,
            'perihal' => $request->perihal,
            'deskripsi' => $request->deskripsi,
            'status' => $status,
        ]);

        if ($request->hasFile('lampiran')) {
            $file = $request->file('lampiran');
            $filename = time() . "_" . $file->getClientOriginalName();
            $path = $file->storeAs('uploads', $filename, 'public');

            Lampiran::create([
                'surat_id' => $surat->surat_id,
                'nama_file' => $filename,
                'file_path' => $path,
            ]);
        }

        return redirect()->route('status')->with('success', 'Surat berhasil diajukan!');
    }

    // Menghapus/membatalkan surat (batal.php)
    public function destroy(Surat $surat)
    {
        $mhs_id = Auth::guard('mahasiswa')->id();

        if ($surat->mhs_id != $mhs_id) {
            return redirect()->route('status')->with('error', 'Anda tidak berhak membatalkan surat ini.');
        }

        // Hapus approval dan lampiran terkait dulu
        $surat->approvals()->delete();
        // Hapus file lampiran dari storage jika ada
        if ($surat->lampiran) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($surat->lampiran->file_path);
            $surat->lampiran->delete();
        }

        $surat->delete();

        return redirect()->route('status')->with('success', 'Pengajuan surat berhasil dibatalkan.');
    }

    // Menampilkan tracking surat (tracking.php)
    public function tracking(Surat $surat)
    {
         $alur = ['Menunggu BEM','Menunggu Akademik','Menunggu Sekretariat','Menunggu Dekan','Disetujui'];
         return view('mahasiswa.tracking', compact('surat', 'alur'));
    }
}