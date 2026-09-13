<?php

namespace App\Filament\Resources\Developers\Pages;

use App\Filament\Resources\Developers\DeveloperResource;
use Filament\Resources\Pages\EditRecord;

class EditDeveloper extends EditRecord
{
    protected static string $resource = DeveloperResource::class;
}
