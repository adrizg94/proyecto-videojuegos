<?php

namespace App\Filament\Resources\Creators\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CreatorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('rawg_id')
                    ->label('RAWG ID')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                ImageColumn::make('image')
                    ->circular()
                    ->imageSize(50),

                TextColumn::make('users_count')
                    ->label('Favorites')
                    ->counts('users')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('user')
                    ->label('Favorited By User')
                    ->relationship('users', 'username')
                    ->searchable()->preload(),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
