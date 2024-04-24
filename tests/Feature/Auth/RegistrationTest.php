<?php

use App\Models\User;

use function Pest\Laravel\{assertDatabaseHas, postJson};

it('should be able to register in the application', function () {
    postJson(route('register'), [
        'name'               => 'Test User',
        'email'              => 'test@example.com',
        'email_confirmation' => 'test@example.com',
        'password'           => 'password',
    ])->assertSuccessful();

    $this->assertAuthenticated();

    assertDatabaseHas('users', [
        'name'  => 'Test User',
        'email' => 'test@example.com',
    ]);

    expect(auth()->user()->name)->toBe('Test User');

});

describe('validations', function () {
    test('name', function ($rule, $value, $meta = []) {
        postJson(route('register'), ['name' => $value])
            ->assertJsonValidationErrors([
                'name' => __('validation.'.$rule, array_merge(['attribute' => 'name'], $meta)),
            ]);
    })->with([
        'required' => ['required', ''],
        'min:3'    => ['min', 'a', ['min' => 3]],
        'max:255'  => ['max', str_repeat('a', 256), ['max' => 255]],
    ]);

    test('email', function ($rule, $value, $meta = []) {
        if($rule === 'unique') {
            User::factory()->create(['email' => $value]);
        }

        postJson(route('register'), ['email' => $value])
            ->assertJsonValidationErrors([
                'email' => __('validation.'.$rule, array_merge(['attribute' => 'email'], $meta)),
            ]);
    })->with([
        'required'  => ['required', ''],
        'min:3'     => ['min', 'a', ['min' => 3]],
        'max:255'   => ['max', str_repeat('a', 256), ['max' => 255]],
        'email'     => ['email', 'invalid-email'],
        'unique'    => ['unique', 'joe@doe.com'],
        'confirmed' => ['confirmed', 'joe@doe.com'],
    ]);

    test('password', function ($rule, $value, $meta = []) {
        postJson(route('register'), ['password' => $value])
            ->assertJsonValidationErrors([
                'password' => __('validation.'.$rule, array_merge(['attribute' => 'password'], $meta)),
            ]);
    })->with([
        'required' => ['required', ''],
        'min:8'    => ['min', 'a', ['min' => 8]],
        'max:40'   => ['max', str_repeat('a', 256), ['max' => 40]],
    ]);
});
