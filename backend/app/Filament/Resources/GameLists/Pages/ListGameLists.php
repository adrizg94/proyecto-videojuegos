<?php

namespace App\Filament\Resources\GameLists\Pages;

use App\Filament\Resources\GameLists\GameListResource;
use Filament\Resources\Pages\ListRecords;

class ListGameLists extends ListRecords
{
    protected static string $resource = GameListResource::class;
}
