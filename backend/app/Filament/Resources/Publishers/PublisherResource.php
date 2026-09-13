<?php

namespace App\Filament\Resources\Publishers;

use App\Filament\Resources\Publishers\Pages\EditPublisher;
use App\Filament\Resources\Publishers\Pages\ListPublishers;
use App\Filament\Resources\Publishers\Schemas\PublisherForm;
use App\Filament\Resources\Publishers\Tables\PublishersTable;
use App\Models\Publisher;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PublisherResource extends Resource
{
    protected static string|UnitEnum|null $navigationGroup = 'Catalog';

    protected static ?int $navigationSort = 20;

    protected static ?string $model = Publisher::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return PublisherForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PublishersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        // Desde el panel de administración no se pueden crear ni borrar publishers.
        // Los publishers son proporcionados por RAWG y se almacenan localmente
        // cuando son necesarios para las funcionalidades propias de NextPlay
        return [
            'index' => ListPublishers::route('/'),
            'edit' => EditPublisher::route('/{record}/edit'),
        ];
    }
}
