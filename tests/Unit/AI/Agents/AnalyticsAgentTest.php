<?php

use App\AI\Agents\AnalyticsAgent;
use App\Models\BusinessSnapshot;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\JsonSchema\JsonSchemaTypeFactory;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('build prompt formats snapshot data correctly', function () {
    $snapshot = BusinessSnapshot::factory()->create([
        'revenue' => 15000000,
        'total_orders' => 150,
        'average_order_value' => 100000,
        'new_vs_returning_customers' => ['new' => 100, 'returning' => 50],
        'product_performances' => [
            ['name' => 'Kopi Arabika', 'revenue' => 8000000, 'orders' => 80],
            ['name' => 'Kopi Robusta', 'revenue' => 7000000, 'orders' => 70],
        ],
    ]);

    $agent = new AnalyticsAgent($snapshot);
    $prompt = $agent->buildPrompt();

    expect($prompt)->toContain('Revenue: Rp15,000,000')
        ->toContain('Total Orders: 150')
        ->toContain('AOV: Rp100,000')
        ->toContain('Kopi Arabika')
        ->toContain('Kopi Robusta');
});

test('schema returns expected keys', function () {
    $snapshot = BusinessSnapshot::factory()->create();
    $agent = new AnalyticsAgent($snapshot);

    $schema = $agent->schema(new JsonSchemaTypeFactory);

    expect($schema)->toHaveKeys(['summary', 'anomalies', 'key_findings', 'confidence_score']);
});

test('instructions returns non-empty string', function () {
    $snapshot = BusinessSnapshot::factory()->create();
    $agent = new AnalyticsAgent($snapshot);

    $instructions = $agent->instructions();

    expect($instructions)->toBeString()->not->toBeEmpty()
        ->toContain('Analytics Agent')
        ->toContain('UMKM');
});

test('tools returns similarity search tool', function () {
    $snapshot = BusinessSnapshot::factory()->create();
    $agent = new AnalyticsAgent($snapshot);

    $tools = iterator_to_array($agent->tools());

    expect($tools)->toHaveCount(1);
});
