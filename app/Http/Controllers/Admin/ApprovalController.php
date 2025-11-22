<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\PengajuanJadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Events\JadwalDiubah;

class ApprovalController extends Controller
{
    // Tampilkan daftar pengajuan yang 'pending'
    public function index()
    {
        // SINKRON: with('dosen', 'room') sesuai nama relasi di model
        $pengajuans = PengajuanJadwal::where('status', 'pending')->with('dosen', 'room')->latest()->get();
        return view('admin.pengajuan.index', compact('pengajuans'));
    }

    // Fungsi "ASISI" (Setujui)
    public function setujui(PengajuanJadwal $pengajuan)
    {
        DB::transaction(function () use ($pengajuan) {
            
            // JIKA TIPE 'GANTI', hapus jadwal lama
            if ($pengajuan->tipe_pengajuan == 'ganti' && $pengajuan->jadwal_lama_id) {
                Jadwal::find($pengajuan->jadwal_lama_id)->delete();
            }

            // MASUKKAN data ke tabel 'jadwals' utama
            // SINKRON: Menggunakan nama kolom dari tabel jadwals Anda
            $jadwalBaru = Jadwal::create([
                'dosen_id' => $pengajuan->dosen_id,
                'room_id' => $pengajuan->room_id,
                'class_id' => $pengajuan->class_id,
                'mata_kuliah' => $pengajuan->mata_kuliah,
                // Konversi dateTime ke format 'hari' dan 'jam' Anda
                'hari' => Carbon::parse($pengajuan->waktu_mulai_baru)->translatedFormat('l'), // e.g., 'Senin'
                'jam_mulai' => Carbon::parse($pengajuan->waktu_mulai_baru)->format('H:i:s'),
                'jam_selesai' => Carbon::parse($pengajuan->waktu_selesai_baru)->format('H:i:s'),
            ]);

            // UPDATE status pengajuan
            $pengajuan->update(['status' => 'approved']);

            // PANGGIL EVENT UNTUK NOTIFIKASI (Lihat Bagian 2)
            // event(new \App\Events\JadwalDiubah($jadwalBaru)); 
        });

        event(new JadwalDiubah($jadwalBaru));

        return redirect()->route('admin.pengajuan.index')->with('success', 'Jadwal telah disetujui dan diperbarui.');
    }

    // Fungsi Tolak
    public function tolak(PengajuanJadwal $pengajuan)
    {
        $pengajuan->update(['status' => 'rejected']);
        return redirect()->route('admin.pengajuan.index')->with('warning', 'Pengajuan telah ditolak.');
    }
}