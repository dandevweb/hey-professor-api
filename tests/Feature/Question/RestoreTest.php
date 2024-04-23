<?php

use Laravel\Sanctum\Sanctum;

use App\Models\{Question, User};

use function Pest\Laravel\{putJson, assertSoftDeleted, assertNotSoftDeleted};

it('should be able to restore a question', function () {
    $user     = User::factory()->create();
    $question = Question::factory()
        ->for($user)
        ->create();
    $question->delete();

    assertSoftDeleted('questions', [
        'id' => $question->id,
    ]);

    Sanctum::actingAs($user);

    putJson(route('questions.restore', $question))
        ->assertNoContent();

    assertNotSoftDeleted('questions', [
        'id' => $question->id,
    ]);

    expect($question)->refresh()->deleted_at->toBeNull();
});

it('should make sure that only person who created the question can restore it', function () {
    $rightUser = User::factory()->create();
    $wrongUser = User::factory()->create();

    $question = Question::factory()->create([
        'user_id' => $rightUser->id,
    ]);

    $question->delete();

    Sanctum::actingAs($wrongUser);

    putJson(route('questions.restore', $question->id))
        ->assertForbidden();

    Sanctum::actingAs($rightUser);

    putJson(route('questions.restore', $question->id))
        ->assertNoContent();
});
