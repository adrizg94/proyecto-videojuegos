<?php

namespace App\Filament\Resources\Developers;

use App\Filament\Resources\Developers\Pages\EditDeveloper;
use App\Filament\Resources\Developers\Pages\ListDevelopers;
use App\Filament\Resources\Developers\Schemas\DeveloperForm;
use App\Filament\Resources\Developers\Tables\DevelopersTable;
use App\Models\Developer;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class DeveloperResource extends Resource
{
    protected static string|UnitEnum|null $navigationGroup = 'Catalog';

    protected static ?int $navigationSort = 30;

    protected static ?string $model = Developer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCodeBracket;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return DeveloperForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DevelopersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        // Desde el panel de administración no se pueden crear ni borrar developers.
        // Los developers son proporcionados por RAWG y se almacenan localmente
        // cuando son necesarios para las funcionalidades propias de NextPlay
        return [
            'index' => ListDevelopers::route('/'),
            'edit' => EditDeveloper::route('/{record}/edit'),
        ];
    }
}
