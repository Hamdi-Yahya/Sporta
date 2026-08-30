<?php

namespace App\Events;

use App\Models\PesanChat;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PesanTerkirim implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $pesan;

    /**
     * Create a new event instance.
     */
    public function __construct(PesanChat $pesan)
    {
        $this->pesan = $pesan->load('user'); // Load user to include sender name in broadcast
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // Public channel
        return [
            new Channel('komunitas.' . $this->pesan->komunitas_id),
        ];
    }

    /**
     * Data to be broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->pesan->id,
            'komunitas_id' => $this->pesan->komunitas_id,
            'user_id' => $this->pesan->user_id,
            'isi_pesan' => $this->pesan->isi_pesan,
            'waktu_kirim' => $this->pesan->waktu_kirim->format('H:i'),
            'user' => [
                'id' => $this->pesan->user->id,
                'name' => $this->pesan->user->name,
            ],
        ];
    }
}
