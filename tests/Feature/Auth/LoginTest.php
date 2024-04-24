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
