<?php

namespace App\Models;

use Database\Factories\MfaResetTokenFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property string $token
 * @property Carbon $expires_at
 * @property bool $used
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class MfaResetToken extends Model
{
    /** @use HasFactory<MfaResetTokenFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'token',
        'expires_at',
        'used',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'used' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the token is expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Check if the token is valid (not used and not expired).
     */
    public function isValid(): bool
    {
        return ! $this->used && ! $this->isExpired();
    }
}
