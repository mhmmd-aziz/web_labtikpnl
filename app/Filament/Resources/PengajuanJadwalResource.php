<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengajuanJadwalResource\Pages;
use App\Models\PengajuanJadwal;
use Filament\Forms;
use Filament\Forms\Form; // <-- Namespace Filament\Forms
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
// --- Use Statements Lengkap ---
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
// use Filament\Forms\Components\Radio; // Tidak dipakai lagi di form ini
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TimePicker;
use App\Models\Room;
use App\Models\Kelas;
use App\Models\Jadwal;
use App\Models\TimeSlot; // <-- Tetap diperlukan untuk formatStateUsing di table
use Filament\Forms\Get; // <-- Namespace Filament\Forms
use Filament\Forms\Set; // <-- Namespace Filament\Forms
use Illuminate\Database\Eloquent\Collection as EloquentCollection; // <-- Eloquent Collection
use Illuminate\Support\Collection as SupportCollection; // <-- Support Collection (jika perlu)
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth; // <-- Facade Auth
use Illuminate\Support\Facades\Log;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\DB;
use App\Events\JadwalDiubah;
use Filament\Notifications\Notification;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\Textarea as FormsTextarea; // Untuk form alasan tolak
use App\Notifications\StatusPengajuanUntukDosen; // Notifikasi Dosen
use Illuminate\Database\Eloquent\Builder;

// --- Akhir Use Statements ---

class PengajuanJadwalResource extends Resource
{
    protected static ?string $model = PengajuanJadwal::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $modelLabel = 'Pengajuan Ganti Jadwal'; // Lebih spesifik
    protected static ?string $pluralModelLabel = 'Pengajuan Ganti Jadwal';
    protected static ?string $slug = 'pengajuan-ganti-jadwal'; // URL lebih spesifik

    public static function canViewAny(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['admin', 'dosen']);
    }

   // --- FORM DIKEMBALIKAN KE JAM BEBAS (TIMEPICKER) ---
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Kolom hanya tampil saat view (approval admin)
                Group::make([
                     TextInput::make('dosen.name')->label('Dosen Pengaju'),
                     TextInput::make('status'),
                     TextInput::make('jadwalLama.display_info')
                        ->label('Jadwal Asli')
                        ->formatStateUsing(function ($record) {
                            if (!$record || !$record->jadwal_lama_id) return '-';
                            $jadwalLama = Jadwal::find($record->jadwal_lama_id);
                            if (!$jadwalLama) return 'Jadwal Asli Tdk Ditemukan';
                            $jadwalLama->loadMissing('kelas'); // Eager load
                            $jamMulaiFormatted = '-'; try { if($jadwalLama->jam_mulai) $jamMulaiFormatted = Carbon::parse($jadwalLama->jam_mulai)->format('H:i'); } catch(\Exception $e){}
                            return "{$jadwalLama->mata_kuliah} (".($jadwalLama->kelas?->nama_kelas ?? 'N/A').") - {$jadwalLama->hari}, {$jamMulaiFormatted}";
                        })->disabled(),
                ])->visibleOn('view')->columns(2),

                // --- Field untuk Create Dosen (HANYA GANTI JADWAL) ---
                Select::make('jadwal_lama_id') // Selalu ada & wajib saat create
                    ->label('Pilih Jadwal yang Ingin Diganti')
                    ->options(function (): array { // Opsi jadwal dosen (versi aman)
                        $options = []; try { $user = Auth::user();
                            if (!$user instanceof \App\Models\User || !isset($user->role) || $user->role !== 'dosen') { return $options; }
                            $jadwalsResult = Jadwal::where('dosen_id', $user->id)->orderBy('hari')->orderBy('jam_mulai')->get();
                            if (!($jadwalsResult instanceof EloquentCollection)) { return $options; } if ($jadwalsResult->count() === 0) { return $options; }
                            foreach ($jadwalsResult as $jadwal) { if (!is_object($jadwal) || !$jadwal instanceof Jadwal || !isset($jadwal->id)) { continue; }
                                $jamMulaiFormatted = '-'; try { if (!empty($jadwal->jam_mulai)) { $jamMulaiFormatted = Carbon::parse($jadwal->jam_mulai)->format('H:i'); } } catch (\Exception $e) {}
                                $mk = $jadwal->mata_kuliah ?? 'Tanpa MK'; $hari = $jadwal->hari ?? 'Tanpa Hari'; $options[$jadwal->id] = "{$mk} ({$hari}, {$jamMulaiFormatted})";
                            }
                        } catch (\Throwable $th) { Log::critical('CRITICAL Error generating options (v_safe): ' . $th->getMessage()); } return $options;
                    })
                    ->required()->searchable()->placeholder('Cari jadwal...')->live()
                    ->afterStateUpdated(function (Get $get, Set $set, ?string $state) { // Isi otomatis MK & Kelas
                        if ($state) { $jadwal = Jadwal::find($state); if ($jadwal) { $set('mata_kuliah', $jadwal->mata_kuliah); $set('class_id', $jadwal->class_id); }
                        } else { $set('mata_kuliah', null); $set('class_id', null); }
                    })->visibleOn('create'), // Hanya saat create

                // Field Mata Kuliah (Otomatis & Disabled)
                TextInput::make('mata_kuliah')->required()->disabled()->dehydrated(true)->visibleOn(['create', 'view']),

                // Field Kelas (Otomatis & Disabled)
                Select::make('class_id')->relationship('kelas', 'nama_kelas')->label('Kelas')
                    ->required()->disabled()->dehydrated(true)->visibleOn(['create', 'view']),

                // --- Field yang BISA DIEDIT Dosen (Jam Bebas) ---
                Select::make('room_id')->relationship('room', 'nama_ruang')->label('Ubah Ke Ruangan')->required()->live()->visibleOn(['create', 'view']),
                Select::make('hari')->label('Ubah Ke Hari')
                    ->options(['Senin'=>'Senin', 'Selasa'=>'Selasa', 'Rabu'=>'Rabu', 'Kamis'=>'Kamis', 'Jumat'=>'Jumat', 'Sabtu'=>'Sabtu', 'Minggu'=>'Minggu'])
                    ->required()->live()->visibleOn(['create', 'view']),
                TimePicker::make('jam_mulai') // <-- Pakai TimePicker
                    ->label('Ubah Ke Jam Mulai (Bebas)')->required()->seconds(false)->live()->visibleOn(['create', 'view']),
                TimePicker::make('jam_selesai') // <-- Pakai TimePicker
                    ->label('Ubah Ke Jam Selesai (Bebas)')->required()->seconds(false)->after('jam_mulai')->live()->visibleOn(['create', 'view']),
                // ---------------------------------------------

                // Tampilkan jam baru yang diajukan (View Admin)
                 TextInput::make('waktu_diajukan_view')
                    ->label('Waktu Baru Diajukan')
                    ->visibleOn('view')
                    ->formatStateUsing(function ($record) { // Format jam bebas
                        if (!$record || !$record->hari || !$record->jam_mulai || !$record->jam_selesai) return '-';
                        return $record->hari . ', ' . Carbon::parse($record->jam_mulai)->format('H:i') . ' - ' . Carbon::parse($record->jam_selesai)->format('H:i');
                    })->disabled(),

                Textarea::make('alasan_dosen')->label('Alasan Penggantian')
                    ->required()->columnSpanFull()->visibleOn(['create', 'view']),

                // Hidden fields (Otomatis set)
                Hidden::make('dosen_id')->default(fn() => auth()->id())->visibleOn('create'),
                Hidden::make('status')->default('pending')->visibleOn('create'),
                Hidden::make('tipe_pengajuan')->default('ganti')->visibleOn('create'), // Selalu 'ganti'
                Hidden::make('tipe_jadwal')->default('insidental')->visibleOn('create'), // Selalu 'insidental'
                Hidden::make('start_slot_id')->default(null)->visibleOn('create'), // Tidak dipakai
                Hidden::make('end_slot_id')->default(null)->visibleOn('create'), // Tidak dipakai
            ]);
    }
    // --- AKHIR FORM ---

    // --- TABLE DIKEMBALIKAN KE FORMAT JAM BEBAS ---
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('dosen.name')->label('Dosen')->searchable()->sortable()->visible(fn(): bool => auth()->check() && auth()->user()->role === 'admin'),
                TextColumn::make('room.nama_ruang')->label('Ruang Baru'),
                TextColumn::make('kelas.nama_kelas')->label('Kelas'),
                TextColumn::make('mata_kuliah')->searchable(),
                TextColumn::make('hari')->label('Hari Baru')->sortable(),
                TextColumn::make('waktu_diajukan')->label('Jam Baru') // Label disesuaikan
                    ->formatStateUsing(function ($record) { // Format jam bebas
                        if (!$record || !$record->jam_mulai || !$record->jam_selesai) return '-';
                        return Carbon::parse($record->jam_mulai)->format('H:i') . ' - ' . Carbon::parse($record->jam_selesai)->format('H:i');
                    }),
                BadgeColumn::make('status')->colors(['secondary' => 'pending', 'success' => 'approved', 'danger' => 'rejected']),
                TextColumn::make('created_at')->dateTime('d M Y H:i')->label('Diajukan')->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('alasan_dosen')->label('Alasan')->limit(30)->tooltip(fn($record) => $record->alasan_dosen)->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([ /* ... filter status dosen ... */ ])
            ->actions([
                // --- ACTION SETUJUI DIKEMBALIKAN KE JAM BEBAS ---
                Action::make('setujui')
                    ->label('Setujui')->icon('heroicon-o-check-circle')->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Setujui Penggantian Jadwal?')
                    ->modalDescription('Apakah Anda yakin? Jadwal asli akan diperbarui.')
                    ->action(function (PengajuanJadwal $record) {
    try {
        // Validasi awal
        if ($record->tipe_pengajuan !== 'ganti' || !$record->jadwal_lama_id) {
             throw new \Exception("Pengajuan (ID: {$record->id}) tidak valid untuk penggantian.");
        }
        $jadwalLamaId = $record->jadwal_lama_id;
        $ruanganBaruId = $record->room_id;
        $hariBaru = $record->hari;
        $jamMulaiBaru = $record->jam_mulai;
        $jamSelesaiBaru = $record->jam_selesai;
        if (!$ruanganBaruId || !$hariBaru || !$jamMulaiBaru || !$jamSelesaiBaru) {
            throw new \Exception('Data perubahan tidak lengkap.');
        }

        $jadwalYangDiupdate = null;

        DB::transaction(function () use ($record, $jadwalLamaId, $ruanganBaruId, $hariBaru, $jamMulaiBaru, $jamSelesaiBaru, &$jadwalYangDiupdate) {
            
            $jadwalLama = Jadwal::find($jadwalLamaId);
            if (!$jadwalLama) { throw new \Exception("Jadwal asli (ID: {$jadwalLamaId}) tidak ditemukan."); }

            // --- VALIDASI KONFLIK KOMPLEKS (Ruang, Dosen, Kelas) ---
            // (Diadaptasi dari JadwalResource::mutateFormDataBeforeSave)
            $konflik = Jadwal::query()
                ->where('id', '!=', $jadwalLama->id) // Abaikan jadwal lama itu sendiri
                ->where('hari', $hariBaru) // Cek di hari baru
                ->where(function (Builder $query) use ($jamMulaiBaru, $jamSelesaiBaru) { // Cek overlap waktu
                    $query->where('jam_mulai', '<', $jamSelesaiBaru)
                          ->where('jam_selesai', '>', $jamMulaiBaru);
                })
                ->where(function (Builder $query) use ($ruanganBaruId, $jadwalLama) {
                    // Cek apakah RUANGAN BARU sudah dipakai
                    $query->where('room_id', $ruanganBaruId) 
                          // Cek apakah DOSEN (yang sama) sudah ada jadwal lain
                          ->orWhere('dosen_id', $jadwalLama->dosen_id) 
                          // Cek apakah KELAS (yang sama) sudah ada jadwal lain
                          ->orWhere('class_id', $jadwalLama->class_id); 
                })
                ->with(['room', 'dosen', 'kelas'])->first();

            if ($konflik) {
                // Buat pesan error spesifik seperti di JadwalResource
                $pesanError = ''; $fieldError = 'bentrok'; // fieldError umum
                if ($konflik->room_id == $ruanganBaruId) {
                    $pesanError = "Ruangan ".($konflik->room?->nama_ruang ?? 'N/A')." sudah dipakai oleh MK '{$konflik->mata_kuliah}'.";
                    $fieldError = 'room_id'; // Fokuskan error ke field ruangan
                } elseif ($konflik->dosen_id == $jadwalLama->dosen_id) {
                    $pesanError = "Dosen ".($konflik->dosen?->name ?? 'N/A')." sudah mengajar MK '{$konflik->mata_kuliah}' di waktu yang sama.";
                    $fieldError = 'dosen_id'; // Sebenarnya fieldnya tidak ada, tapi ini untuk info
                } elseif ($konflik->class_id == $jadwalLama->class_id) {
                    $pesanError = "Kelas ".($konflik->kelas?->nama_kelas ?? 'N/A')." sudah ada jadwal MK '{$konflik->mata_kuliah}'.";
                    $fieldError = 'class_id'; // Sebenarnya fieldnya tidak ada
                } else {
                     $pesanError = "Terjadi konflik jadwal yang tidak terdeteksi.";
                }
                
                Notification::make()->danger()->title('Terjadi Konflik Jadwal')->body($pesanError)->send(); // Notif instan
                throw ValidationException::withMessages([$fieldError => $pesanError]);
            }
            // --- AKHIR VALIDASI KONFLIK ---

            // Lakukan UPDATE jika tidak ada konflik
            $isUpdated = $jadwalLama->update([
                'room_id' => $ruanganBaruId,
                'hari' => $hariBaru,
                'jam_mulai' => Carbon::parse($jamMulaiBaru)->format('H:i:s'), // Format konsisten
                'jam_selesai' => Carbon::parse($jamSelesaiBaru)->format('H:i:s'), // Format konsisten
            ]);
            if (!$isUpdated) { throw new \Exception("Gagal mengupdate Jadwal ID {$jadwalLama->id}."); }
            $jadwalYangDiupdate = $jadwalLama->fresh();

            $record->update(['status' => 'approved']);
            $record->loadMissing('dosen');
            $record->dosen?->notify(new StatusPengajuanUntukDosen($record));
        });

        // Panggil event SETELAH transaction
        if ($jadwalYangDiupdate) { event(new JadwalDiubah($jadwalYangDiupdate)); }

        Notification::make()->title('Jadwal Berhasil Diperbarui')->success()->send();

    } catch (ValidationException $e) { // Tangkap bentrok
        Notification::make()->title('Validasi Gagal: Jadwal Bentrok')->danger()
            // Ambil pesan error pertama (karena kita pakai key dinamis/umum)
            ->body($e->validator->errors()->first()) 
            ->persistent()->send();
        return;
    } catch (\Throwable $th) { // Tangkap semua error lain
        Log::critical("CRITICAL Error approving schedule change for Pengajuan ID {$record->id}: " . $th->getMessage(), ['trace' => $th->getTraceAsString()]);
        Notification::make()->title('Gagal Memperbarui Jadwal')->danger()
            ->body('Terjadi kesalahan: ' . $th->getMessage())->persistent()->send();
    }
})
->visible(fn(): bool => auth()->check() && auth()->user()->role === 'admin'),
                // --- AKHIR ACTION SETUJUI ---

                Action::make('tolak') /* ... */ ->visible(fn(): bool => auth()->check() && auth()->user()->role === 'admin'),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make() /* ... */ ->visible(fn(): bool => auth()->check() && auth()->user()->role === 'dosen') /* ... */,
            ])
            ->bulkActions([ /* ... */ ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPengajuanJadwals::route('/'),
            'create' => Pages\CreatePengajuanJadwal::route('/create'),
            'view' => Pages\ViewPengajuanJadwal::route('/{record}'),
            // 'edit' => Pages\EditPengajuanJadwal::route('/{record}/edit'), // Edit tidak diperlukan lagi
        ];
    }
}