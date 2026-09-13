<?php

namespace App\Filament\Resources\Reviews\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ReviewsTable
{
    public static function configure(Table $table): Table
    {
        return $table->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('user.username')
                    ->label('User')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('game.name')
                    ->label('Game')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('rating')
                    ->formatStateUsing(
                        fn ($state): string => str_repeat('★', (int) $state).
                            str_repeat('☆', 5 - (int) $state)
                    )
                    ->sortable(),
                TextColumn::make('review')
                    ->limit(50)
                    ->searchable(),
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
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
