<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsultationMessageNote extends Model
{
    protected $fillable = [
        'consultation_message_id',
        'user_id',
        'body',
    ];

    public function message(): BelongsTo
    {
        return $this->belongsTo(ConsultationMessage::class, 'consultation_message_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

