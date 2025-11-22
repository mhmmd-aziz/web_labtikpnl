<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Jadwal;

class JadwalDiubah
{

    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $jadwal;

    /**
     * Create a new event instance.
     */
    public function __construct(Jadwal $jadwal) // <-- Modifikasi
    {
        $this->jadwal = $jadwal; // <-- Tambahkan
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
