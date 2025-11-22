<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // If you need user relation

class PushSubscription extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // --- TAMBAHKAN INI ---
    protected $fillable = [
        'user_id',
        'endpoint',
        'public_key',
        'auth_token',
    ];
    // --------------------

    // ATAU, jika kamu lebih suka mengizinkan SEMUA kolom (kurang aman tapi simpel):
    // protected $guarded = [];


    // Opsional: Relasi ke User (jika perlu)
    // public function user(): BelongsTo
    // {
    //     return $this->belongsTo(User::class);
    // }
}