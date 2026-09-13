<?php

namespace App\Filament\Resources\Users\RelationManagers;

use App\Filament\Resources\Games\GameResource;
use App\Models\Game;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ReleaseAlertGamesRelationManager extends RelationManager
{
    protected static string $relationship = 'releaseAlertGames';

    protected static ?string $relatedResource = GameResource::class;

    protected static ?string $title = 'Release Alerts';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('rawg_id')
                    ->label('RAWG ID')
                    ->numeric()
                    ->sortable(),

                ImageColumn::make('image')
                    ->label('Image')
                    ->imageWidth(80)
                    ->imageHeight(45),

                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('release_date')
                    ->label('Release Date')
                    ->date()
                    ->sortable(),

                TextColumn::make('alert_created_at')
                    ->label('Alert Created At')
                    ->state(fn (Game $record) => $record->pivot?->created_at)
                    ->dateTime('d/m/Y H:i')
                    ->sortable(
                        query: fn (Builder $query, string $direction): Builder => $query->orderBy('release_alerts.created_at', $direction)
                    ),
            ])
            ->defaultSort('release_alerts.created_at', 'desc')
            ->headerActions([])
            ->recordActions([])
            ->toolbarActions([]);
    }
}
