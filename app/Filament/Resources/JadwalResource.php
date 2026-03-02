<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JadwalResource\Pages;
use App\Models\Jadwal;
use App\Models\Room;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException; // Perbaikan typo huruf besar
use App\Models\TimeSlot;
use Filament\Forms\Get;
use Illuminate\Support\Collection;

class JadwalResource extends Resource
{
    protected static ?string $model = Jadwal::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if ($user->role === 'admin') {
            return $query; // Admin lihat semua
        }

        if ($user->role === 'mahasiswa') {
            // [FIX] Mengambil ID Kelas dari tabel 'mahasiswas' lewat relasi
            // Kita cek dulu apakah data mahasiswanya ada untuk menghindari error
            if ($user->mahasiswa) {
                // Pastikan nama kolom di database mahasiswas adalah 'kelas_id' atau 'class_id'
                // Berdasarkan migration sebelumnya, namanya 'kelas_id'
                return $query->where('class_id', $user->mahasiswa->kelas_id);
            }
            
            // Jika user mahasiswa tapi datanya belum lengkap (tidak punya kelas), jangan tampilkan apa-apa
            return $query->whereRaw('1 = 0');
        }

        if ($user->role === 'dosen') {
             // Dosen lihat berdasarkan dosen_id (asumsi dosen_id di tabel jadwal mengacu ke ID User Dosen)
            return $query->where('dosen_id', $user->id);
        }

        // Default: Jangan tampilkan apa-apa jika role tidak dikenali
        return $query->whereRaw('1 = 0');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Field umum yang selalu tampil
                Forms\Components\Select::make('room_id')->relationship('room', 'nama_ruang')->required()->live(),
                Forms\Components\Select::make('class_id')->relationship('kelas', 'nama_kelas')->required()->live(),
                Forms\Components\Select::make('dosen_id')
                    ->relationship('dosen', 'name', modifyQueryUsing: fn ($query) => $query->where('role', 'dosen'))
                    ->label('Dosen')
                    ->required()
                    ->live(),
                Forms\Components\Select::make('mata_kuliah_id')
                    ->relationship('mataKuliah', 'nama_matkul')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('hari')
                    ->options([
                        'Senin' => 'Senin', 'Selasa' => 'Selasa', 'Rabu' => 'Rabu',
                        'Kamis' => 'Kamis', 'Jumat' => 'Jumat', 'Sabtu' => 'Sabtu', 'Minggu' => 'Minggu',
                    ])
                    ->required()
                    ->live(),

                // --- PEMICU UTAMA FORM HYBRID ---
                Forms\Components\Select::make('tipe_jadwal')
                    ->options([
                        'rutin' => 'Jadwal Rutin (Sesuai Slot)',
                        'insidental' => 'Jadwal Insidental (Waktu Bebas)',
                    ])
                    ->default('rutin')
                    ->required()
                    ->live(),

                // --- KELOMPOK FORM UNTUK JADWAL RUTIN ---
                Forms\Components\Select::make('start_slot_id')
                    ->label('Mulai dari Jam Ke-')
                    ->options(TimeSlot::orderBy('jam_mulai')->get()->pluck('jam_mulai_formatted', 'id'))
                    ->live()
                    ->required()
                    ->visible(fn (Get $get) => $get('tipe_jadwal') === 'rutin'),

                Forms\Components\Select::make('end_slot_id')
                    ->label('Selesai Sampai Jam Ke-')
                    ->options(function (Get $get): Collection {
                        $startTime = TimeSlot::find($get('start_slot_id'))?->jam_mulai;
                        $query = TimeSlot::query();
                        if ($startTime) {
                            $query->where('jam_mulai', '>=', $startTime);
                        }
                        return $query->orderBy('jam_mulai')->get()->pluck('jam_selesai_formatted', 'id');
                    })
                    ->required()
                    ->visible(fn (Get $get) => $get('tipe_jadwal') === 'rutin'),

                // --- KELOMPOK FORM UNTUK JADWAL INSIDENTAL ---
                Forms\Components\TimePicker::make('jam_mulai')
                    ->label('Jam Mulai (Bebas)')
                    ->required()
                    ->seconds(false)
                    ->live()
                    ->visible(fn (Get $get) => $get('tipe_jadwal') === 'insidental'),

                Forms\Components\TimePicker::make('jam_selesai')
                    ->label('Jam Selesai (Bebas)')
                    ->required()
                    ->seconds(false)
                    ->after('jam_mulai')
                    ->live()
                    ->visible(fn (Get $get) => $get('tipe_jadwal') === 'insidental')
                    ->rules([
                        // --- PERBAIKAN DI SINI: Tambahkan ?\Illuminate\Database\Eloquent\Model $record ---
                        function (Get $get, ?\Illuminate\Database\Eloquent\Model $record) { 
                            return function (string $attribute, $value, \Closure $fail) use ($get, $record) {
                                if ($get('tipe_jadwal') !== 'insidental' || !$get('room_id') || !$get('hari') || !$get('jam_mulai')) {
                                    return;
                                }

                                // Sekarang $record akan berisi data jadwal yang sedang diedit (jika sedang mode edit)
                                $jadwalIdToIgnore = $record ? $record->id : null;

                                $konflik = Jadwal::where('room_id', $get('room_id'))
                                    ->where('hari', $get('hari'))
                                    // Abaikan pengecekan dengan ID jadwal ini sendiri saat diedit
                                    ->when($jadwalIdToIgnore, fn ($query) => $query->where('id', '!=', $jadwalIdToIgnore))
                                    ->where(function ($query) use ($get, $value) {
                                        $query->where('jam_selesai', '>', $get('jam_mulai'))
                                              ->where('jam_mulai', '<', $value);
                                    })->exists();

                                if ($konflik) {
                                    $fail("Jadwal konflik! Sudah ada jadwal lain di ruangan dan waktu tersebut.");
                                }
                            };
                        },
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('hari')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('jam_mulai')->time('H:i')->sortable()->label('Mulai'),
                Tables\Columns\TextColumn::make('jam_selesai')->time('H:i')->sortable()->label('Selesai'),
                Tables\Columns\TextColumn::make('mataKuliah.nama_matkul')
                    ->label('Mata Kuliah')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipe_jadwal')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'rutin' => 'success',
                        'insidental' => 'warning',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('dosen.name')->label('Dosen')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('kelas.nama_kelas')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('room.nama_ruang')->searchable()->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                Action::make('unduhJadwalSaya')
                    ->label('Unduh Jadwal Saya (PDF)')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('primary')
                    ->visible(fn () => auth()->user()->role !== 'admin')
                    ->action(function ($livewire) {
                        $jadwals = $livewire->getFilteredTableQuery()->get();
                        $user = Auth::user();
                        $pdf = Pdf::loadView('pdf.jadwal', [
                            'jadwals' => $jadwals,
                            'user'    => $user
                        ]);
                        $namaFile = 'jadwal-' . \Str::slug($user->name) . '.pdf';
                        return response()->streamDownload(fn() => print($pdf->output()), $namaFile);
                    }),
                Action::make('unduhJadwalRuangan')
                    ->label('Unduh Jadwal per Ruang')
                    ->icon('heroicon-o-building-office')
                    ->color('warning')
                    ->visible(fn () => auth()->user()->role === 'admin')
                    ->form([
                        Select::make('room_id')
                            ->label('Pilih Ruangan')
                            ->options(Room::all()->pluck('nama_ruang', 'id'))
                            ->required()
                            ->searchable()
                            ->placeholder('Cari atau pilih ruangan'),
                    ])
                    ->action(function (array $data) {
                        $room = Room::find($data['room_id']);
                        $jadwals = Jadwal::where('room_id', $data['room_id'])->orderBy('hari')->get();
                        if ($jadwals->count() === 0) {
                            \Filament\Notifications\Notification::make()
                                ->title('Tidak Ada Jadwal')
                                ->body("Tidak ada jadwal yang ditemukan untuk ruangan {$room->nama_ruang}.")
                                ->warning()
                                ->send();
                            return;
                        }
                        $pdf = Pdf::loadView('pdf.jadwal_ruangan', [
                            'jadwals' => $jadwals,
                            'room'    => $room
                        ]);
                        $namaFile = 'jadwal-ruang-' . \Str::slug($room->nama_ruang) . '.pdf';
                        return response()->streamDownload(fn() => print($pdf->output()), $namaFile);
                    }),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJadwals::route('/'),
            'create' => Pages\CreateJadwal::route('/create'),
            'edit' => Pages\EditJadwal::route('/{record}/edit'),
        ];
    }
}