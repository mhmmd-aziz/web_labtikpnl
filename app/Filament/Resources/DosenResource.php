<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DosenResource\Pages;
use App\Models\Dosen;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

class DosenResource extends Resource
{
    protected static ?string $model = Dosen::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase'; // Ganti ikon jika perlu
    protected static ?string $navigationGroup = 'Manajemen Data';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->label('User (Nama Dosen)')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('nidn') // <-- Pastikan ini 'nidn'
                    ->label('NIP / NIDN')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(20),
                Select::make('prodi_id')
                    ->relationship('prodi', 'nama')
                    ->label('Prodi')
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Kolom ini mengambil 'name' dari relasi 'user'
                TextColumn::make('user.name') 
                    ->label('Nama Dosen')
                    ->searchable()
                    ->sortable(),
                
                // --- INI PERBAIKANNYA ---
                // Pastikan Anda memanggil 'nidn', sesuai nama kolom di database
                TextColumn::make('nidn') 
                    ->label('NIP / NIDN')
                    ->searchable(),
                // --------------------------

                // Kolom ini mengambil 'nama' dari relasi 'prodi'
                TextColumn::make('prodi.nama')
                    ->label('Prodi')
                    ->searchable()
                    ->sortable(),

                // Kolom ini mengambil 'email' dari relasi 'user'
                TextColumn::make('user.email')
                    ->label('Email')
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
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDosens::route('/'),
            'create' => Pages\CreateDosen::route('/create'),
            'edit' => Pages\EditDosen::route('/{record}/edit'),
        ];
    }    
}