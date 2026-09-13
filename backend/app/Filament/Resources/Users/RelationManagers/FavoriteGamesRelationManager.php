<?php

namespace App\Filament\Resources\Users\RelationManagers;

use App\Filament\Resources\Games\GameResource;
use App\Models\Game;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class FavoriteGamesRelationManager extends RelationManager
{
    protected static string $relationship = 'favoriteGames';

    protected static ?string $relatedResource = GameResource::class;

    protected static ?string $title = 'Favorite games';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('rawg_id')
                    ->label('RAWG ID')
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('release_date')
                    ->label('Release Date')
                    ->date()
                    ->sortable(),

                ImageColumn::make('image')
                    ->label('Image')
                    ->imageWidth(80)
                    ->imageHeight(45),

                TextColumn::make('favorited_at')
                    ->label('Favorited at')
                    ->state(fn (Game $record) => $record->pivot?->created_at)
                    ->dateTime('d/m/Y H:i')
                    ->sortable(
                        query: fn (Builder $query, string $direction): Builder => $query->orderBy('favorites.created_at', $direction)
                    ),
            ])
            ->defaultSort('favorites.created_at', 'desc')
            ->headerActions([])
            ->recordActions([])
            ->toolbarActions([]);
    }
}
