<?php

use App\Models\AgentKnowledge;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Ai\Embeddings;
use Laravel\Ai\Responses\Data\Meta;
use Laravel\Ai\Responses\EmbeddingsResponse;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('cosine similarity returns 1.0 for identical vectors', function () {
    $knowledge = new AgentKnowledge;
    $result = $knowledge->cosineSimilarity([1.0, 0.0, 0.0], [1.0, 0.0, 0.0]);

    expect($result)->toBe(1.0);
});

test('cosine similarity returns 0.0 for orthogonal vectors', function () {
    $knowledge = new AgentKnowledge;
    $result = $knowledge->cosineSimilarity([1.0, 0.0], [0.0, 1.0]);

    expect($result)->toBe(0.0);
});

test('cosine similarity returns 0.0 for empty vectors', function () {
    $knowledge = new AgentKnowledge;

    expect($knowledge->cosineSimilarity([], [1.0, 0.0]))->toBe(0.0)
        ->and($knowledge->cosineSimilarity([1.0, 0.0], []))->toBe(0.0)
        ->and($knowledge->cosineSimilarity([], []))->toBe(0.0);
});

test('cosine similarity returns 0.0 for mismatched dimensions', function () {
    $knowledge = new AgentKnowledge;
    $result = $knowledge->cosineSimilarity([1.0, 0.0], [1.0, 0.0, 0.0]);

    expect($result)->toBe(0.0);
});

test('cosine similarity computes correct value', function () {
    $knowledge = new AgentKnowledge;
    $result = $knowledge->cosineSimilarity([1.0, 1.0], [1.0, 0.0]);

    expect(abs($result - 0.7071) < 0.001)->toBeTrue();
});

test('findSimilar returns empty collection when no knowledge exists', function () {
    Embeddings::fake();

    $result = AgentKnowledge::findSimilar('analytics', 'test query');

    expect($result)->toHaveCount(0);
});

test('findSimilar returns matching entries above threshold', function () {
    Embeddings::fake([
        new EmbeddingsResponse([[0.9, 0.1, 0.0]], 10, new Meta('openrouter', 'test')),
    ]);

    AgentKnowledge::create([
        'agent_type' => 'analytics',
        'title' => 'Test Knowledge',
        'content' => 'Test content',
        'embedding' => [0.9, 0.1, 0.0],
    ]);

    $result = AgentKnowledge::findSimilar('analytics', 'test query');

    expect($result)->toHaveCount(1)
        ->and($result->first()['title'])->toBe('Test Knowledge');
});

test('findSimilar filters out entries below threshold', function () {
    Embeddings::fake([
        new EmbeddingsResponse([[0.0, 1.0, 0.0]], 10, new Meta('openrouter', 'test')),
    ]);

    AgentKnowledge::create([
        'agent_type' => 'analytics',
        'title' => 'Unrelated Knowledge',
        'content' => 'Unrelated content',
        'embedding' => [1.0, 0.0, 0.0],
    ]);

    $result = AgentKnowledge::findSimilar('analytics', 'test query');

    expect($result)->toHaveCount(0);
});

test('findSimilar only returns entries for the specified agent type', function () {
    Embeddings::fake([
        new EmbeddingsResponse([[0.9, 0.1, 0.0]], 10, new Meta('openrouter', 'test')),
    ]);

    AgentKnowledge::create([
        'agent_type' => 'analytics',
        'title' => 'Analytics Knowledge',
        'content' => 'Analytics content',
        'embedding' => [0.9, 0.1, 0.0],
    ]);

    AgentKnowledge::create([
        'agent_type' => 'customer',
        'title' => 'Customer Knowledge',
        'content' => 'Customer content',
        'embedding' => [0.9, 0.1, 0.0],
    ]);

    $result = AgentKnowledge::findSimilar('analytics', 'test query');

    expect($result)->toHaveCount(1)
        ->and($result->first()['title'])->toBe('Analytics Knowledge');
});

test('findSimilar respects limit parameter', function () {
    Embeddings::fake([
        new EmbeddingsResponse([[0.9, 0.1, 0.0]], 10, new Meta('openrouter', 'test')),
    ]);

    for ($i = 0; $i < 5; $i++) {
        AgentKnowledge::create([
            'agent_type' => 'analytics',
            'title' => "Knowledge {$i}",
            'content' => "Content {$i}",
            'embedding' => [0.9, 0.1, 0.0],
        ]);
    }

    $result = AgentKnowledge::findSimilar('analytics', 'test query', limit: 2);

    expect($result)->toHaveCount(2);
});

test('generate embedding calls embeddings api', function () {
    Embeddings::fake([
        new EmbeddingsResponse([[0.1, 0.2, 0.3]], 10, new Meta('openrouter', 'test')),
    ]);

    $knowledge = AgentKnowledge::create([
        'agent_type' => 'analytics',
        'title' => 'Test',
        'content' => 'Test content',
        'embedding' => null,
    ]);

    $knowledge->generateEmbedding();

    expect($knowledge->fresh()->embedding)->toBe([0.1, 0.2, 0.3]);
});
