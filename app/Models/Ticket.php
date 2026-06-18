<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property string $message
 * @property string|null $reply
 * @property string $status
 * @property Carbon|null $replied_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $user
 */
#[Fillable(['user_id', 'message', 'reply', 'status', 'replied_at'])]
class Ticket extends Model
{
    public const string STATUS_OPEN = 'open';

    public const string STATUS_ANSWERED = 'answered';

    public const string STATUS_CLOSED = 'closed';

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'status' => self::STATUS_OPEN,
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'replied_at' => 'datetime',
        ];
    }

    /**
     * When a reply is added, stamp the answer time and mark it answered.
     */
    protected static function booted(): void
    {
        static::saving(function (Ticket $ticket): void {
            if ($ticket->isDirty('reply') && filled($ticket->reply)) {
                $ticket->replied_at ??= Carbon::now();

                if ($ticket->status !== self::STATUS_CLOSED) {
                    $ticket->status = self::STATUS_ANSWERED;
                }
            }
        });
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
