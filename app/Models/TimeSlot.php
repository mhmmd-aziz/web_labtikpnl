<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TimeSlot extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected function jamMulaiFormatted(): Attribute
{
    return Attribute::make(
        get: fn ($value, $attributes) => isset($attributes['jam_mulai']) ? Carbon::parse($attributes['jam_mulai'])->format('H:i') : null,
    );
}

protected function jamSelesaiFormatted(): Attribute
{
    return Attribute::make(
        get: fn ($value, $attributes) => isset($attributes['jam_selesai']) ? Carbon::parse($attributes['jam_selesai'])->format('H:i') : null,
    );
}
}