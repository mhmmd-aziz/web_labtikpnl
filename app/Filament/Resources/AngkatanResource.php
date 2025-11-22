<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AngkatanResource\Pages;
use App\Models\Angkatan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

class AngkatanResource extends Resource
{
    protected static ?string $model = Angkatan::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Manajemen Data';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama_angkatan')
                    ->label('Nama Angkatan (Cth: TI 2023)')
                    ->required()
                    ->maxLength(255),
                
                // --- INI BAGIAN PERBAIKANNYA ---
                Select::make('prodi_id')
                    ->label('Prodi')
                    ->relationship('prodi', 'nama') // <-- Ganti 'nama_prodi' menjadi 'nama'
                    ->searchable()
                    ->preload()
                    ->required(),
                // ---------------------------------
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_angkatan')
                    ->label('Nama Angkatan')
                    ->searchable(),
                
                // --- INI JUGA PERBAIKAN UNTUK TABEL ---
                TextColumn::make('prodi.nama') // <-- Ganti 'prodi.nama_prodi' menjadi 'prodi.nama'
                    ->label('Prodi')
                    ->searchable()
                    ->sortable(),
                // --------------------------------------
                
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable()
                    ->toggledHiddenByDefault(true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable()
                    ->toggledHiddenByDefault(true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListAngkatans::route('/'),
            'create' => Pages\CreateAngkatan::route('/create'),
            'edit' => Pages\EditAngkatan::route('/{record}/edit'),
        ];
    }    
}