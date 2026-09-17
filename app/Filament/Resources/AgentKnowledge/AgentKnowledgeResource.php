<?php

namespace App\Filament\Resources\AgentKnowledge;

use App\Filament\Resources\AgentKnowledge\Pages\CreateAgentKnowledge;
use App\Filament\Resources\AgentKnowledge\Pages\EditAgentKnowledge;
use App\Filament\Resources\AgentKnowledge\Pages\ListAgentKnowledge;
use App\Filament\Resources\AgentKnowledge\Schemas\AgentKnowledgeForm;
use App\Filament\Resources\AgentKnowledge\Tables\AgentKnowledgeTable;
use App\Models\AgentKnowledge;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AgentKnowledgeResource extends Resource
{
    protected static ?string $model = AgentKnowledge::class;

    protected static ?string $navigationLabel = 'Pengetahuan Agen';

    protected static ?string $modelLabel = 'Pengetahuan Agen';

    protected static ?string $pluralModelLabel = 'Pengetahuan Agen';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return AgentKnowledgeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AgentKnowledgeTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAgentKnowledge::route('/'),
            'create' => CreateAgentKnowledge::route('/create'),
            'edit' => EditAgentKnowledge::route('/{record}/edit'),
        ];
    }
}
