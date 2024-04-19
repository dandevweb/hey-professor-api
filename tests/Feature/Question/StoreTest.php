<?php

use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{postJson, assertDatabaseHas};

it('should be able to store a new question', function () {
    $user = \App\Models\User::factory()->create();

    Sanctum::actingAs($user);

    postJson(route('questions.store'), [
        'question' => 'What is the capital of France?',
    ])->assertSuccessful();

    assertDatabaseHas('questions', [
        'user_id'  => $user->id,
        'question' => 'What is the capital of France?',
    ]);
});
