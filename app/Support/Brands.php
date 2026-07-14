<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

/**
 * Resolves which of our sites a request came from (by the referrer/origin
 * domain) and wires up that brand's SMTP mailbox at runtime so an outgoing
 * email is sent from the same site the visitor arrived on.
 */
class Brands
{
    /**
     * The brand a request originated from, or null when the referrer domain
     * matches no active brand.
     */
    public function resolveFromRequest(Request $request): ?Brand
    {
        $host = $this->hostFromRequest($request);

        return $host !== null ? $this->matchHost($host) : null;
    }

    /**
     * The normalised domain a request came from (origin header first, then
     * referrer), or null when neither header carries a usable host. Recorded
     * against the signup so we keep the domain even when no brand matches.
     */
    public function hostFromRequest(Request $request): ?string
    {
        $origin = $request->headers->get('origin') ?? $request->headers->get('referer');
        $host = is_string($origin) ? parse_url($origin, PHP_URL_HOST) : null;

        if (! is_string($host) || $host === '') {
            return null;
        }

        return $this->normalizeHost($host) ?: null;
    }

    /**
     * Strip a host down to a bare, comparable domain: no scheme, path, port,
     * credentials, "www." prefix or casing.
     */
    public function normalizeHost(string $host): string
    {
        $host = Str::lower(Str::of($host)->after('//')->before('/')->toString());
        $host = Str::of($host)->after('@')->before(':')->toString();

        return str_starts_with($host, 'www.') ? substr($host, 4) : $host;
    }

    /**
     * Find the active brand whose domain matches the given hostname. The most
     * specific (longest) domain wins so "1win.com" beats a broader "win".
     */
    public function matchHost(string $host): ?Brand
    {
        $host = $this->normalizeHost($host);

        if ($host === '') {
            return null;
        }

        return Brand::query()
            ->where('is_active', true)
            ->whereNotNull('domain')
            ->where('domain', '!=', '')
            ->get()
            ->sortByDesc(fn (Brand $brand): int => strlen((string) $brand->domain))
            ->first(fn (Brand $brand): bool => $this->hostMatchesDomain($host, Str::lower(trim((string) $brand->domain))));
    }

    /**
     * Register this brand's SMTP credentials as a runtime mailer and return its
     * config name for {@see MailMessage::mailer()}.
     */
    public function configureMailer(Brand $brand): string
    {
        $name = 'brand_'.$brand->getKey();

        Config::set("mail.mailers.{$name}", [
            'transport' => 'smtp',
            'host' => $brand->mail_host,
            'port' => $brand->mail_port ?? 587,
            'username' => $brand->mail_username,
            'password' => $brand->mail_password,
            'scheme' => $brand->smtpScheme(),
            'timeout' => null,
        ]);

        return $name;
    }

    private function hostMatchesDomain(string $host, string $domain): bool
    {
        if ($domain === '') {
            return false;
        }

        return $host === $domain
            || str_ends_with($host, '.'.$domain)
            || str_contains($host, $domain);
    }
}
