<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\BrandFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * One of our public sites. Matched to an incoming signup by {@see $domain}
 * (against the request referrer) and carries its own SMTP credentials and
 * sender identity so verification emails go out from that site's mailbox.
 *
 * @property int $id
 * @property string $name
 * @property string|null $domain
 * @property string|null $from_name
 * @property string|null $from_address
 * @property string|null $mail_host
 * @property int|null $mail_port
 * @property string|null $mail_username
 * @property string|null $mail_password
 * @property string|null $mail_encryption
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'name',
    'domain',
    'from_name',
    'from_address',
    'mail_host',
    'mail_port',
    'mail_username',
    'mail_password',
    'mail_encryption',
    'is_active',
])]
#[Hidden(['mail_password'])]
class Brand extends Model
{
    /** @use HasFactory<BrandFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            // Encrypted at rest so SMTP passwords never sit in plaintext.
            'mail_password' => 'encrypted',
            'mail_port' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Affiliates who registered on this site.
     *
     * @return HasMany<User, $this>
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Whether this brand has enough SMTP config to send on its own.
     */
    public function hasSmtp(): bool
    {
        return filled($this->mail_host);
    }

    /**
     * Symfony mailer scheme for this brand's encryption setting. "smtps" forces
     * implicit TLS (port 465); null lets the transport pick smtp/STARTTLS.
     */
    public function smtpScheme(): ?string
    {
        return $this->mail_encryption === 'ssl' ? 'smtps' : null;
    }

    /**
     * Sender address, falling back to the global "from" address.
     */
    public function fromAddress(): string
    {
        return $this->from_address ?: (string) config('mail.from.address');
    }

    /**
     * Sender name, falling back to the brand name, then the global one.
     */
    public function fromName(): string
    {
        return $this->from_name ?: ($this->name ?: (string) config('mail.from.name'));
    }
}
