<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Shared option lists for partner payout settings, used by both the user
 * facing wallet form and the Filament admin panel so the two stay in sync.
 */
class PartnerOptions
{
    /**
     * Payout wallet networks. Keys are stored; values are shown to the user.
     *
     * @var array<string, string>
     */
    public const array NETWORKS = [
        'USDT_TRC20' => 'USDT (TRC-20)',
        'USDT_ERC20' => 'USDT (ERC-20)',
        'USDT_BEP20' => 'USDT (BEP-20)',
        'BTC' => 'Bitcoin (BTC)',
        'ETH' => 'Ethereum (ETH)',
        'LTC' => 'Litecoin (LTC)',
        'TRX' => 'Tron (TRX)',
    ];

    /**
     * Currencies available for the fixed payment amount.
     *
     * @var array<string, string>
     */
    public const array CURRENCIES = [
        'USDT' => 'USDT',
        'USD' => 'USD ($)',
        'EUR' => 'EUR (€)',
        'BTC' => 'BTC',
        'ETH' => 'ETH',
    ];

    /**
     * Network options shaped for a front-end select component.
     *
     * @return array<int, array{value: string, label: string}>
     */
    public static function networkOptions(): array
    {
        return array_map(
            static fn (string $value, string $label): array => ['value' => $value, 'label' => $label],
            array_keys(self::NETWORKS),
            array_values(self::NETWORKS),
        );
    }
}
