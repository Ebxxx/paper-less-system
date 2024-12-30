<?php

namespace App\Events;

use App\Models\Message;
use App\Models\User;
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
        $recipientUser = User::find($this->message->to_user_id);
        
        return [
            'id' => $this->message->id,
            'subject' => $this->message->subject,
            'content' => $this->message->content,
            'created_at' => $this->message->created_at->format('M d, Y h:i A'),
            'sender' => [
                'id' => $this->message->sender->id,
                'username' => $this->message->sender->username,
                'email' => $this->message->sender->email,
            ],
            'recipient' => [
                'id' => $this->message->recipient->id,
                'username' => $this->message->recipient->username,
                'email' => $this->message->recipient->email,
            ],
            'parent_id' => $this->message->parent_id,
            'parent_message' => $this->message->parentMessage ? [
                'id' => $this->message->parentMessage->id,
                'subject' => $this->message->parentMessage->subject
            ] : null,
            'has_attachments' => $this->message->attachments->count() > 0,
            'attachments' => $this->message->attachments->map(function($attachment) {
                return [
                    'id' => $attachment->id,
                    'original_filename' => $attachment->original_filename,
                    'file_size' => $attachment->file_size,
                ];
            }),
            'mark' => $this->message->mark ? [
                'is_important' => $this->message->mark->is_important,
                'is_urgent' => $this->message->mark->is_urgent,
                'deadline' => $this->message->mark->deadline
            ] : null,
            // Add counts for real-time updates
            'unread_count' => $recipientUser->unreadMessages()->count(),
            'inbox_count' => $recipientUser->receivedMessages()->where('is_archived', false)->count()
        ];
    }
} 