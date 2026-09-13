<?php

namespace App\Filament\Resources\Users\RelationManagers;

use App\Filament\Resources\Creators\CreatorResource;
use App\Models\Creator;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class FavoriteCreatorsRelationManager extends RelationManager
{
    protected static string $relationship = 'creators';

    protected static ?string $relatedResource = CreatorResource::class;

    protected static ?string $title = 'Favorite Creators';

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
                    ->circular()
                    ->imageSize(50),

                TextColumn::make('favorited_at')
                    ->label('Favorited At')
                    ->state(fn (Creator $record) => $record->pivot?->created_at)
                    ->dateTime('d/m/Y H:i')
                    ->sortable(
                        query: fn (Builder $query, string $direction): Builder => $query->orderBy('user_creators.created_at', $direction)
                    ),
            ])
            ->defaultSort('user_creators.created_at', 'desc')
            ->headerActions([])
            ->recordActions([])
            ->toolbarActions([]);
    }
}
