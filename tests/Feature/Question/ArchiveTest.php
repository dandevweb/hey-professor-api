<?php

use Laravel\Sanctum\Sanctum;

use App\Models\{Question, User};
use function Pest\Laravel\{ assertSoftDeleted,  patchJson};

it('should be able to archive a question', function () {
    $user     = User::factory()->create();
    $question = Question::factory()
        ->for($user)
        ->create();

    Sanctum::actingAs($user);

    patchJson(route('questions.archive', $question))
        ->assertNoContent();

    assertSoftDeleted('questions', [
        'id' => $question->id,
    ]);

    expect($question)->refresh()->deleted_at->not->toBeNull();
});

it('should make sure that only person who created the question can archive it', function () {
    $rightUser = User::factory()->create();
    $wrongUser = User::factory()->create();

    $question = Question::factory()->create([
        'user_id' => $rightUser->id,
    ]);

    Sanctum::actingAs($wrongUser);

    patchJson(route('questions.archive', $question->id))
        ->assertForbidden();

    Sanctum::actingAs($rightUser);

    patchJson(route('questions.archive', $question->id))
        ->assertNoContent();
});

// it('should be able  to restore an archived question', function () {
//     $user     = User::factory()->create();
//     $question = Question::factory()
//         ->for($user, 'createdBy')
//         ->create([
//             'draft'      => true,
//             'deleted_at' => now(),
//         ]);

//     Sanctum::actingAs($user);

//     patchJson(route('question.restore', $question))
//         ->assertRedirect();

//     assertNotSoftDeleted('questions', [
//         'id' => $question->id,
//     ]);

//     expect($question)->refresh()->deleted_at->toBeNull();
// });
