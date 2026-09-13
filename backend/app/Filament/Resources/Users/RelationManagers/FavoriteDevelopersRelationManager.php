<?php

namespace App\Filament\Resources\Users\RelationManagers;

use App\Filament\Resources\Developers\DeveloperResource;
use App\Models\Developer;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class FavoriteDevelopersRelationManager extends RelationManager
{
    protected static string $relationship = 'developers';

    protected static ?string $relatedResource = DeveloperResource::class;

    protected static ?string $title = 'Favorite Developers';

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
                    ->state(fn (Developer $record) => $record->pivot?->created_at)
                    ->dateTime('d/m/Y H:i')
                    ->sortable(
                        query: fn (Builder $query, string $direction): Builder => $query->orderBy('user_developers.created_at', $direction)
                    ),
            ])
            ->defaultSort('user_developers.created_at', 'desc')
            ->headerActions([])
            ->recordActions([])
            ->toolbarActions([]);
    }
}
