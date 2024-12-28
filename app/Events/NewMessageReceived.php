<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessageReceived implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message->load(['sender', 'attachments', 'mark']);
    }

    public function broadcastOn()
    {
        return new PrivateChannel('messages.' . $this->message->to_user_id);
    }

    public function broadcastAs()
    {
        return 'new.message';
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->message->id,
            'subject' => $this->message->subject,
            'content' => \Str::limit($this->message->content, 50),
            'created_at' => $this->message->created_at->format('M d, Y h:i A'),
            'sender' => [
                'username' => $this->message->sender->username,
                'email' => $this->message->sender->email,
            ],
            'has_attachments' => $this->message->attachments->count() > 0,
            'read_at' => null,
            'mark' => $this->message->mark ? [
                'is_important' => $this->message->mark->is_important,
                'is_urgent' => $this->message->mark->is_urgent,
                'deadline' => $this->message->mark->deadline
            ] : null
        ];
    }
} 