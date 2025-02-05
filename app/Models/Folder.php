<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Folder extends Model
{
    protected $fillable = ['name', 'user_id'];
    protected $withCount = ['messages'];
    protected $appends = ['messages_count'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the messages in this folder.
     */
    public function messages(): BelongsToMany
    {
        return $this->belongsToMany(Message::class, 'folder_messages')
                    ->withTimestamps();
    }

    public function getMessagesCountAttribute()
    {
        return $this->messages()->count();
    }
} 