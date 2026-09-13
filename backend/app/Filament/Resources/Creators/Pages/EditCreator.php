<?php

namespace App\Filament\Resources\Creators\Pages;

use App\Filament\Resources\Creators\CreatorResource;
use Filament\Resources\Pages\EditRecord;

class EditCreator extends EditRecord
{
    protected static string $resource = CreatorResource::class;
}
