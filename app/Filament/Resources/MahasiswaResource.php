<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MahasiswaResource\Pages;
// vvv IMPORT YANG BENAR vvv
use App\Models\Mahasiswa;
use Illuminate\Validation\Rule;
// ^^^ IMPORT YANG BENAR ^^^
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class MahasiswaResource extends Resource
{
    protected static ?string $model = Mahasiswa::class;
// ... (sisa file biarkan sama persis seperti di context)
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    // protected static ?string $navigationGroup = 'Manajemen Data';

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\Section::make('Akun User')
                ->schema([
                    Forms\Components\TextInput::make('user.name')
                        ->label('Nama Lengkap')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('user.email')
                        ->label('Email')
                        ->email()
                        ->required()
                        ->maxLength(255)
                        ->unique('users', 'email', ignoreRecord: true),

                    Forms\Components\TextInput::make('user.password')
                        ->label('Password')
                        ->password()
                        ->maxLength(255)
                        ->dehydrateStateUsing(fn ($state) => filled($state) ? bcrypt($state) : null)
                        ->dehydrated(fn ($state) => filled($state))
                        ->required(fn (string $context): bool => $context === 'create'),
                ])
                ->columns(2),

            Forms\Components\Section::make('Data Mahasiswa')
                ->schema([
                    Forms\Components\TextInput::make('nim')
                        ->required()
                        ->maxLength(255)
                        ->unique('mahasiswas', 'nim', ignoreRecord: true),
                    Forms\Components\Select::make('prodi_id')
                        ->label('Program Studi')
                        ->relationship('prodi', 'nama')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Forms\Components\Select::make('angkatan_id')
                        ->label('Angkatan')
                        ->relationship('angkatan', 'nama_angkatan')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Forms\Components\Select::make('kelas_id')
                        ->label('Kelas')
                        ->relationship('kelas', 'nama_kelas')
                        ->searchable()
                        ->preload()
                        ->required(),
                ])
                ->columns(2),
        ]);
}



    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name') 
                    ->label('Nama Mahasiswa')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nim')
                    ->searchable(),
                Tables\Columns\TextColumn::make('prodi.nama') 
                    ->label('Prodi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('angkatan.nama_angkatan')
                    ->label('Angkatan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMahasiswas::route('/'),
            'create' => Pages\CreateMahasiswa::route('/create'),
            'edit' => Pages\EditMahasiswa::route('/{record}/edit'),
        ];
    }    
}