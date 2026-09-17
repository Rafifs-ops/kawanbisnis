<?php

namespace App\AI\Responses;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\ArrayType;
use Illuminate\JsonSchema\Types\NumberType;
use Illuminate\JsonSchema\Types\StringType;

class MarketingResult
{
    /**
     * @param  array<int, string>  $channelPerformance
     * @param  array<int, string>  $opportunities
     * @param  array<int, string>  $hypotheses
     */
    public function __construct(
        public string $summary,
        public array $channelPerformance,
        public array $opportunities,
        public array $hypotheses,
        public float $confidenceScore,
    ) {}

    /**
     * @return array{summary: StringType, channel_performance: ArrayType, opportunities: ArrayType, hypotheses: ArrayType, confidence_score: NumberType}
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'summary' => $schema->string()->required(),
            'channel_performance' => $schema->array()->items($schema->string())->required(),
            'opportunities' => $schema->array()->items($schema->string())->required(),
            'hypotheses' => $schema->array()->items($schema->string())->required(),
            'confidence_score' => $schema->number()->required(),
        ];
    }
}
