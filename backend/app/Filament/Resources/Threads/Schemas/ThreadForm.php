<?php

namespace App\Filament\Resources\Threads\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ThreadForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'username')
                    ->searchable()
                    ->required(),
                Select::make('game_id')
                    ->relationship('game', 'name')
                    ->searchable()
                    ->placeholder('General discussion'),
                TextInput::make('title')
                    ->required(),
            ]);
    }
}
