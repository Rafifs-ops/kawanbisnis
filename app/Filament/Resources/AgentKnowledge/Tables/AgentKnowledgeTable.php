<?php

namespace App\Filament\Resources\AgentKnowledge\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AgentKnowledgeTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('agent_type')
                    ->label('Tipe Agen')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'analytics' => 'info',
                        'customer' => 'success',
                        'marketing' => 'warning',
                        'strategy' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('content')
                    ->label('Konten')
                    ->limit(80)
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
