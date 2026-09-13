<?php

namespace App\Filament\Resources\Users\RelationManagers;

use App\Filament\Resources\Publishers\PublisherResource;
use App\Models\Publisher;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class FavoritePublishersRelationManager extends RelationManager
{
    protected static string $relationship = 'publishers';

    protected static ?string $relatedResource = PublisherResource::class;

    protected static ?string $title = 'Favorite Publishers';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('rawg_id')
                    ->label('RAWG ID')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                ImageColumn::make('image')
                    ->label('Image')
                    ->imageWidth(80)
                    ->imageHeight(45),

                TextColumn::make('favorited_at')
                    ->label('Favorited At')
                    ->state(fn (Publisher $record) => $record->pivot?->created_at)
                    ->dateTime('d/m/Y H:i')
                    ->sortable(
                        query: fn (Builder $query, string $direction): Builder => $query->orderBy('user_publishers.created_at', $direction)
                    ),
            ])
            ->defaultSort('user_publishers.created_at', 'desc')
            ->headerActions([])
            ->recordActions([])
            ->toolbarActions([]);
    }
}
