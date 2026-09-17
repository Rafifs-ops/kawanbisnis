<?php

namespace App\Filament\Resources\AgentKnowledge\Pages;

use App\Filament\Resources\AgentKnowledge\AgentKnowledgeResource;
use App\Models\AgentKnowledge;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditAgentKnowledge extends EditRecord
{
    protected static string $resource = AgentKnowledgeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('generateEmbedding')
                ->label('Generate Embedding')
                ->icon('heroicon-o-sparkles')
                ->action(function (): void {
                    /** @var AgentKnowledge $record */
                    $record = $this->getRecord();
                    $record->generateEmbedding();
                })
                ->after(function (): void {
                    Notification::make()->title('Embedding berhasil di-generate.')->success()->send();
                }),

            DeleteAction::make(),
        ];
    }
}
