<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Brand>
 */
class BrandFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $slug = fake()->unique()->domainWord();

        return [
            'name' => ucfirst($slug),
            'domain' => $slug.'.com',
            'from_name' => ucfirst($slug),
            'from_address' => 'no-reply@'.$slug.'.com',
            'mail_host' => null,
            'mail_port' => 587,
            'mail_username' => null,
            'mail_password' => null,
            'mail_encryption' => null,
            'is_active' => true,
        ];
    }

    /**
     * A brand with working-looking SMTP credentials.
     */
    public function withSmtp(): static
    {
        return $this->state(fn (array $attributes): array => [
            'mail_host' => 'smtp.'.($attributes['domain'] ?? 'example.com'),
            'mail_port' => 587,
            'mail_username' => $attributes['from_address'] ?? 'no-reply@example.com',
            'mail_password' => 'secret',
            'mail_encryption' => 'tls',
        ]);
    }
}
