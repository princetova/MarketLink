<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FarmerProfile extends Model
{
    public const STATUS_PENDING = 'PENDING';

    public const STATUS_APPROVED = 'APPROVED';

    public const STATUS_SUSPENDED = 'SUSPENDED';

    public const STATUS_REJECTED = 'REJECTED';

    /** @var list<string> */
    protected $fillable = [
        'user_id',
        'business_name',
        'address',
        'approval_status',
        'reviewed_by',
        'reviewed_at',
        'review_note',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo<User, $this> */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
