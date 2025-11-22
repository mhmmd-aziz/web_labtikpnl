<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KelasResource\Pages;
use App\Models\Kelas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

class KelasResource extends Resource
{
    protected static ?string $model = Kelas::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';
    protected static ?string $navigationGroup = 'Manajemen Data';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama_kelas')
                    ->label('Nama Kelas (Cth: TI-1A)')
                    ->required()
                    ->maxLength(255),
                Select::make('angkatan_id')
                    ->relationship('angkatan', 'nama_angkatan')
                    ->label('Angkatan')
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // --- INI PERBAIKANNYA ---
                // Kita ambil 'nama' prodi dari relasi 'angkatan'
                TextColumn::make('angkatan.prodi.nama')
                    ->label('Prodi')
                    ->searchable()
                    ->sortable(),
                // --------------------------

                TextColumn::make('angkatan.nama_angkatan')
                    ->label('Angkatan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nama_kelas')
                    ->label('Nama Kelas')
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
            'index' => Pages\ListKelas::route('/'),
            'create' => Pages\CreateKelas::route('/create'),
            'edit' => Pages\EditKelas::route('/{record}/edit'),
        ];
    }    
}