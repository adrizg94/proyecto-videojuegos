<?php

namespace App\Filament\Resources\Creators\Pages;

use App\Filament\Resources\Creators\CreatorResource;
use Filament\Resources\Pages\ListRecords;

class ListCreators extends ListRecords
{
    protected static string $resource = CreatorResource::class;
}
