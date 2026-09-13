<?php

namespace App\Filament\Resources\Games\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class GamesTable
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

                TextColumn::make('release_date')
                    ->date()
                    ->sortable(),

                ImageColumn::make('image')
                    ->imageWidth(80)
                    ->imageHeight(45),

                TextColumn::make('favorited_by_users_count')
                    ->label('Favorites')
                    ->counts('favoritedByUsers')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('reviews_count')
                    ->counts('reviews')
                    ->label('Reviews')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('threads_count')
                    ->counts('threads')
                    ->label('Threads')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('game_lists_count')
                    ->counts('gameLists')
                    ->label('Lists')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

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
                SelectFilter::make('favorited_by_user')
                    ->label('Favorited By User')
                    ->relationship('favoritedByUsers', 'username')
                    ->searchable()->preload(),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
