<?php

namespace App\Filament\Resources\GameLists\Pages;

use App\Filament\Resources\GameLists\GameListResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGameList extends EditRecord
{
    protected static string $resource = GameListResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
