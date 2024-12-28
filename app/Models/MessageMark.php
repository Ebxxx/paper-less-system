<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MessageMark extends Model
{
    protected $fillable = [
        'message_id',
        'is_important',
        'is_urgent',
        'deadline'
    ];

    protected $casts = [
        'is_important' => 'boolean',
        'is_urgent' => 'boolean',
        'deadline' => 'datetime'
    ];

    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class);
    }
}