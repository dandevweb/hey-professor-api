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

test('after creating a new question, I need to make sure that it creates on draft_status', function () {
    $user = \App\Models\User::factory()->create();

    Sanctum::actingAs($user);

    postJson(route('questions.store'), [
        'question' => 'What is the capital of France?',
    ])->assertSuccessful();

    assertDatabaseHas('questions', [
        'user_id'  => $user->id,
        'status'  => 'draft',
        'question' => 'What is the capital of France?',
    ]);
});

describe('validation rules', function () {
    test('question::required', function () {
        $user = \App\Models\User::factory()->create();

        Sanctum::actingAs($user);

        postJson(route('questions.store'), [])->assertJsonValidationErrors([
            'question' => 'required'
        ]);
    });

});
