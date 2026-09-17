<?php

namespace App\AI\Responses;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\ArrayType;
use Illuminate\JsonSchema\Types\NumberType;
use Illuminate\JsonSchema\Types\StringType;

class CustomerResult
{
    /**
     * @param  array<int, string>  $segments
     * @param  array<int, string>  $insights
     * @param  array<int, string>  $hypotheses
     */
    public function __construct(
        public string $summary,
        public array $segments,
        public array $insights,
        public array $hypotheses,
        public float $confidenceScore,
    ) {}

    /**
     * @return array{summary: StringType, segments: ArrayType, insights: ArrayType, hypotheses: ArrayType, confidence_score: NumberType}
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'summary' => $schema->string()->required(),
            'segments' => $schema->array()->items($schema->string())->required(),
            'insights' => $schema->array()->items($schema->string())->required(),
            'hypotheses' => $schema->array()->items($schema->string())->required(),
            'confidence_score' => $schema->number()->required(),
        ];
    }
}
