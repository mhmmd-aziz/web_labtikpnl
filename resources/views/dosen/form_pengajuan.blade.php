@extends('layouts.app')  @section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Formulir Pengajuan Jadwal Baru/Ganti</div>
                <div class="card-body">

                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> Ada masalah dengan input Anda.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('dosen.jadwal.store') }}" method="POST">
                        @csrf <div class="mb-3">
                            <label class="form-label">Tipe Pengajuan</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="tipe_pengajuan" id="tipe_ganti" value="ganti" {{ old('tipe_pengajuan') == 'ganti' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="tipe_ganti">Jadwal Ganti</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="tipe_pengajuan" id="tipe_konsisten" value="konsisten" {{ old('tipe_pengajuan', 'konsisten') == 'konsisten' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="tipe_konsisten">Jadwal Tetap (Baru/Konsisten)</label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3" id="kolom_jadwal_lama" style="display: none;">
                            <label for="jadwal_lama_id" class="form-label">Pilih Jadwal yang Ingin Diganti</label>
                            <select class="form-select @error('jadwal_lama_id') is-invalid @enderror" id="jadwal_lama_id" name="jadwal_lama_id">
                                <option value="">-- Pilih Jadwal --</option>
                                @foreach ($jadwalsDosen as $jadwal)
                                    <option value="{{ $jadwal->id }}" {{ old('jadwal_lama_id') == $jadwal->id ? 'selected' : '' }}>
                                        {{ $jadwal->mata_kuliah }} ({{ $jadwal->hari }}, {{ $jadwal->jam_mulai }})
                                    </option>
                                @endforeach
                            </select>
                            @error('jadwal_lama_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="room_id" class="form-label">Ruangan yang Diajukan</label>
                            <select class="form-select @error('room_id') is-invalid @enderror" id="room_id" name="room_id" required>
                                <option value="">-- Pilih Ruangan --</option>
                                @foreach ($rooms as $room)
                                    <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                                        {{ $room->nama_ruang }}
                                    </option>
                                @endforeach
                            </select>
                            @error('room_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="class_id" class="form-label">Untuk Kelas</label>
                            <select class="form-select @error('class_id') is-invalid @enderror" id="class_id" name="class_id" required>
                                <option value="">-- Pilih Kelas --</option>
                                @foreach ($kelases as $kelas)
                                    <option value="{{ $kelas->id }}" {{ old('class_id') == $kelas->id ? 'selected' : '' }}>
                                        {{ $kelas->nama_kelas }} </option>
                                @endforeach
                            </select>
                            @error('class_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="mata_kuliah" class="form-label">Mata Kuliah</label>
                            <input type="text" class="form-control @error('mata_kuliah') is-invalid @enderror" id="mata_kuliah" name="mata_kuliah" value="{{ old('mata_kuliah') }}" required>
                            @error('mata_kuliah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="waktu_mulai_baru" class="form-label">Waktu Mulai Baru</label>
                            <input type="datetime-local" class="form-control @error('waktu_mulai_baru') is-invalid @enderror" id="waktu_mulai_baru" name="waktu_mulai_baru" value="{{ old('waktu_mulai_baru') }}" required>
                            @error('waktu_mulai_baru')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="waktu_selesai_baru" class="form-label">Waktu Selesai Baru</label>
                            <input type="datetime-local" class="form-control @error('waktu_selesai_baru') is-invalid @enderror" id="waktu_selesai_baru" name="waktu_selesai_baru" value="{{ old('waktu_selesai_baru') }}" required>
                            @error('waktu_selesai_baru')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="alasan_dosen" class="form-label">Alasan Pengajuan</label>
                            <textarea class="form-control @error('alasan_dosen') is-invalid @enderror" id="alasan_dosen" name="alasan_dosen" rows="3" required>{{ old('alasan_dosen') }}</textarea>
                            @error('alasan_dosen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tipeGanti = document.getElementById('tipe_ganti');
        const tipeKonsisten = document.getElementById('tipe_konsisten');
        const kolomJadwalLama = document.getElementById('kolom_jadwal_lama');

        function toggleJadwalLama() {
            if (tipeGanti.checked) {
                kolomJadwalLama.style.display = 'block';
                document.getElementById('jadwal_lama_id').setAttribute('required', 'required');
            } else {
                kolomJadwalLama.style.display = 'none';
                document.getElementById('jadwal_lama_id').removeAttribute('required');
            }
        }

        // Panggil fungsi saat halaman dimuat (untuk cek old value)
        toggleJadwalLama();

        // Tambah event listener
        tipeGanti.addEventListener('change', toggleJadwalLama);
        tipeKonsisten.addEventListener('change', toggleJadwalLama);
    });
</script>

@endsection