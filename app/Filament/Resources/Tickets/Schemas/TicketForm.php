<?php

declare(strict_types=1);

namespace App\Filament\Resources\Tickets\Schemas;

use App\Models\Ticket;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TicketForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Ticket')
                    ->columns(2)
                    ->schema([
                        Select::make('user_id')
                            ->label('User')
                            ->relationship('user', 'name')
                            ->disabled(),
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                Ticket::STATUS_OPEN => 'Open',
                                Ticket::STATUS_ANSWERED => 'Answered',
                                Ticket::STATUS_CLOSED => 'Closed',
                            ])
                            ->required(),
                        Textarea::make('message')
                            ->label('Message from user')
                            ->disabled()
                            ->rows(4)
                            ->columnSpanFull(),
                        Textarea::make('reply')
                            ->label('Your reply')
                            ->placeholder('Write your reply to the user…')
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
