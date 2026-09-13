<?php

namespace App\Filament\Resources\GameLists\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class GameListForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'username')
                    ->searchable()
                    ->required(),

                TextInput::make('title')
                    ->required(),

                Select::make('games')
                    ->relationship('games', 'name')
                    ->multiple()
                    ->searchable()->preload(),
            ]);
    }
}
