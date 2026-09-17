<?php

use App\AI\Agents\MarketingAgent;
use App\Models\BusinessPassport;
use App\Models\BusinessSnapshot;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\JsonSchema\JsonSchemaTypeFactory;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('build prompt formats passport and snapshot data correctly', function () {
    $passport = BusinessPassport::factory()->create([
        'sales_channels' => ['Instagram', 'Tokopedia', 'Shopee'],
        'constraints' => ['marketing_budget' => 3000000, 'team_capacity' => 2],
    ]);
    $snapshot = BusinessSnapshot::factory()->create([
        'business_passport_id' => $passport->id,
        'revenue' => 25000000,
    ]);

    $agent = new MarketingAgent($snapshot, $passport);
    $prompt = $agent->buildPrompt();

    expect($prompt)->toContain('Instagram')
        ->toContain('Tokopedia')
        ->toContain('Shopee')
        ->toContain('Revenue: Rp25,000,000')
        ->toContain('marketing_budget')
        ->toContain('team_capacity');
});

test('schema returns expected keys', function () {
    $passport = BusinessPassport::factory()->create();
    $snapshot = BusinessSnapshot::factory()->create(['business_passport_id' => $passport->id]);
    $agent = new MarketingAgent($snapshot, $passport);

    $schema = $agent->schema(new JsonSchemaTypeFactory);

    expect($schema)->toHaveKeys(['summary', 'channel_performance', 'opportunities', 'hypotheses', 'confidence_score']);
});

test('instructions returns non-empty string', function () {
    $passport = BusinessPassport::factory()->create();
    $snapshot = BusinessSnapshot::factory()->create(['business_passport_id' => $passport->id]);
    $agent = new MarketingAgent($snapshot, $passport);

    $instructions = $agent->instructions();

    expect($instructions)->toBeString()->not->toBeEmpty()
        ->toContain('Marketing Agent')
        ->toContain('UMKM');
});

test('tools returns similarity search tool', function () {
    $passport = BusinessPassport::factory()->create();
    $snapshot = BusinessSnapshot::factory()->create(['business_passport_id' => $passport->id]);
    $agent = new MarketingAgent($snapshot, $passport);

    $tools = iterator_to_array($agent->tools());

    expect($tools)->toHaveCount(1);
});
