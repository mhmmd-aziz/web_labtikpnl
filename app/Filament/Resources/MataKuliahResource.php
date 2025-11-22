<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MataKuliahResource\Pages;
use App\Filament\Resources\MataKuliahResource\RelationManagers;
use App\Models\MataKuliah;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;


class MataKuliahResource extends Resource
{
    protected static ?string $model = MataKuliah::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            TextInput::make('kode_matkul')
                ->label('Kode Mata Kuliah')
                ->required()
                ->unique(ignoreRecord: true) // unik, kecuali untuk record saat ini
                ->maxLength(20),
            TextInput::make('nama_matkul')
                ->label('Nama Mata Kuliah')
                ->required()
                ->maxLength(255),
            TextInput::make('sks')
                ->numeric()
                ->required(),
            Select::make('prodi_id')
                ->relationship('programStudi', 'nama') // Asumsi kolom display di ProgramStudi adalah 'nama_prodi'
                ->searchable()
                ->preload()
                ->required(),
        ]);
}
    public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('kode_matkul')
                ->label('Kode')
                ->searchable()
                ->sortable(),
            TextColumn::make('nama_matkul')
                ->label('Nama Mata Kuliah')
                ->searchable()
                ->sortable(),
            TextColumn::make('sks')
                ->sortable(),
            TextColumn::make('programStudi.nama') // Tampilkan nama prodi
                ->label('Program Studi')
                ->searchable()
                ->sortable(),
        ])
        ->filters([
            // ...
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
            'index' => Pages\ListMataKuliahs::route('/'),
            'create' => Pages\CreateMataKuliah::route('/create'),
            'edit' => Pages\EditMataKuliah::route('/{record}/edit'),
        ];
    }
}
