<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('username')
                    ->searchable()->sortable(),

                TextColumn::make('email')
                    ->searchable()->sortable(),

                IconColumn::make('is_admin')
                    ->boolean()->sortable(),

                TextColumn::make('game_statuses_count')
                    ->label('Game Statuses')
                    ->counts('gameStatuses')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('game_lists_count')
                    ->label('Game Lists')
                    ->counts('gameLists')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('favorite_games_count')
                    ->label('Favorite Games')
                    ->counts('favoriteGames')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('publishers_count')
                    ->label('Favorite Publishers')
                    ->counts('publishers')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('developers_count')
                    ->label('Favorite Developers')
                    ->counts('developers')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('creators_count')
                    ->label('Favorite Creators')
                    ->counts('creators')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('release_alert_games_count')
                    ->label('Release Alerts')
                    ->counts('releaseAlertGames')
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
                TernaryFilter::make('is_admin')
                    ->label('Is Admin'),

                SelectFilter::make('favorite_game')
                    ->label('Favorite Game')
                    ->relationship('favoriteGames', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('publisher')
                    ->label('Favorite Publisher')
                    ->relationship('publishers', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('developer')
                    ->label('Favorite Developer')
                    ->relationship('developers', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('creator')
                    ->label('Favorite Creator')
                    ->relationship('creators', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->authorizeIndividualRecords('delete'),
                ]),
            ]);
    }
}
