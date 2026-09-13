<?php

namespace App\Filament\Resources\Reviews\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ReviewForm
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
                    ->required(),
                Select::make('rating')
                    ->options([
                        1 => '★☆☆☆☆',
                        2 => '★★☆☆☆',
                        3 => '★★★☆☆',
                        4 => '★★★★☆',
                        5 => '★★★★★',
                    ])
                    ->selectablePlaceholder(false)
                    ->required(),
                Textarea::make('review')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
