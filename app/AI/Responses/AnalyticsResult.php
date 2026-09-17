<?php

namespace App\AI\Responses;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\ArrayType;
use Illuminate\JsonSchema\Types\NumberType;
use Illuminate\JsonSchema\Types\StringType;

class AnalyticsResult
{
    /**
     * @param  array<int, string>  $anomalies
     * @param  array<int, string>  $keyFindings
     */
    public function __construct(
        public string $summary,
        public array $anomalies,
        public array $keyFindings,
        public float $confidenceScore,
    ) {}

    /**
     * @return array{summary: StringType, anomalies: ArrayType, key_findings: ArrayType, confidence_score: NumberType}
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'summary' => $schema->string()->required(),
            'anomalies' => $schema->array()->items($schema->string())->required(),
            'key_findings' => $schema->array()->items($schema->string())->required(),
            'confidence_score' => $schema->number()->required(),
        ];
    }
}
