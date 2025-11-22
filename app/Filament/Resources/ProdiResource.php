<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProdiResource\Pages;
use App\Models\Prodi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

class ProdiResource extends Resource
{
    protected static ?string $model = Prodi::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';
    protected static ?string $navigationGroup = 'Manajemen Data';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Pastikan ini 'nama', 'jenjang', dan 'kode_prodi'
                // Sesuai dengan seeder Anda
                TextInput::make('nama')
                    ->label('Nama Prodi')
                    ->required()
                    ->maxLength(255),
                TextInput::make('jenjang')
                    ->label('Jenjang (Cth: D4)')
                    ->required()
                    ->maxLength(10),
                TextInput::make('kode_prodi')
                    ->label('Kode Prodi (Cth: TI)')
                    ->required()
                    ->maxLength(10),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Pastikan ini 'nama', bukan 'nama_prodi'
                TextColumn::make('nama')
                    ->label('Nama Prodi')
                    ->searchable(),
                TextColumn::make('jenjang')
                    ->searchable(),
                TextColumn::make('kode_prodi')
                    ->label('Kode')
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
            'index' => Pages\ListProdis::route('/'),
            'create' => Pages\CreateProdi::route('/create'),
            'edit' => Pages\EditProdi::route('/{record}/edit'),
        ];
    }    
}