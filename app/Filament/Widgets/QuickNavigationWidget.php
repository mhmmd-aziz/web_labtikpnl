<?php

namespace App\Filament\Widgets; // 

use Filament\Widgets\Widget;

class QuickNavigationWidget extends Widget
{
    protected static string $view = 'filament.widgets.quick-navigation-widget';
    protected int | string | array $columnSpan = 'full';

    /**
     * Metode ini menentukan siapa yang bisa melihat widget ini.
     * Hanya akan tampil jika role pengguna adalah 'admin'.
     */
    public static function canView(): bool
    {
        return auth()->user()->role === 'admin';
    }
}