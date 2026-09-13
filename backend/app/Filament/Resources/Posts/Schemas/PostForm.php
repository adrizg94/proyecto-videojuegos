<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'username')
                    ->searchable()
                    ->required(),

                Select::make('thread_id')
                    ->relationship('thread', 'title')
                    ->searchable()
                    ->required(),

                Textarea::make('body')
                    ->required()
                    ->rows(6),
            ]);
    }
}
