<?php

use App\Models\{Question, User};
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{putJson, assertDatabaseHas};

it('should be able to update a question', function () {
    $user     = User::factory()->create();
    $question = Question::factory()->create(['user_id' => $user->id]);

    Sanctum::actingAs($user);

    putJson(route('questions.update', $question->id), [
        'question' => 'What is the capital of France?',
    ])->assertOk();

    assertDatabaseHas('questions', [
        'user_id'  => $user->id,
        'question' => 'What is the capital of France?',
        'id'       => $question->id,
    ]);
});

describe('validation rules', function () {
    test('question::required', function () {
        $user     = User::factory()->create();
        $question = Question::factory()->create(['user_id' => $user->id]);

        Sanctum::actingAs($user);

        putJson(route('questions.update', $question), [])->assertJsonValidationErrors([
            'question' => __('validation.required', ['attribute' => 'question'])
        ]);
    });

    test('question::ending with question mark', function () {
        $user     = User::factory()->create();
        $question = Question::factory()->create(['user_id' => $user->id]);

        Sanctum::actingAs($user);

        putJson(route('questions.update', $question), [
            'question' => 'What is the capital of France',
        ])->assertJsonValidationErrors([
            'question' => 'The question must end with a question mark.'
        ]);
    });

    test('question::min characters should be 10', function () {
        $user     = User::factory()->create();
        $question = Question::factory()->create(['user_id' => $user->id]);

        Sanctum::actingAs($user);

        putJson(route('questions.update', $question), [
            'question' => 'What?',
        ])->assertJsonValidationErrors([
            'question' => 'least 10 characters.'
        ]);
    });

    test('question::should be unique only if id is different', function () {
        $user = User::factory()->create();

        $question = Question::factory()->create([
            'question' => 'What is the capital of France?',
            'user_id'  => $user->id,
            'status'   => 'draft'
        ]);

        Sanctum::actingAs($user);

        putJson(route('questions.update', $question), [
            'question' => 'What is the capital of France?',
        ])->assertOk();
    });

});

test('the user can update a question only for themselves', function () {
    $user      = User::factory()->create();
    $otherUser = User::factory()->create();
    $question  = Question::factory()->create(['user_id' => $otherUser->id]);

    Sanctum::actingAs($user);

    putJson(route('questions.update', $question), [
        'question' => 'What is the capital of France?',
    ])->assertForbidden();
});


test('the user can update a question if status is draft', function () {
    $user     = User::factory()->create();
    $question = Question::factory()->create(['user_id' => $user->id, 'status' => 'other']);

    Sanctum::actingAs($user);

    putJson(route('questions.update', $question), [
        'question' => 'What is the capital of France?',
    ])->assertForbidden();
});

test('after updating we should return a status 200 with the updated question', function (){
    $user = User::factory()->create();
    $question = Question::factory()->create(['user_id' => $user->id]);

    Sanctum::actingAs($user);

    $request = putJson(route('questions.update', $question), [
        'question' => 'What is the capital of France?',
    ])->assertOk();

    $request->assertJson([
        'data' => [
            'id' => $question->id,
            'question' => 'What is the capital of France?',
            'status' => $question->status,
            'created_at' => $question->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $question->updated_at->format('Y-m-d H:i:s'),
            'created_by' => [
                'id' => $user->id,
                'name' => $user->name,
            ],
        ]
    ]);
});
