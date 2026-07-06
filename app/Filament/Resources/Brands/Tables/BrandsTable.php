<?php

declare(strict_types=1);

namespace App\Filament\Resources\Brands\Tables;

use App\Models\Brand;
use App\Support\Brands;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Throwable;

class BrandsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('domain')
                    ->label('Domain')
                    ->searchable()
                    ->placeholder('—'),
                TextColumn::make('from_address')
                    ->label('From')
                    ->placeholder('—')
                    ->toggleable(),
                TextColumn::make('mail_host')
                    ->label('SMTP host')
                    ->placeholder('global')
                    ->toggleable(),
                TextColumn::make('users_count')
                    ->label('Affiliates')
                    ->counts('users')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
            ])
            ->recordActions([
                Action::make('sendTest')
                    ->label('Send test')
                    ->icon(Heroicon::OutlinedPaperAirplane)
                    ->schema([
                        TextInput::make('email')
                            ->label('Send test email to')
                            ->email()
                            ->required()
                            ->default(fn (): ?string => Auth::user()?->email),
                    ])
                    ->action(function (Brand $record, array $data): void {
                        try {
                            $mailer = $record->hasSmtp()
                                ? Mail::mailer(app(Brands::class)->configureMailer($record))
                                : Mail::mailer();

                            $mailer->raw(
                                'This is a test email from '.$record->name.' ('.($record->domain ?: 'no domain').'). If you received it, this site\'s mail settings work.',
                                function ($message) use ($data, $record): void {
                                    $message->to($data['email'])
                                        ->subject($record->name.' — SMTP test')
                                        ->from($record->fromAddress(), $record->fromName());
                                },
                            );

                            Notification::make()
                                ->title('Test email sent')
                                ->body('Sent to '.$data['email'].'.')
                                ->success()
                                ->send();
                        } catch (Throwable $e) {
                            Notification::make()
                                ->title('Could not send test email')
                                ->body($e->getMessage())
                                ->danger()
                                ->persistent()
                                ->send();
                        }
                    }),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
