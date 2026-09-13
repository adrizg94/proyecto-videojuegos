<?php

namespace App\Filament\Resources\Threads;

use App\Filament\Resources\Threads\Pages\EditThread;
use App\Filament\Resources\Threads\Pages\ListThreads;
use App\Filament\Resources\Threads\Schemas\ThreadForm;
use App\Filament\Resources\Threads\Tables\ThreadsTable;
use App\Models\Thread;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ThreadResource extends Resource
{
    protected static string|UnitEnum|null $navigationGroup = 'Community';

    protected static ?int $navigationSort = 20;

    protected static ?string $model = Thread::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return ThreadForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ThreadsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        // Desde el panel de administración no se pueden crear threads
        return [
            'index' => ListThreads::route('/'),
            'edit' => EditThread::route('/{record}/edit'),
        ];
    }
}
