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

    public const STATUS_CONTACTED = 'contacted';

    public const STATUS_CLOSED = 'closed';

    /** @deprecated Use STATUS_CONTACTED */
    public const STATUS_READ = 'contacted';

    /** @deprecated Use STATUS_CONTACTED */
    public const STATUS_REPLIED = 'contacted';

    /** @deprecated Use STATUS_CLOSED */
    public const STATUS_ARCHIVED = 'closed';

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
