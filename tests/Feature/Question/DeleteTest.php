<?php

use Laravel\Sanctum\Sanctum;

use App\Models\{Question, User};
use function Pest\Laravel\{assertDatabaseMissing, deleteJson};

it('should be able to delete a question', function () {
    $user     = User::factory()->create();
    $question = Question::factory()
        ->for($user)
        ->create();

    Sanctum::actingAs($user);

    deleteJson(route('questions.delete', $question->id))
        ->assertNoContent();

    assertDatabaseMissing('questions', [
        'id' => $question->id,
    ]);
});

it('should make sure that only person who created the question can delete it', function () {
    $rightUser = User::factory()->create();
    $wrongUser = User::factory()->create();

    $question = Question::factory()->create([
        'user_id' => $rightUser->id,
    ]);

    Sanctum::actingAs($wrongUser);

    deleteJson(route('questions.delete', $question->id))
        ->assertForbidden();

    Sanctum::actingAs($rightUser);

    deleteJson(route('questions.delete', $question->id))
        ->assertNoContent();
});
