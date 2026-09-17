<?php

namespace App\Filament\Resources\AgentKnowledge\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AgentKnowledgeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('agent_type')
                    ->label('Tipe Agen')
                    ->options([
                        'analytics' => 'Analytics',
                        'customer' => 'Customer',
                        'marketing' => 'Marketing',
                        'strategy' => 'Strategy',
                    ])
                    ->required()
                    ->native(false),

                TextInput::make('title')
                    ->label('Judul')
                    ->required()
                    ->maxLength(255),

                Textarea::make('content')
                    ->label('Konten')
                    ->required()
                    ->rows(8),
            ]);
    }
}
