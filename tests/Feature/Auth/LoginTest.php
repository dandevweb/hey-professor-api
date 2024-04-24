<?php

use App\Models\User;

use function Pest\Laravel\{assertAuthenticatedAs, postJson};

it('should be able to login', function () {
    $user = User::factory()->create();

    postJson(route('login'), [
        'email'    => $user->email,
        'password' => 'password',
    ])->assertNoContent();

    assertAuthenticatedAs($user);
});

it('should check if the email and password is valid', function ($email, $password) {
    User::factory()->create(['email' => 'joe@doe.com']);

    postJson(route('login'), [
        'email'    => $email,
        'password' => $password,
    ])->assertJsonMissingValidationErrors([
        'email' => __('auth.failed'),
    ]);

})->with([
    'wrong mail'     => ['wrong@email.com', 'password'],
    'wrong password' => ['wrong@email.com', 'password12312'],
    'invalid mail'   => ['invalid', 'password12313'],
]);

test('required fields', function () {
    postJson(route('login'))
        ->assertJsonValidationErrors([
            'email'    => __('validation.required', ['attribute' => 'email']),
            'password' => __('validation.required', ['attribute' => 'password']),
        ]);
});
