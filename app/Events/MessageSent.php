<?php

namespace App\Events;

use App\Models\ChatMensaje;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct(ChatMensaje $message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chat.' . $this->message->conversacion_id),
        ];
    }

    /**
     * Get the data that should be sent with the broadcasted event.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
            'mensaje' => $this->message->mensaje,
            'emisor_id' => $this->message->emisor_id,
            'emisor_name' => $this->message->emisor->name,
            'created_at' => $this->message->created_at->toISOString(),
            'conversacion_id' => $this->message->conversacion_id,
        ];
    }

    /**
     * Get the event name that should be broadcasted.
     */
    public function broadcastAs(): string
    {
        return 'message.sent';
    }
}
