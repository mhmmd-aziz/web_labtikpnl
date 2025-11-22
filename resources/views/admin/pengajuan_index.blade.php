@extends('layouts.app')  @section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Daftar Pengajuan Jadwal (Pending)
                </div>
                <div class="card-body">

                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('warning'))
                        <div class="alert alert-warning" role="alert">
                            {{ session('warning') }}
                        </div>
                    @endif

                    @if ($pengajuans->isEmpty())
                        <p class="text-center">Tidak ada pengajuan jadwal yang menunggu persetujuan.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Dosen</th>
                                        <th>Ruang & Kelas</th>
                                        <th>Mata Kuliah</th>
                                        <th>Tipe</th>
                                        <th>Waktu Baru</th>
                                        <th>Alasan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pengajuans as $index => $pengajuan)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        
                                        <td>{{ $pengajuan->dosen->name }}</td>
                                        
                                        <td>
                                            <strong>{{ $pengajuan->room->nama_ruang }}</strong><br>
                                            <small>{{ $pengajuan->kelas->nama_kelas ?? 'Nama Kelas' }}</small> </td>
                                        
                                        <td>{{ $pengajuan->mata_kuliah }}</td>
                                        
                                        <td>
                                            @if($pengajuan->tipe_pengajuan == 'ganti')
                                                <span class="badge bg-warning text-dark">Ganti</span>
                                            @else
                                                <span class="badge bg-info text-dark">Konsisten</span>
                                            @endif
                                        </td>
                                        
                                        <td>
                                            {{ \Carbon\Carbon::parse($pengajuan->waktu_mulai_baru)->format('d, M Y') }}<br>
                                            <small>
                                                {{ \Carbon\Carbon::parse($pengajuan->waktu_mulai_baru)->format('H:i') }} - 
                                                {{ \Carbon\Carbon::parse($pengajuan->waktu_selesai_baru)->format('H:i') }}
                                            </small>
                                        </td>
                                        
                                        <td>{{ $pengajuan->alasan_dosen }}</td>
                                        
                                        <td class="text-center">
                                            <form action="{{ route('admin.pengajuan.setujui', $pengajuan) }}" method="POST" class="d-inline" onsubmit="return confirm('Anda yakin ingin menyetujui jadwal ini?');">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm mb-1">
                                                    Setujui
                                                </button>
                                            </form>
                                            
                                            <form action="{{ route('admin.pengajuan.tolak', $pengajuan) }}" method="POST" class="d-inline" onsubmit="return confirm('Anda yakin ingin MENOLAK jadwal ini?');">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    Tolak
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection