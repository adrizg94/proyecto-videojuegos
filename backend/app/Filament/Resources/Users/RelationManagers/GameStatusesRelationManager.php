<?php

namespace App\Filament\Resources\Users\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GameStatusesRelationManager extends RelationManager
{
    protected static string $relationship = 'gameStatuses';

    protected static ?string $title = 'Game Statuses';

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('game.rawg_id')
                    ->label('RAWG ID')
                    ->numeric()
                    ->sortable(),

                ImageColumn::make('game.image')
                    ->label('Image')
                    ->imageWidth(80)
                    ->imageHeight(45),

                TextColumn::make('game.name')
                    ->label('Game')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([])
            ->recordActions([])
            ->toolbarActions([]);
    }
}
