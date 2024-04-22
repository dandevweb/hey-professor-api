<?php

use App\Models\User;

use App\Models\Question;
use Laravel\Sanctum\Sanctum;
use App\Rules\WithQuestionMark;
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

test('with the creation a new question, we need to make sure that it creates with status draft', function () {
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
            'question' => __('validation.required', ['attribute' => 'question'])
        ]);
    });

    test('question::ending with question mark', function (){
        $user = \App\Models\User::factory()->create();

        Sanctum::actingAs($user);

        postJson(route('questions.store'), [
            'question' => 'What is the capital of France',
        ])->assertJsonValidationErrors([
            'question' => 'The question must end with a question mark.'
        ]);
    });

    test('question::min characters should be 10', function (){
        $user = \App\Models\User::factory()->create();

        Sanctum::actingAs($user);

        postJson(route('questions.store'), [
            'question' => 'What?',
        ])->assertJsonValidationErrors([
            'question' => 'least 10 characters.'
        ]);
    });

    test('question::should be unique', function (){
        $user = \App\Models\User::factory()->create();

        \App\Models\Question::factory()->create([
            'question' => 'What is the capital of France?',
            'user_id' => $user->id,
            'status' => 'draft'
        ]);

        Sanctum::actingAs($user);

        postJson(route('questions.store'), [
            'question' => 'What is the capital of France?',
        ])->assertJsonValidationErrors([
            'question' => 'already been taken'
        ]);
    });

});

test('after creating we should return a status 201 with the created question', function (){
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    $request = postJson(route('questions.store'), [
        'question' => 'What is the capital of France?',
    ])->assertCreated();

    $question = Question::latest()->first();

    $request->assertJson([
        'data' => [
            'id' => $question->id,
            'question' => 'What is the capital of France?',
            'status' => $question->status,
            'created_at' => $question->created_at->format('Y-m-d'),
            'updated_at' => $question->updated_at->format('Y-m-d'),
            'user_id' => $user->id,
        ]
    ]);
});
