<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsultationMessage extends Model
{
    protected $fillable = [
        'name',
        'company',
        'email',
        'area',
        'message',
        'status',
        'notes',
        'first_reply',
    ];

    public const STATUS_NEW = 'new';
    public const STATUS_READ = 'read';
    public const STATUS_REPLIED = 'replied';
    public const STATUS_ARCHIVED = 'archived';

    public const AREAS = [
        'Strategy & Market Intelligence',
        'Business Development & Growth',
        'Talent & People Solutions',
        'Content & Communications',
        'Not sure yet',
    ];

    public function adminNotes()
    {
        return $this->hasMany(ConsultationMessageNote::class)
            ->latest();
    }
}
