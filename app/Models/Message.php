<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\MessageAttachment;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Attachment;
use App\Events\MessageRead;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_user_id',
        'to_user_id',
        'subject',
        'content',
        'is_important',
        'is_urgent',
        'deadline',
        'read_at',
        'is_starred',
        'is_archived',
        'parent_id'
    ];

    protected $casts = [        
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(MessageAttachment::class);
    }

    public function mark()
    {
        return $this->hasOne(MessageMark::class);
    }

    protected $with = ['mark'];

    public function parentMessage()
    {
        return $this->belongsTo(Message::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Message::class, 'parent_id')
            ->orderBy('created_at', 'asc');
    }

    public function thread()
    {
        if ($this->parent_id) {
            return $this->parentMessage->thread();
        }
        return $this;
    }

    public function getAllReplies()
    {
        return $this->replies()
            ->with(['sender', 'recipient', 'mark', 'attachments'])
            ->get()
            ->concat($this->replies()->get()->flatMap->getAllReplies());
    }

    public function marks(): HasMany
    {
        return $this->hasMany(MessageMark::class);
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    public function markAsRead()
    {
        $this->read_at = now();
        $this->save();
        
        // Dispatch event if needed
        event(new MessageRead($this));
        
        return $this;
    }
}