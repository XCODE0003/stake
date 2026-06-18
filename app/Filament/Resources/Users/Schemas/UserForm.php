<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users\Schemas;

use App\Support\PartnerOptions;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Account')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Username')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->revealable()
                            ->helperText('Leave blank to keep the current password.')
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->maxLength(255),
                        TextInput::make('referral_code')
                            ->label('Referral code')
                            ->maxLength(64),
                        Toggle::make('is_admin')
                            ->label('Administrator / manager')
                            ->helperText('Grants access to this admin panel.'),
                    ]),

                Section::make('Status')
                    ->columns(2)
                    ->schema([
                        DateTimePicker::make('email_verified_at')
                            ->label('Email verified at'),
                        DateTimePicker::make('approved_at')
                            ->label('Approved by manager at')
                            ->helperText('Set to activate the account.'),
                    ]),

                Section::make('Affiliate terms')
                    ->columns(2)
                    ->schema([
                        TextInput::make('fixed_payment_amount')
                            ->label('Fixed payment')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->default(0),
                        Select::make('fixed_payment_currency')
                            ->label('Currency')
                            ->options(PartnerOptions::CURRENCIES)
                            ->required()
                            ->default('USDT'),
                        TextInput::make('streams_count')
                            ->label('Streams')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->default(0),
                        TextInput::make('referrals_count')
                            ->label('Referrals')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->default(0),
                        TextInput::make('casino_profit')
                            ->label('Casino profit')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->default(0),
                        TextInput::make('your_profit')
                            ->label('Your profit')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->default(0),
                    ]),

                Section::make('Payout wallet')
                    ->columns(2)
                    ->schema([
                        Select::make('wallet_network')
                            ->label('Network')
                            ->options(PartnerOptions::NETWORKS)
                            ->searchable(),
                        TextInput::make('wallet_address')
                            ->label('Wallet address')
                            ->maxLength(255),
                    ]),
            ]);
    }
}
