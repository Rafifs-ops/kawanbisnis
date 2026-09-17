<?php

namespace App\AI\Responses;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\ArrayType;
use Illuminate\JsonSchema\Types\StringType;

class ActionPlanResult
{
    /**
     * @param  array<int, array{title: string, description: string, priority_rank: int, priority_score: float, timeline_days: int, target_kpi: string}>  $actions
     */
    public function __construct(
        public string $overallStrategy,
        public array $actions,
    ) {}

    /**
     * @return array{overall_strategy: StringType, actions: ArrayType}
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'overall_strategy' => $schema->string()->required(),
            'actions' => $schema->array()->items(
                $schema->object(fn (JsonSchema $schema) => [
                    'title' => $schema->string()->required(),
                    'description' => $schema->string()->required(),
                    'priority_rank' => $schema->integer()->required(),
                    'priority_score' => $schema->number()->required(),
                    'timeline_days' => $schema->integer()->required(),
                    'target_kpi' => $schema->string()->required(),
                ])->required()
            )->required(),
        ];
    }
}
