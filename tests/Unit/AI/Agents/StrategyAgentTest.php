<?php

use App\AI\Agents\StrategyAgent;
use Illuminate\JsonSchema\JsonSchemaTypeFactory;

test('build prompt formats multi-agent findings and constraints correctly', function () {
    $findings = [
        'analytics' => ['Revenue turun 10%', 'AOV naik 5%'],
        'customer' => ['Churn rate tinggi', 'Loyal customers berkurang'],
        'marketing' => ['Instagram ROAS turun', 'Shopee conversion bagus'],
    ];
    $constraints = ['marketing_budget' => 2000000, 'team_capacity' => 1];

    $agent = new StrategyAgent($findings, $constraints);
    $prompt = $agent->buildPrompt();

    expect($prompt)->toContain('Revenue turun 10%')
        ->toContain('Churn rate tinggi')
        ->toContain('Instagram ROAS turun')
        ->toContain('marketing_budget')
        ->toContain('team_capacity');
});

test('schema returns expected keys with all 6 sections', function () {
    $agent = new StrategyAgent([], []);

    $schema = $agent->schema(new JsonSchemaTypeFactory);

    expect($schema)->toHaveKeys([
        'business_diagnosis',
        'root_causes',
        'growth_opportunity',
        'recommendations',
        'action_plan',
        'kpi_metrics',
    ]);
});

test('instructions returns non-empty string with Indonesian context', function () {
    $agent = new StrategyAgent([], []);

    $instructions = $agent->instructions();

    expect($instructions)->toBeString()->not->toBeEmpty()
        ->toContain('Strategy Agent')
        ->toContain('UMKM')
        ->toContain('business_diagnosis')
        ->toContain('root_causes')
        ->toContain('growth_opportunity')
        ->toContain('recommendations')
        ->toContain('action_plan')
        ->toContain('kpi_metrics');
});

test('tools returns similarity search tool', function () {
    $agent = new StrategyAgent([], []);

    $tools = iterator_to_array($agent->tools());

    expect($tools)->toHaveCount(1);
});

test('handles empty constraints gracefully', function () {
    $agent = new StrategyAgent(['analytics' => ['finding']], []);
    $prompt = $agent->buildPrompt();

    expect($prompt)->toContain('Business Constraints: []');
});

test('handles null constraints via null coalesce in job', function () {
    $constraints = null ?? [];
    $agent = new StrategyAgent(['analytics' => ['finding']], $constraints);
    $prompt = $agent->buildPrompt();

    expect($prompt)->toContain('Business Constraints: []');
});
