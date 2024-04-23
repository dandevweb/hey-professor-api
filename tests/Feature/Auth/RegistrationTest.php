<?php

use function Pest\Laravel\{assertDatabaseHas, postJson};

it('should be able to register in the application', function () {
    postJson(route('register'), [
        'name'                  => 'Test User',
        'email'                 => 'test@example.com',
        'password'              => 'password',
        'password_confirmation' => 'password',
    ])->assertSuccessful();

    $this->assertAuthenticated();

    assertDatabaseHas('users', [
        'name'  => 'Test User',
        'email' => 'test@example.com',
    ]);

    expect(auth()->user()->name)->toBe('Test User');

});
