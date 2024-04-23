<?php

use Laravel\Sanctum\Sanctum;

use App\Models\{Question, User};

use function Pest\Laravel\{putJson, assertDatabaseHas};

it('should be able to publish a question', function () {
    $user     = User::factory()->create();
    $question = Question::factory()
        ->for($user)
        ->create([
            'status' => 'draft',
        ]);

    Sanctum::actingAs($user);

    putJson(route('questions.publish', $question))
        ->assertNoContent();

    $question->refresh();

    assertDatabaseHas('questions', [
        'id'     => $question->id,
        'status' => 'published',
    ]);


    expect($question)->status->toBe('published');
});

it('should make sure that only person who created the question can publish it', function () {
    $rightUser = User::factory()->create();
    $wrongUser = User::factory()->create();

    $question = Question::factory()->create([
        'user_id' => $rightUser->id,
        'status'  => 'draft',
    ]);

    Sanctum::actingAs($wrongUser);

    putJson(route('questions.publish', $question->id))
        ->assertForbidden();

    Sanctum::actingAs($rightUser);

    putJson(route('questions.publish', $question->id))
        ->assertNoContent();
});
