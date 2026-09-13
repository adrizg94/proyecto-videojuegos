<?php

namespace App\Filament\Resources\Developers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DeveloperForm
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

                TextInput::make('image')
                    ->url()
                    ->required(),
            ]);
    }
}
