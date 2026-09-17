<?php

namespace App\AI\Responses;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\ArrayType;
use Illuminate\JsonSchema\Types\StringType;

class DiagnosisResult
{
    /**
     * @param  array<int, string>  $keyFindings
     * @param  array<int, string>  $rootCauses
     * @param  array<int, string>  $opportunities
     */
    public function __construct(
        public string $summaryDiagnosis,
        public array $keyFindings,
        public array $rootCauses,
        public array $opportunities,
    ) {}

    /**
     * @return array{summary_diagnosis: StringType, key_findings: ArrayType, root_causes: ArrayType, opportunities: ArrayType}
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'summary_diagnosis' => $schema->string()->required(),
            'key_findings' => $schema->array()->items($schema->string())->required(),
            'root_causes' => $schema->array()->items($schema->string())->required(),
            'opportunities' => $schema->array()->items($schema->string())->required(),
        ];
    }
}
