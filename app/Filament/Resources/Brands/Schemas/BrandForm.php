<?php

declare(strict_types=1);

namespace App\Filament\Resources\Brands\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BrandForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Site')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('domain')
                            ->label('Domain')
                            ->placeholder('vodka-partners.com')
                            ->helperText('Signups are matched to this site by the referrer domain. No https:// or path.')
                            ->maxLength(255),
                        Toggle::make('is_active')
                            ->label('Active')
                            ->helperText('Only active sites are matched to new signups.')
                            ->default(true),
                    ]),

                Section::make('Sender')
                    ->columns(2)
                    ->schema([
                        TextInput::make('from_name')
                            ->label('From name')
                            ->placeholder('Vodka')
                            ->maxLength(255),
                        TextInput::make('from_address')
                            ->label('From address')
                            ->email()
                            ->placeholder('no-reply@vodka-partners.com')
                            ->maxLength(255),
                    ]),

                Section::make('SMTP')
                    ->description('Outgoing mail server for this site. Leave blank to use the global mail settings.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('mail_host')
                            ->label('Host')
                            ->placeholder('smtp.zoho.eu')
                            ->maxLength(255),
                        TextInput::make('mail_port')
                            ->label('Port')
                            ->numeric()
                            ->default(587)
                            ->helperText('465 for SSL, 587 for TLS/STARTTLS.'),
                        TextInput::make('mail_username')
                            ->label('Username')
                            ->maxLength(255),
                        TextInput::make('mail_password')
                            ->label('Password')
                            ->password()
                            ->revealable()
                            ->helperText('Leave blank to keep the current password.')
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->maxLength(255),
                        Select::make('mail_encryption')
                            ->label('Encryption')
                            ->options([
                                'tls' => 'TLS / STARTTLS (587)',
                                'ssl' => 'SSL (465)',
                            ])
                            ->placeholder('None')
                            ->native(false),
                    ]),
            ]);
    }
}
