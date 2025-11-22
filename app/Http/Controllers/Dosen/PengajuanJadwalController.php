<?php


namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Room;    
use App\Models\Jadwal;  
use App\Models\Kelas;   
use App\Models\PengajuanJadwal;

class PengajuanJadwalController extends Controller
{
    
    public function create()
    {
        $rooms = Room::all();
        $kelases = Kelas::all();
        $jadwalsDosen = Jadwal::where('dosen_id', auth()->id())->get(); 

        return view('dosen.form_pengajuan', compact('rooms', 'kelases', 'jadwalsDosen'));
    }


    public function store(Request $request)
    {
        
        $request->validate([
            'tipe_pengajuan' => 'required|in:ganti,konsisten',
            'jadwal_lama_id' => 'nullable|exists:jadwals,id', 
            'room_id' => 'required|exists:rooms,id',        
            'class_id' => 'required|exists:kelas,id',       
            'mata_kuliah' => 'required|string|max:255',
            'waktu_mulai_baru' => 'required|date',
            'waktu_selesai_baru' => 'required|date|after:waktu_mulai_baru',
            'alasan_dosen' => 'required|string|min:10',
          
        ]);

        
        PengajuanJadwal::create([
            'dosen_id' => auth()->id(),
            'status' => 'pending', 
            'room_id' => $request->room_id,
            'class_id' => $request->class_id,
            'mata_kuliah' => $request->mata_kuliah,
            'waktu_mulai_baru' => $request->waktu_mulai_baru,
            'waktu_selesai_baru' => $request->waktu_selesai_baru,
            'tipe_pengajuan' => $request->tipe_pengajuan,
            'alasan_dosen' => $request->alasan_dosen,
            'jadwal_lama_id' => $request->jadwal_lama_id,
        ]);

        return redirect()->back()->with('success', 'Pengajuan jadwal terkirim. Menunggu persetujuan Admin.');
    }
}