<?php

namespace App\Filament\Resources\Games\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class GameForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('rawg_id')
                    ->label('RAWG ID')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(false),

                TextInput::make('name')
                    ->required(),

                DatePicker::make('release_date'),

                TextInput::make('image')
                    ->url(),
            ]);
    }
}
