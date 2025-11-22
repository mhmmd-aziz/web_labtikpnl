<?php

namespace App\Filament\Resources\PengajuanJadwalResource\Pages;

use App\Filament\Resources\PengajuanJadwalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPengajuanJadwal extends EditRecord
{
    protected static string $resource = PengajuanJadwalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
