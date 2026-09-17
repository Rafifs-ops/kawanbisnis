<?php

use App\AI\Agents\CustomerAgent;
use App\Models\BusinessSnapshot;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\JsonSchema\JsonSchemaTypeFactory;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('build prompt formats snapshot data correctly', function () {
    $snapshot = BusinessSnapshot::factory()->create([
        'revenue' => 20000000,
        'total_orders' => 200,
        'average_order_value' => 100000,
        'new_vs_returning_customers' => ['new' => 120, 'returning' => 80],
        'product_performances' => [
            ['name' => 'Paket Hemat', 'revenue' => 10000000, 'orders' => 100],
        ],
    ]);

    $agent = new CustomerAgent($snapshot);
    $prompt = $agent->buildPrompt();

    expect($prompt)->toContain('Revenue: Rp20,000,000')
        ->toContain('Total Orders: 200')
        ->toContain('AOV: Rp100,000')
        ->toContain('Paket Hemat');
});

test('schema returns expected keys', function () {
    $snapshot = BusinessSnapshot::factory()->create();
    $agent = new CustomerAgent($snapshot);

    $schema = $agent->schema(new JsonSchemaTypeFactory);

    expect($schema)->toHaveKeys(['summary', 'segments', 'insights', 'hypotheses', 'confidence_score']);
});

test('instructions returns non-empty string', function () {
    $snapshot = BusinessSnapshot::factory()->create();
    $agent = new CustomerAgent($snapshot);

    $instructions = $agent->instructions();

    expect($instructions)->toBeString()->not->toBeEmpty()
        ->toContain('Customer Agent')
        ->toContain('UMKM');
});

test('tools returns similarity search tool', function () {
    $snapshot = BusinessSnapshot::factory()->create();
    $agent = new CustomerAgent($snapshot);

    $tools = iterator_to_array($agent->tools());

    expect($tools)->toHaveCount(1);
});
