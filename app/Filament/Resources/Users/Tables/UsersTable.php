<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users\Tables;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('name')
                    ->label('Username')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                IconColumn::make('email_verified_at')
                    ->label('Verified')
                    ->boolean(),
                IconColumn::make('approved_at')
                    ->label('Approved')
                    ->boolean(),
                TextColumn::make('fixed_payment_amount')
                    ->label('Fixed payment')
                    ->formatStateUsing(fn (string $state, User $record): string => number_format((float) $state, 2).' '.$record->fixed_payment_currency)
                    ->sortable(),
                TextColumn::make('streams_count')
                    ->label('Streams')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('referrals_count')
                    ->label('Referrals')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_admin')
                    ->label('Admin')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('wallet_network')
                    ->label('Network')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Registered')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('approved')
                    ->label('Approval')
                    ->placeholder('All')
                    ->trueLabel('Approved')
                    ->falseLabel('Awaiting approval')
                    ->queries(
                        true: fn (Builder $query): Builder => $query->whereNotNull('approved_at'),
                        false: fn (Builder $query): Builder => $query->whereNull('approved_at'),
                        blank: fn (Builder $query): Builder => $query,
                    ),
            ])
            ->recordActions([
                Action::make('approve')
                    ->label('Approve')
                    ->icon(Heroicon::OutlinedCheckCircle)
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Approve account')
                    ->modalDescription('The account will be activated and the affiliate will get access to their dashboard.')
                    ->successNotificationTitle('Account approved')
                    ->visible(fn (User $record): bool => ! $record->isApproved())
                    ->action(fn (User $record) => $record->update(['approved_at' => now()])),
                Action::make('revoke')
                    ->label('Revoke')
                    ->icon(Heroicon::OutlinedXCircle)
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Revoke approval')
                    ->successNotificationTitle('Approval revoked')
                    ->visible(fn (User $record): bool => $record->isApproved())
                    ->action(fn (User $record) => $record->update(['approved_at' => null])),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
