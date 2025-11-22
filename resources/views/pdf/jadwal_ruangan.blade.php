<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Jadwal Ruang {{ $room->nama_ruang }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #999999ff; padding: 7px; text-align: left; }
        th { background-color: #08a544ff; color: white; } /* Warna diubah agar beda */
        h1 { text-align: center; margin-bottom: 0; }
        .header-info { text-align: center; margin-top: 5px; color: #555555ff; }
    </style>
</head>
<body>
    <h1>Jadwal Penggunaan Ruang<br>"{{ $room->nama_ruang }}"</h1> 
    
    <p class="header-info">Dicetak pada: {{ date('d F Y, H:i') }} WIB</p>
    
    <table>
        <thead>
            <tr>
                <th>Hari</th>
                <th>Jam</th>
                <th>Mata Kuliah</th>
                <th>Dosen</th>
                <th>Kelas</th>
                </tr>
        </thead>
        <tbody>
            @forelse($jadwals as $jadwal)
                <tr>
                    <td>{{ $jadwal->hari }}</td>
                    <td>{{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}</td>
                    <td>{{ $jadwal->mata_kuliah }}</td>
                    <td>{{ $jadwal->dosen->name ?? 'N/A' }}</td>
                    <td>{{ $jadwal->kelas->nama_kelas ?? 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Tidak ada data jadwal yang ditemukan untuk ruangan ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>