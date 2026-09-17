<?php

namespace App\Filament\Resources\AgentKnowledge\Pages;

use App\Filament\Resources\AgentKnowledge\AgentKnowledgeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAgentKnowledge extends ListRecords
{
    protected static string $resource = AgentKnowledgeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
