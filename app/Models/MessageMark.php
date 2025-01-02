<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageMark extends Model
{
    protected $fillable = [
        'message_id',
        'is_important',
        'is_urgent',
        'deadline',
        'pre_reply'
    ];

    protected $casts = [
        'is_important' => 'boolean',
        'is_urgent' => 'boolean',
        'deadline' => 'datetime',
    ];

    public function message()
    {
        return $this->belongsTo(Message::class);
    }
}