<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DosenResource\Pages;
use App\Models\Dosen;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class DosenResource extends Resource
{
    protected static ?string $model = Dosen::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase'; 
    protected static ?string $navigationGroup = 'Manajemen Data';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Akun User (Login)')
                    ->schema([
                        Forms\Components\TextInput::make('user.name')
                            ->label('Nama Lengkap Dosen')
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

                Forms\Components\Section::make('Data Akademik Dosen')
                    ->schema([
                        Forms\Components\TextInput::make('nidn') 
                            ->label('NIP / NIDN')
                            ->required()
                            ->unique('dosens', 'nidn', ignoreRecord: true)
                            ->maxLength(20),
                            
                        Forms\Components\Select::make('prodi_id')
                            ->relationship('prodi', 'nama')
                            ->label('Prodi (Opsional)') // <-- Ditambahkan keterangan opsional
                            ->searchable()
                            ->preload(), // <-- Dihapus ->required() nya disini
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name') 
                    ->label('Nama Dosen')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('nidn') 
                    ->label('NIP / NIDN')
                    ->searchable(),

                TextColumn::make('prodi.nama')
                    ->label('Prodi')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'), // Menampilkan strip '-' jika tidak ada prodi

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