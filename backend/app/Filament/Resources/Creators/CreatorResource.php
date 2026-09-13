<?php

namespace App\Filament\Resources\Creators;

use App\Filament\Resources\Creators\Pages\EditCreator;
use App\Filament\Resources\Creators\Pages\ListCreators;
use App\Filament\Resources\Creators\Schemas\CreatorForm;
use App\Filament\Resources\Creators\Tables\CreatorsTable;
use App\Models\Creator;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CreatorResource extends Resource
{
    protected static string|UnitEnum|null $navigationGroup = 'Catalog';

    protected static ?int $navigationSort = 40;

    protected static ?string $model = Creator::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserCircle;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return CreatorForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CreatorsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        // Desde el panel de administración no se pueden crear ni borrar creators.
        // Los creators son proporcionados por RAWG y se almacenan localmente
        // cuando son necesarios para las funcionalidades propias de NextPlay
        return [
            'index' => ListCreators::route('/'),
            'edit' => EditCreator::route('/{record}/edit'),
        ];
    }
}
